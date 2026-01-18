<?php
$conn = new mysqli('localhost', 'root', '', 'apotek_friendly');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Add id_petugas_konfirmasi if not exists
$check = $conn->query("SHOW COLUMNS FROM penjualan LIKE 'id_petugas_konfirmasi'");
if ($check->num_rows == 0) {
    $conn->query("ALTER TABLE penjualan ADD COLUMN id_petugas_konfirmasi INT NULL AFTER id_karyawan");
    echo "Column 'id_petugas_konfirmasi' added successfully to 'penjualan' table.\n";
} else {
    echo "Column 'id_petugas_konfirmasi' already exists.\n";
}

$conn->close();
