<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "Perpustakaan2";

$conn = new mysqli($host, $username, $password, $database, 3307);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
