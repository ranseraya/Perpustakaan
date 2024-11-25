<?php 
session_start();

if( !isset($_SESSION["login"]) ) {
	header("Location: login.php");
	exit;
}

require 'functions.php';

$id = $_GET["id"];

if( hapusAnggota($id) > 0 ) {
	echo "
		<script>
			alert('data berhasil dihapus!');
			document.location.href = '../admin/anggota.php';
		</script>
	";
} else {
	echo "
		<script>
			alert('data gagal ditambahkan!');
			document.location.href = '../admin/anggota.php';
		</script>
	";
}

?>