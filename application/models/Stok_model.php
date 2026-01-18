<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends CI_Model {
    
    public function get_all_stok() {
        $this->db->select('obat.*, (obat.stok - obat.stok_minimum) as selisih');
        $this->db->order_by('selisih', 'ASC');
        return $this->db->get('obat')->result();
    }
    
    public function get_stok_log() {
        $this->db->select('stok_log.*, obat.nama_obat, users.nama');
        $this->db->from('stok_log');
        $this->db->join('obat', 'obat.id_obat = stok_log.id_obat');
        $this->db->join('users', 'users.id_user = stok_log.id_user');
        $this->db->order_by('stok_log.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
public function get_obat_habis() {
    return $this->db
        ->where('stok', 0)
        ->get('obat')
        ->result();
}
    
public function get_obat_kritis() {
    return $this->db
        ->where('stok >', 0)
        ->where('stok <= stok_minimum', null, false)
        ->get('obat')
        ->result();
}
    
    public function save_notification($message) {
        $data = array(
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        );
        return $this->db->insert('notifications', $data);
    }
  public function add_log($data) {
        return $this->db->insert('stok_log', $data);
    }
    public function insert_log($data) {
    return $this->db->insert('stok_log', $data);
}

    public function get_dead_stock($days = 60) {
        // Cari obat yang tidak terjual dalam X hari terakhir (Online & Manual)
        $this->db->select("o.*, 
            GREATEST(
                COALESCE(MAX(p.created_at), '0000-00-00 00:00:00'), 
                COALESCE(MAX(t.tanggal_transaksi), '0000-00-00 00:00:00')
            ) as terakhir_terjual");
        $this->db->from('obat o');
        
        // 1. Join Sales (Offline/POS - Penjualan)
        $this->db->join('detail_penjualan dp', 'o.id_obat = dp.id_obat', 'left');
        $this->db->join('penjualan p', 'dp.id_penjualan = p.id_penjualan AND p.status != "dibatalkan"', 'left');
        
        // 2. Join Transactions (Online - Transaksi)
        $this->db->join('detail_transaksi dt', 'o.id_obat = dt.id_obat', 'left');
        $this->db->join('transaksi t', 'dt.id_transaksi = t.id_transaksi AND t.status_pembayaran != "dibatalkan"', 'left');
        
        $this->db->where('o.stok >', 0);
        $this->db->group_by('o.id_obat');
        $this->db->having("terakhir_terjual IS NULL OR terakhir_terjual < DATE_SUB(NOW(), INTERVAL $days DAY)");
        $this->db->order_by('terakhir_terjual', 'ASC');
        
        return $this->db->get()->result();
    }

}