<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Kategori_model');
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
   public function index()
{
    $data['kategori'] = $this->Kategori_model->get_all();
    $data['total_kategori'] = count($data['kategori']);
    $this->load->view('kategori/index', $data);
}
}
