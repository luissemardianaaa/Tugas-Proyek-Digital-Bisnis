<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Db_setup extends CI_Controller {
    public function index() {
        $this->load->database();
        
        $sql1 = "CREATE TABLE IF NOT EXISTS konsultasi (
            id_konsultasi INT AUTO_INCREMENT PRIMARY KEY, 
            id_user INT NULL, 
            nama_pengirim VARCHAR(100), 
            email VARCHAR(100), 
            subjek VARCHAR(255), 
            status ENUM('open', 'answered', 'closed') DEFAULT 'open', 
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $sql2 = "CREATE TABLE IF NOT EXISTS konsultasi_detail (
            id_detail INT AUTO_INCREMENT PRIMARY KEY, 
            id_konsultasi INT, 
            pengirim ENUM('user', 'admin', 'ai'), 
            pesan TEXT, 
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
            FOREIGN KEY (id_konsultasi) REFERENCES konsultasi(id_konsultasi)
        )";

        if($this->db->query($sql1) && $this->db->query($sql2)) {
            echo "Tables created successfully.";
        } else {
            echo "Error creating tables: " . $this->db->error();
        }
    }
}
