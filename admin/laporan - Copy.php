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


// Buku dengan peminjaman terbanyak
$queryBukuTerbanyak = "
    SELECT Buku.judul, COUNT(Peminjaman.id_buku) AS jumlah_peminjaman
    FROM Buku
    JOIN Peminjaman ON Buku.id_buku = Peminjaman.id_buku
    GROUP BY Buku.judul
    ORDER BY jumlah_peminjaman DESC
    LIMIT 1";
$resultBukuTerbanyak = $conn->query($queryBukuTerbanyak);
$bukuTerbanyak = $resultBukuTerbanyak->fetch_assoc();

// Anggota paling lama bergabung
$queryAnggotaLama = "
    SELECT nama_anggota, tanggal_registrasi
    FROM Anggota
    ORDER BY tanggal_registrasi ASC
    LIMIT 1";
$resultAnggotaLama = $conn->query($queryAnggotaLama);
$anggotaLama = $resultAnggotaLama->fetch_assoc();

// Petugas paling lama bertugas
$queryPetugasLama = "
    SELECT nama_petugas, bertugas_sejak
    FROM Petugas
    ORDER BY bertugas_sejak ASC
    LIMIT 1";
$resultPetugasLama = $conn->query($queryPetugasLama);
$petugasLama = $resultPetugasLama->fetch_assoc();

// Buku paling populer di setiap kategori
$queryPopulerKategori = "
    SELECT kategori, judul, MAX(jumlah_peminjaman) AS max_peminjaman
    FROM (
        SELECT Buku.kategori, Buku.judul, COUNT(Peminjaman.id_buku) AS jumlah_peminjaman
        FROM Buku
        LEFT JOIN Peminjaman ON Buku.id_buku = Peminjaman.id_buku
        GROUP BY Buku.kategori, Buku.judul
    ) AS subquery
    GROUP BY kategori";
$resultPopulerKategori = $conn->query($queryPopulerKategori);

// Rata-rata durasi peminjaman buku di setiap kategori
$queryDurasiKategori = "
    SELECT kategori, AVG(DATEDIFF(tanggal_pengembalian, tanggal_peminjaman)) AS rata_rata_durasi
    FROM Buku
    JOIN Peminjaman ON Buku.id_buku = Peminjaman.id_buku
    WHERE tanggal_pengembalian IS NOT NULL
    GROUP BY kategori";
$resultDurasiKategori = $conn->query($queryDurasiKategori);

// Anggota yang tidak pernah meminjam buku
$queryAnggotaNoPinjam = "
    SELECT nama_anggota
    FROM Anggota
    LEFT JOIN Peminjaman ON Anggota.id_anggota = Peminjaman.id_anggota
    WHERE Peminjaman.id_anggota IS NULL";
$resultAnggotaNoPinjam = $conn->query($queryAnggotaNoPinjam);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assest/laporan.css">
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
<div class="report-container">
    <div class="report-header">
        <h1>Laporan Perpustakaan</h1>
        <p>Periode: Januari 2024</p>
        <p>Dibuat oleh: Admin Perpustakaan</p>
    </div>

    <div class="report-body">
        <div class="report-item">
            <h4>Total Buku: </h4>
            <p><?= $result_buku['total'] ?> Buku</p>
        </div>
        <div class="report-item">
            <h4>Total Anggota: </h4>
            <p><?= $result_anggota['total'] ?> Buku</p>
        </div>
        <div class="report-item">
            <h4>Total Petugas: </h4>
            <p><?= $result_petugas['total'] ?> Buku</p>
        </div>
        <div class="report-item">
            <h4>Total Peminjaman: </h4>
            <p><?= $result_peminjaman['total'] ?> Buku</p>
        </div>
        <div class="report-item">
            <h4>Buku dengan Peminjaman Terbanyak</h4>
             <p><?= htmlspecialchars($bukuTerbanyak['judul'] ?? 'Tidak ada data') ?> (<?= htmlspecialchars($bukuTerbanyak['jumlah_peminjaman'] ?? 0) ?> peminjaman)</p>
        </div>
        <div class="report-item">
            <h4>Anggota Paling Lama Bergabung</h4>
            <p><?= htmlspecialchars($anggotaLama['nama_anggota'] ?? 'Tidak ada data') ?> (Bergabung sejak <?= htmlspecialchars($anggotaLama['tanggal_registrasi'] ?? '-') ?>)</p>
        </div>
        <div class="report-item">
            <h4>Petugas Paling Lama Bertugas</h4>
            <p><?= htmlspecialchars($petugasLama['nama_petugas'] ?? 'Tidak ada data') ?> (Bertugas sejak <?= htmlspecialchars($petugasLama['bertugas_sejak'] ?? '-') ?>)</p>
        </div>
        <div class="report-item">
            <h4>Buku Paling Populer di Setiap Kategori</h4>
            <ul>
                <?php while ($row = $resultPopulerKategori->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['kategori']) ?>: <?= htmlspecialchars($row['judul']) ?> (<?= htmlspecialchars($row['max_peminjaman']) ?> peminjaman)</li>
                <?php endwhile; ?>
            </ul>
        </div>
        <div class="report-item">
            <h4>Rata-rata Durasi Peminjaman Buku di Setiap Kategori</h4>
            <ul>
                <?php while ($row = $resultDurasiKategori->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['kategori']) ?>: <?= htmlspecialchars(number_format($row['rata_rata_durasi'], 2)) ?> hari</li>
                <?php endwhile; ?>
            </ul>
        </div>
        <div class="report-item">
            <h4>Anggota yang Tidak Pernah Meminjam Buku</h4>
            <ul>
                <?php while ($row = $resultAnggotaNoPinjam->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['nama_anggota']) ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
        <!-- Tambahkan item lainnya di sini -->


    </div>

    <div class="report-footer">
        <p>Tanggal Cetak: 21 Desember 2024</p>
    </div>
</div>
</div>
</body>
</html>
