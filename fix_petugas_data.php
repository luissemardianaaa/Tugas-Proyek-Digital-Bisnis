<?php
$conn = new mysqli('localhost', 'root', '', 'apotek_friendly');

// Get first user from pengguna table
$admin = $conn->query("SELECT id_user FROM pengguna ORDER BY id_user LIMIT 1")->fetch_assoc();
$default_user_id = $admin['id_user'];

echo "Default User ID: $default_user_id\n\n";

// Update pembelian yang id_user nya NULL atau 0
$result1 = $conn->query("UPDATE pembelian SET id_user = $default_user_id WHERE id_user IS NULL OR id_user = '' OR id_user = 0");
if($result1) {
    $affected1 = $conn->affected_rows;
    echo "✓ Updated $affected1 rows in pembelian table\n";
} else {
    echo "✗ Error updating pembelian: " . $conn->error . "\n";
}

// Update penjualan yang id_karyawan nya NULL atau 0
$result2 = $conn->query("UPDATE penjualan SET id_karyawan = $default_user_id WHERE id_karyawan IS NULL OR id_karyawan = '' OR id_karyawan = 0");
if($result2) {
    $affected2 = $conn->affected_rows;
    echo "✓ Updated $affected2 rows in penjualan table (id_karyawan)\n";
} else {
    echo "✗ Error updating penjualan (id_karyawan): " . $conn->error . "\n";
}

// Update penjualan yang id_petugas_konfirmasi nya NULL (untuk yang sudah selesai/dikemas)
$result3 = $conn->query("UPDATE penjualan SET id_petugas_konfirmasi = $default_user_id WHERE (id_petugas_konfirmasi IS NULL OR id_petugas_konfirmasi = '' OR id_petugas_konfirmasi = 0) AND status IN ('dikemas', 'dikirim', 'selesai')");
if($result3) {
    $affected3 = $conn->affected_rows;
    echo "✓ Updated $affected3 rows in penjualan table (id_petugas_konfirmasi)\n";
} else {
    echo "✗ Error updating penjualan (id_petugas_konfirmasi): " . $conn->error . "\n";
}

echo "\n✓ Selesai! Silakan coba export lagi.\n";

$conn->close();
