<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Obat</th>
            <th>Jumlah</th>
            <th>Petugas</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($laporan as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
            <td><?= $row->nama_obat ?? '-' ?></td>
            <td><?= $row->jumlah ?> pcs</td>
            <td><?= $row->nama_petugas ?? '-' ?></td>
            <td>Rp <?= number_format($row->total_harga,0,',','.') ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
