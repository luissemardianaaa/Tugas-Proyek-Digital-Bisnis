<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function get_laporan_penjualan($start_date, $end_date) {
        $this->db->select('transaksi_penjualan.*, users.nama');
        $this->db->from('transaksi_penjualan');
        $this->db->join('users', 'users.id_user = transaksi_penjualan.id_user');
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_total_penjualan_periode($start_date, $end_date) {
        $this->db->select_sum('total_harga');
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $query = $this->db->get('transaksi_penjualan');
        return $query->row()->total_harga;
    }
    
    public function get_pendapatan_bulan($bulan) {
        $this->db->select_sum('total_harga');
        $this->db->like('tanggal', $bulan, 'after');
        $query = $this->db->get('transaksi_penjualan');
        return $query->row()->total_harga ?: 0;
    }
    
    public function get_pengeluaran_bulan($bulan) {
        $this->db->select_sum('total_beli');
        $this->db->like('tanggal', $bulan, 'after');
        $query = $this->db->get('transaksi_pembelian');
        return $query->row()->total_beli ?: 0;
    }
    
    public function get_laba_rugi_bulan($bulan) {
        $pendapatan = $this->get_pendapatan_bulan($bulan);
        $pengeluaran = $this->get_pengeluaran_bulan($bulan);
        return $pendapatan - $pengeluaran;
    }
    
    public function get_grafik_pendapatan_tahunan($tahun) {
        $this->db->select('MONTH(tanggal) as bulan, SUM(total_harga) as total');
        $this->db->like('tanggal', $tahun, 'after');
        $this->db->group_by('MONTH(tanggal)');
        $this->db->order_by('MONTH(tanggal)', 'ASC');
        return $this->db->get('transaksi_penjualan')->result();
    }
    
    public function get_obat_terlaris($limit = 10) {
        $this->db->select('obat.nama_obat, SUM(detail_penjualan.jumlah) as total_terjual');
        $this->db->from('detail_penjualan');
        $this->db->join('obat', 'obat.id_obat = detail_penjualan.id_obat');
        $this->db->group_by('detail_penjualan.id_obat');
        $this->db->order_by('total_terjual', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
}