<?php
define('BASEPATH', 'debug');
$conn = new mysqli('localhost', 'root', '', 'apotek_friendly');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

echo "<h3>Table: Konsultasi</h3>";
$result = $conn->query("SELECT * FROM konsultasi");
if ($result->num_rows > 0) {
    echo "<table border='1'><tr><th>ID</th><th>User</th><th>Status</th><th>Created</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id_konsultasi"]."</td><td>".$row["nama_pengirim"]."</td><td>".$row["status"]."</td><td>".$row["created_at"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results in Konsultasi";
}

echo "<h3>Table: Konsultasi Detail</h3>";
$result2 = $conn->query("SELECT * FROM konsultasi_detail");
if ($result2->num_rows > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Konsultasi ID</th><th>Sender</th><th>Message</th></tr>";
    while($row = $result2->fetch_assoc()) {
        echo "<tr><td>".$row["id_detail"]."</td><td>".$row["id_konsultasi"]."</td><td>".$row["pengirim"]."</td><td>".substr($row["pesan"], 0, 50)."...</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results in Detail";
}
$conn->close();
?>
