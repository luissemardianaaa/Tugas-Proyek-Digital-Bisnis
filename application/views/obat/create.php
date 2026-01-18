<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Obat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #e6f7f1, #f0fdfa);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Inter, Arial, sans-serif;
        }

        .card {
            width: 100%;
            max-width: 650px;
            border-radius: 16px;
            border: none;
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
            border-top: 6px solid #16a34a;
        }

        .card-title {
            color: #065f46;
            font-weight: 700;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            color: #064e3b;
        }

        .form-control:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34,197,94,.15);
        }

        .btn-success {
            background: #16a34a;
            border: none;
        }

        .btn-success:hover {
            background: #15803d;
        }
    </style>
</head>

<body>

<div class="container">

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger mb-3">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="card mx-auto">
        <div class="card-body p-4">

            <h4 class="card-title text-center mb-4">
                <i class="fas fa-pills me-2"></i>Tambah Obat Baru
            </h4>

            <form action="<?= site_url('karyawan/obat/store') ?>" method="POST" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nama Obat</label>
                        <input type="text" class="form-control" name="nama_obat" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jenis</label>
                        <input type="text" class="form-control" name="jenis"
                               placeholder="Antibiotik, Vitamin, Herbal" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Gambar Obat</label>
                    <input type="file" class="form-control" name="gambar">
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Satuan</label>
                        <select name="satuan" class="form-control" required>
                            <option value="">-- Pilih Satuan --</option>
                            <option>Tablet</option>
                            <option>Botol</option>
                            <option>Box</option>
                            <option>Strip</option>
                            <option>Pieces</option>
                            <option>Kapsul</option>
                            <option>Tube</option>
                            <option>inhaler</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Harga</label>
                        <input type="number" class="form-control" name="harga" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Stok Awal</label>
                        <input type="number" class="form-control" name="stok" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"
                              placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('karyawan/obat') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>

                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i> Simpan Obat
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>
