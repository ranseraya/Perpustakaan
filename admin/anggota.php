<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}
require '../config/functions.php';

$default_id = query("SELECT id_anggota FROM anggota ORDER BY id_anggota ASC LIMIT 1")[0]['id_anggota'];
$id = isset($_GET["id"]) ? $_GET["id"] : $default_id;
$currentData = query("SELECT * FROM anggota WHERE id_anggota = $id")[0];

$jumlahDataPerHalaman = 10;
$jumlahData = count(query("SELECT * FROM anggota"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = isset($_GET["halaman"]) ? $_GET["halaman"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
// $anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
// $filter = $_POST["filter_by"] ?? '';
// $startDate = $_POST["start_date"] ?? '';
// $endDate = $_POST["end_date"] ?? '';
// // $filter = 'default';
// if ($halamanAktif == 1){
// 	$anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
// } else {
// // Periksa apakah ada filter
// 	if ($filter == 'nama') {
// 		$anggota = query("SELECT * FROM anggota ORDER BY nama_anggota ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 	} elseif ($filter == 'alamat') {
// 		$anggota = query("SELECT * FROM anggota ORDER BY alamat ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 	} elseif ($filter == 'tanggal') {
// 		$anggota = query("SELECT * FROM anggota ORDER BY tanggal_registrasi ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 	} elseif ($filter == 'rentangTanggal') {
// 		if (!empty($startDate) && !empty($endDate)) {
// 			$anggota = query("SELECT * FROM anggota WHERE tanggal_registrasi BETWEEN '$startDate' AND '$endDate' ORDER BY tanggal_registrasi ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 		} else {
// 			// Default jika rentang tanggal tidak valid
// 			$anggota = query("SELECT * FROM anggota ORDER BY tanggal_registrasi ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 		}
// 	} else {
// 		// Default tanpa filter
// 		$anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
// 	}

// }
// if (isset($_POST["cari"])) {
//     $anggota = cariAnggota($_POST["keyword"]);
// } else {
//     $anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
// }
// if (isset($_POST["filter"])) {
// if (isset($_POST["filter_by"])) {
//     $filterBy = $_POST["filter_by"];
//     $startDate = $_POST["start_date"] ?? '';
//     $endDate = $_POST["end_date"] ?? '';
//     if ($filterBy === "nama") {
//         $anggota = query("SELECT * FROM anggota ORDER BY nama_anggota ASC LIMIT $awalData, $jumlahDataPerHalaman");
//     } elseif ($filterBy === "alamat") {
// 		$anggota = query("SELECT * FROM anggota ORDER BY alamat ASC LIMIT $awalData, $jumlahDataPerHalaman");
//     } elseif ($filterBy === "tanggal") {
// 		if (!empty($startDate) && !empty($endDate)) {
// 			$anggota = query("SELECT * FROM anggota WHERE tanggal_registrasi BETWEEN '$startDate' AND '$endDate' ORDER BY tanggal_registrasi ASC LIMIT $awalData, $jumlahDataPerHalaman");
//         } else {
//             $anggota = query("SELECT * FROM anggota ORDER BY tanggal_registrasi ASC LIMIT $awalData, $jumlahDataPerHalaman");
//         }
//     } else {
//         $anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
//     }
// }
// } else {
//     $anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
// }
$sortBy = isset($_POST['filter_by']) ? $_POST['filter_by'] : (isset($_GET['filter_by']) ? $_GET['filter_by'] : 'id_anggota');
$order = 'ASC';
if ($sortBy == 'tanggal') {
    $sortBy = 'tanggal_register';
}
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : (isset($_GET['start_date']) ? $_GET['start_date'] : '');
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : (isset($_GET['end_date']) ? $_GET['end_date'] : '');
$dateFilter = "";
if ($startDate && $endDate) {
    $dateFilter = "WHERE tanggal_register BETWEEN '$startDate' AND '$endDate'";
}

// Query dengan sorting, filter tanggal, dan pagination
$anggota = query("SELECT * FROM anggota $dateFilter ORDER BY $sortBy $order LIMIT $awalData, $jumlahDataPerHalaman");
// if (isset($_POST["filter_by"])) {
//     $filterBy = $_POST["filter_by"];
//     $startDate = $_POST["start_date"] ?? '';
//     $endDate = $_POST["end_date"] ?? '';
//     if ($filterBy === "nama") {
//         $anggota = query("SELECT * FROM anggota ORDER BY nama_anggota ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 		$filter = 'nama';
// 		$status = 1;
//     } elseif ($filterBy === "alamat") {
// 		$anggota = query("SELECT * FROM anggota ORDER BY alamat ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 		$filter = 'alamat';
// 		$status = 1;
//     } elseif ($filterBy === "tanggal") {
// 		if (!empty($startDate) && !empty($endDate)) {
// 			$anggota = query("SELECT * FROM anggota WHERE tanggal_registrasi BETWEEN '$startDate' AND '$endDate' ORDER BY tanggal_registrasi ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 			$filter = 'rentangTanggal';
// 			$status = 1;
//         } else {
// 			// Jika rentang tanggal tidak lengkap
//             $anggota = query("SELECT * FROM anggota ORDER BY tanggal_registrasi ASC LIMIT $awalData, $jumlahDataPerHalaman");
// 			$filter = 'tanggal';
// 			$status = 1;
//         }
//     } else {
//         // Jika filter tidak dipilih
//         $anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
//     }
// } elseif (isset($_POST["cari"])) {
//     // Logika untuk fitur pencarian
//     $anggota = cariAnggota($_POST["keyword"]);
// } else {
//     // Default menampilkan data
//     // $anggota = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");
// }

if( isset($_POST["submitTambah"]) ) {
	
	if( tambahAnggota($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil ditambahkan!');
				document.location.href = 'anggota.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'anggota.php';
			</script>
		";
	}
}

if( isset($_POST["submitUbah"]) ) {
	if( ubahAnggota($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil diubah!');
				document.location.href = 'anggota.php?';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal diubah!');
				document.location.href = 'anggota.php';
			</script>
		";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assest/anggota.css">
</head>
<body>
    <div class="sidebar">
        <h2><a href="dashboard.php">Admin Dashboard</a></h2>
        <!-- <a href="users.php">Users</a> -->
        <a href="buku.php">Kelola Buku</a>
        <a href="anggota.php">Kelola Anggota</a>
        <a href="petugas.php">Kelola Petugas</a>
        <a href="peminjaman.php">Kelola Peminjaman</a>
        <a href="../public/logout.php">Logout</a>
    </div>
    <div class="main-content">
        <h1></h1>




<div class="menu-anggota">
	<a id="tambahData">Tambah data anggota</a>
	
	<br><br>
	
	<form action="" method="post">
		<input type="text" name="keyword" class="search-data" autofocus placeholder="masukkan keyword pencarian.." autocomplete="off">
	<button type="submit" name="cari" class="search-button">Cari!</button>
</form>
</div>

<br><br>

<div class="row">
<div class="page">
    <a href="?halaman=1&filter_by=<?= $sortBy; ?>&start_date=<?= $startDate; ?>&end_date=<?= $endDate; ?>">awal</a>

    <?php if ($halamanAktif > 1) : ?>
        <a href="?halaman=<?= $halamanAktif - 1; ?>&filter_by=<?= $sortBy; ?>&start_date=<?= $startDate; ?>&end_date=<?= $endDate; ?>">&laquo;</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : 
        if ($i == 1 || $i == $jumlahHalaman || ($i >= $halamanAktif - 1 && $i <= $halamanAktif + 1)) : ?>
            <?php if ($i == $halamanAktif) : ?>
                <a href="?halaman=<?= $i; ?>&filter_by=<?= $sortBy; ?>&start_date=<?= $startDate; ?>&end_date=<?= $endDate; ?>" style="font-weight: bold; color: red;"><?= $i; ?></a>
            <?php else : ?>
                <a href="?halaman=<?= $i; ?>&filter_by=<?= $sortBy; ?>&start_date=<?= $startDate; ?>&end_date=<?= $endDate; ?>"><?= $i; ?></a>
            <?php endif; ?>
        <?php elseif ($i == $halamanAktif - 2 || $i == $halamanAktif + 2) : ?>
            <span>...</span>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($halamanAktif < $jumlahHalaman) : ?>
        <a href="?halaman=<?= $halamanAktif + 1; ?>&filter_by=<?= $sortBy; ?>&start_date=<?= $startDate; ?>&end_date=<?= $endDate; ?>">&raquo;</a>
    <?php endif; ?>

    <a href="?halaman=<?= $jumlahHalaman; ?>&filter_by=<?= $sortBy; ?>&start_date=<?= $startDate; ?>&end_date=<?= $endDate; ?>">akhir</a>
</div>


    <div class="filter">
        <form method="post" action="" style="margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div>
                    <label for="filter-by">Sorting Berdasarkan:</label>
                    <select id="filter-by" name="filter_by" onchange="toggleFilterOptions()">
                        <option value="">Pilih Sorting</option>
                        <option value="nama_anggota" <?php echo $sortBy === 'nama_anggota' ? 'selected' : ''; ?>>Nama</option>
                        <option value="alamat" <?php echo $sortBy === 'alamat' ? 'selected' : ''; ?>>Alamat</option>
                        <option value="tanggal" <?php echo $sortBy === 'tanggal_register' ? 'selected' : ''; ?>>Tanggal Registrasi</option>
                    </select>
                </div>

                <!-- Input untuk Rentang Tanggal -->
                <div id="filter-tanggal" style="display: <?php echo $sortBy === 'tanggal_register' ? 'block' : 'none'; ?>;">
                    <label for="start-date">Tanggal Awal:</label>
                    <input type="date" id="start-date" name="start_date" value="<?php echo $startDate; ?>">
                    <label for="end-date">Tanggal Akhir:</label>
                    <input type="date" id="end-date" name="end_date" value="<?php echo $endDate; ?>">
                </div>

                <!-- Tombol Filter -->
                <button type="submit" name="filter" class="btn-filter">Filter</button>
            </div>
        </form>
    </div>
	</div>

<br>
<table border="1" cellpadding="10" cellspacing="0">

	<tr>
		<th>No.</th>
		<th>Foto</th>
		<th>Id nggota</th>
		<th>Nama</th>
		<th>Alamat</th>
		<th>No telepon</th>
		<th>Jenis kelamin</th>
		<th>Tanggal registrasi</th>
		<th>Status</th>
        <th>Aksi</th>
	</tr>

	<?php $i = 1; ?>
	<?php foreach( $anggota as $row ) : ?>
	<tr>
		<td><?= $i+$jumlahDataPerHalaman*($halamanAktif-1); ?></td>
		<td><img src="../assest/images/<?= $row["foto"];?>" width="100" style="border-radius:50%;"></td>
		<td style="text-align: center;"><?= $row["id_anggota"]; ?></td>
		<td><?= $row["nama_anggota"]; ?></td>
		<td><?= $row["alamat"]; ?></td>
		<td><?= $row["no_telepon"]; ?></td>
		<td><?= $row["jenis_kelamin"]; ?></td>
		<td><?= $row["tanggal_registrasi"]; ?></td>
		<td><?= $row["STATUS"]; ?></td>
        <td >
			<div class="td-aksi">
				<!-- <a id="editData" data-id="<?= $anggota["id_anggota"]; ?>">ubah</a>  -->
				 <a id="editData" href="anggota.php?halaman=<?= $halamanAktif; ?> &id=<?= $row["id_anggota"]; ?>">Ubah</a>
				<a href="../config/hapus.php?aksi=anggota&halaman=<?= $halamanAktif; ?> &id=<?= $row["id_anggota"]; ?>" onclick="return confirm('yakin?');">hapus</a>
			</div>
		</td>
	</tr>
	<?php $i++; ?>
	<?php endforeach; ?>
	
</table>
    </div>


<!-- Popup Tambah Data -->
<div class="container-overlay" id="tambahData-overlay">
	<div class="container-content">
		<span class="close-popup" id="close-tambahData">&times;</span>
		<h1>Tambah Data Anggota</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<ul>
				<li>
					<label for="nama_anggota">Nama: </label>
					<input type="text" name="nama_anggota" id="nama_anggota" required>
				</li>
				<li>
					<label for="no_telepon">No Telepon: </label>
					<input type="text" name="no_telepon" id="no_telepon" required>
				</li>
				<li>
					<label for="alamat">Alamat: </label>
					<input type="text" name="alamat" id="alamat" required>
				</li>
				<li>
					<label for="jenis_kelamin">Jenis Kelamin: </label>
					<select id="jenis_kelamin" name="jenis_kelamin">
						<option value="Laki-laki">Laki-laki</option>
						<option value="Perempuan">Perempuan</option>
						<option value="Non-binary">Non-binary</option>
					</select>
				</li>
				<li>
					<label for="foto">Foto: </label>
					<input type="file" name="foto" id="foto">
				</li>
				<li>
					<button type="submit" name="submitTambah">Tambah Data!</button>
				</li>
			</ul>
		</form>
	</div>
</div>



<div class="container-overlay" id="editData-overlay">
        <div class="container-content">
            <span class="close-popup" id="close-editData">&times;</span>
            <h1>Edit Data Anggota</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_anggota" value="<?= $currentData["id_anggota"]; ?>">
                <input type="hidden" name="fotoLama" value="<?= $currentData["foto"]; ?>">
                <ul>
                    <li>
                        <label for="nama_anggota">Nama: </label>
                        <input type="text" name="nama_anggota" id="nama_anggota" value="<?= $currentData["nama_anggota"]; ?>" required>
                    </li>
                    <li>
                        <label for="alamat">Alamat: </label>
                        <input type="text" name="alamat" id="alamat" value="<?= $currentData["alamat"]; ?>" required>
                    </li>
                    <li>
                        <label for="no_telepon">No Telepon: </label>
                        <input type="text" name="no_telepon" id="no_telepon" value="<?= $currentData["no_telepon"]; ?>" required>
                    </li>
                    <li>
                        <label for="jenis_kelamin">Jenis Kelamin: </label>
                        <select id="jenis_kelamin" name="jenis_kelamin">
                            <option value="Laki-laki" <?= $currentData["jenis_kelamin"] == "Laki-laki" ? "selected" : ""; ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $currentData["jenis_kelamin"] == "Perempuan" ? "selected" : ""; ?>>Perempuan</option>
                            <option value="Non-binary" <?= $currentData["jenis_kelamin"] == "Non-binary" ? "selected" : ""; ?>>Non-binary</option>
                        </select>
                    </li>
                    <li>
                        <label for="foto">Foto: </label> <br>
                        <img src="../assest/images/<?= $currentData['foto']; ?>" width="40"> <br>
                        <input type="file" name="foto" id="foto">
                    </li>
                    <li>
                        <button type="submit" name="submitUbah">Ubah Data!</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

	<script>
        const tambahData = document.getElementById('tambahData');
        const tambahData_overlay = document.getElementById('tambahData-overlay');
        const editData= document.querySelectorAll('#editData');
        const editData_overlay = document.getElementById('editData-overlay');
        const close_tambahData = document.getElementById('close-tambahData');
        const close_editData = document.getElementById('close-editData');

        tambahData.addEventListener('click', () => {
            tambahData_overlay.classList.add('active');
        });
        tambahData_overlay.addEventListener('click', (e) => {
            if (e.target === tambahData_overlay) {
                tambahData_overlay.classList.remove('active');
            }
        });
		close_tambahData.addEventListener('click', () => {
            tambahData_overlay.classList.remove('active');
        });
		editData.forEach((a)=>{
			a.addEventListener('click', (e) => {
				// e.preventDefault(); 
				// editData_overlay.classList.add('active');
	
				const id = a.getAttribute('href').split('=')[1]; 
				history.pushState({ id: id }, '', `anggota.php?id=${id}`);
			});
		})

		window.addEventListener('load', () => {
		const urlParams = new URLSearchParams(window.location.search);
		const id = urlParams.get('id');
		
		if (id) {
			editData_overlay.classList.add('active'); 
			
			fetch(`anggota.php?id=${id}`)
				.then(response => response.json())
				.then(data => {
					document.getElementById('nama_anggota').value = data.nama_anggota;
					document.getElementById('alamat').value = data.alamat;
					document.getElementById('no_telepon').value = data.no_telepon;
					document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
					document.querySelector('#editData-overlay img').src = `../assest/images/${data.foto}`;
				})
				.catch(error => {
					console.error('Terjadi kesalahan:', error);
				});
		}
		});
		editData_overlay.addEventListener('click', (e) => {
			if (e.target === editData_overlay) {
                editData_overlay.classList.remove('active');
            }
        });
        close_editData.addEventListener('click', () => {
            editData_overlay.classList.remove('active');
        });

        function toggleFilterOptions() {
            const filterBy = document.getElementById('filter-by').value;
            const filterTanggal = document.getElementById('filter-tanggal');
            if (filterBy === 'tanggal') {
                filterTanggal.style.display = 'block';
            } else {
                filterTanggal.style.display = 'none';
            }
        }

    </script>
</body>
</html>
