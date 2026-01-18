<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    // ==============================
    // AMBIL SEMUA KATEGORI
    // ==============================
public function get_all()
{
    return $this->db
        ->select('
            obat.jenis AS nama_kategori,
            COUNT(obat.id_obat) AS total_obat,
            NOW() AS created_at
        ')
        ->from('obat')
        ->group_by('obat.jenis')
        ->get()
        ->result();
}


    public function get_by_nama($nama) {
        return $this->db
            ->where('nama_kategori', $nama)
            ->get('kategori')
            ->row();
    }

    // ==============================
    // SIMPAN KATEGORI BARU
    // ==============================
    public function create($nama) {
        $this->db->insert('kategori', [
            'nama_kategori' => $nama,
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        return $this->db->insert_id();
    }

    // ==============================
    // HITUNG TOTAL KATEGORI
    // ==============================
    public function count_all() {
        return $this->db->count_all('kategori');
    }
public function get_all_kategori_with_total_obat()
{
    return $this->db
        ->select('kategori.*, COUNT(obat.id_obat) AS total_obat')
        ->from('kategori')
        ->join('obat', 'obat.jenis = kategori.nama_kategori', 'left')
        ->group_by('kategori.id_kategori')
        ->order_by('kategori.nama_kategori', 'ASC')
        ->get()
        ->result();
}

}
