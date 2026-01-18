<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layanan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function tanya_apoteker() {
        $this->load->view('layanan/tanya_apoteker');
    }

    public function upload_resep() {
        // Fitur dinonaktifkan sementara
        $this->session->set_flashdata('error', 'Maaf, layanan Upload Resep sedang tidak tersedia saat ini.');
        redirect('home');
        // $this->load->view('layanan/upload_resep');
    }

    public function cek_lab() {
        $this->load->view('layanan/cek_lab');
    }

    public function booking($id_paket = null) {
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $paket_list = [
            1 => ['nama' => 'Cek Gula Darah', 'harga' => 25000],
            2 => ['nama' => 'Cek Kolesterol Lengkap', 'harga' => 45000],
            3 => ['nama' => 'Cek Asam Urat', 'harga' => 30000]
        ];

        if(!isset($paket_list[$id_paket])) {
            redirect('layanan/cek_lab');
        }

        $paket = $paket_list[$id_paket];
        $paket['dp'] = $paket['harga'] * 0.5; // 50% DP
        
        $data['paket'] = $paket;
        $data['id_paket'] = $id_paket;
        $data['user'] = $this->session->userdata();
        
        $this->load->view('layanan/booking_lab', $data);
    }

    public function submit_booking() {
        if(!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => false, 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $this->load->model('Pembayaran_model'); // Load Model for CodeGen

        $id_user = $this->session->userdata('id_user');
        $nama_paket = $this->input->post('nama_paket');
        $harga = $this->input->post('harga');
        $dp_amount = $this->input->post('dp_amount');
        $tanggal = $this->input->post('tanggal');

        // Validasi H-3
        $min_date = date('Y-m-d', strtotime('+3 days'));
        if($tanggal < $min_date) {
            echo json_encode(['status' => false, 'message' => 'Booking wajib dilakukan minimal H-3']);
            return;
        }

        // 1. Insert Booking Lab
        $data_booking = [
            'id_user' => $id_user,
            'paket' => $nama_paket,
            'harga' => $harga,
            'dp_amount' => $dp_amount,
            'tanggal_booking' => $tanggal,
            'status' => 'pending'
        ];

        // Disable DB Debug
        $db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        if (!$this->db->insert('booking_lab', $data_booking)) {
            $error = $this->db->error();
            echo json_encode(['status' => false, 'message' => 'Error Booking: ' . $error['message']]);
            $this->db->db_debug = $db_debug; return;
        }
        $id_booking = $this->db->insert_id();

        // 2. Insert ke Transaksi (Gunakan tabel transaksi agar kompatibel dengan Pembayaran_model)
        $kode_transaksi = $this->Pembayaran_model->generate_kode_transaksi();
        $data_transaksi = [
            'id_user' => $id_user,
            'kode_transaksi' => $kode_transaksi,
            'total_harga' => $dp_amount, // Tagihan DP
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => 'pending',
            'nama_penerima' => $this->session->userdata('nama') ?: 'Pelanggan', // Default Name
            'alamat_pengiriman' => 'Layanan Lab: ' . $nama_paket . ' (Tgl: ' . $tanggal . ')',
            'no_telepon' => '-', // Default
            'tanggal_transaksi' => date('Y-m-d H:i:s')
        ];
        
        if (!$this->db->insert('transaksi', $data_transaksi)) {
            $error = $this->db->error();
            $this->db->delete('booking_lab', ['id_booking' => $id_booking]);
            echo json_encode(['status' => false, 'message' => 'Error Transaksi: ' . $error['message']]);
            $this->db->db_debug = $db_debug; return;
        }
        $id_transaksi = $this->db->insert_id();

        // 3. Insert Konsultasi
        $data_konsultasi = [
            'id_user' => $id_user,
            'nama_pengirim' => $this->session->userdata('nama') ?: 'Pelanggan',
            // Email tidak wajib, atau bisa ambil dari session jika ada
            'subjek' => 'Tagihan Booking Lab #' . $id_booking,
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (!$this->db->insert('konsultasi', $data_konsultasi)) {
            $error = $this->db->error();
            $this->db->delete('booking_lab', ['id_booking' => $id_booking]);
            $this->db->delete('transaksi', ['id_transaksi' => $id_transaksi]);
            echo json_encode(['status' => false, 'message' => 'Error Konsultasi: ' . $error['message']]);
            $this->db->db_debug = $db_debug; return;
        }
        $id_konsultasi = $this->db->insert_id();

        // 4. Insert Konsultasi Detail
        $pesan_system = "Halo " . $this->session->userdata('nama') . ",\n\n";
        $pesan_system .= "Terima kasih telah melakukan booking layanan **" . $nama_paket . "**.\n";
        $pesan_system .= "Jadwal: " . date('d M Y', strtotime($tanggal)) . "\n";
        $pesan_system .= "Total Tagihan: Rp " . number_format($harga, 0, ',', '.') . "\n";
        $pesan_system .= "--------------------------------\n";
        $pesan_system .= "**Wajib DP (50%): Rp " . number_format($dp_amount, 0, ',', '.') . "**\n\n";
        $pesan_system .= "Pembayaran dapat dilakukan melalui halaman Konfirmasi kami.\n";
        $pesan_system .= "*Mohon segera selesaikan pembayaran agar booking Anda diproses.*";

        $data_pesan = [
            'id_konsultasi' => $id_konsultasi,
            'pengirim' => 'admin', 
            'pesan' => $pesan_system,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if (!$this->db->insert('konsultasi_detail', $data_pesan)) {
            $error = $this->db->error();
            echo json_encode(['status' => false, 'message' => 'Error Pesan: ' . $error['message']]);
            $this->db->db_debug = $db_debug; return;
        }

        // Redirect ke Halaman Konfirmasi Pembayaran
        echo json_encode(['status' => true, 'message' => 'Booking berhasil dibuat!', 'redirect' => site_url('pembayaran/konfirmasi/' . $id_transaksi)]);
        
        // Restore debug setting
        $this->db->db_debug = $db_debug;
    }    

}
