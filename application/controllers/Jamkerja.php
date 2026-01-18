<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jamkerja extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Jamkerja_model');
        $this->load->model('Konsultasi_model');
    }

    public function index() {
        $data['jamkerja'] = $this->Jamkerja_model->get_all();
        $data['karyawan'] = $this->Jamkerja_model->get_karyawan();
        $data['unread_count'] = $this->Konsultasi_model->count_unread(); // Pass variable
        $this->load->view('jamkerja/index', $data);
    }

    // =====================
    // SIMPAN JAM MASUK
    // =====================
    public function simpan() {

        if (!$this->input->post('id_user')) {
            show_error('Karyawan belum dipilih');
        }

        $data = [
            'id_user'    => $this->input->post('id_user'),
            'tanggal'    => $this->input->post('tanggal'),
            'jam_masuk'  => $this->input->post('jam_masuk'),
            'keterangan' => $this->input->post('keterangan')
        ];

        $this->Jamkerja_model->insert($data);
        redirect('jamkerja');
    }

    // =====================
    // JAM PULANG
    // =====================
    public function pulang($id) {

        $row = $this->db
            ->where('id_jam', $id)
            ->get('jam_kerja')
            ->row();

        if (!$row) {
            show_error('Data jam kerja tidak ditemukan');
        }

        // ⛔ sudah pulang
        if ($row->jam_pulang) {
            redirect('jamkerja');
        }

        $jam_pulang = date('H:i:s');

        $masuk  = strtotime($row->jam_masuk);
        $pulang = strtotime($jam_pulang);
        $total  = round(($pulang - $masuk) / 3600, 2);

        $this->Jamkerja_model->update_pulang(
            $id,
            $jam_pulang,
            $total
        );

        redirect('jamkerja');
    }
}
