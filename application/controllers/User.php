<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('User_model');
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        // Hanya admin dan pemilik yang bisa akses
        if($this->session->userdata('role') == 'karyawan') {
            redirect('dashboard');
        }
    }
    
    public function index() {
        $data['title'] = 'Manajemen Pengguna';
        $data['users'] = $this->User_model->get_all_users();
        
        $this->load->view('templates/header', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function create() {
        if($this->session->userdata('role') != 'admin') {
            redirect('user');
        }
        
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|max_length[30]|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules('role', 'Role', 'required');
        
        if($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Pengguna';
            $this->load->view('templates/header', $data);
            $this->load->view('user/create', $data);
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => $this->input->post('role'),
                'status' => 'active'
            );
            
            if($this->User_model->create($data)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengguna.');
            }
            redirect('user');
        }
    }
    
    public function edit($id) {
        if($this->session->userdata('role') != 'admin') {
            redirect('user');
        }
        
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('role', 'Role', 'required');
        
        if($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Pengguna';
            $data['user'] = $this->User_model->get_user_by_id($id);
            
            $this->load->view('templates/header', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role')
            );
            
            // Jika password diisi
            if($this->input->post('password')) {
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }
            
            if($this->User_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil diupdate.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate pengguna.');
            }
            redirect('user');
        }
    }
    
    public function toggle_status($id) {
        if($this->session->userdata('role') != 'admin') {
            redirect('user');
        }
        
        $user = $this->User_model->get_user_by_id($id);
        $new_status = ($user->status == 'active') ? 'inactive' : 'active';
        
        if($this->User_model->update_status($id, $new_status)) {
            $this->session->set_flashdata('success', 'Status pengguna berhasil diubah.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah status pengguna.');
        }
        redirect('user');
    }
    
    public function delete($id) {
        if($this->session->userdata('role') != 'admin') {
            redirect('user');
        }
        
        // Tidak boleh menghapus diri sendiri
        if($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus akun sendiri.');
            redirect('user');
        }
        
        if($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna.');
        }
        redirect('user');
    }
}