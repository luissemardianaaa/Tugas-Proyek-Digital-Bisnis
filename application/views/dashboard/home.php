<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Obat - Pelanggan</title>

<style>
    body { font-family: Arial; background: #f2f8ff; margin: 0; padding: 0;}
    .container { width: 90%; margin: 30px auto; }
    h2 { text-align: center; color:#003f6f; }
    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px,1fr)); gap:20px; }

    .card {
        background:white; padding:15px; border-radius:12px;
        box-shadow:0 3px 10px rgba(0,0,0,.1);
        border:1px solid #e4e4e4;
        transition:.2s;
    }
    .card:hover { transform: translateY(-4px); }

    .card h3 { margin:0 0 8px; color:#01497c; font-size:18px; }
    .detail { font-size:14px; margin-bottom:6px; }
    .price { font-size:16px; font-weight:bold; color:#0a7a26; margin-bottom:10px; }

    .stok-habis { color:red; font-weight:bold; font-size:13px; }
    .stok-aman { color:#1c651c; font-weight:bold; font-size:13px; }

    .btn {
        display:block; text-align:center; padding:10px 0;
        background:#0077cc; color:white; border-radius:8px;
        text-decoration:none; margin-top:10px;
    }
    .btn:hover { background:#005fa3; }
</style>

</head>

<body>

<div class="container">
    <h2>Daftar Obat</h2>

    <div class="grid">

        <?php foreach($obat as $o): ?>
        <div class="card">

            <h3><?= $o->nama_obat ?></h3>

            <div class="detail">Jenis: <?= $o->jenis ?></div>
            <div class="detail">Satuan: <?= $o->satuan ?></div>

            <div class="price">Rp <?= number_format($o->harga,0,',','.') ?></div>

            <div class="detail"><?= $o->deskripsi ?></div>

            <?php if($o->stok == 0): ?>
                <div class="stok-habis">Stok habis</div>
            <?php else: ?>
                <div class="stok-aman">Stok: <?= $o->stok ?></div>
            <?php endif; ?>

            <a class="btn" href="<?= site_url('auth/login') ?>">Tambah ke Keranjang</a>

        </div>
        <?php endforeach; ?>

    </div>
</div>

</body>
</html>
