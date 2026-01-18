<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('session');
        // $this->load->model('Auth_model'); 
    }

    public function index() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'karyawan') {
             redirect('karyawan/dashboard');
        }
        $this->load->view('auth/karyawan');
    }

    public function login() {
        // Pastikan login hanya diproses jika ada data POST (mencegah error saat diakses langsung)
        if ($this->input->method() !== 'post') {
            redirect('karyawan/auth');
            return;
        }

        $username = trim($this->input->post('username'));
        $password = $this->input->post('password');

        // 1. Validasi Input
        if (!$username || !$password) {
            $this->session->set_flashdata('error', 'Username dan Password wajib diisi!');
            redirect('karyawan/auth');
        }

        // 2. Ambil data dari tabel users
        $user = $this->db->get_where('users', ['username' => $username])->row();

        if ($user) {
            // 3. Validasi Status Aktif
            if ($user->status != 'aktif' && $user->status != 'active') {
                $this->session->set_flashdata('error', 'Akun tidak aktif.');
                redirect('karyawan/auth');
            }

            // 4. Validasi Role Karyawan (Hanya role karyawan yang bisa masuk)
            if ($user->role !== 'karyawan') {
                 $this->session->set_flashdata('error', 'Login ditolak. Akun Anda bukan Karyawan.');
                 redirect('karyawan/auth');
            }

            // 5. Verifikasi Password
            if (password_verify($password, $user->password)) {
                
                // 6. Set Session (Role hanya karyawan)
                $sess_data = [
                    'logged_in' => true,
                    'id_user'   => $user->id_user,
                    'nama'      => $user->nama,
                    'role'      => 'karyawan' // Explicitly set to karyawan
                ];
                $this->session->set_userdata($sess_data);

                // 7. Arahkan ke controller karyawan/dashboard
                redirect('karyawan/dashboard');

            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('karyawan/auth');
            }

        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan!');
            redirect('karyawan/auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('karyawan/auth');
    }
}
