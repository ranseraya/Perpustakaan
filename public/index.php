<?php 
session_start();

if( !isset($_SESSION["login"]) ) {
	header("Location: login.php");
	exit;
}

require '../config/functions.php';
$jumlahDataPerHalaman = 25;
$jumlahData = count(query("SELECT * FROM buku"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
$awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;
$buku = query("SELECT * FROM buku LIMIT $awalData, $jumlahDataPerHalaman");


// tombol cari ditekan
if (isset($_POST["cari"])) {
    $buku = cari($_POST["keyword"]);
} else {
    $buku = query("SELECT * FROM buku LIMIT $awalData, $jumlahDataPerHalaman");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Halaman Admin</title>
</head>
<body>

<a href="logout.php">Logout</a>

<h1>Daftar buku</h1>

<a href="tambah.php">Tambah data buku</a>
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
		<th>Judul</th>
		<th>Pengarang</th>
		<th>Penerbit</th>
		<th>Tahun Terbit</th>
		<th>Kategori</th>
		<th>Jumlah Halaman</th>
		<th>ISBN</th>
		<th>stok</th>
	</tr>

	<?php $i = 1; ?>
	<?php foreach( $buku as $row ) : ?>
	<tr>
		<td><?= $i; ?></td>
		<!-- <td> -->
			<!-- <a href="ubah.php?id=<?= $row["id"]; ?>">ubah</a> | -->
			<!-- <a>ubah</a> | -->
			<!-- <a href="hapus.php?id=<?= $row["id"]; ?>" onclick="return confirm('yakin?');">hapus</a> -->
			<!-- <a>hapus</a> -->
		<!-- </td> -->
		<!-- <td><img src="img/<?= $row["gambar"]; ?>" width="50"></td> -->
		<td><?= $row["judul"]; ?></td>
		<td><?= $row["pengarang"]; ?></td>
		<td><?= $row["penerbit"]; ?></td>
		<td><?= $row["tahun_terbit"]; ?></td>
		<td><?= $row["kategori"]; ?></td>
		<td><?= $row["jumlah"]; ?></td>
		<td><?= $row["ISBN"]; ?></td>
		<td><?= $row["stok"]; ?></td>
	</tr>
	<?php $i++; ?>
	<?php endforeach; ?>
	
</table>

</body>
</html>