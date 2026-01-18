-- =============================================
-- Tambahkan kolom status ke tabel obat
-- Jalankan query ini di phpMyAdmin atau MySQL CLI
-- =============================================

-- Menambahkan kolom status dengan default 'aktif'
ALTER TABLE `obat` 
ADD COLUMN `status` ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif' 
AFTER `gambar`;

-- Pastikan semua obat yang ada sekarang statusnya 'aktif'
UPDATE `obat` SET `status` = 'aktif' WHERE `status` IS NULL;
