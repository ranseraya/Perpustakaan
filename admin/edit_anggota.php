<?php
session_start();

if( !isset($_SESSION["login"]) ) {
	header("Location: ../public/login.php");
	exit;
}

require '../config/functions.php';

$id = $_GET["id"];

$anggota = query("SELECT * FROM anggota WHERE id_anggota = $id")[0];


if( isset($_POST["submit"]) ) {
	
	if( ubah($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil diubah!');
				document.location.href = 'dashboard.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal diubah!');
				document.location.href = 'dashboard.php';
			</script>
		";
	}


}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Ubah data anggota</title>
</head>
<body>
	<h1>Ubah data anggota</h1>

	<form action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id_anggota" value="<?= $anggota["id_anggota"]; ?>">
		<input type="hidden" name="gambarLama" value="<?= $anggota["foto"]; ?>">
		<ul>
			<li>
				<label for="nama_anggota">Nama : </label>
				<input type="text" name="nama_anggota" id="nama_anggota" value="<?= $anggota["nama_anggota"]; ?>">
			</li>
			<li>
				<label for="alamat">Alamat :</label>
				<input type="text" name="alamat" id="alamat" value="<?= $anggota["alamat"]; ?>">
			</li>
			<li>
				<label for="no_telepon">No Telepon :</label>
				<input type="text" name="no_telepon" id="no_telepon" value="<?= $anggota["no_telepon"]; ?>">
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
				<label for="foto">Foto :</label> <br>
				<img src="img/<?= $anggota['foto']; ?>" width="40"> <br>
				<input type="file" name="foto" id="foto">
			</li>
			<li>
				<button type="submit" name="submit">Ubah Data!</button>
			</li>
		</ul>

	</form>




</body>
</html>