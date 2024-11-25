<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}
require '../config/functions.php';


$jumlahDataPerHalaman = 25;
$jumlahData = count(query("SELECT * FROM anggota"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
$awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;
$anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");


if (isset($_POST["cari"])) {
    $anggota = cariAnggota($_POST["keyword"]);
} else {
    $buku = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
}
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
        <a href="users.php">Users</a>
        <a href="buku.php">Kelola Buku</a>
        <a href="anggota.php">Kelola Anggota</a>
        <a href="petugas.php">Kelola Petugas</a>
        <a href="peminjaman.php">Kelola Peminjaman</a>
        <a href="../public/logout.php">Logout</a>
    </div>
    <div class="main-content">
        <h1></h1>




<a href="tambah_anggota.php">Tambah data anggota</a>
<br><br>

<form action="" method="post">
	<input type="text" name="keyword" size="40" autofocus placeholder="masukkan keyword pencarian.." autocomplete="off">
	<button type="submit" name="cari">Cari!</button>
	
</form>

<br><br>
<a href="?halaman=1">awal</a>

<?php if( $halamanAktif > 1 ) : ?>
	<a href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
<?php endif; ?>

<?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : ?>
	<?php if( $i == $halamanAktif ) : ?>
		<a href="?halaman=<?= $i; ?>" style="font-weight: bold; color: red;"><?= $i; ?></a>
	<?php else : ?>
		<a href="?halaman=<?= $i; ?>"><?= $i; ?></a>
	<?php endif; ?>
<?php endfor; ?>

<?php if( $halamanAktif < $jumlahHalaman ) : ?>
	<a href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
<?php endif; ?>

<a href="?halaman=<?= $jumlahHalaman; ?>">akhir</a>

<br>
<table border="1" cellpadding="10" cellspacing="0">

	<tr>
		<th>No.</th>
		<th>Foto</th>
		<th>Id anggota</th>
		<th>Nama</th>
		<th>Alamat</th>
		<th>No telepon</th>
		<th>Jenis kelamin</th>
		<th>Tanggal registrasi</th>
		<th>Status</th>
        <th>Set</th>
	</tr>

	<?php $i = 1; ?>
	<?php foreach( $anggota as $row ) : ?>
	<tr>
		<td><?= $i; ?></td>
		<td><img src="../assest/images/<?= $row["foto"]; ?>" width="50"></td>
		<td style="text-align: center;"><?= $row["id_anggota"]; ?></td>
		<td><?= $row["nama_anggota"]; ?></td>
		<td><?= $row["alamat"]; ?></td>
		<td><?= $row["no_telepon"]; ?></td>
		<td><?= $row["jenis_kelamin"]; ?></td>
		<td><?= $row["tanggal_registrasi"]; ?></td>
		<td><?= $row["status"]; ?></td>
        <td>
			<a href="edit_anggota.php?id=<?= $row["id_anggota"]; ?>">ubah</a> |
			<a href="../config/hapus.php?id=<?= $row["id_anggota"]; ?>" onclick="return confirm('yakin?');">hapus</a>
		</td>
	</tr>
	<?php $i++; ?>
	<?php endforeach; ?>
	
</table>
    </div>
</body>
</html>
