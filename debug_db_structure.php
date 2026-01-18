<?php
$conn = new mysqli('localhost', 'root', '', 'apotek_friendly');
$tables = ['penjualan', 'pembelian', 'transaksi_penjualan', 'transaksi_pembelian', 'users'];
foreach($tables as $t) {
    echo "TABLE: $t\n";
    $res = $conn->query("DESC $t");
    while($row = $res->fetch_assoc()) {
        echo "  " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    echo "\n";
}
