<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Jadwal_model');
        $this->load->model('User_model');
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        // Hanya admin yang bisa akses
        if($this->session->userdata('role') != 'admin') {
            redirect('dashboard');
        }
    }
    
    public function index() {
        $data['title'] = 'Manajemen Jadwal Kerja';
        $data['jadwal'] = $this->Jadwal_model->get_all_jadwal();
        $data['karyawan'] = $this->User_model->get_karyawan();
        
        $this->load->view('templates/header', $data);
        $this->load->view('jadwal/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function create() {
        $this->form_validation->set_rules('id_user', 'Karyawan', 'required');
        $this->form_validation->set_rules('hari[]', 'Hari', 'required');
        $this->form_validation->set_rules('jam_masuk[]', 'Jam Masuk', 'required');
        $this->form_validation->set_rules('jam_keluar[]', 'Jam Keluar', 'required');
        
        if($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Data tidak valid.');
            redirect('jadwal');
        }
        
        $id_user = $this->input->post('id_user');
        $hari = $this->input->post('hari');
        $jam_masuk = $this->input->post('jam_masuk');
        $jam_keluar = $this->input->post('jam_keluar');
        
        // Hapus jadwal lama untuk user ini
        $this->Jadwal_model->delete_by_user($id_user);
        
        // Simpan jadwal baru
        foreach($hari as $key => $day) {
            if(!empty($day) && !empty($jam_masuk[$key]) && !empty($jam_keluar[$key])) {
                $data = array(
                    'id_user' => $id_user,
                    'hari' => $day,
                    'jam_masuk' => $jam_masuk[$key],
                    'jam_keluar' => $jam_keluar[$key]
                );
                $this->Jadwal_model->create($data);
            }
        }
        
        $this->session->set_flashdata('success', 'Jadwal berhasil disimpan.');
        redirect('jadwal');
    }
    
    public function get_jadwal_by_user($id_user) {
        $jadwal = $this->Jadwal_model->get_by_user($id_user);
        echo json_encode($jadwal);
    }
    
    public function delete($id) {
        if($this->Jadwal_model->delete($id)) {
            $this->session->set_flashdata('success', 'Jadwal berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus jadwal.');
        }
        redirect('jadwal');
    }
    
    public function absensi() {
        $data['title'] = 'Data Absensi';
        $data['absensi'] = $this->Jadwal_model->get_absensi_today();
        
        $this->load->view('templates/header', $data);
        $this->load->view('jadwal/absensi', $data);
        $this->load->view('templates/footer');
    }
    
    public function check_in() {
        $id_user = $this->session->userdata('user_id');
        $waktu = date('H:i:s');
        
        if($this->Jadwal_model->check_in($id_user, $waktu)) {
            $this->session->set_flashdata('success', 'Check-in berhasil.');
        } else {
            $this->session->set_flashdata('error', 'Gagal check-in.');
        }
        redirect('dashboard');
    }
    
    public function check_out() {
        $id_user = $this->session->userdata('user_id');
        $waktu = date('H:i:s');
        
        if($this->Jadwal_model->check_out($id_user, $waktu)) {
            $this->session->set_flashdata('success', 'Check-out berhasil.');
        } else {
            $this->session->set_flashdata('error', 'Gagal check-out.');
        }
        redirect('dashboard');
    }
}