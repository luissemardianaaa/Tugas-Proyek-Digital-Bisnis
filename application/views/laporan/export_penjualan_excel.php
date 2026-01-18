<table border="1">
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
            <td><?= $no++ ?></td>
            <td><?= date('d-m-Y', strtotime($row->created_at)) ?></td>
            <td><?= $row->nama_obat ?? '-' ?></td>
            <td><?= $row->jumlah ?? 0 ?> pcs</td>
            <td><?= $row->nama_kasir ?? '-' ?></td>
            <td><?= $row->nama_petugas_konfirmasi ?? '-' ?></td>
            <td>Rp <?= number_format($row->subtotal ?? 0, 0, ',', '.') ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
