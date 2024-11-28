<?php 
session_start();

if( !isset($_SESSION["login"]) ) {
	header("Location: login.php");
	exit;
}

require 'functions.php';

$id = $_GET["id"];
$halamanAktif = $_GET["halaman"];
$aksi = $_GET["aksi"];

switch($aksi){
	case "buku":
		if( hapusBuku($id) > 0 ) {
			echo "
				<script>
					alert('Buku berhasil dihapus!');
					document.location.href = '../admin/buku.php?halaman=$halamanAktif';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Buku gagal dihapus!');
					document.location.href = '../admin/buku.php?halaman=$halamanAktif';
				</script>
			";
		}
	case "anggota":
		if( hapusAnggota($id) > 0 ) {
			echo "
				<script>
					alert('Data anggota berhasil dihapus!');
					document.location.href = '../admin/anggota.php?halaman=$halamanAktif';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Data anggota gagal dihapus!');
					document.location.href = '../admin/anggota.php?halaman=$halamanAktif';
				</script>
			";
		}
	case "petugas":
		if( hapusPetugas($id) > 0 ) {
			echo "
				<script>
					alert('Data petugas berhasil dihapus!');
					document.location.href = '../admin/petugas.php?halaman=$halamanAktif';
				</script>
			";
		} else {
			echo "
				<script>
					alert('Data petugas gagal dihapus!');
					document.location.href = '../admin/petugas.php?halaman=$halamanAktif';
				</script>
			";
		}
	case "peminjaman":
	default:
		echo "
			<script>
				alert('Aksi gagal dilakukan!');
				document.location.href = '../admin/dashboard.php';
			</script>
		";
}

		// if( hapusBuku($id) > 0 ) {
		// 	echo "
		// 		<script>
		// 			alert('Buku berhasil dihapus!');
		// 			document.location.href = '../admin/buku.php?halaman=$halamanAktif';
		// 		</script>
		// 	";
		// } else  {
		// 	echo "
		// 		<script>
		// 			alert('Buku gagal dihapus!');
		// 			document.location.href = '../admin/buku.php?halaman=$halamanAktif';
		// 		</script>
		// 	";
		// }
		// if( hapusAnggota($id) > 0 ) {
		// 	echo "
		// 		<script>
		// 			alert('Data anggota berhasil dihapus!');
		//  			document.location.href = '../admin/anggota.php?halaman=$halamanAktif';
		// 			 </script>
		// 			 ";
		// } else {
		// 	echo "
		// 		<script>
		// 			alert('Data anggota gagal dihapus!');
		// 			document.location.href = '../admin/anggota.php?halaman=$halamanAktif';
		// 		</script>
		// 	";
		// }

?>