<?php
$conn = new mysqli('localhost', 'root', '', 'apotek_friendly');

echo "=== CEK DATA PEMBELIAN ===\n";
$res = $conn->query("
    SELECT pembelian.id_pembelian, pembelian.tanggal, obat.nama_obat, pembelian.jumlah, 
           pembelian.id_user, users.nama as nama_petugas, pembelian.total_harga
    FROM pembelian
    LEFT JOIN obat ON obat.id_obat = pembelian.id_obat
    LEFT JOIN users ON users.id_user = pembelian.id_user
    ORDER BY pembelian.tanggal DESC
    LIMIT 5
");

while($row = $res->fetch_assoc()) {
    echo "ID: {$row['id_pembelian']}, Tanggal: {$row['tanggal']}, Obat: {$row['nama_obat']}, ";
    echo "Jumlah: {$row['jumlah']}, id_user: {$row['id_user']}, Petugas: " . ($row['nama_petugas'] ?? 'NULL') . "\n";
}

echo "\n=== CEK DATA PENJUALAN ===\n";
$res2 = $conn->query("
    SELECT penjualan.id_penjualan, penjualan.created_at, obat.nama_obat, detail_penjualan.jumlah,
           penjualan.id_karyawan, kasir.nama as nama_kasir,
           penjualan.id_petugas_konfirmasi, petugas.nama as nama_petugas_konfirmasi
    FROM penjualan
    LEFT JOIN detail_penjualan ON detail_penjualan.id_penjualan = penjualan.id_penjualan
    LEFT JOIN obat ON obat.id_obat = detail_penjualan.id_obat
    LEFT JOIN users as kasir ON kasir.id_user = penjualan.id_karyawan
    LEFT JOIN users as petugas ON petugas.id_user = penjualan.id_petugas_konfirmasi
    WHERE penjualan.status != 'dibatalkan'
    ORDER BY penjualan.created_at DESC
    LIMIT 5
");

while($row = $res2->fetch_assoc()) {
    echo "ID: {$row['id_penjualan']}, Tanggal: {$row['created_at']}, Obat: {$row['nama_obat']}, ";
    echo "id_karyawan: {$row['id_karyawan']}, Kasir: " . ($row['nama_kasir'] ?? 'NULL');
    echo ", id_petugas: {$row['id_petugas_konfirmasi']}, Konfirmasi: " . ($row['nama_petugas_konfirmasi'] ?? 'NULL') . "\n";
}

$conn->close();
