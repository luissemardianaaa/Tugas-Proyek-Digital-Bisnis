<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Auth_model'); // optional jika pakai model
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    // Halaman login
    public function index() {
        $this->load->view('auth/login');
    }

    // Halaman register admin
    public function register_admin() {
        $this->load->view('auth/register_admin');
    }

    // Halaman register
    public function register() {
        $this->load->view('auth/register');
    }

    // Proses register
    public function register_action() {
        $nama     = trim($this->input->post('nama'));
        $email    = trim($this->input->post('email'));
        $username = trim($this->input->post('username'));
        $password = $this->input->post('password');

        // Cek apakah username sudah ada
        $existing = $this->db->get_where('users', ['username' => $username])->row();
        if ($existing) {
            $this->session->set_flashdata('error', 'Username sudah digunakan!');
            redirect('auth/register');
            return;
        }

        // hashing password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $role = $this->input->post('role') ? $this->input->post('role') : 'pelanggan';

        // Penyesuaian data berdasarkan role
        $data = [
            'nama'     => $role === 'admin' ? $username : $nama, // Admin pakai username sebagai nama
            'email'    => $email,
            'no_hp'    => $this->input->post('no_hp'),
            'username' => $username,
            'password' => $password_hash,
            'role'     => $role,
            'status'   => ($role == 'karyawan') ? 'nonaktif' : 'aktif'
        ];

        // Kota & Alamat hanya mandatory untuk Pelanggan
        if ($role == 'pelanggan') {
            $data['kota']   = $this->input->post('kota');
            $data['alamat'] = $this->input->post('alamat');
        } else {
            // Untuk Admin & Karyawan, set default kosong sesuai request (tidak perlu kota/alamat)
            $data['kota']   = null;
            $data['alamat'] = null;
        }

        $this->db->insert('users', $data);

        if ($role == 'karyawan') {
            $this->session->set_flashdata('success', 'Registrasi berhasil! Akun Karyawan Anda sedang menunggu verifikasi Admin.');
        } elseif ($role == 'admin') {
            $this->session->set_flashdata('success', 'Registrasi Admin berhasil! Silakan login menggunakan Email.');
        } else {
            $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
        }
        redirect('auth/login');
    }
    
    // Proses login
    public function login()
    {
        // Pastikan login hanya diproses jika ada data POST (mencegah error saat diakses langsung)
        if ($this->input->method() !== 'post') {
            redirect('auth');
            return;
        }

        $username = trim($this->input->post('username'));
        $password = $this->input->post('password');

        // 2. Tentukan apakah menggunakan Email (untuk Admin) atau Username (Lainnya)
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            // Jika format email, cari di tabel users dan pastikan role-nya admin
            $user = $this->db->get_where('users', [
                'email' => $username,
                'role'  => 'admin'
            ])->row();
            
            if (!$user) {
                $this->session->set_flashdata('error', 'Login Admin gagal. Pastikan Email Anda benar.');
                redirect('auth');
                return;
            }
        } else {
            // Jika bukan email, cari berdasarkan username (untuk Karyawan/Pelanggan)
            $user = $this->db->get_where('users', [
                'username' => $username
            ])->row();

            // Cegah Admin login pakai username
            if ($user && $user->role === 'admin') {
                $this->session->set_flashdata('error', 'Admin wajib login menggunakan Email!');
                redirect('auth');
                return;
            }
            // Add status check for non-admin users logging in with username
            if ($user && $user->status !== 'aktif') {
                $this->session->set_flashdata('error', 'Akun tidak aktif. Hubungi admin.');
                redirect('auth');
                return;
            }
        }

        // Cek apakah user ditemukan
        if (!$user) {
            $this->session->set_flashdata('error', 'Username tidak ditemukan!');
            redirect('auth');
            return;
        }

        // Cek status akun (aktif)
        if ($user->status !== 'aktif') {
            $this->session->set_flashdata('error', 'Akun Anda tidak aktif. Hubungi admin.');
            redirect('auth');
            return;
        }

        // Verifikasi password
        if (!password_verify($password, $user->password)) {
            $this->session->set_flashdata('error', 'Password salah!');
            redirect('auth');
            return;
        }

        // Update track login (Tambah kolom jika belum ada)
        if (!$this->db->field_exists('last_login', 'users')) {
            $this->load->dbforge();
            $this->dbforge->add_column('users', [
                'last_login' => ['type' => 'DATETIME', 'null' => TRUE]
            ]);
        }
        $this->db->where('id_user', $user->id_user)->update('users', ['last_login' => date('Y-m-d H:i:s')]);

        // Login berhasil - simpan session
        $this->session->set_userdata([
            'logged_in' => true,
            'id_user'   => $user->id_user,
            'nama'      => $user->nama,
            'no_hp'     => $user->no_hp,
            'kota'      => $user->kota,
            'alamat'    => $user->alamat,
            'role'      => $user->role
        ]);

        // Redirect berdasarkan role
        switch ($user->role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'karyawan':
                redirect('karyawan/dashboard');
                break;
            case 'pelanggan':
            default:
                redirect('home');
                break;
        }
    }

    // Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }

    // Optional: helper cek login
    private function check_login() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }
}
