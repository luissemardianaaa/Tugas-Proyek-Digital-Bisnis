<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_model extends CI_Model {

 public function get_all()
    {
        return $this->db
            ->select('pb.*, o.nama_obat, p.nama_pemasok')
            ->from('pembelian pb')
            ->join('obat o', 'o.id_obat = pb.id_obat', 'left')
            ->join('pemasok p', 'p.id_pemasok = pb.id_pemasok', 'left')
            ->order_by('pb.id_pembelian', 'DESC')
            ->get()
            ->result();
    }
    public function save_transaksi($data) {
        $this->db->insert('transaksi_pembelian', $data);
        return $this->db->insert_id();
}

    public function save_detail($data) {
        return $this->db->insert('detail_pembelian', $data);
    }

    public function generate_purchase_number() {
        $prefix = 'PBL';
        $date   = date('Ymd');

        $this->db->select('MAX(no_pembelian) as last_purchase');
        $this->db->like('no_pembelian', $prefix . $date, 'after');
        $row = $this->db->get('transaksi_pembelian')->row();

        $next = ($row && $row->last_purchase)
            ? ((int) substr($row->last_purchase, -4) + 1)
            : 1;

        return $prefix . $date . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /* =======================
       DATA PEMBELIAN
    ======================== */

    public function get_transaksi_by_id($id) {
        return $this->db
            ->select('tp.*, u.nama as nama_pembeli,
                      p.nama_pemasok, p.telepon, p.email')
            ->from('transaksi_pembelian tp')
            ->join('users u', 'u.id_user = tp.id_user')
            ->join('pemasok p', 'p.id_pemasok = tp.id_pemasok', 'left')
            ->where('tp.id_pembelian', $id)
            ->get()
            ->row();
    }

    public function get_detail_by_transaksi($id_pembelian) {
        return $this->db
            ->select('dp.*, o.kode_obat, o.nama_obat, o.satuan')
            ->from('detail_pembelian dp')
            ->join('obat o', 'o.id_obat = dp.id_obat')
            ->where('dp.id_pembelian', $id_pembelian)
            ->get()
            ->result();
    }

    public function get_pembelian_periode($start_date, $end_date) {
        return $this->db
            ->select('tp.*, u.nama as nama_pembeli, p.nama_pemasok')
            ->from('transaksi_pembelian tp')
            ->join('users u', 'u.id_user = tp.id_user')
            ->join('pemasok p', 'p.id_pemasok = tp.id_pemasok', 'left')
            ->where('DATE(tp.tanggal) >=', $start_date)
            ->where('DATE(tp.tanggal) <=', $end_date)
            ->order_by('tp.tanggal', 'DESC')
            ->get()
            ->result();
    }

    /* =======================
       RINGKASAN & STATISTIK
    ======================== */

 public function get_pembelian_hari_ini() {
    $today = date('Y-m-d');

    return $this->db
        ->select('tp.*, u.nama as nama_pembeli, p.nama_pemasok')
        ->from('transaksi_pembelian tp')
        ->join('users u', 'u.id_user = tp.id_user')
        ->join('pemasok p', 'p.id_pemasok = tp.id_pemasok', 'left')
        ->where('DATE(tp.tanggal)', $today)
        ->order_by('tp.tanggal', 'DESC') // ✅ FIX DI SINI
        ->get()
        ->result();
}


    public function get_total_pembelian_bulan($bulan) {
        $row = $this->db
            ->select_sum('total_beli')
            ->like('tanggal', $bulan, 'after')
            ->get('transaksi_pembelian')
            ->row();

        return $row->total_beli ?? 0;
    }
public function insertPembelian($data)
{
    return $this->db->insert('pembelian', $data);
}
    public function get_statistik_pembelian($days = 7) {
        $start = date('Y-m-d', strtotime("-$days days"));

        return $this->db
            ->select('
                DATE(tanggal) as tanggal,
                COUNT(*) as jumlah_transaksi,
                SUM(total_beli) as total_pembelian,
                AVG(total_beli) as rata_transaksi
            ')
            ->where('DATE(tanggal) >=', $start)
            ->group_by('DATE(tanggal)')
            ->order_by('tanggal', 'ASC')
            ->get('transaksi_pembelian')
            ->result();
    }

    /* =======================
       PEMBELIAN PER PEMASOK
    ======================== */

    public function get_pembelian_by_pemasok($id_pemasok, $start = null, $end = null) {
        $this->db
            ->select('tp.*, u.nama as nama_pembeli')
            ->from('transaksi_pembelian tp')
            ->join('users u', 'u.id_user = tp.id_user')
            ->where('tp.id_pemasok', $id_pemasok);

        if ($start && $end) {
            $this->db->where('DATE(tp.tanggal) >=', $start);
            $this->db->where('DATE(tp.tanggal) <=', $end);
        }

        return $this->db
            ->order_by('tp.tanggal', 'DESC')
            ->get()
            ->result();
    }

    /* =======================
       PRODUK TERBANYAK DIBELI
    ======================== */

    public function get_obat_terbanyak_dibeli($limit = 10, $start = null, $end = null) {
        $this->db
            ->select('o.id_obat, o.kode_obat, o.nama_obat,
                      SUM(dp.jumlah) as total_dibeli,
                      SUM(dp.subtotal) as total_nilai')
            ->from('detail_pembelian dp')
            ->join('obat o', 'o.id_obat = dp.id_obat')
            ->join('transaksi_pembelian tp', 'tp.id_pembelian = dp.id_pembelian');

        if ($start && $end) {
            $this->db->where('DATE(tp.tanggal) >=', $start);
            $this->db->where('DATE(tp.tanggal) <=', $end);
        }

        return $this->db
            ->group_by('dp.id_obat')
            ->order_by('total_dibeli', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    /* =======================
       PURCHASE ORDER
    ======================== */

    public function get_purchase_order($id_pembelian) {
        return $this->db
            ->select('
                tp.*,
                u.nama as nama_pembeli,
                p.nama_pemasok,
                p.alamat,
                p.telepon,
                p.email,
                (SELECT COUNT(*) FROM detail_pembelian
                 WHERE id_pembelian = tp.id_pembelian) as jumlah_item
            ')
            ->from('transaksi_pembelian tp')
            ->join('users u', 'u.id_user = tp.id_user')
            ->join('pemasok p', 'p.id_pemasok = tp.id_pemasok', 'left')
            ->where('tp.id_pembelian', $id_pembelian)
            ->get()
            ->row();
    }
    public function get_all_obat()
{
    return $this->db->get('obat')->result(); // WAJIB result()
}
public function get_data_pembelian()
{
    return $this->db
        ->select('
            pb.tanggal,
            p.nama_pemasok,
            o.nama_obat,
            pb.jumlah,
            pb.harga_satuan,
            pb.total_harga,
            pb.keterangan
        ')
        ->from('pembelian pb')
        ->join('pemasok p', 'p.id_pemasok = pb.id_pemasok', 'left')
        ->join('obat o', 'o.id_obat = pb.id_obat', 'left')
        ->order_by('pb.tanggal', 'DESC')
        ->get()
        ->result();
}

public function get_riwayat_pemasok()
{
    $this->db->select('p.id_pemasok, p.nama_pemasok');
    $this->db->from('transaksi_pembelian tp');
    $this->db->join('pemasok p', 'p.id_pemasok = tp.id_pemasok');
    $this->db->group_by('p.id_pemasok');
    $this->db->order_by('MAX(tp.tanggal)', 'DESC');

    return $this->db->get()->result();
}
public function get_total_pembelian_hari_ini()
{
    $today = date('Y-m-d');

    $this->db->where('DATE(tanggal)', $today);
    return $this->db->count_all_results('transaksi_pembelian');
}

}
