<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Jamkerja_model extends CI_Model {


public function get_all() {
return $this->db
->select('j.*, u.nama')
->from('jam_kerja j')
->join('users u', 'u.id_user = j.id_user')
->order_by('j.tanggal', 'DESC')
->get()
->result();
}


public function insert($data) {
return $this->db->insert('jam_kerja', $data);
}


public function update_pulang($id, $jam_pulang, $total_jam) {
return $this->db
->where('id_jam', $id)
->update('jam_kerja', [
'jam_pulang' => $jam_pulang,
'total_jam' => $total_jam
]);
}


public function get_karyawan() {
return $this->db
->where('role', 'karyawan')
->where('status', 'aktif')
->get('users')
->result();
}
}