<?php
// Script to run SQL migration manually via PHP
$dsn = 'mysql:host=localhost;dbname=apotek_friendly';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "ALTER TABLE `users`
            ADD COLUMN `no_hp` VARCHAR(20) NULL AFTER `email`,
            ADD COLUMN `kota` VARCHAR(100) NULL AFTER `no_hp`,
            ADD COLUMN `alamat` TEXT NULL AFTER `kota`;";

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
