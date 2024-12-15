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

function cariBuku($keyword) {
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
function tambahBuku($data) {
	global $conn;

	$judul = htmlspecialchars($data["judul"]);
	$pengarang = htmlspecialchars($data["pengarang"]);
	$penerbit = htmlspecialchars($data["penerbit"]);
	$tahun_terbit = htmlspecialchars($data["tahun_terbit"]);
	$kategori = htmlspecialchars($data["kategori"]);
	$jumlah = htmlspecialchars($data["jumlah"]);
	$ISBN = htmlspecialchars($data["ISBN"]);
	$stok = htmlspecialchars($data["stok"]);
	$query = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, kategori, jumlah, ISBN, stok)
				VALUES
			  ('$judul', '$pengarang', '$penerbit', '$tahun_terbit', '$kategori', '$jumlah', '$ISBN', '$stok')
			";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function ubahBuku($data) {
	global $conn;

	$id = $data["id_buku"];
	$judul = htmlspecialchars($data["judul"]);
	$pengarang = htmlspecialchars($data["pengarang"]);
	$penerbit = htmlspecialchars($data["penerbit"]);
	$tahun_terbit = htmlspecialchars($data["tahun_terbit"]);
	$kategori = htmlspecialchars($data["kategori"]);
	$jumlah = htmlspecialchars($data["jumlah"]);
	$ISBN = htmlspecialchars($data["ISBN"]);
	$stok = htmlspecialchars($data["stok"]);
	
	// if( $_FILES['cover']['error'] === 4 ) {
		// $cover = $coverLama;
	// } else {
		// $cover = upload();
	// }
	

	$query = "UPDATE buku SET
				judul = '$judul',
				pengarang = '$pengarang',
				penerbit = '$penerbit',
				tahun_terbit = '$tahun_terbit',
				kategori = '$kategori',
				jumlah = '$jumlah',
				ISBN = '$ISBN',
				stok = '$stok'
			  WHERE id_buku = $id
			";

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);	
}

function hapusBuku($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM buku WHERE id_buku = $id");
	return mysqli_affected_rows($conn);
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

function ubahAnggota($data) {
	global $conn;

	$id = $data["id_anggota"];
	$nama = htmlspecialchars($data["nama_anggota"]);
	$alamat = htmlspecialchars($data["alamat"]);
	$no_telepon = htmlspecialchars($data["no_telepon"]);
	$jenis_kelamin = htmlspecialchars($data["jenis_kelamin"]);
	$fotoLama = htmlspecialchars($data["fotoLama"]);
	
	// cek apakah user pilih foto baru atau tidak
	if( $_FILES['foto']['error'] === 4 ) {
		$foto = $fotoLama;
	} else {
		$foto = upload();
	}
	

	$query = "UPDATE anggota SET
				nama_anggota = '$nama',
				alamat = '$alamat',
				no_telepon = '$no_telepon',
				jenis_kelamin = '$jenis_kelamin',
				foto = '$foto'
			  WHERE id_anggota = $id
			";

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);	
}






function cariPetugas($keyword) {
	$query = "SELECT * FROM petugas
				WHERE
			  id_petugas LIKE '%$keyword%' OR
			  nama_petugas LIKE '%$keyword%' OR
			  jabatan LIKE '%$keyword%' OR
			  alamat LIKE '%$keyword%' OR
			  no_telepon LIKE '%$keyword%' OR
			  jenis_kelamin LIKE '%$keyword%' OR
			  bertugas_sejak LIKE '%$keyword%'
			";
	return query($query);
}
function tambahPetugas($data) {
	global $conn;

	$nama = htmlspecialchars($data["nama_petugas"]);
	$jabatan = htmlspecialchars($data["jabatan"]);
	$alamat = htmlspecialchars($data["alamat"]);
	$no_telepon = htmlspecialchars($data["no_telepon"]);
	$jenis_kelamin = htmlspecialchars($data["jenis_kelamin"]);
	// $bertugas_sejak = htmlspecialchars($data["bertugas_sejak"]);
	$foto = upload();
	if( !$foto ) {
		return false;
	}

	$query = "INSERT INTO petugas (nama_petugas, jabatan, alamat, no_telepon, jenis_kelamin, bertugas_sejak, foto)
				VALUES
			  ('$nama', '$jabatan', '$alamat', '$no_telepon', '$jenis_kelamin', CURDATE(), '$foto')
			";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function hapusPetugas($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM petugas WHERE id_petugas = $id");
	return mysqli_affected_rows($conn);
}

function ubahPetugas($data) {
	global $conn;

	$id = $data["id_petugas"];
	$nama = htmlspecialchars($data["nama_petugas"]);
	$jabatan = htmlspecialchars($data["jabatan"]);
	
	// cek apakah user pilih foto baru atau tidak
	// if( $_FILES['foto']['error'] === 4 ) {
		// $foto = $fotoLama;
	// } else {
		// $foto = upload();
	// }
	

	$query = "UPDATE petugas SET
				nama_petugas = '$nama',
				jabatan = '$jabatan'
			  WHERE id_petugas = $id
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
	mysqli_query($conn, "INSERT INTO users (username, password_hash, role) VALUES('$username', '$password', 'admin')");

	return mysqli_affected_rows($conn);

}









?>