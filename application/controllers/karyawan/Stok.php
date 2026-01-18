<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Obat_model');
        $this->load->model('Stok_model');

        $this->load->library('session');
        // Cek Login & Role
        // Cek Login & Role (Admin & Karyawan diperbolehkan)
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $role = $this->session->userdata('role');
        if ($role !== 'karyawan' && $role !== 'admin') {
             redirect('auth');
        }

        // Misal kamu punya session user
        $this->data['user_id'] = $this->session->userdata('id_user');
    }

   public function index() {

    // Ambil semua log stok (join ke tabel obat & user)
    $this->data['logs'] = $this->Stok_model->get_stok_log();

    // Ambil obat kritis & habis
    $this->data['obat_kritis'] = $this->Stok_model->get_obat_kritis();
    $this->data['obat_habis']  = $this->Stok_model->get_obat_habis();

    // Ambil semua obat untuk dropdown modal
    $this->data['obat'] = $this->Obat_model->get_all_obat();

    // Ambil dead stock (60 hari)
    $this->data['dead_stock'] = $this->Stok_model->get_dead_stock(60);

    // Muat view
    $this->load->view('stok/index', $this->data);
}

public function export_log() {
    $logs = $this->Stok_model->get_stok_log();
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=log_stok_'.date('Y-m-d').'.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array('No', 'Waktu', 'Nama Obat', 'Jenis', 'Jumlah', 'Stok Sesudah', 'Petugas', 'Keterangan'));
    
    $no = 1;
    foreach ($logs as $log) {
        fputcsv($output, array(
            $no++,
            date('d/m/Y H:i', strtotime($log->created_at)),
            $log->nama_obat,
            ucfirst($log->jenis),
            $log->jumlah_perubahan,
            $log->stok_sesudah,
            $log->nama,
            $log->keterangan
        ));
    }
    
    fclose($output);
}

    public function tambah() {
        $data['obat'] = $this->Obat_model->get_all();
        $this->load->view('stok/tambah', $data);
    }

    public function tambah_stok() {
        $id_obat     = $this->input->post('id_obat');
        $jenis       = $this->input->post('jenis');     // masuk / keluar / adjust
        $jumlah      = $this->input->post('jumlah');    // angka
        $keterangan  = $this->input->post('keterangan');

        // Ambil data obat
        $obat = $this->Obat_model->get_by_id($id_obat);

        if (!$obat) {
            $this->session->set_flashdata('error', 'Obat tidak ditemukan.');
            redirect('karyawan/stok/tambah');
        }

        // Simpan stok sebelum
        $stok_sebelum = $obat->stok;

        // Hitung stok baru + jumlah perubahan
        if ($jenis == 'masuk') {

            $new_stock = $obat->stok + $jumlah;
            $jumlah_perubahan = $jumlah;

        } elseif ($jenis == 'keluar') {

            if ($jumlah > $obat->stok) {
                $this->session->set_flashdata('error', 'Stok tidak cukup!');
                redirect('karyawan/stok/tambah');
            }

            $new_stock = $obat->stok - $jumlah;
            $jumlah_perubahan = -$jumlah;

        } else { // adjust → set nilai baru

            $new_stock = $jumlah;
            $jumlah_perubahan = $jumlah - $obat->stok;

        }

        // Stok sesudah
        $stok_sesudah = $new_stock;

        // Update stok obat
        $this->Obat_model->update_stock($id_obat, $new_stock);

        // Insert log stok
        $log_data = [
            'id_obat'          => $id_obat,
            'id_user'          => $this->data['user_id'],
            'jenis'            => $jenis,
            'stok_sebelum'     => $stok_sebelum,
            'stok_sesudah'     => $stok_sesudah,
            'jumlah_perubahan' => $jumlah_perubahan,
            'keterangan'       => $keterangan,
            'created_at'       => date('Y-m-d H:i:s')
        ];

        $this->Stok_model->add_log($log_data);

        $this->session->set_flashdata('success', 'Perubahan stok berhasil disimpan.');
        redirect('karyawan/stok');
    }
}
