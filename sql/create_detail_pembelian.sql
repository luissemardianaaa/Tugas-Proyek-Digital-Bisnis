-- Create the missing detail_pembelian table
-- This table stores the detail items for each purchase transaction

CREATE TABLE IF NOT EXISTS `detail_pembelian` (
    `id_detail` INT(11) NOT NULL AUTO_INCREMENT,
    `id_pembelian` INT(11) NOT NULL,
    `id_obat` INT(11) NOT NULL,
    `jumlah` INT(11) NOT NULL DEFAULT 0,
    `harga_beli` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_detail`),
    INDEX `idx_id_pembelian` (`id_pembelian`),
    INDEX `idx_id_obat` (`id_obat`),
    CONSTRAINT `fk_detail_pembelian_transaksi` 
        FOREIGN KEY (`id_pembelian`) 
        REFERENCES `transaksi_pembelian` (`id_pembelian`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT `fk_detail_pembelian_obat` 
        FOREIGN KEY (`id_obat`) 
        REFERENCES `obat` (`id_obat`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
