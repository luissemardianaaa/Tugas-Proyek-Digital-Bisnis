<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    /* a. laporan penjualan */
    public function penjualan($bulan, $tahun)
    {
        $sql = "
            SELECT tanggal, SUM(total) as total FROM (
                SELECT DATE(created_at) AS tanggal, total_harga AS total 
                FROM penjualan 
                WHERE MONTH(created_at) = ? AND YEAR(created_at) = ? AND status != 'dibatalkan'
                
                UNION ALL
                
                SELECT tanggal, total_harga AS total 
                FROM transaksi_penjualan 
                WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ?

                UNION ALL

                SELECT DATE(tanggal_transaksi) AS tanggal, total_harga AS total
                FROM transaksi
                WHERE MONTH(tanggal_transaksi) = ? AND YEAR(tanggal_transaksi) = ? AND status_pembayaran != 'dibatalkan'
            ) AS combined_sales 
            GROUP BY tanggal 
            ORDER BY tanggal ASC
        ";
        return $this->db->query($sql, [$bulan, $tahun, $bulan, $tahun, $bulan, $tahun])->result();
    }

    /* b. laporan pembelian */
    public function pembelian($bulan, $tahun) {
        $sql = "
            SELECT tanggal, SUM(total) as total FROM (
                SELECT tanggal, total_harga AS total 
                FROM pembelian 
                WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ?
                
                UNION ALL
                
                SELECT tanggal, total_beli AS total 
                FROM transaksi_pembelian 
                WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ?
            ) AS combined_purchases 
            GROUP BY tanggal 
            ORDER BY tanggal ASC
        ";
        return $this->db->query($sql, [$bulan, $tahun, $bulan, $tahun])->result();
    }
    /* c. rekap keuangan */
    public function rekap_bulanan($bulan, $tahun)
    {
        // 1. Hitung Penjualan (Checkout + Manual)
        // Penjualan Table
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('MONTH(created_at)', $bulan);
        $this->db->where('YEAR(created_at)', $tahun);
        $this->db->where('status !=', 'dibatalkan');
        $res1 = $this->db->get('penjualan')->row();
        
        // Transaksi_penjualan Table
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $res2 = $this->db->get('transaksi_penjualan')->row();

        // Transaksi Table (Set C)
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('MONTH(tanggal_transaksi)', $bulan);
        $this->db->where('YEAR(tanggal_transaksi)', $tahun);
        $this->db->where('status_pembayaran !=', 'dibatalkan');
        $res5 = $this->db->get('transaksi')->row();

        $total_penjualan = (float)($res1->total ?? 0) + (float)($res2->total ?? 0) + (float)($res5->total ?? 0);
        
        // 2. Hitung Pembelian (Stock Masal + Manual)
        // Pembelian Table
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $res3 = $this->db->get('pembelian')->row();

        // Transaksi_pembelian Table
        $this->db->select_sum('total_beli', 'total');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $res4 = $this->db->get('transaksi_pembelian')->row();

        $total_pembelian = (float)($res3->total ?? 0) + (float)($res4->total ?? 0);

        return [
            'penjualan' => $total_penjualan,
            'pembelian' => $total_pembelian,
            'laba'      => $total_penjualan - $total_pembelian
        ];
    }
    /* d. obat terlaris */
    public function obat_terlaris() {
        return $this->db
            ->select('obat.nama_obat, SUM(detail_penjualan.jumlah) AS total_terjual')
            ->from('detail_penjualan')
            ->join('obat', 'obat.id_obat = detail_penjualan.id_obat')
            ->group_by('detail_penjualan.id_obat')
            ->order_by('total_terjual', 'DESC')
            ->limit(5)
            ->get()
            ->result();
    }

    /* e. total omzet */
    public function total_omzet($bulan = null, $tahun = null) {
        // 1. Penjualan Online
        $this->db->select_sum('total_harga', 'total');
        if ($bulan) $this->db->where('MONTH(created_at)', $bulan);
        if ($tahun) $this->db->where('YEAR(created_at)', $tahun);
        $this->db->where('status !=', 'dibatalkan');
        $res1 = $this->db->get('penjualan')->row();

        // 2. Penjualan Manual
        $this->db->select_sum('total_harga', 'total');
        if ($bulan) $this->db->where('MONTH(tanggal)', $bulan);
        if ($tahun) $this->db->where('YEAR(tanggal)', $tahun);
        $res2 = $this->db->get('transaksi_penjualan')->row();

        // 3. Transaksi (Online B)
        $this->db->select_sum('total_harga', 'total');
        if ($bulan) $this->db->where('MONTH(tanggal_transaksi)', $bulan);
        if ($tahun) $this->db->where('YEAR(tanggal_transaksi)', $tahun);
        $this->db->where('status_pembayaran !=', 'dibatalkan');
        $res3 = $this->db->get('transaksi')->row();

        return (object)['total' => (float)($res1->total ?? 0) + (float)($res2->total ?? 0) + (float)($res3->total ?? 0)];
    }
    public function jumlah_transaksi($bulan, $tahun)
    {
        $c1 = $this->db
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->where('status !=', 'dibatalkan')
            ->from('penjualan')
            ->count_all_results();

        $c2 = $this->db
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->from('transaksi_penjualan')
            ->count_all_results();

        $c3 = $this->db
            ->where('MONTH(tanggal_transaksi)', $bulan)
            ->where('YEAR(tanggal_transaksi)', $tahun)
            ->where('status_pembayaran !=', 'dibatalkan')
            ->from('transaksi')
            ->count_all_results();

        return $c1 + $c2 + $c3;
    }
public function getLaporanPembelian()
{
    // Detailed purchase report with drug name, quantity, and employee
    $this->db->select('pembelian.tanggal, obat.nama_obat, pembelian.jumlah, pembelian.total_harga, users.nama as nama_petugas');
    $this->db->from('pembelian');
    $this->db->join('obat', 'obat.id_obat = pembelian.id_obat', 'left');
    $this->db->join('users', 'users.id_user = pembelian.id_user', 'left');
    $this->db->order_by('pembelian.tanggal', 'DESC');
    return $this->db->get()->result();
}

public function getLaporanPenjualan()
{
    // Detailed sales report with drug name, quantity, cashier, and confirming employee
    $this->db->select('penjualan.tanggal, penjualan.created_at, obat.nama_obat, detail_penjualan.jumlah, detail_penjualan.harga, (detail_penjualan.jumlah * detail_penjualan.harga) as subtotal, kasir.nama as nama_kasir, petugas.nama as nama_petugas_konfirmasi, penjualan.status');
    $this->db->from('penjualan');
    $this->db->join('detail_penjualan', 'detail_penjualan.id_penjualan = penjualan.id_penjualan', 'left');
    $this->db->join('obat', 'obat.id_obat = detail_penjualan.id_obat', 'left');
    $this->db->join('users as kasir', 'kasir.id_user = penjualan.id_karyawan', 'left');
    $this->db->join('users as petugas', 'petugas.id_user = penjualan.id_petugas_konfirmasi', 'left');
    $this->db->where('penjualan.status !=', 'dibatalkan');
    $this->db->order_by('penjualan.created_at', 'DESC');
    return $this->db->get()->result();
}
}
