<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Konsultasi_model');
        $this->load->library('session');
        
        // Cek Login & Role
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        $role = $this->session->userdata('role');
        if ($role !== 'karyawan' && $role !== 'admin') {
            redirect('auth');
        }
    }

    public function index() {
        $data['tickets'] = $this->Konsultasi_model->get_all_tickets();
        // Gunakan view yang sama dengan admin
        $this->load->view('admin/daftar_pesan', $data);
    }

    public function detail($id_konsultasi) {
        $ticket = $this->Konsultasi_model->get_ticket($id_konsultasi);
        if(!$ticket) {
            show_404();
        }

        $data['ticket'] = $ticket;
        $data['messages'] = $this->Konsultasi_model->get_messages($id_konsultasi);
        $this->load->view('admin/detail_pesan', $data);
    }

    public function reply() {
        $id_konsultasi = $this->input->post('id_konsultasi');
        $pesan = $this->input->post('pesan');

        if($id_konsultasi && $pesan) {
            $data = [
                'id_konsultasi' => $id_konsultasi,
                'pengirim' => 'admin', // Tetap 'admin' agar di sisi user terlihat sebagai CS/Toko
                'pesan' => $pesan
            ];
            $this->Konsultasi_model->add_message($data);
        }
        
        redirect('karyawan/pesan/detail/' . $id_konsultasi);
    }
    
    public function tutup_tiket($id_konsultasi) {
         $this->Konsultasi_model->update_status($id_konsultasi, 'closed');
         redirect('karyawan/pesan');
    }
}
