<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Transaksi_model');
        $this->load->model('Obat_model');
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        // Cek hak akses
        if($this->session->userdata('role') == 'pemilik') {
            redirect('dashboard');
        }
    }
    
    public function penjualan() {
        $data['title'] = 'Transaksi Penjualan';
        $data['obat'] = $this->Obat_model->get_all_obat();
        
        $this->load->view('templates/header', $data);
        $this->load->view('transaksi/penjualan', $data);
        $this->load->view('templates/footer');
    }
    
    public function proses_penjualan() {
        $this->form_validation->set_rules('items', 'Items', 'required');
        
        if($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Tidak ada item dalam transaksi.');
            redirect('transaksi/penjualan');
        }
        
        // Generate nomor transaksi
        $no_transaksi = 'TRX-' . date('Ymd') . '-' . str_pad($this->Transaksi_model->get_next_id(), 4, '0', STR_PAD_LEFT);
        
        $items = json_decode($this->input->post('items'), true);
        $total_harga = 0;
        
        // Validasi stok dan hitung total
        foreach($items as $item) {
            $obat = $this->Obat_model->get_obat_by_id($item['id_obat']);
            if($obat->stok < $item['jumlah']) {
                $this->session->set_flashdata('error', 'Stok ' . $obat->nama_obat . ' tidak mencukupi!');
                redirect('transaksi/penjualan');
            }
            $total_harga += $item['subtotal'];
        }
        
        // Data transaksi
        $transaksi_data = array(
            'no_transaksi' => $no_transaksi,
            'id_user' => $this->session->userdata('user_id'),
            'tanggal' => date('Y-m-d'),
            'total_harga' => $total_harga,
            'metode_bayar' => $this->input->post('metode_bayar')
        );
        
        // Mulai transaksi database
        $this->db->trans_start();
        
        // Simpan transaksi
        $id_penjualan = $this->Transaksi_model->save_penjualan($transaksi_data);
        
        // Simpan detail dan update stok
        foreach($items as $item) {
            $detail_data = array(
                'id_penjualan' => $id_penjualan,
                'id_obat' => $item['id_obat'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga'],
                'subtotal' => $item['subtotal']
            );
            $this->Transaksi_model->save_detail_penjualan($detail_data);
            
            // Update stok
            $this->Obat_model->reduce_stock($item['id_obat'], $item['jumlah']);
            
            // Log stok
            $log_data = array(
                'id_obat' => $item['id_obat'],
                'id_user' => $this->session->userdata('user_id'),
                'jenis' => 'keluar',
                'jumlah_perubahan' => $item['jumlah'] * -1,
                'keterangan' => 'Penjualan ' . $no_transaksi
            );
            $this->Obat_model->add_stok_log($log_data);
        }
        
        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Transaksi gagal disimpan.');
        } else {
            $this->session->set_flashdata('success', 'Transaksi berhasil disimpan. No: ' . $no_transaksi);
        }
        
        redirect('transaksi/penjualan');
    }
    
    public function pembelian() {
        $data['title'] = 'Pembelian Obat';
        $data['obat'] = $this->Obat_model->get_all_obat();
        
        $this->load->view('templates/header', $data);
        $this->load->view('transaksi/pembelian', $data);
        $this->load->view('templates/footer');
    }
    
    public function proses_pembelian() {
        $this->form_validation->set_rules('supplier', 'Supplier', 'required');
        
        if($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Supplier harus diisi.');
            redirect('transaksi/pembelian');
        }
        
        // Generate nomor pembelian
        $no_pembelian = 'PBL-' . date('Ymd') . '-' . str_pad($this->Transaksi_model->get_next_pembelian_id(), 4, '0', STR_PAD_LEFT);
        
        $items = json_decode($this->input->post('items'), true);
        $total_beli = 0;
        
        // Hitung total
        foreach($items as $item) {
            $total_beli += $item['subtotal'];
        }
        
        // Data pembelian
        $pembelian_data = array(
            'no_pembelian' => $no_pembelian,
            'id_user' => $this->session->userdata('user_id'),
            'supplier' => $this->input->post('supplier'),
            'tanggal' => date('Y-m-d'),
            'total_beli' => $total_beli
        );
        
        $this->db->trans_start();
        
        // Simpan pembelian
        $id_pembelian = $this->Transaksi_model->save_pembelian($pembelian_data);
        
        // Simpan detail dan update stok
        foreach($items as $item) {
            $detail_data = array(
                'id_pembelian' => $id_pembelian,
                'id_obat' => $item['id_obat'],
                'jumlah' => $item['jumlah'],
                'harga_beli' => $item['harga_beli'],
                'subtotal' => $item['subtotal']
            );
            $this->Transaksi_model->save_detail_pembelian($detail_data);
            
            // Update stok
            $this->Obat_model->increase_stock($item['id_obat'], $item['jumlah']);
            
            // Log stok
            $log_data = array(
                'id_obat' => $item['id_obat'],
                'id_user' => $this->session->userdata('user_id'),
                'jenis' => 'masuk',
                'jumlah_perubahan' => $item['jumlah'],
                'keterangan' => 'Pembelian dari ' . $this->input->post('supplier')
            );
            $this->Obat_model->add_stok_log($log_data);
        }
        
        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Pembelian gagal disimpan.');
        } else {
            $this->session->set_flashdata('success', 'Pembelian berhasil disimpan.');
        }
        
        redirect('transaksi/pembelian');
    }
    
    public function get_obat_ajax($id) {
        $obat = $this->Obat_model->get_obat_by_id($id);
        echo json_encode($obat);
    }
}
