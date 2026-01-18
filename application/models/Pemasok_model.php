<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemasok_model extends CI_Model {

    private $table = 'pemasok';

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db
            ->get_where($this->table, ['id_pemasok' => $id])
            ->row(); // PENTING
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function generate_kode_pemasok() {
        // Format: SUP-0001
        $this->db->select('RIGHT(kode_pemasok,4) as kode', false);
        $this->db->order_by('kode_pemasok', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $data = $query->row();
            $kode = intval($data->kode) + 1;
        } else {
            $kode = 1;
        }

        return 'SUP-' . str_pad($kode, 4, '0', STR_PAD_LEFT);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id_pemasok' => $id]);
    }
}