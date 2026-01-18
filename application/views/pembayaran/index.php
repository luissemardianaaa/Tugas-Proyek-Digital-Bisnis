<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f7f4;
            margin: 0;
            padding: 0;
        }

        /* HEADER */
        .header {
            background: #2e7d32;
            color: white;
            padding: 18px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        /* CONTAINER */
        .container {
            padding: 16px;
        }

        /* CARD */
        .card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .label {
            color: #777;
        }

        .value {
            font-weight: bold;
            color: #333;
        }

        .total {
            font-size: 16px;
            color: #2e7d32;
        }

        /* BADGE STATUS */
        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        /* BUTTON */
        .btn {
            display: block;
            text-align: center;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            background: #2e7d32;
            color: white;
        }

        .empty {
            text-align: center;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="header">
    Riwayat Pembayaran
</div>

<div class="container">

<?php if (!empty($transaksi)): ?>
    <?php foreach ($transaksi as $t): ?>
        <div class="card">

            <div class="row">
                <span class="label">Kode Transaksi</span>
                <span class="value"><?= $t->kode_transaksi ?></span>
            </div>

            <div class="row">
                <span class="label">Tanggal</span>
                <span class="value">
                    <?= date('d M Y H:i', strtotime($t->tanggal_transaksi)) ?>
                </span>
            </div>

            <div class="row">
                <span class="label">Total Pembayaran</span>
                <span class="value total">
                    Rp <?= number_format($t->total_harga, 0, ',', '.') ?>
                </span>
            </div>

            <div class="row">
                <span class="label">Status</span>
                <span class="status <?= $t->status_pembayaran == 'berhasil' ? 'success' : 'pending' ?>">
                    <?= strtoupper($t->status_pembayaran) ?>
                </span>
            </div>

            <a class="btn" href="<?= base_url('pembayaran/konfirmasi/'.$t->id_transaksi) ?>">
                Lihat Detail
            </a>

        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="empty">
        Belum ada transaksi pembayaran
    </div>
<?php endif; ?>

</div>

</body>
</html>
