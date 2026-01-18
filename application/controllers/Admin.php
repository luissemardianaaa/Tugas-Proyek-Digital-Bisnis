<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Konsultasi_model');
        $this->load->model('Dashboard_model');
        $this->load->model('Jamkerja_model');
        $this->load->library('session');
        
        // Cek login & role admin
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        if ($this->session->userdata('role') !== 'admin') {
            redirect('auth');
        }
        
        // Auto-add shift column if not exists
        $this->_ensure_shift_column();
    }
    
    private function _ensure_shift_column() {
        if (!$this->db->field_exists('shift', 'users')) {
            $this->load->dbforge();
            $fields = array(
                'shift' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => TRUE
                )
            );
            $this->dbforge->add_column('users', $fields);
        }
    }

    // =====================
    // ADMIN DASHBOARD
    // =====================
    public function dashboard() {
        $data['total_obat']       = $this->Dashboard_model->hitungObat();
        $data['total_kategori']   = $this->Dashboard_model->hitungKategori();
        $data['total_pemasok']    = $this->Dashboard_model->hitungPemasok();
        $data['total_unit']       = $this->Dashboard_model->hitungUnit();
        $data['total_penjualan']  = $this->Dashboard_model->totalPenjualan(date('m'), date('Y'));
        $data['total_pembelian']  = $this->Dashboard_model->totalPembelian(date('m'), date('Y'));
        
        // Hitung total karyawan
        $data['total_karyawan'] = $this->db->where('role', 'karyawan')->where('status', 'aktif')->count_all_results('users');
        
        // Pending karyawan (belum diverifikasi)
        $data['pending_karyawan'] = $this->db
            ->where('role', 'karyawan')
            ->where('status', 'nonaktif')
            ->get('users')
            ->result();
        
        // Active karyawan dengan shift permanen + status absensi hari ini
        $today = date('Y-m-d');
        $data['active_karyawan'] = $this->db
            ->select('users.*, jam_kerja.id_jam, jam_kerja.jam_masuk AS jam_masuk_harian, jam_kerja.jam_pulang AS jam_pulang_harian, jam_kerja.keterangan AS shift_harian, jam_kerja.total_jam')
            ->from('users')
            ->join('jam_kerja', 'jam_kerja.id_user = users.id_user AND DATE(jam_kerja.tanggal) = "' . $today . '"', 'left')
            ->where('users.role', 'karyawan')
            ->where('users.status', 'aktif')
            ->get()
            ->result();
        
        // Absensi hari ini
        $data['today_attendance'] = $this->db
            ->select('j.*, u.nama')
            ->from('jam_kerja j')
            ->join('users u', 'u.id_user = j.id_user')
            ->where('DATE(j.tanggal)', date('Y-m-d'))
            ->get()
            ->result();
        
        // All recent attendance (untuk fallback)
        $data['all_recent_attendance'] = $this->db
            ->select('j.*, u.nama')
            ->from('jam_kerja j')
            ->join('users u', 'u.id_user = j.id_user')
            ->where('j.tanggal >=', date('Y-m-d', strtotime('-7 days')))
            ->order_by('j.tanggal', 'DESC')
            ->get()
            ->result();
        
        $data['business_date'] = date('Y-m-d');
        $data['server_date'] = date('Y-m-d H:i:s');
        
        // Deteksi aktivitas luar jam kerja
        $data['shift_definitions'] = [
            'pagi' => ['start' => '07:00:00', 'end' => '13:00:00', 'label' => '07:00 - 13:00'],
            'siang' => ['start' => '12:30:00', 'end' => '17:30:00', 'label' => '12:30 - 17:30'],
            'malam' => ['start' => '17:00:00', 'end' => '23:00:00', 'label' => '17:00 - 23:00']
        ];
        $data['peringatan_absensi'] = $this->_detect_outside_hours_activity($data['shift_definitions']);
        
        $this->load->view('admin/dashboard', $data);
    }
    
    private function _detect_outside_hours_activity($shifts) {
        $warnings = [];
        $today = date('Y-m-d');
        
        // Ambil semua karyawan yang login hari ini
        $karyawan = $this->db
            ->select('u.id_user, u.nama, u.shift, u.last_login, j.keterangan, j.jam_masuk, j.jam_pulang')
            ->from('users u')
            ->join('jam_kerja j', 'j.id_user = u.id_user AND DATE(j.tanggal) = "' . $today . '"', 'left')
            ->where('u.role', 'karyawan')
            ->where('u.status', 'aktif')
            ->where('DATE(u.last_login)', $today)
            ->get()
            ->result();
        
        
        foreach ($karyawan as $k) {
            $start = null;
            $end = null;
            $shift_label = "Jadwal Tidak Diketahui";

            // 1. Cek Record Absensi Hari Ini
            if ($k->keterangan) {
                // Priority: Specific Times > Default by Shift Name
                if ($k->jam_masuk) {
                    $start = $k->jam_masuk;
                    // If end time missing, try to guess from shift name default
                    $end = $k->jam_pulang; 
                    if(!$end) {
                        $key = strtolower($k->keterangan);
                        // Find matching default
                        foreach($shifts as $s_key => $s_val) {
                            if(strpos($key, $s_key) !== false) {
                                $end = $s_val['end']; break;
                            }
                        }
                    }
                } else {
                    // Fallback to name-based default
                    $key = strtolower($k->keterangan);
                    foreach($shifts as $s_key => $s_val) {
                        if(strpos($key, $s_key) !== false) {
                            $start = $s_val['start'];
                            $end = $s_val['end'];
                            break;
                        }
                    }
                }
                $shift_label = ucfirst($k->keterangan);
            } 
            // 2. Fallback: Gunakan Shift Permanen (Profile)
            elseif (!empty($k->shift)) {
                $key = strtolower($k->shift);
                if (isset($shifts[$key])) {
                    $start = $shifts[$key]['start'];
                    $end = $shifts[$key]['end'];
                    $shift_label = ucfirst($key);
                }
            }

            // Validasi
            if ($start && $end) {
                // Ensure seconds included
                if(strlen($start) == 5) $start .= ":00";
                if(strlen($end) == 5) $end .= ":00";

                $login_time = date('H:i:s', strtotime($k->last_login));
                
                // Logic Comparison
                if ($login_time < $start || $login_time > $end) {
                    $warnings[] = [
                        'nama' => $k->nama,
                        'shift' => $shift_label,
                        // Show range in alert
                        'jam_masuk' => substr($login_time, 0, 5),
                        'range' => substr($start,0,5) . ' - ' . substr($end,0,5)
                    ];
                }
            }
        }
        
        return $warnings;
    }

    // =====================
    // AKTIVASI KARYAWAN
    // =====================
    public function aktivasi_karyawan() {
        $id_user = $this->input->post('id_user');
        $shift = $this->input->post('shift');
        
        if (!$id_user || !$shift) {
            $this->session->set_flashdata('error', 'Data tidak lengkap!');
            redirect('admin/dashboard');
            return;
        }
        
        // Update status dan shift
        $this->db->where('id_user', $id_user)->update('users', [
            'status' => 'aktif',
            'shift' => $shift
        ]);
        
        // Insert jam kerja hari ini
        $this->db->insert('jam_kerja', [
            'id_user' => $id_user,
            'tanggal' => date('Y-m-d'),
            'jam_masuk' => date('H:i:s'),
            'keterangan' => ucfirst($shift)
        ]);
        
        $this->session->set_flashdata('success', 'Karyawan berhasil diaktifkan!');
        redirect('admin/dashboard');
    }

    // =====================
    // ASSIGN SHIFT PERMANEN
    // =====================
    public function assign_shift() {
        $id_user = $this->input->post('id_user');
        $shift = $this->input->post('shift');
        
        if (!$id_user || !$shift) {
            redirect('admin/dashboard');
            return;
        }
        
        $this->db->where('id_user', $id_user)->update('users', [
            'shift' => $shift
        ]);
        
        $this->session->set_flashdata('success', 'Shift permanen berhasil diupdate!');
        redirect('admin/dashboard');
    }

    // =====================
    // HALAMAN ABSENSI
    // =====================
    public function absensi() {
        $data['jam_kerja'] = $this->Jamkerja_model->get_all();
        $data['karyawan'] = $this->Jamkerja_model->get_karyawan();
        $this->load->view('admin/absensi', $data);
    }

    // =====================
    // UPDATE/INSERT ABSENSI
    // =====================
    public function update_absensi() {
        $id_jam = $this->input->post('id_jam');
        $id_user = $this->input->post('id_user');
        $tanggal = $this->input->post('tanggal');
        $jam_masuk = $this->input->post('jam_masuk');
        $jam_pulang = $this->input->post('jam_pulang');
        $keterangan = $this->input->post('keterangan');
        
        // Hitung total jam jika jam_pulang ada
        $total_jam = null;
        if ($jam_pulang) {
            $masuk = strtotime($jam_masuk);
            $pulang = strtotime($jam_pulang);
            $total_jam = round(($pulang - $masuk) / 3600, 2);
        }
        
        $data = [
            'id_user' => $id_user,
            'tanggal' => $tanggal,
            'jam_masuk' => $jam_masuk,
            'jam_pulang' => $jam_pulang,
            'total_jam' => $total_jam,
            'keterangan' => $keterangan
        ];
        
        if ($id_jam) {
            // Update
            $this->db->where('id_jam', $id_jam)->update('jam_kerja', $data);
            $this->session->set_flashdata('success', 'Data absensi berhasil diupdate!');
        } else {
            // Insert
            $this->db->insert('jam_kerja', $data);
            $this->session->set_flashdata('success', 'Data absensi berhasil ditambahkan!');
        }

        // SYNC: Update Shift Permanen User agar Dashboard besok/hari ini sesuai
        // Normalisasi keterangan (hapus 'Shift ', dll)
        $shift_key = strtolower($keterangan);
        $new_shift = null;
        if (strpos($shift_key, 'pagi') !== false) $new_shift = 'Pagi';
        elseif (strpos($shift_key, 'siang') !== false) $new_shift = 'Siang';
        elseif (strpos($shift_key, 'malam') !== false) $new_shift = 'Malam';

        if ($new_shift) {
            $this->db->where('id_user', $id_user)->update('users', ['shift' => $new_shift]);
        }
        
        redirect('admin/absensi');
    }

    // =====================
    // HAPUS ABSENSI
    // =====================
    public function hapus_absensi($id_jam) {
        $this->db->where('id_jam', $id_jam)->delete('jam_kerja');
        $this->session->set_flashdata('success', 'Data absensi berhasil dihapus!');
        redirect('admin/absensi');
    }

    // =====================
    // SET JAM PULANG
    // =====================
    public function pulang($id_jam) {
        $row = $this->db->where('id_jam', $id_jam)->get('jam_kerja')->row();
        
        if (!$row) {
            show_error('Data jam kerja tidak ditemukan');
        }
        
        if ($row->jam_pulang) {
            redirect('admin/dashboard');
            return;
        }
        
        $jam_pulang = date('H:i:s');
        $masuk = strtotime($row->jam_masuk);
        $pulang = strtotime($jam_pulang);
        $total = round(($pulang - $masuk) / 3600, 2);
        
        $this->db->where('id_jam', $id_jam)->update('jam_kerja', [
            'jam_pulang' => $jam_pulang,
            'total_jam' => $total
        ]);
        
        redirect('admin/dashboard');
    }

    // =====================
    // KONSULTASI (existing methods)
    // =====================
    public function pesan() {
        $data['tickets'] = $this->Konsultasi_model->get_all_tickets();
        $this->load->view('admin/daftar_pesan', $data);
    }

    public function detail_pesan($id_konsultasi) {
        $ticket = $this->Konsultasi_model->get_ticket($id_konsultasi);
        if(!$ticket) {
            show_404();
        }

        $data['ticket'] = $ticket;
        $data['messages'] = $this->Konsultasi_model->get_messages($id_konsultasi);
        $this->load->view('admin/detail_pesan', $data);
    }

    public function reply_pesan() {
        $id_konsultasi = $this->input->post('id_konsultasi');
        $pesan = $this->input->post('pesan');

        if($id_konsultasi && $pesan) {
            $data = [
                'id_konsultasi' => $id_konsultasi,
                'pengirim' => 'admin',
                'pesan' => $pesan
            ];
            $this->Konsultasi_model->add_message($data);
        }
        
        redirect('admin/detail_pesan/' . $id_konsultasi);
    }
    
    public function tutup_tiket($id_konsultasi) {
         $this->Konsultasi_model->update_status($id_konsultasi, 'closed');
         redirect('admin/pesan');
    }
}
