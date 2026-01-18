-- Fix Foreign Key: penjualan.id_karyawan -> users.id_user
-- Jalankan query ini di phpMyAdmin atau MySQL client

-- 1. Hapus foreign key constraint lama yang merujuk ke tabel pengguna
ALTER TABLE `penjualan` DROP FOREIGN KEY `fk_penjualan_karyawan`;

-- 2. Tambahkan foreign key baru yang merujuk ke tabel users
ALTER TABLE `penjualan` 
ADD CONSTRAINT `fk_penjualan_users` 
FOREIGN KEY (`id_karyawan`) REFERENCES `users`(`id_user`)
ON DELETE SET NULL 
ON UPDATE CASCADE;
