<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Pembayaran_model');
        $this->load->model('Keranjang_model');
        $this->load->model('Penjualan_model'); // Load Penjualan_model
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }
public function index()
{
    $id_user = $this->session->userdata('id_user');

    // Ambil riwayat transaksi user dari tabel penjualan
    $data['transaksi'] = $this->Penjualan_model->get_riwayat_by_user($id_user);

    $this->load->view('pembayaran/index', $data);
}

    public function checkout() {
        $id_user = $this->session->userdata('id_user');

        // Ambil data keranjang user
        $data['items'] = $this->Keranjang_model->get_items($id_user);
        
        // Cek apakah keranjang kosong
        if (empty($data['items'])) {
            $this->session->set_flashdata('error', 'Keranjang Anda kosong! Silakan tambahkan produk terlebih dahulu.');
            redirect('keranjang');
            return;
        }

        // Hitung total harga
        $data['total'] = 0;
        foreach ($data['items'] as $item) {
            $data['total'] += $item->subtotal;
        }

        // Ambil data user untuk pre-fill form
        $data['user'] = $this->db->get_where('users', array('id_user' => $id_user))->row();

        // Load view checkout
        $this->load->view('pembayaran/checkout', $data);
    }

    /**
     * Proses pembayaran dari form checkout
     * Validasi data dan buat transaksi baru
     */
    public function proses() {
        $id_user = $this->session->userdata('id_user');

        // Set rules validasi form
        $this->form_validation->set_rules('nama_penerima', 'Nama Penerima', 'required|trim|min_length[3]', array(
            'required' => 'Nama penerima harus diisi',
            'min_length' => 'Nama penerima minimal 3 karakter'
        ));
        
        $this->form_validation->set_rules('alamat_pengiriman', 'Alamat Pengiriman', 'required|trim|min_length[10]', array(
            'required' => 'Alamat pengiriman harus diisi',
            'min_length' => 'Alamat pengiriman minimal 10 karakter'
        ));
        
        $this->form_validation->set_rules('no_telepon', 'No Telepon', 'required|trim|numeric|min_length[10]|max_length[15]', array(
            'required' => 'Nomor telepon harus diisi',
            'numeric' => 'Nomor telepon harus berupa angka',
            'min_length' => 'Nomor telepon minimal 10 digit',
            'max_length' => 'Nomor telepon maksimal 15 digit'
        ));
        
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required|in_list[transfer,ewallet,cod]', array(
            'required' => 'Metode pembayaran harus dipilih',
            'in_list' => 'Metode pembayaran tidak valid'
        ));

        // Jika validasi gagal, kembali ke halaman checkout
        if ($this->form_validation->run() == FALSE) {
            $this->checkout();
            return;
        }

        // Ambil items keranjang
        $items = $this->Keranjang_model->get_items($id_user);
        
        // Validasi ulang keranjang tidak kosong
        if (empty($items)) {
            $this->session->set_flashdata('error', 'Keranjang kosong! Tidak dapat melanjutkan pembayaran.');
            redirect('keranjang');
            return;
        }

        // Validasi stok obat mencukupi
        foreach ($items as $item) {
            $obat = $this->db->get_where('obat', array('id_obat' => $item->id_obat))->row();
            if (!$obat || $obat->stok < $item->jumlah) {
                $this->session->set_flashdata('error', 'Stok ' . $item->nama_obat . ' tidak mencukupi!');
                redirect('keranjang');
                return;
            }
        }

        // Siapkan data transaksi
        $data_transaksi = array(
            'id_user' => $id_user,
            'metode_pembayaran' => $this->input->post('metode_pembayaran', TRUE),
            'nama_penerima' => $this->input->post('nama_penerima', TRUE),
            'alamat_pengiriman' => $this->input->post('alamat_pengiriman', TRUE),
            'no_telepon' => $this->input->post('no_telepon', TRUE),
            'catatan' => $this->input->post('catatan', TRUE)
        );

        // Buat transaksi
        $id_transaksi = $this->Pembayaran_model->buat_transaksi($data_transaksi, $items);

        if ($id_transaksi) {
            $this->session->set_flashdata('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            redirect('pembayaran/konfirmasi/' . $id_transaksi);
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
            redirect('pembayaran/checkout');
        }
    }

    /**
     * Halaman konfirmasi setelah checkout berhasil
     * Menampilkan detail transaksi dan informasi pembayaran
     * @param int $id_transaksi
     */
    public function konfirmasi($id_transaksi) {
        $id_user = $this->session->userdata('id_user');

        // Ambil data transaksi
        $data['transaksi'] = $this->Pembayaran_model->get_by_id($id_transaksi);
        
        // Validasi transaksi ada dan milik user ini
        if (!$data['transaksi']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan!');
            redirect('pembayaran/riwayat');
            return;
        }

        if ($data['transaksi']->id_user != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke transaksi ini!');
            redirect('pembayaran/riwayat');
            return;
        }

        // Ambil detail items transaksi
        $data['items'] = $this->Pembayaran_model->get_detail($id_transaksi);

        // Load view konfirmasi
        $this->load->view('pembayaran/konfirmasi', $data);
    }

    /**
     * Halaman riwayat transaksi user
     * Menampilkan semua transaksi yang pernah dibuat
     */
    public function riwayat() {
        $id_user = $this->session->userdata('id_user');

        // Ambil riwayat transaksi user dari tabel penjualan
        $data['transaksi'] = $this->Penjualan_model->get_riwayat_by_user($id_user);

        // Load view riwayat
        $this->load->view('pembayaran/riwayat', $data);
    }

    /**
     * Halaman detail transaksi
     * Menampilkan detail lengkap satu transaksi
     * @param int $id_transaksi
     */
    public function detail($id_transaksi) {
        $id_user = $this->session->userdata('id_user');

        // Ambil data transaksi dari tabel penjualan
        $data['transaksi'] = $this->Penjualan_model->get_by_id($id_transaksi);
        
        // Validasi transaksi
        if (!$data['transaksi']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan!');
            redirect('pembayaran/riwayat');
            return;
        }

        // Validasi kepemilikan (id_karyawan used for user ID)
        if ($data['transaksi']->id_karyawan != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke transaksi ini!');
            redirect('pembayaran/riwayat');
            return;
        }

        // Ambil detail items
        $data['items'] = $this->Penjualan_model->get_detail($id_transaksi);

        // Load view detail
        $this->load->view('pembayaran/detail', $data);
    }

    /**
     * Batalkan transaksi
     * Hanya bisa dibatalkan jika status masih pending
     * @param int $id_transaksi
     */
    public function batalkan($id_transaksi) {
        $id_user = $this->session->userdata('id_user');

        // Cek kepemilikan transaksi
        if (!$this->Pembayaran_model->is_owner($id_transaksi, $id_user)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke transaksi ini!');
            redirect('pembayaran/riwayat');
            return;
        }

        // Ambil data transaksi
        $transaksi = $this->Pembayaran_model->get_by_id($id_transaksi);

        // Validasi status transaksi
        if ($transaksi->status_pembayaran != 'pending') {
            $this->session->set_flashdata('error', 'Transaksi tidak dapat dibatalkan! Status: ' . $transaksi->status_pembayaran);
            redirect('pembayaran/detail/' . $id_transaksi);
            return;
        }

        // Batalkan transaksi
        if ($this->Pembayaran_model->batalkan_transaksi($id_transaksi)) {
            $this->session->set_flashdata('success', 'Transaksi berhasil dibatalkan. Stok obat telah dikembalikan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal membatalkan transaksi. Silakan coba lagi.');
        }

        redirect('pembayaran/riwayat');
    }

    /**
     * Cari transaksi berdasarkan keyword
     */
    public function search() {
        $id_user = $this->session->userdata('id_user');
        $keyword = $this->input->get('keyword', TRUE);

        if (empty($keyword)) {
            redirect('pembayaran/riwayat');
            return;
        }

        // Search transaksi
        $data['transaksi'] = $this->Pembayaran_model->search($id_user, $keyword);
        $data['keyword'] = $keyword;
        $data['statistik'] = $this->Pembayaran_model->get_statistik($id_user);

        // Load view riwayat dengan hasil pencarian
        $this->load->view('pembayaran/riwayat', $data);
    }

    /**
     * Cetak invoice transaksi (PDF atau Print)
     * @param int $id_transaksi
     */
    public function invoice($id_transaksi) {
        $id_user = $this->session->userdata('id_user');

        // Validasi kepemilikan
        if (!$this->Pembayaran_model->is_owner($id_transaksi, $id_user)) {
            show_404();
            return;
        }

        // Ambil data transaksi
        $data['transaksi'] = $this->Pembayaran_model->get_by_id($id_transaksi);
        $data['items'] = $this->Pembayaran_model->get_detail($id_transaksi);

        // Load view invoice (format print-friendly)
        $this->load->view('pembayaran/invoice', $data);
    }

    /**
     * Upload bukti pembayaran
     * @param int $id_transaksi
     */
    public function upload_bukti($id_transaksi) {
        $id_user = $this->session->userdata('id_user');

        // Validasi kepemilikan
        if (!$this->Pembayaran_model->is_owner($id_transaksi, $id_user)) {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('pembayaran/riwayat');
            return;
        }

        // Konfigurasi upload
        $config['upload_path'] = './uploads/bukti_pembayaran/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = 'bukti_' . $id_transaksi . '_' . time();

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('bukti_pembayaran')) {
            $upload_data = $this->upload->data();
            
            // Update database dengan path file
            $this->db->where('id_transaksi', $id_transaksi);
            $this->db->update('transaksi', array(
                'bukti_pembayaran' => $upload_data['file_name']
            ));

            $this->session->set_flashdata('success', 'Bukti pembayaran berhasil diupload!');
        } else {
            $this->session->set_flashdata('error', 'Gagal upload: ' . $this->upload->display_errors());
        }

        redirect('pembayaran/detail/' . $id_transaksi);
    }

}