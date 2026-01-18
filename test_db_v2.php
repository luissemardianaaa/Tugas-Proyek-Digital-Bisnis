<?php
$conn = new mysqli('localhost', 'root', '', 'apotek_friendly');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

echo "DEBUG DB\n";
$result = $conn->query("SELECT count(*) as cnt FROM konsultasi WHERE status='open'");
$row = $result->fetch_assoc();
echo "Open Count: " . $row['cnt'] . "\n";

$result2 = $conn->query("SELECT * FROM konsultasi");
echo "Total Rows: " . $result2->num_rows . "\n";
while($r = $result2->fetch_assoc()) {
    echo "ID: " . $r['id_konsultasi'] . " | Status: " . $r['status'] . "\n";
}
?>
