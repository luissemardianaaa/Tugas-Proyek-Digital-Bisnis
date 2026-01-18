<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Obat_model');
        $this->load->model('Stok_model'); 
        $this->load->library(['session', 'form_validation', 'upload']);
        // Cek Login & Role
        // Cek Login & Role (Admin & Karyawan diperbolehkan)
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        $role = $this->session->userdata('role');
        if ($role !== 'karyawan' && $role !== 'admin') {
            redirect('auth');
        }
    }

    // ==============================
    // LIST OBAT
    // ==============================
public function index()
{
    $keluhan = $this->input->get('keluhan'); // For AI recommendations
    $search  = $this->input->get('search');  // For searching obat by name
    $satuan  = $this->input->get('satuan');

    // stok stats (always needed)
    $data['obat_kritis'] = $this->Obat_model->get_obat_kritis();
    $data['obat_habis']  = $this->Obat_model->get_obat_habis();

    // ===== AI REKOMENDASI =====
    $data['rekomendasi'] = null;
    
    if (!empty($keluhan)) {
        // Get AI re commendations
        $data['rekomendasi'] = $this->Obat_model->get_rekomendasi_ai($keluhan);
        
        // Show ONLY obat that match AI recommendations and exist in stock
        if (isset($data['rekomendasi']['tersedia_di_apotek']) && !empty($data['rekomendasi']['tersedia_di_apotek'])) {
            $data['obat'] = $data['rekomendasi']['tersedia_di_apotek'];
        } else {
            $data['obat'] = []; // No matching obat in stock
        }
    } elseif (!empty($search)) {
        // Search obat by name
        $data['obat'] = $this->Obat_model->search_by_name($search);
    } elseif ($satuan) {
        $data['obat'] = $this->Obat_model->get_by_satuan($satuan);
    } else {
        // Default: show all obat
        $data['obat'] = $this->Obat_model->get_all_obat();
    }

    // total dari DB
    $data['total_obat'] = count($data['obat']);

    $this->load->view('obat/index', $data);
}
public function katalog()
{
    $satuan = $this->input->get('satuan');

    if ($satuan) {
        $data['obat'] = $this->Obat_model->get_by_satuan($satuan);
    } else {
        $data['obat'] = $this->Obat_model->get_all_obat();
    }

    $this->load->view('obat/katalog', $data);
}
    // ==============================
    // TAMPIL FORM TAMBAH OBAT
    // ==============================
    public function create() {
        $this->load->view('templates/header');
        $this->load->view('obat/create');
        $this->load->view('templates/footer');
    }

    // ==============================
    // PROSES SIMPAN OBAT (STORE)
    // ==============================
    public function store() {
        $this->form_validation->set_rules('nama_obat', 'Nama Obat', 'required');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|integer');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->create(); // tampil form ulang
            return;
        }

        // upload gambar
        $gambar = null;
        if (!empty($_FILES['gambar']['name'])) {

            $config['upload_path'] = './uploads/obat/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('gambar')) {
                $gambar = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('karyawan/obat/create');
            }
        }

        $kode_obat = $this->Obat_model->generate_kode_obat();
        $data = [
    'kode_obat'     => $kode_obat, // 🔥 otomatis
    'nama_obat'     => $this->input->post('nama_obat'),
    'jenis'         => $this->input->post('jenis'),
    'satuan'        => $this->input->post('satuan'),
    'harga'         => $this->input->post('harga'),
    'stok'          => $this->input->post('stok'),
    'stok_minimum'  => $this->input->post('stok_minimum'),
    'deskripsi'     => $this->input->post('deskripsi'),
    'gambar'        => $gambar,
    'created_at'    => date('Y-m-d H:i:s')
    ];

        $insert_id = $this->Obat_model->create($data);

        // catat log stok awal
        $this->Stok_model->add_log([
            'id_obat' => $insert_id,
            'id_user' => $this->session->userdata('id_user'),
            'jenis'   => 'masuk',
            'stok_sebelum' => 0,
            'stok_sesudah' => $data['stok'],
            'jumlah_perubahan' => $data['stok'],
            'keterangan' => 'Stok awal',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'obat berhasil ditambahkan');
        redirect('karyawan/obat/index');
    }

    // ==============================
    // EDIT OBAT
    // ==============================
public function edit($id)
{
    $data['obat'] = $this->Obat_model->get_by_id($id);
    $this->load->view('obat/edit', $data);
}

    // ==============================
    // UPDATE OBAT
    // ==============================
    public function update($id) {

        $this->form_validation->set_rules('nama_obat', 'Nama Obat', 'required');

        $obat = $this->Obat_model->get_by_id($id);
        if (!$obat) show_404();

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        // upload gambar baru jika ada
        $gambar = $obat->gambar;
        if (!empty($_FILES['gambar']['name'])) {

            $config['upload_path'] = './uploads/obat/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('gambar')) {
                if ($gambar && file_exists('./uploads/obat/' . $gambar)) {
                    unlink('./uploads/obat/' . $gambar);
                }
                $gambar = $this->upload->data('file_name');
            }
        }

        $update_data = [
            'nama_obat' => $this->input->post('nama_obat'),
            'jenis'     => $this->input->post('jenis'),
            'satuan'    => $this->input->post('satuan'),
            'harga'     => $this->input->post('harga'),
            'stok'      => $this->input->post('stok'),
            'stok_minimum' => $this->input->post('stok_minimum'),
            'deskripsi'    => $this->input->post('deskripsi'),
            'gambar'       => $gambar
        ];

        $this->Obat_model->update($id, $update_data);

        $this->session->set_flashdata('success', 'Data obat berhasil diupdate');
        redirect('karyawan/obat');
    }

    // ==============================
    // HAPUS OBAT
    // ==============================
    public function delete($id) {
        $obat = $this->Obat_model->get_by_id($id);
        if (!$obat) show_404();

        // Obat tidak bisa dihapus - harus dinonaktifkan
        $this->session->set_flashdata('error', 'Obat tidak bisa dihapus karena sudah tercatat dalam proses transaksi. Silakan nonaktifkan obat jika memang tidak diproduksi lagi.');
        redirect('karyawan/obat');
    }

    // ==============================
    // TOGGLE STATUS OBAT (AKTIF/NONAKTIF)
    // ==============================
    public function toggle_status($id) {
        $obat = $this->Obat_model->get_by_id($id);
        if (!$obat) show_404();

        // Get current status, default to 'aktif' if not set
        $current_status = isset($obat->status) ? $obat->status : 'aktif';
        
        // Toggle status
        $new_status = ($current_status === 'aktif') ? 'nonaktif' : 'aktif';
        
        $this->Obat_model->set_status($id, $new_status);

        if ($new_status === 'nonaktif') {
            $this->session->set_flashdata('success', 'Obat "' . $obat->nama_obat . '" berhasil dinonaktifkan. Obat tidak akan ditampilkan kepada pelanggan.');
        } else {
            $this->session->set_flashdata('success', 'Obat "' . $obat->nama_obat . '" berhasil diaktifkan kembali.');
        }
        
        redirect('karyawan/obat');
    }

    public function cari()
    {
        $keyword = $this->input->get('keyword');

        if (!$keyword) {
            redirect('karyawan/obat');
        }

        $data['keyword'] = $keyword;
        $data['obat'] = $this->Obat_model->cari_obat_rekomendasi($keyword);

        $this->load->view('obat/index', $data);
    }
}
