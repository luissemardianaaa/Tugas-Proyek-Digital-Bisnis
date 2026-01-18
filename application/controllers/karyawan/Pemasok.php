<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemasok extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Pemasok_model');
        $this->load->library('form_validation');
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

    // LIST DATA
    public function index() {
        $data['pemasok'] = $this->Pemasok_model->get_all();
        $this->load->view('pemasok/index', $data);
    }

    // FORM EDIT
    public function edit($id) {
        $data['pemasok'] = $this->Pemasok_model->get_by_id($id);

        // CEGAH ERROR JIKA DATA TIDAK ADA
        if (!$data['pemasok']) {
            show_404();
        }

        $this->load->view('pemasok/edit', $data);
    }

    // FORM TAMBAH
    public function create() {
        $data['kode'] = $this->Pemasok_model->generate_kode_pemasok();
        $this->load->view('pemasok/create', $data);
    }

    // PROSES SIMPAN
    public function store() {
        $this->form_validation->set_rules('nama_pemasok', 'Nama Pemasok', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = [
                'kode_pemasok' => $this->input->post('kode_pemasok'),
                'nama_pemasok' => $this->input->post('nama_pemasok'),
                'alamat'       => $this->input->post('alamat'),
                'telepon'      => $this->input->post('telepon'),
                'email'        => $this->input->post('email'),
                'keterangan'   => $this->input->post('keterangan'),
                'created_at'   => date('Y-m-d H:i:s')
            ];

            $this->Pemasok_model->insert($data);
            $this->session->set_flashdata('success', 'Data pemasok berhasil ditambahkan');
            redirect('karyawan/pemasok');
        }
    }

    // UPDATE DATA
    public function update($id) {
        $data = [
            'kode_pemasok' => $this->input->post('kode_pemasok'),
            'nama_pemasok' => $this->input->post('nama_pemasok'),
            'alamat'       => $this->input->post('alamat'),
            'telepon'      => $this->input->post('telepon'),
            'email'        => $this->input->post('email'),
            'keterangan'   => $this->input->post('keterangan'),
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        $this->Pemasok_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data pemasok berhasil diperbarui');
        redirect('karyawan/pemasok');
    }

    // HAPUS DATA
    public function delete($id) {
        $this->Pemasok_model->delete($id);
        $this->session->set_flashdata('success', 'Data pemasok berhasil dihapus');
        redirect('karyawan/pemasok');
    }
}
