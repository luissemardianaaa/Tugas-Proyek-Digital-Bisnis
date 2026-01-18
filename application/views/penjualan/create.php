<!DOCTYPE html>
<html>
<head>
<title>Transaksi Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #f6f9fc;
    font-family: "Inter", sans-serif;
}
.card { border-radius: 14px; }
.table th { background: #f1f5ff; }
.total-box {
    background: #e8f1ff;
    border-radius: 12px;
    padding: 15px;
    font-size: 1.1rem;
}
</style>
</head>

<body>

<div class="container mt-4 mb-5">
<div class="card shadow-sm">
<div class="card-body">

<h4 class="fw-bold mb-4">
<i class="bi bi-cash-coin text-success"></i>
Transaksi Penjualan
</h4>

<form method="post" action="<?= site_url('karyawan/penjualan/store') ?>">

<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead>
<tr>
<th>Obat</th>
<th>Harga</th>
<th width="120">Qty</th>
</tr>
</thead>

<tbody>
<?php foreach($obat as $index => $o): ?>
<tr>
    <td><?= $o->nama_obat ?></td>
    <td>Rp<?= number_format($o->harga,0,',','.') ?></td>
    <td>
        <input type="number"
               class="form-control qty"
               name="items[<?= $index ?>][qty]"
               min="0"
               max="<?= $o->stok ?>">

        <input type="hidden"
               name="items[<?= $index ?>][id_obat]"
               value="<?= $o->id_obat ?>">

        <input type="hidden"
               name="items[<?= $index ?>][harga]"
               value="<?= $o->harga ?>"
               class="harga">
    </td>
</tr>
<?php endforeach ?>
</tbody>
</table>
</div>

<div class="row mt-4 g-3">
<div class="col-md-4">
<label class="form-label fw-semibold">Total Bayar</label>
<div class="total-box text-success fw-bold">
Rp <span id="totalHarga">0</span>
</div>
</div>

<div class="col-md-4">
<label class="form-label fw-semibold">Bayar</label>
<input type="number"
       id="bayar"
       name="bayar"
       class="form-control"
       value="0"
       required>
</div>

<div class="col-md-4">
<label class="form-label fw-semibold">Kembalian</label>
<input type="text" id="kembalian" class="form-control" readonly>
</div>
</div>

<div class="mt-4 d-flex justify-content-end gap-2">
<a href="<?= site_url('karyawan/penjualan') ?>" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Kembali
</a>
<button type="submit" class="btn btn-success">
<i class="bi bi-save"></i> Simpan Transaksi
</button>
</div>

</form>

</div>
</div>
</div>

<script>
function formatRupiah(angka) {
    return angka.toLocaleString('id-ID');
}

function hitungTotal() {
    let total = 0;

    document.querySelectorAll('tbody tr').forEach(row => {
        const qty = parseInt(row.querySelector('.qty').value) || 0;
        const harga = parseInt(row.querySelector('.harga').value) || 0;
        total += qty * harga;
    });

    document.getElementById('totalHarga').innerText = formatRupiah(total);

    const bayar = parseInt(document.getElementById('bayar').value) || 0;
    const kembali = bayar - total;

    document.getElementById('kembalian').value =
        kembali >= 0 ? 'Rp ' + formatRupiah(kembali) : 'Rp 0';
}

document.querySelectorAll('.qty').forEach(el => {
    el.addEventListener('input', hitungTotal);
});
document.getElementById('bayar').addEventListener('input', hitungTotal);

hitungTotal();
</script>

</body>
</html>
