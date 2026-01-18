<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    
    public function authenticate($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('status', 'active');
        $query = $this->db->get('users');
        
        if($query->num_rows() == 1) {
            $user = $query->row();

            // password_hash → password_verify
            if(password_verify($password, $user->password_hash)) {
                return $user;
            }
        }
        return false;
    }

    public function register_user($data){
        return $this->db->insert('users', $data);
    }

    public function check_username_exists($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    public function check_email_exists($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }
}
