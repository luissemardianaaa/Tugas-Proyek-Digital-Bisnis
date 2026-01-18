<?php
// Script to run SQL migration manually via PHP
$dsn = 'mysql:host=localhost;dbname=apotek_friendly';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "ALTER TABLE `penjualan`
            ADD COLUMN `status` ENUM('menunggu_pembayaran', 'menunggu_konfirmasi', 'dikemas', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'menunggu_pembayaran' AFTER `created_at`,
            ADD COLUMN `metode_pembayaran` VARCHAR(50) NULL AFTER `status`,
            ADD COLUMN `alamat_pengiriman` TEXT NULL AFTER `metode_pembayaran`;";

    $pdo->exec($sql);
    echo "Migration successful!";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Columns already exist. Skipping.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
