<?php
class Home_model extends CI_Model {


private $table = 'obat';


public function getAll() {
return $this->db
->order_by('created_at', 'DESC')
->get($this->table)
->result();
}


public function insert($data) {
return $this->db->insert($this->table, $data);
}
}