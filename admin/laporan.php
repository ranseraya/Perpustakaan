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

// Mendapatkan daftar kategori
$queryKategori = "SELECT DISTINCT kategori FROM Buku";
$resultKategori = $conn->query($queryKategori);

// Memproses pilihan kategori
$kategoriDipilih = isset($_POST['kategori']) ? $_POST['kategori'] : '';
$bukuPopuler = '';
$rataRataDurasi = '';

if (!empty($kategoriDipilih)) {
    // Query untuk buku paling populer di kategori tertentu
    $queryPopuler = "
        SELECT judul, COUNT(Peminjaman.id_buku) AS jumlah_peminjaman
        FROM Buku
        LEFT JOIN Peminjaman ON Buku.id_buku = Peminjaman.id_buku
        WHERE kategori = ?
        GROUP BY judul
        ORDER BY jumlah_peminjaman DESC
        LIMIT 1";
    $stmt = $conn->prepare($queryPopuler);
    $stmt->bind_param("s", $kategoriDipilih);
    $stmt->execute();
    $resultPopuler = $stmt->get_result();
    $bukuPopuler = $resultPopuler->fetch_assoc()['judul'] ?? 'Tidak ada data';

    // Query untuk rata-rata durasi peminjaman buku di kategori tertentu
    $queryDurasi = "
        SELECT AVG(DATEDIFF(tanggal_pengembalian, tanggal_peminjaman)) AS rata_rata_durasi
        FROM Buku
        JOIN Peminjaman ON Buku.id_buku = Peminjaman.id_buku
        WHERE kategori = ? AND tanggal_pengembalian IS NOT NULL";
    $stmt = $conn->prepare($queryDurasi);
    $stmt->bind_param("s", $kategoriDipilih);
    $stmt->execute();
    $resultDurasi = $stmt->get_result();
    $rataRataDurasi = $resultDurasi->fetch_assoc()['rata_rata_durasi'] ?? 'Tidak ada data';
}

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
        <p>Dibuat oleh: Kelompok 3</p>
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
    <h3>Laporan Per Kategori</h3>
    <form method="POST" action="laporan.php">
        <label for="kategori">Pilih Kategori:</label>
        <select name="kategori" id="kategori">
            <option value="">-- Pilih Kategori --</option>
            <?php while ($row = $resultKategori->fetch_assoc()): ?>
                <option value="<?= $row['kategori'] ?>" <?= $kategoriDipilih === $row['kategori'] ? 'selected' : '' ?>>
                    <?= $row['kategori'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Tampilkan</button>
    </form>
    </div>

    <?php if (!empty($kategoriDipilih)): ?>
        <h4>Kategori: <?= htmlspecialchars($kategoriDipilih) ?></h4>
        <p>Buku Paling Populer: <?= htmlspecialchars($bukuPopuler) ?></p>
        <p>Rata-rata Durasi Peminjaman: <?= htmlspecialchars($rataRataDurasi) ?> hari</p>
    <?php endif; ?>
        </div>
        <!-- Tambahkan item lainnya di sini -->


        <div class="report-footer">
            <p>Tanggal Cetak: 1 Desember 2024</p>
        </div>
    </div>

</div>
</div>
</body>
</html>
