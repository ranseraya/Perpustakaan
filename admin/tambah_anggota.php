<?php
session_start();

if( !isset($_SESSION["login"]) ) {
	header("Location: login.php");
	exit;
}

require '../config/functions.php';

if( isset($_POST["submit"]) ) {
	
	if( tambahAnggota($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil ditambahkan!');
				document.location.href = 'dashboard.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'dashboard.php';
			</script>
		";
	}


}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Tambah data anggota</title>
</head>
<body>
	<h1>Tambah data anggota</h1>

	<form action="" method="post" enctype="multipart/form-data">
		<ul>
			<li>
				<label for="nama_anggota">Nama : </label>
				<input type="text" name="nama_anggota" id="nama_anggota">
			</li>
			<li>
				<label for="no_telepon">No telepon :</label>
				<input type="text" name="no_telepon" id="no_telepon">
			</li>
			<li>
				<label for="alamat">Alamat :</label>
				<input type="text" name="alamat" id="alamat">
			</li>
			<li>
				<label for="jenis_kelamin">Jenis Kelamin :</label>
				    <select id="jenis_kelamin" name="jenis_kelamin">
						<option value="Laki-laki">Laki-laki</option>
						<option value="Perempuan">Perempuan</option>
						<option value="Non-binary">Non binary</option>
					</select>
			</li>
            <li>
				<label for="foto">Foto :</label>
				<input type="file" name="foto" id="foto">
			</li>
			<li>
				<button type="submit" name="submit">Tambah Data!</button>
			</li>
		</ul>

	</form>




</body>
</html>