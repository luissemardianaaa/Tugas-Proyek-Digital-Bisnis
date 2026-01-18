<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {
    
    public function get_next_id() {
        $this->db->select('MAX(id_penjualan) as max_id');
        $query = $this->db->get('transaksi_penjualan');
        $result = $query->row();
        return $result ? $result->max_id + 1 : 1;
    }
    
    public function get_next_pembelian_id() {
        $this->db->select('MAX(id_pembelian) as max_id');
        $query = $this->db->get('transaksi_pembelian');
        $result = $query->row();
        return $result ? $result->max_id + 1 : 1;
    }
    
    public function save_penjualan($data) {
        $this->db->insert('transaksi_penjualan', $data);
        return $this->db->insert_id();
    }
    
    public function save_detail_penjualan($data) {
        return $this->db->insert('detail_penjualan', $data);
    }
    
    public function save_pembelian($data) {
        $this->db->insert('transaksi_pembelian', $data);
        return $this->db->insert_id();
    }
    
    public function save_detail_pembelian($data) {
        return $this->db->insert('detail_pembelian', $data);
    }
    
    public function get_transaksi_hari_ini() {
        $today = date('Y-m-d');
        $this->db->where('tanggal', $today);
        $this->db->join('users', 'users.id_user = transaksi_penjualan.id_user');
        return $this->db->get('transaksi_penjualan')->result();
    }
    
    public function get_detail_transaksi($id_penjualan) {
        $this->db->select('detail_penjualan.*, obat.nama_obat, obat.satuan');
        $this->db->from('detail_penjualan');
        $this->db->join('obat', 'obat.id_obat = detail_penjualan.id_obat');
        $this->db->where('id_penjualan', $id_penjualan);
        return $this->db->get()->result();
    }
}