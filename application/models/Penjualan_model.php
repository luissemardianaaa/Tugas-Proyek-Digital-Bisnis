<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_model extends CI_Model {

public function get_all()
{
    $this->db->select('penjualan.*, users.nama');
    $this->db->from('penjualan');
    $this->db->join('users', 'users.id_user = penjualan.id_karyawan', 'left');
    $this->db->order_by('penjualan.created_at', 'DESC');

    return $this->db->get()->result();
}

    public function insert_penjualan($data) {
        // Disable FK check sementara untuk bypass constraint lama
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->insert('penjualan', $data);
        $id = $this->db->insert_id();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        return $id;
    }

    public function insert_detail($data) {
        return $this->db->insert('detail_penjualan', $data);
    }

    public function get_detail($id_penjualan) {
        return $this->db
            ->select('d.*, o.nama_obat')
            ->from('detail_penjualan d')
            ->join('obat o','o.id_obat=d.id_obat')
            ->where('d.id_penjualan', $id_penjualan)
            ->get()->result();
    }

    public function get_by_id($id_penjualan) {
        return $this->db->get_where('penjualan', ['id_penjualan' => $id_penjualan])->row();
    }

    public function update_status($id_penjualan, $status) {
        $this->db->where('id_penjualan', $id_penjualan);
        $update = $this->db->update('penjualan', ['status' => $status]);

        if($update) {
            // Ambil data user dari penjualan (id_karyawan di tabel ini adalah id_pelanggan)
            $penjualan = $this->get_by_id($id_penjualan);
            if ($penjualan) {
                $user = $this->db->get_where('users', ['id_user' => $penjualan->id_karyawan])->row();
                $nama = $user ? $user->nama : 'Pelanggan';
                
                $status_label = ucwords(str_replace('_', ' ', $status));
                $pesan = "";

                switch($status) {
                    case 'menunggu_konfirmasi':
                        $pesan = "Halo $nama, pembayaran kamu sedang kami verifikasi. Mohon ditunggu ya!";
                        break;
                    case 'dikemas':
                        $pesan = "Halo $nama, pesanan kamu sedang dikemas oleh tim farmasi kami. 📦";
                        break;
                    case 'dikirim':
                        $pesan = "Kabar gembira $nama! Pesanan kamu sudah diserahkan ke kurir dan dalam perjalanan. 🚀";
                        break;
                    case 'selesai':
                        $pesan = "Halo $nama, pesanan kamu sudah sampai. Terima kasih sudah berbelanja di Apotek Friendly!";
                        break;
                    case 'dibatalkan':
                        $pesan = "Mohon maaf $nama, pesanan kamu dibatalkan. Silakan hubungi admin untuk info lebih lanjut.";
                        break;
                    default:
                        $pesan = "Status pesanan #$id_penjualan diperbarui menjadi: $status_label";
                }

                $this->db->insert('notifikasi_pesanan', [
                    'id_user' => $penjualan->id_karyawan,
                    'id_penjualan' => $id_penjualan,
                    'pesan' => $pesan
                ]);
            }
        }
        return $update;
    }
    public function get_riwayat_by_user($id_user) {
        $this->db->where('id_karyawan', $id_user); // id_karyawan holds the customer ID in this context
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('penjualan')->result();
    }
}
