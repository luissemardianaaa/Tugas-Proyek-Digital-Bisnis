<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('session');
        // Cek Login & Role
        // Cek Login & Role (Admin & Karyawan diperbolehkan)
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        $role = $this->session->userdata('role');
        if ($role !== 'karyawan' && $role !== 'admin') {
            redirect('auth');
        }
        $this->load->model('Dashboard_model');
        
        // Update aktivitas terakhir (untuk monitoring admin)
        $this->db->where('id_user', $this->session->userdata('id_user'))->update('users', ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function index() {

        $data['total_obat']       = $this->Dashboard_model->hitungObat();
        $data['total_kategori']   = $this->Dashboard_model->hitungKategori();
        $data['total_pemasok']    = $this->Dashboard_model->hitungPemasok();
        $data['total_unit']       = $this->Dashboard_model->hitungUnit();
        $data['total_penjualan']  = $this->Dashboard_model->totalPenjualan(date('m'), date('Y'));
        $data['total_pembelian']  = $this->Dashboard_model->totalPembelian(date('m'), date('Y'));

        $data['grafik_harian']    = $this->Dashboard_model->grafikPenjualanHarian();
        $data['grafik_bulanan']   = $this->Dashboard_model->grafikPenjualanBulanan();

        // === LOGIKA SHIFT PRIORITY (Sama dengan Admin Dashboard) ===
        $id_user = $this->session->userdata('id_user');
        
        // 1. Ambil Shift Hari Ini
        $shift_today = $this->db->get_where('jam_kerja', [
            'id_user' => $id_user,
            'tanggal' => date('Y-m-d')
        ])->row();

        // 2. Ambil Shift Terakhir (History)
        $shift_history = $this->db->select('*')
            ->from('jam_kerja')
            ->where('id_user', $id_user)
            ->order_by('tanggal', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        // 3. Ambil Shift Permanen User
        $user_profile = $this->db->get_where('users', ['id_user' => $id_user])->row();
        
        // --- TENTUKAN FINAL SHIFT ---
        $final_shift = (object)[
            'keterangan' => 'Belum Diatur',
            'jam_masuk'  => '--:--',
            'jam_pulang' => '--:--'
        ];

        // Definisi Shift Standar (Fallback)
        $shift_defs = [
            'pagi'  => ['start' => '07:00', 'end' => '13:00'],
            'siang' => ['start' => '12:30', 'end' => '17:30'],
            'malam' => ['start' => '17:00', 'end' => '23:00']
        ];

        // LOGIC PRIORITY
        if ($shift_today) {
            // Priority 1: Hari Ini
            $final_shift->keterangan = $shift_today->keterangan;
            $final_shift->jam_masuk  = $shift_today->jam_masuk;
            $final_shift->jam_pulang = $shift_today->jam_pulang; // Bisa kosong
        } elseif ($shift_history) {
            // Priority 2: History Terakhir
            $final_shift->keterangan = $shift_history->keterangan;
            $final_shift->jam_masuk  = $shift_history->jam_masuk;
            $final_shift->jam_pulang = $shift_history->jam_pulang;
        } elseif ($user_profile && !empty($user_profile->shift)) {
            // Priority 3: Permanen
            $key = strtolower($user_profile->shift);
            $final_shift->keterangan = ucfirst($key);
            if (isset($shift_defs[$key])) {
                $final_shift->jam_masuk = $shift_defs[$key]['start'];
                $final_shift->jam_pulang = $shift_defs[$key]['end'];
            }
        }

        // Fix Jam Pulang jika kosong (Ambil dari defs berdasarkan nama shift)
        if (empty($final_shift->jam_pulang) || $final_shift->jam_pulang == '--:--') {
            $key = strtolower($final_shift->keterangan);
            // Cek substring
            $def_key = null;
            if (strpos($key, 'pagi') !== false) $def_key = 'pagi';
            elseif (strpos($key, 'siang') !== false) $def_key = 'siang';
            elseif (strpos($key, 'malam') !== false) $def_key = 'malam';

            if ($def_key && isset($shift_defs[$def_key])) {
                $final_shift->jam_pulang = $shift_defs[$def_key]['end'];
            }
        }

        $data['shift_hari_ini'] = $final_shift; // Kirim ke view sebagai 'shift_hari_ini' agar kompatibel
        
        // --- HITUNG LUAR JAM KERJA ---
        $data['luar_jam_kerja'] = false;
        
        // Cek jika final shift valid (ada jamnya)
        if ($final_shift->jam_masuk != '--:--' && $final_shift->jam_pulang != '--:--') {
            $now = date('H:i:s');
            // Tambah toleransi login (misal 30 menit sebelum/sesudah) - Opsional
            // Strict sesuai request:
            $start_time = $final_shift->jam_masuk . ":00";
            $end_time   = $final_shift->jam_pulang . ":00";
            
            // Handle shift lewat tengah malam (jika ada, misal 23:00 - 07:00) - current logic simple linear
            if ($now < $start_time || $now > $end_time) {
                $data['luar_jam_kerja'] = true;
            }
        } else {
             // Jika tidak ada shift sama sekali (misal user baru)
             $data['luar_jam_kerja'] = true;
        }

        // Override: Admin tidak perlu notifikasi ini karena akses penuh
        if ($this->session->userdata('role') == 'admin') {
            $data['luar_jam_kerja'] = false;
        }

        // Hitung Pesan Masuk
        $this->load->model('Konsultasi_model');
        $data['unread_chat'] = $this->Konsultasi_model->count_unread();

        $this->load->view('dashboard/index', $data);
    }
}

