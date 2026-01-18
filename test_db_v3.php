<?php
// Disable caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$conn = new mysqli('localhost', 'root', '', 'apotek_friendly');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

echo "DEBUG_V3_START\n";
$result = $conn->query("SELECT count(*) as cnt FROM konsultasi WHERE status='open'");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Open Count: " . $row['cnt'] . "\n";
} else {
    echo "Query Error: " . $conn->error . "\n";
}

$result2 = $conn->query("SELECT * FROM konsultasi");
if ($result2) {
    echo "Total Rows: " . $result2->num_rows . "\n";
    while($r = $result2->fetch_assoc()) {
        echo "ID: " . $r['id_konsultasi'] . " | Status: " . $r['status'] . "\n";
    }
}
echo "DEBUG_V3_END";
?>
