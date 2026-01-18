<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        line-height: 1.5;
    }
    .title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .subtitle {
        text-align: center;
        font-size: 12px;
        margin-bottom: 20px;
    }
    .section-title {
        font-weight: bold;
        margin-top: 15px;
        margin-bottom: 5px;
    }
    .content {
        text-align: justify;
        margin-bottom: 15px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #000;
        padding: 6px;
        font-size: 10px;
    }
    th {
        background-color: #f2f2f2;
        text-align: center;
    }
    .footer {
        margin-top: 40px;
        text-align: right;
    }
</style>

<div class="title">LAPORAN PENJUALAN OBAT</div>
<div class="subtitle">
    Apotek Friendly<br>
    Periode Laporan: <?= date('F Y') ?>
</div>

<div class="section-title">A. Pendahuluan</div>
<div class="content">
    Laporan penjualan obat ini disusun sebagai bentuk pertanggungjawaban
    serta dokumentasi administrasi terhadap kegiatan penjualan obat
    yang dilakukan di Apotek Friendly.
    Laporan ini bertujuan untuk memberikan gambaran mengenai
    total penjualan obat berdasarkan data transaksi yang tercatat
    dalam sistem informasi apotek.
</div>

<div class="section-title">B. Tujuan</div>
<div class="content">
    Adapun tujuan penyusunan laporan ini adalah untuk mengetahui
    jumlah total penjualan obat dalam periode tertentu,
    sebagai bahan evaluasi serta pendukung dalam pengambilan
    keputusan manajerial terkait pengelolaan persediaan obat.
</div>

<div class="section-title">C. Data Penjualan</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Obat</th>
            <th>Jumlah</th>
            <th>Kasir/Pelanggan</th>
            <th>Petugas Konfirmasi</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($laporan as $row): ?>
        <tr>
            <td align="center"><?= $no++ ?></td>
            <td align="center"><?= date('d-m-Y', strtotime($row->created_at)) ?></td>
            <td><?= $row->nama_obat ?? '-' ?></td>
            <td align="center"><?= $row->jumlah ?? 0 ?> pcs</td>
            <td><?= $row->nama_kasir ?? '-' ?></td>
            <td><?= $row->nama_petugas_konfirmasi ?? '-' ?></td>
            <td align="right">Rp <?= number_format($row->subtotal ?? 0, 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="section-title">D. Penutup</div>
<div class="content">
    Demikian laporan penjualan obat ini disusun berdasarkan
    data yang diperoleh dari sistem informasi apotek.
    Diharapkan laporan ini dapat digunakan sebagaimana mestinya
    dan menjadi bahan pertimbangan dalam pengelolaan operasional apotek.
</div>
<div style="width:100%; text-align:right; margin-top:50px;">
    <p><?= date('d F Y') ?></p>

    <img src="<?= $ttd ?>" width="120"><br>

    <strong>Keylia</strong><br>
    Pimpinan Apotek
</div>
