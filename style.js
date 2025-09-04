<?php
$conn = new mysqli("localhost", "fasttrav_swidodocom", "II^vDBwHRmZ&", "fasttrav_swidodo");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$res = $conn->query("SHOW TABLES LIKE 'theme'");
if ($res && $res->num_rows > 0) {
    echo "✅ Database OK, tabel theme ditemukan!";
} else {
    echo "❌ Tabel theme tidak ada di DB ini.";
}
?>
