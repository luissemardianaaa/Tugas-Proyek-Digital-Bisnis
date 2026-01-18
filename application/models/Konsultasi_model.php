<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konsultasi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Buat Ticket Baru
    public function create_ticket($data) {
        $this->db->insert('konsultasi', $data);
        return $this->db->insert_id();
    }

    // Tambah Pesan ke Ticket
    public function add_message($data) {
        return $this->db->insert('konsultasi_detail', $data);
    }

    // Ambil Tiket berdasarkan ID (dengan cek kepemilikan opsional)
    public function get_ticket($id_konsultasi, $id_user = null) {
        $this->db->where('id_konsultasi', $id_konsultasi);
        if($id_user) {
            $this->db->where('id_user', $id_user);
        }
        return $this->db->get('konsultasi')->row();
    }

    // Ambil Tiket Aktif User
    public function get_active_ticket($id_user) {
        $this->db->where('id_user', $id_user);
        $this->db->where('status', 'open');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        return $this->db->get('konsultasi')->row();
    }

    // Ambil detail pesan
    public function get_messages($id_konsultasi) {
        $this->db->where('id_konsultasi', $id_konsultasi);
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('konsultasi_detail')->result();
    }

    // Cek Tiket User (List)
    public function get_user_tickets($id_user) {
        $this->db->where('id_user', $id_user);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('konsultasi')->result();
    }

    // Admin: Get All Tickets (Filter by status)
    public function get_all_tickets($status = null) {
        if($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('konsultasi')->result();
    }

    public function update_status($id, $status) {
        $this->db->where('id_konsultasi', $id);
        return $this->db->update('konsultasi', ['status' => $status]);
    }

    public function count_unread() {
        $this->db->where('status', 'open');
        return $this->db->count_all_results('konsultasi');
    }
}
