<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_model extends CI_Model {
    
    public function get_all_jadwal() {
        $this->db->select('jadwal_kerja.*, users.nama');
        $this->db->from('jadwal_kerja');
        $this->db->join('users', 'users.id_user = jadwal_kerja.id_user');
        $this->db->order_by('users.nama', 'ASC');
        $this->db->order_by('FIELD(hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")');
        return $this->db->get()->result();
    }
    
    public function get_by_user($id_user) {
        $this->db->where('id_user', $id_user);
        $this->db->order_by('FIELD(hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")');
        return $this->db->get('jadwal_kerja')->result();
    }
    
    public function create($data) {
        return $this->db->insert('jadwal_kerja', $data);
    }
    
    public function delete($id) {
        $this->db->where('id_jadwal', $id);
        return $this->db->delete('jadwal_kerja');
    }
    
    public function delete_by_user($id_user) {
        $this->db->where('id_user', $id_user);
        return $this->db->delete('jadwal_kerja');
    }
    
    public function get_absensi_today() {
        $today = date('Y-m-d');
        $this->db->select('absensi.*, users.nama');
        $this->db->from('absensi');
        $this->db->join('users', 'users.id_user = absensi.id_user');
        $this->db->where('DATE(tanggal)', $today);
        $this->db->order_by('absensi.check_in', 'DESC');
        return $this->db->get()->result();
    }
    
    public function check_in($id_user, $waktu) {
        $today = date('Y-m-d');
        
        // Cek apakah sudah check-in hari ini
        $this->db->where('id_user', $id_user);
        $this->db->where('DATE(tanggal)', $today);
        $query = $this->db->get('absensi');
        
        if($query->num_rows() == 0) {
            $data = array(
                'id_user' => $id_user,
                'tanggal' => date('Y-m-d H:i:s'),
                'check_in' => $waktu,
                'status' => 'hadir'
            );
            return $this->db->insert('absensi', $data);
        }
        return false;
    }
    
    public function check_out($id_user, $waktu) {
        $today = date('Y-m-d');
        
        $this->db->where('id_user', $id_user);
        $this->db->where('DATE(tanggal)', $today);
        $this->db->where('check_out IS NULL', NULL, FALSE);
        return $this->db->update('absensi', array('check_out' => $waktu));
    }
}