<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    
    public function get_total_obat() {
        return $this->db->count_all('obat');
    }
     public function hitungObat() {
        return $this->db->count_all('obat');
     }
      public function hitungKategori()
{
    return $this->db
        ->select('COUNT(DISTINCT jenis) AS total')
        ->from('obat')
        ->get()
        ->row()
        ->total;
}
    // Hitung total pemasok
    public function hitungPemasok() {
        return $this->db->count_all('pemasok');
    }

    // Hitung total unit/satuan obat
    public function hitungUnit()
{
    $this->db->select('COUNT(DISTINCT satuan) AS total');
    $query = $this->db->get('obat')->row();
    return $query->total;
}

    // Total nilai penjualan (Lengkap: Online + Manual + Transaksi)
    public function totalPenjualan($bulan = null, $tahun = null) {
        // Online
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('status !=', 'dibatalkan');
        if ($bulan) $this->db->where('MONTH(created_at)', $bulan);
        if ($tahun) $this->db->where('YEAR(created_at)', $tahun);
        $res1 = $this->db->get('penjualan')->row();

        // Manual
        $this->db->select_sum('total_harga', 'total');
        if ($bulan) $this->db->where('MONTH(tanggal)', $bulan);
        if ($tahun) $this->db->where('YEAR(tanggal)', $tahun);
        $res2 = $this->db->get('transaksi_penjualan')->row();

        // Transaksi (Set C)
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('status_pembayaran !=', 'dibatalkan');
        if ($bulan) $this->db->where('MONTH(tanggal_transaksi)', $bulan);
        if ($tahun) $this->db->where('YEAR(tanggal_transaksi)', $tahun);
        $res3 = $this->db->get('transaksi')->row();

        return (float)($res1->total ?? 0) + (float)($res2->total ?? 0) + (float)($res3->total ?? 0);
    }

    // Total nilai pembelian (Lengkap: Stok + Manual)
    public function totalPembelian($bulan = null, $tahun = null) {
        // Stok
        $this->db->select_sum('total_harga', 'total');
        if ($bulan) $this->db->where('MONTH(tanggal)', $bulan);
        if ($tahun) $this->db->where('YEAR(tanggal)', $tahun);
        $res3 = $this->db->get('pembelian')->row();

        // Manual
        $this->db->select_sum('total_beli', 'total');
        if ($bulan) $this->db->where('MONTH(tanggal)', $bulan);
        if ($tahun) $this->db->where('YEAR(tanggal)', $tahun);
        $res4 = $this->db->get('transaksi_pembelian')->row();

        return (float)($res3->total ?? 0) + (float)($res4->total ?? 0);
    }
    public function get_total_transaksi_hari() {
        $today = date('Y-m-d');
        $this->db->where('DATE(tanggal)', $today);
        return $this->db->count_all_results('transaksi_penjualan');
    }
    
    public function get_obat_habis() {
        // stok <= stok_minimum (kolom ke kolom)
        $this->db->where('stok <= stok_minimum', NULL, FALSE);
        return $this->db->count_all_results('obat');
    }
    
    public function get_grafik_penjualan_bulan() {
        $bulan_ini = date('Y-m');
        $this->db->select('DATE(tanggal) as tanggal, SUM(total_harga) as total');
        $this->db->like('tanggal', $bulan_ini, 'after');
        $this->db->group_by('DATE(tanggal)');
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get('transaksi_penjualan')->result();
    }
    
    public function get_total_pendapatan_hari() {
        $today = date('Y-m-d');
        $this->db->select_sum('total_harga');
        $this->db->where('DATE(tanggal)', $today);
        $q = $this->db->get('transaksi_penjualan')->row();
        return $q->total_harga ?? 0;
    }
    public function grafikPenjualanHarian() {
        $start_date = date('Y-m-d', strtotime('-7 days'));
        $sql = "SELECT hari, SUM(total) as total FROM (
                    SELECT DATE_FORMAT(created_at, '%d/%m') AS hari, total_harga AS total, created_at as original_date
                    FROM penjualan 
                    WHERE created_at >= ? AND status != 'dibatalkan'
                    
                    UNION ALL
                    
                    SELECT DATE_FORMAT(tanggal, '%d/%m') AS hari, total_harga AS total, tanggal as original_date
                    FROM transaksi_penjualan 
                    WHERE tanggal >= ?
                ) AS combined 
                GROUP BY hari 
                ORDER BY original_date ASC";
        return $this->db->query($sql, array($start_date, $start_date))->result_array();
    }

    public function grafikPenjualanBulanan() {
        $start_date = date('Y-m-d', strtotime('-12 months'));
        $sql = "SELECT bulan, SUM(total) as total FROM (
                    SELECT DATE_FORMAT(created_at, '%b %Y') AS bulan, total_harga AS total, created_at as original_date
                    FROM penjualan 
                    WHERE created_at >= ? AND status != 'dibatalkan'
                    
                    UNION ALL
                    
                    SELECT DATE_FORMAT(tanggal, '%b %Y') AS bulan, total_harga AS total, tanggal as original_date
                    FROM transaksi_penjualan 
                    WHERE tanggal >= ?
                ) AS combined 
                GROUP BY DATE_FORMAT(original_date, '%Y-%m') 
                ORDER BY original_date ASC";
        return $this->db->query($sql, array($start_date, $start_date))->result_array();
    }

}
