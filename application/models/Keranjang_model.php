<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keranjang_model extends CI_Model {

    // ambil / buat keranjang user
    private function get_keranjang($id_user) {
        $keranjang = $this->db
            ->where('id_user', $id_user)
            ->get('keranjang')
            ->row();

        if (!$keranjang) {
            $this->db->insert('keranjang', [
                'id_user' => $id_user
            ]);
            return $this->db->insert_id();
        }

        return $keranjang->id_keranjang;
    }

    // tambah item
    public function tambah($id_user, $obat) {
        $id_keranjang = $this->get_keranjang($id_user);

        $detail = $this->db
            ->where('id_keranjang', $id_keranjang)
            ->where('id_obat', $obat->id_obat)
            ->get('keranjang_detail')
            ->row();

        if ($detail) {
            $this->db
                ->set('jumlah', 'jumlah+1', FALSE)
                ->where('id_detail', $detail->id_detail)
                ->update('keranjang_detail');
        } else {
            $this->db->insert('keranjang_detail', [
                'id_keranjang' => $id_keranjang,
                'id_obat' => $obat->id_obat,
                'jumlah' => 1,
                'harga' => $obat->harga
            ]);
        }
    }
    
    // kurang item
    public function kurang($id_user, $id_obat) {
        $id_keranjang = $this->get_keranjang($id_user);

        $detail = $this->db
            ->where('id_keranjang', $id_keranjang)
            ->where('id_obat', $id_obat)
            ->get('keranjang_detail')
            ->row();

        if ($detail && $detail->jumlah > 1) {
            $this->db
                ->set('jumlah', 'jumlah-1', FALSE)
                ->where('id_detail', $detail->id_detail)
                ->update('keranjang_detail');
        }
    }

    // ambil isi keranjang
    public function get_items($id_user) {
        return $this->db
            ->select('kd.*, o.nama_obat, o.gambar, o.stok, o.satuan, o.harga as harga_asli')
            ->from('keranjang_detail kd')
            ->join('keranjang k', 'k.id_keranjang = kd.id_keranjang')
            ->join('obat o', 'o.id_obat = kd.id_obat')
            ->where('k.id_user', $id_user)
            ->get()
            ->result();
    }

    // hapus item
    public function hapus($id_detail) {
        $this->db->where('id_detail', $id_detail)->delete('keranjang_detail');
    }
}
