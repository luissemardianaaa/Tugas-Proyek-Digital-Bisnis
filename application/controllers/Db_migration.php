<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Db_migration extends CI_Controller {
    
    public function add_midtrans_columns() {
        $this->load->database();
        $this->load->dbforge();
        
        echo "<h3>Adding Midtrans columns to penjualan table...</h3>";
        
        // Check and add columns one by one
        $columns = [
            'snap_token' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
                'after' => 'metode_pembayaran'
            ],
            'order_id' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
                'after' => 'snap_token'
            ],
            'payment_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
                'after' => 'order_id'
            ],
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
                'after' => 'payment_type'
            ],
            'transaction_time' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'transaction_id'
            ],
            'transaction_status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
                'after' => 'transaction_time'
            ]
        ];
        
        foreach ($columns as $column_name => $column_config) {
            if (!$this->db->field_exists($column_name, 'penjualan')) {
                $this->dbforge->add_column('penjualan', [$column_name => $column_config]);
                echo "✓ Column '$column_name' added successfully.<br>";
            } else {
                echo "- Column '$column_name' already exists.<br>";
            }
        }
        
        echo "<br><strong>Migration completed!</strong>";
    }
    
    public function add_shift_column() {
        $this->load->database();
        $this->load->dbforge();
        
        // Check if column already exists
        if (!$this->db->field_exists('shift', 'users')) {
            $fields = array(
                'shift' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => TRUE,
                    'after' => 'role'
                )
            );
            
            $this->dbforge->add_column('users', $fields);
            echo "Column 'shift' added successfully to 'users' table.";
        } else {
            echo "Column 'shift' already exists in 'users' table.";
        }
    }
}
