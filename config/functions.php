<?php

// koneksi ke database
require '../config/database.php';
$conn;


function query($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
	return $rows;
}

/*
function tambah($data) {
	global $conn;

	$npm = htmlspecialchars($data["npm"]);
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);

	// upload foto
	$foto = upload();
	if( !$foto ) {
		return false;
	}

	$query = "INSERT INTO mahasiswa
				VALUES
			  ('', '$npm', '$nama', '$email', '$jurusan', '$foto')
			";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}


function upload() {

	$namaFile = $_FILES['foto']['name'];
	$ukuranFile = $_FILES['foto']['size'];
	$error = $_FILES['foto']['error'];
	$tmpName = $_FILES['foto']['tmp_name'];

	// cek apakah tidak ada foto yang diupload
	if( $error === 4 ) {
		echo "<script>
				alert('pilih foto terlebih dahulu!');
			  </script>";
		return false;
	}

	// cek apakah yang diupload adalah foto
	$ekstensifotoValid = ['jpg', 'jpeg', 'png'];
	$ekstensifoto = explode('.', $namaFile);
	$ekstensifoto = strtolower(end($ekstensifoto));
	if( !in_array($ekstensifoto, $ekstensifotoValid) ) {
		echo "<script>
				alert('yang anda upload bukan foto!');
			  </script>";
		return false;
	}

	// cek jika ukurannya terlalu besar
	if( $ukuranFile > 1000000 ) {
		echo "<script>
				alert('ukuran foto terlalu besar!');
			  </script>";
		return false;
	}

	// lolos pengecekan, foto siap diupload
	// generate nama foto baru
	$namaFileBaru = uniqid();
	$namaFileBaru .= '.';
	$namaFileBaru .= $ekstensifoto;

	move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

	return $namaFileBaru;
}




function hapus($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");
	return mysqli_affected_rows($conn);
}


function ubah($data) {
	global $conn;

	$id = $data["id"];
	$npm = htmlspecialchars($data["npm"]);
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);
	$fotoLama = htmlspecialchars($data["fotoLama"]);
	
	// cek apakah user pilih foto baru atau tidak
	if( $_FILES['foto']['error'] === 4 ) {
		$foto = $fotoLama;
	} else {
		$foto = upload();
	}
	

	$query = "UPDATE mahasiswa SET
				npm = '$npm',
				nama = '$nama',
				email = '$email',
				jurusan = '$jurusan',
				foto = '$foto'
			  WHERE id = $id
			";

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);	
}
*/

function cari($keyword) {
	$query = "SELECT * FROM buku
				WHERE
			  judul LIKE '%$keyword%' OR
			  pengarang LIKE '%$keyword%' OR
			  penerbit LIKE '%$keyword%' OR
			  tahun_terbit LIKE '%$keyword%' OR
			  kategori LIKE '%$keyword%' OR
			  ISBN LIKE '%$keyword%'
			";
	return query($query);
}
function cariAnggota($keyword) {
	$query = "SELECT * FROM anggota
				WHERE
			  id_anggota LIKE '%$keyword%' OR
			  nama_anggota LIKE '%$keyword%' OR
			  alamat LIKE '%$keyword%' OR
			  no_telepon LIKE '%$keyword%' OR
			  jenis_kelamin LIKE '%$keyword%' OR
			  tanggal_registrasi LIKE '%$keyword%' OR
			  status LIKE '%$keyword%'
			";
	return query($query);
}
function tambahAnggota($data) {
	global $conn;

	$nama = htmlspecialchars($data["nama_anggota"]);
	$alamat = htmlspecialchars($data["alamat"]);
	$no_telepon = htmlspecialchars($data["no_telepon"]);
	$jenis_kelamin = htmlspecialchars($data["jenis_kelamin"]);

	// upload foto
	$foto = upload();
	if( !$foto ) {
		return false;
	}

	$query = "INSERT INTO anggota (nama_anggota, alamat, no_telepon, jenis_kelamin, tanggal_registrasi, status, foto)
				VALUES
			  ('$nama', '$alamat', '$no_telepon', '$jenis_kelamin', CURDATE(), 'aktif', '$foto')
			";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function hapusAnggota($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM anggota WHERE id_anggota = $id");
	return mysqli_affected_rows($conn);
}



function upload() {

	$namaFile = $_FILES['foto']['name'];
	$ukuranFile = $_FILES['foto']['size'];
	$error = $_FILES['foto']['error'];
	$tmpName = $_FILES['foto']['tmp_name'];

	// cek apakah tidak ada foto yang diupload
	if( $error === 4 ) {
		echo "<script>
				alert('pilih foto terlebih dahulu!');
			  </script>";
		return false;
	}

	// cek apakah yang diupload adalah foto
	$ekstensifotoValid = ['jpg', 'jpeg', 'png'];
	$ekstensifoto = explode('.', $namaFile);
	$ekstensifoto = strtolower(end($ekstensifoto));
	if( !in_array($ekstensifoto, $ekstensifotoValid) ) {
		echo "<script>
				alert('yang anda upload bukan foto!');
			  </script>";
		return false;
	}

	// cek jika ukurannya terlalu besar
	if( $ukuranFile > 1000000 ) {
		echo "<script>
				alert('ukuran foto terlalu besar!');
			  </script>";
		return false;
	}

	// lolos pengecekan, foto siap diupload
	// generate nama foto baru
	$namaFileBaru = uniqid();
	$namaFileBaru .= '.';
	$namaFileBaru .= $ekstensifoto;

	move_uploaded_file($tmpName, '../assest/images/' . $namaFileBaru);

	return $namaFileBaru;
}

function registrasi($data) {
	global $conn;

	$username = strtolower(stripslashes($data["username"]));
	$password = mysqli_real_escape_string($conn, $data["password"]);
	$password2 = mysqli_real_escape_string($conn, $data["password2"]);

	// cek username sudah ada atau belum
	$result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");

	if( mysqli_fetch_assoc($result) ) {
		echo "<script>
				alert('username sudah terdaftar!')
		      </script>";
		return false;
	}


	// cek konfirmasi password
	if( $password !== $password2 ) {
		echo "<script>
				alert('konfirmasi password tidak sesuai!');
		      </script>";
		return false;
	}

	// enkripsi password
	$password = password_hash($password, PASSWORD_DEFAULT);

	// tambahkan userbaru ke database
	mysqli_query($conn, "INSERT INTO users (username, password_hash, role) VALUES('$username', '$password', 'anggota')");

	return mysqli_affected_rows($conn);

}









?>