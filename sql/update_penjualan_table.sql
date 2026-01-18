ALTER TABLE `penjualan`
ADD COLUMN `status` ENUM('menunggu_pembayaran', 'menunggu_konfirmasi', 'dikemas', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'menunggu_pembayaran' AFTER `created_at`,
ADD COLUMN `metode_pembayaran` VARCHAR(50) NULL AFTER `status`,
ADD COLUMN `alamat_pengiriman` TEXT NULL AFTER `metode_pembayaran`;
