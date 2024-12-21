<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}
require '../config/functions.php';


$query_buku = "SELECT COUNT(*) as total FROM Buku";
$query_anggota = "SELECT COUNT(*) as total FROM Anggota";
$query_petugas = "SELECT COUNT(*) as total FROM Petugas";
$query_peminjaman = "SELECT COUNT(*) as total FROM Peminjaman";

$result_buku = $conn->query($query_buku)->fetch_assoc();
$result_anggota = $conn->query($query_anggota)->fetch_assoc();
$result_petugas = $conn->query($query_petugas)->fetch_assoc();
$result_peminjaman = $conn->query($query_peminjaman)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assest/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2><a href="dashboard.php">Admin Dashboard</a></h2>
        <!-- <a href="users.php">Users</a> -->
        <a href="buku.php">Kelola Buku</a>
        <a href="anggota.php">Kelola Anggota</a>
        <a href="petugas.php">Kelola Petugas</a>
        <a href="peminjaman.php">Kelola Peminjaman</a>
        <a href="laporan.php">Laporan</a>
        <a href="../public/logout.php">Logout</a>
    </div>
    <div class="main-content">
        <h1>Selamat Datang, Admin!</h1>
        <div class="card">
            <h3>Total Buku</h3>
            <p><?= $result_buku['total'] ?> Buku</p>
        </div>
        <div class="card">
            <h3>Total Anggota</h3>
            <p><?= $result_anggota['total'] ?> Anggota</p>
        </div>
        <div class="card">
            <h3>Total Petugas</h3>
            <p><?= $result_petugas['total'] ?> Petugas</p>
        </div>
        <div class="card">
            <h3>Total Peminjaman</h3>
            <p><?= $result_peminjaman['total'] ?> Peminjaman</p>
        </div>
    </div>
</body>
</html>
