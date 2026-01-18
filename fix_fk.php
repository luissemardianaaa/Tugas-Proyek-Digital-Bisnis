<?php
/**
 * Script untuk memperbaiki Foreign Key penjualan
 * Jalankan di browser: http://localhost/Apotek/fix_fk.php
 * HAPUS FILE INI SETELAH BERHASIL
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'apotek_friendly';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "<h2>Fixing Foreign Key Constraint...</h2>";

// Step 1: Drop old foreign key
$sql1 = "ALTER TABLE `penjualan` DROP FOREIGN KEY `fk_penjualan_karyawan`";
if ($conn->query($sql1) === TRUE) {
    echo "<p style='color:green'>✅ Foreign key lama berhasil dihapus</p>";
} else {
    echo "<p style='color:orange'>⚠️ Drop FK: " . $conn->error . "</p>";
}

// Step 2: Add new foreign key referencing users table
$sql2 = "ALTER TABLE `penjualan` 
         ADD CONSTRAINT `fk_penjualan_users` 
         FOREIGN KEY (`id_karyawan`) REFERENCES `users`(`id_user`)
         ON DELETE SET NULL 
         ON UPDATE CASCADE";

if ($conn->query($sql2) === TRUE) {
    echo "<p style='color:green'>✅ Foreign key baru berhasil ditambahkan (penjualan -> users)</p>";
} else {
    echo "<p style='color:red'>❌ Error: " . $conn->error . "</p>";
}

$conn->close();

echo "<hr>";
echo "<p><strong>Selesai!</strong> Silakan hapus file fix_fk.php setelah ini.</p>";
echo "<p><a href='/Apotek/karyawan/penjualan'>← Kembali ke Penjualan</a></p>";
?>
