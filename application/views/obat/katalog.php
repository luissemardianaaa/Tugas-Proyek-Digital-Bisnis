<!DOCTYPE html>
<html lang="id">
<head>
    <title>Katalog Obat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f6f9fc;
        }

        .sidebar {
            position: sticky;
            top: 20px;
        }

        .main-content {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
        }

        .card-obat {
            border-radius: 12px;
            transition: .3s;
        }

        .card-obat:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,.15);
        }

        .card-obat img {
            height: 150px;
            object-fit: contain;
            padding: 10px;
        }

        .harga {
            color: #ee4d2d;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 sidebar">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-list"></i> Satuan Obat
                    </h6>

                    <ul class="list-unstyled mb-0">
                        <li><a href="<?= site_url('karyawan/obat/katalog?satuan=Tablet') ?>">Tablet</a></li>
                        <li><a href="<?= site_url('karyawan/obat/katalog?satuan=Kapsul') ?>">Kapsul</a></li>
                        <li><a href="<?= site_url('karyawan/obat/katalog?satuan=Strip') ?>">Strip</a></li>
                        <li><a href="<?= site_url('karyawan/obat/katalog?satuan=Botol') ?>">Botol</a></li>
                        <li><a href="<?= site_url('karyawan/obat/katalog?satuan=Tube') ?>">Tube</a></li>
                        <li><a href="<?= site_url('karyawan/obat/katalog?satuan=Pieces') ?>">Pieces</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-9">
            <div class="main-content">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Katalog Obat</h5>
                    <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </div>

                <!-- INFO FILTER -->
                <?php if($this->input->get('satuan')): ?>
                <div class="alert alert-light mb-3">
                    Menampilkan satuan:
                    <b><?= $this->input->get('satuan') ?></b>
                    <a href="<?= site_url('karyawan/obat/katalog') ?>" class="ms-2 text-decoration-none">Reset</a>
                </div>
                <?php endif; ?>

                <!-- PRODUK -->
                <div class="row g-4">
                <?php if(empty($obat)): ?>
                    <div class="col-12">
                        <div class="alert alert-warning">
                            Obat tidak tersedia.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($obat as $o): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card card-obat h-100">
                            <img src="<?= base_url('uploads/obat/'.$o->gambar) ?>" class="card-img-top">

                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title text-truncate">
                                    <?= $o->nama_obat ?>
                                </h6>

                                <div class="harga mb-1">
                                    Rp<?= number_format($o->harga,0,',','.') ?>
                                </div>

                                <small class="text-muted mb-3">
                                    Stok: <?= $o->stok ?> <?= $o->satuan ?>
                                </small>

                                <button
                                    class="btn btn-sm btn-success mt-auto w-100"
                                    onclick="tambahKeranjang(<?= $o->id_obat ?>)">
                                    <i class="bi bi-cart-plus"></i> Tambah Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- TOAST -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toastKeranjang" class="toast align-items-center text-bg-success border-0">
        <div class="d-flex">
            <div class="toast-body">
                ✅ Obat ditambahkan ke keranjang
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function tambahKeranjang(id) {
    fetch("<?= base_url('keranjang/tambah/') ?>" + id)
        .then(() => {
            new bootstrap.Toast(
                document.getElementById('toastKeranjang')
            ).show();
        });
}
</script>

</body>
</html>
