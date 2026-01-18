<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Generate kode transaksi unik
     * Format: TRX-YYYYMMDD-XXXX
     * @return string
     */
    public function generate_kode_transaksi() {
        $prefix = 'TRX';
        $date = date('Ymd');
        
        // Generate random 4 digit
        $random = mt_rand(1000, 9999);
        $kode = $prefix . '-' . $date . '-' . $random;
        
        // Cek apakah kode sudah ada
        $this->db->where('kode_transaksi', $kode);
        $exists = $this->db->get('transaksi')->num_rows();
        
        // Jika sudah ada, generate ulang
        if ($exists > 0) {
            return $this->generate_kode_transaksi();
        }
        
        return $kode;
    }

    /**
     * Buat transaksi baru dari keranjang
     * @param array $data_transaksi - Data pembayaran dari form
     * @param array $items_keranjang - Items dari keranjang
     * @return int|bool - ID transaksi atau false jika gagal
     */
    public function buat_transaksi($data_transaksi, $items_keranjang) {
        // Validasi keranjang tidak kosong
        if (empty($items_keranjang)) {
            return false;
        }

        // Mulai transaction database
        $this->db->trans_start();

        // Generate kode transaksi unik
        $kode_transaksi = $this->generate_kode_transaksi();
        
        // Hitung total harga
        $total_harga = 0;
        foreach ($items_keranjang as $item) {
            $total_harga += $item->subtotal;
        }

        // Data untuk tabel transaksi
        $transaksi = array(
            'id_user' => $data_transaksi['id_user'],
            'kode_transaksi' => $kode_transaksi,
            'total_harga' => $total_harga,
            'metode_pembayaran' => $data_transaksi['metode_pembayaran'],
            'status_pembayaran' => 'pending',
            'nama_penerima' => $data_transaksi['nama_penerima'],
            'alamat_pengiriman' => $data_transaksi['alamat_pengiriman'],
            'no_telepon' => $data_transaksi['no_telepon'],
            'catatan' => isset($data_transaksi['catatan']) ? $data_transaksi['catatan'] : null,
            'tanggal_transaksi' => date('Y-m-d H:i:s')
        );

        // Insert ke tabel transaksi
        $this->db->insert('transaksi', $transaksi);
        $id_transaksi = $this->db->insert_id();

        // Insert detail transaksi (items yang dibeli)
        foreach ($items_keranjang as $item) {
            $detail = array(
                'id_transaksi' => $id_transaksi,
                'id_obat' => $item->id_obat,
                'nama_obat' => $item->nama_obat,
                'harga' => $item->harga,
                'jumlah' => $item->jumlah,
                'subtotal' => $item->subtotal
            );
            $this->db->insert('detail_transaksi', $detail);

            // Optional: Kurangi stok obat
            $this->db->set('stok', 'stok - ' . (int)$item->jumlah, FALSE);
            $this->db->where('id_obat', $item->id_obat);
            $this->db->update('obat');
        }

        // Hapus keranjang user setelah checkout berhasil
        $this->db->where('id_user', $data_transaksi['id_user']);
        $this->db->delete('keranjang');

        // Complete transaction
        $this->db->trans_complete();

        // Cek apakah transaction berhasil
        if ($this->db->trans_status() === FALSE) {
            return false;
        }

        return $id_transaksi;
    }

    /**
     * Get transaksi berdasarkan ID
     * @param int $id_transaksi
     * @return object|null
     */
    public function get_by_id($id_transaksi) {
        $this->db->where('id_transaksi', $id_transaksi);
        $query = $this->db->get('transaksi');
        return $query->row();
    }

    /**
     * Get transaksi berdasarkan kode transaksi
     * @param string $kode_transaksi
     * @return object|null
     */
    public function get_by_kode($kode_transaksi) {
        $this->db->where('kode_transaksi', $kode_transaksi);
        $query = $this->db->get('transaksi');
        return $query->row();
    }

    /**
     * Get detail items transaksi
     * @param int $id_transaksi
     * @return array
     */
    public function get_detail($id_transaksi) {
        $this->db->where('id_transaksi', $id_transaksi);
        $this->db->order_by('id_detail_transaksi', 'ASC');
        $query = $this->db->get('detail_transaksi');
        return $query->result();
    }

    /**
     * Get riwayat transaksi user
     * @param int $id_user
     * @param string $status - Optional filter status
     * @param int $limit - Optional limit
     * @return array
     */
    public function get_riwayat($id_user, $status = null, $limit = null) {
        $this->db->where('id_user', $id_user);
        
        if ($status) {
            $this->db->where('status_pembayaran', $status);
        }
        
        $this->db->order_by('tanggal_transaksi', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get('transaksi');
        return $query->result();
    }

    /**
     * Get semua transaksi (untuk admin)
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_all($limit = null, $offset = 0) {
        $this->db->order_by('tanggal_transaksi', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get('transaksi');
        return $query->result();
    }

    /**
     * Hitung total transaksi user
     * @param int $id_user
     * @return int
     */
    public function count_transaksi($id_user) {
        $this->db->where('id_user', $id_user);
        return $this->db->count_all_results('transaksi');
    }

    /**
     * Update status pembayaran
     * @param int $id_transaksi
     * @param string $status - pending, dibayar, dibatalkan
     * @return bool
     */
    public function update_status($id_transaksi, $status) {
        $data = array(
            'status_pembayaran' => $status
        );
        
        // Jika status dibayar, simpan waktu pembayaran
        if ($status == 'dibayar') {
            $data['tanggal_dibayar'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id_transaksi', $id_transaksi);
        return $this->db->update('transaksi', $data);
    }

    /**
     * Batalkan transaksi
     * @param int $id_transaksi
     * @return bool
     */
    public function batalkan_transaksi($id_transaksi) {
        // Get detail transaksi untuk kembalikan stok
        $details = $this->get_detail($id_transaksi);
        
        $this->db->trans_start();

        // Kembalikan stok obat
        foreach ($details as $detail) {
            $this->db->set('stok', 'stok + ' . (int)$detail->jumlah, FALSE);
            $this->db->where('id_obat', $detail->id_obat);
            $this->db->update('obat');
        }

        // Update status transaksi
        $this->update_status($id_transaksi, 'dibatalkan');

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Get statistik transaksi user
     * @param int $id_user
     * @return array
     */
    public function get_statistik($id_user) {
        $stats = array();

        // Total transaksi
        $this->db->where('id_user', $id_user);
        $stats['total_transaksi'] = $this->db->count_all_results('transaksi');

        // Total pending
        $this->db->where('id_user', $id_user);
        $this->db->where('status_pembayaran', 'pending');
        $stats['pending'] = $this->db->count_all_results('transaksi');

        // Total dibayar
        $this->db->where('id_user', $id_user);
        $this->db->where('status_pembayaran', 'dibayar');
        $stats['dibayar'] = $this->db->count_all_results('transaksi');

        // Total dibatalkan
        $this->db->where('id_user', $id_user);
        $this->db->where('status_pembayaran', 'dibatalkan');
        $stats['dibatalkan'] = $this->db->count_all_results('transaksi');

        // Total belanja
        $this->db->select_sum('total_harga');
        $this->db->where('id_user', $id_user);
        $this->db->where('status_pembayaran', 'dibayar');
        $query = $this->db->get('transaksi');
        $row = $query->row();
        $stats['total_belanja'] = $row->total_harga ? $row->total_harga : 0;

        return $stats;
    }

    /**
     * Cari transaksi berdasarkan keyword
     * @param int $id_user
     * @param string $keyword
     * @return array
     */
    public function search($id_user, $keyword) {
        $this->db->where('id_user', $id_user);
        $this->db->group_start();
            $this->db->like('kode_transaksi', $keyword);
            $this->db->or_like('nama_penerima', $keyword);
            $this->db->or_like('no_telepon', $keyword);
        $this->db->group_end();
        $this->db->order_by('tanggal_transaksi', 'DESC');
        
        $query = $this->db->get('transaksi');
        return $query->result();
    }

    /**
     * Validasi kepemilikan transaksi
     * @param int $id_transaksi
     * @param int $id_user
     * @return bool
     */
    public function is_owner($id_transaksi, $id_user) {
        $this->db->where('id_transaksi', $id_transaksi);
        $this->db->where('id_user', $id_user);
        return $this->db->count_all_results('transaksi') > 0;
    }

}