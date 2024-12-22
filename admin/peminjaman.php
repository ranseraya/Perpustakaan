<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}
require '../config/functions.php';


$id_default = query("SELECT id_peminjaman FROM peminjaman ORDER BY id_peminjaman ASC LIMIT 1")[0]['id_peminjaman'];
$id = isset($_GET["id"]) ? $_GET["id"] : $id_default;
$currentData = query("SELECT 
        peminjaman.id_peminjaman,
        anggota.nama_anggota AS nama_anggota,
        buku.judul AS judul_buku,
        petugas.nama_petugas AS nama_petugas,
        peminjaman.tanggal_peminjaman,
        peminjaman.tanggal_pengembalian,
        peminjaman.status_pengembalian
    FROM 
        peminjaman
    JOIN 
        anggota ON peminjaman.id_anggota = anggota.id_anggota
    JOIN 
        buku ON peminjaman.id_buku = buku.id_buku
    JOIN 
        petugas ON peminjaman.id_petugas = petugas.id_petugas
    WHERE id_peminjaman = $id")[0];

$jumlahDataPerHalaman = 10;
$jumlahData = count(query("SELECT * FROM peminjaman"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
$awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;

$sortBy = isset($_POST['filter_by']) ? $_POST['filter_by'] : (isset($_GET['filter_by']) ? $_GET['filter_by'] : 'id_peminjaman');
$order = 'ASC';
if ($sortBy == 'tanggal') {
    $sortBy = 'tanggal_peminjaman';
}
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : (isset($_GET['start_date']) ? $_GET['start_date'] : '');
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : (isset($_GET['end_date']) ? $_GET['end_date'] : '');
$dateFilter = "";
if ($startDate && $endDate) {
    $dateFilter = "WHERE tanggal_peminjaman BETWEEN '$startDate' AND '$endDate'";
}


$peminjaman = query("SELECT 
        peminjaman.id_peminjaman,
        anggota.nama_anggota AS nama_anggota,
        buku.judul AS judul_buku,
        petugas.nama_petugas AS nama_petugas,
        peminjaman.tanggal_peminjaman,
        peminjaman.tanggal_pengembalian,
        peminjaman.status_pengembalian
    FROM 
        peminjaman
    JOIN 
        anggota ON peminjaman.id_anggota = anggota.id_anggota
    JOIN 
        buku ON peminjaman.id_buku = buku.id_buku
    JOIN 
        petugas ON peminjaman.id_petugas = petugas.id_petugas
    $dateFilter ORDER BY $sortBy $order LIMIT $awalData, $jumlahDataPerHalaman");


// $peminjaman = query("SELECT 
//         peminjaman.id_peminjaman,
//         anggota.nama_anggota AS nama_anggota,
//         buku.judul AS judul_buku,
//         petugas.nama_petugas AS nama_petugas,
//         peminjaman.tanggal_peminjaman,
//         peminjaman.tanggal_pengembalian,
//         peminjaman.status_pengembalian
//     FROM 
//         peminjaman
//     JOIN 
//         anggota ON peminjaman.id_anggota = anggota.id_anggota
//     JOIN 
//         buku ON peminjaman.id_buku = buku.id_buku
//     JOIN 
//         petugas ON peminjaman.id_petugas = petugas.id_petugas
//     ORDER BY 
//         peminjaman.id_peminjaman ASC 
//     LIMIT $awalData, $jumlahDataPerHalaman
// ");


if (isset($_POST["cari"])) {
	if($_POST["keyword"] != ""){
		$peminjaman = cariPeminjaman($_POST["keyword"]);
	}
} else {
	$peminjaman = query("SELECT 
	peminjaman.id_peminjaman,
	anggota.nama_anggota AS nama_anggota,
	buku.judul AS judul_buku,
	petugas.nama_petugas AS nama_petugas,
	peminjaman.tanggal_peminjaman,
	peminjaman.tanggal_pengembalian,
	peminjaman.status_pengembalian
FROM 
	peminjaman
JOIN 
	anggota ON peminjaman.id_anggota = anggota.id_anggota
JOIN 
	buku ON peminjaman.id_buku = buku.id_buku
JOIN 
	petugas ON peminjaman.id_petugas = petugas.id_petugas
$dateFilter ORDER BY $sortBy $order LIMIT $awalData, $jumlahDataPerHalaman");
}

if( isset($_POST["submitTambah"]) ) {
	
	if( tambahPeminjaman($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil ditambahkan!');
				document.location.href = 'peminjaman.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'peminjaman.php';
			</script>
		";
	}
}

if( isset($_POST["submitUbah"]) ) {
	if( ubahPeminjaman($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil diubah!');
				document.location.href = 'peminjaman.php?';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal diubah!');
				document.location.href = 'peminjaman.php';
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
    <link rel="stylesheet" href="../assest/peminjaman.css">
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
        <h1></h1>




<div class="menu-peminjaman">
	<a id="tambahData">Tambah data peminjaman</a>
	
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
                        <option value="nama_anggota" <?php echo $sortBy === 'nama_anggota' ? 'selected' : ''; ?>>Nama Anggota</option>
                        <option value="nama_petugas" <?php echo $sortBy === 'nama_petugas' ? 'selected' : ''; ?>>Pengurus Peminjaman</option>
                        <option value="judul" <?php echo $sortBy === 'judul' ? 'selected' : ''; ?>>Judul Buku</option>
                        <option value="alamat" <?php echo $sortBy === 'alamat' ? 'selected' : ''; ?>>Alamat</option>
                        <option value="tanggal" <?php echo $sortBy === 'tanggal_peminjaman' ? 'selected' : ''; ?>>Tanggal Peminjaman</option>
                    </select>
                </div>

                <!-- Input untuk Rentang Tanggal -->
                <div id="filter-tanggal" style="display: <?php echo $sortBy === 'tanggal_peminjaman' ? 'block' : 'none'; ?>;">
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
		<th>Id Peminjaman</th>
		<th>Nama Anggota</th>
		<th>Pengurus Peminjaman</th>
		<th>Judul Buku</th>
		<th>Tanggal Peminjaman</th>
		<th>Tanggal Pengembalian</th>
		<th>Status Pengembalian</th>
		<!-- <th>Aksi</th> -->
	</tr>

	<?php $i = 1; ?>
	<?php foreach( $peminjaman as $row ) : ?>
	<tr>
		<td><?= $i+$jumlahDataPerHalaman*($halamanAktif-1); ?></td>
		<!-- <td><img src="../assest/images/<?= $row["foto"];?>" width="100" style="border-radius:50%;"></td> -->
		<td style="text-align: center;"><?= $row["id_peminjaman"]; ?></td>
		<td><?= $row["nama_anggota"]; ?></td>
		<td><?= $row["nama_petugas"]; ?></td>
		<td><?= $row["judul_buku"]; ?></td>
		<td><?= $row["tanggal_peminjaman"]; ?></td>
		<td><?= $row["tanggal_pengembalian"]; ?></td>
		<td><?= $row["status_pengembalian"]; ?></td>
	</tr>
	<?php $i++; ?>
	<?php endforeach; ?>
	
</table>
    </div>


<!-- Popup Tambah Data -->
<div class="container-overlay" id="tambahData-overlay">
	<div class="container-content">
		<span class="close-popup" id="close-tambahData">&times;</span>
		<h1>Tambah Data peminjaman</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<ul>
				<li>
					<label for="nama_anggota">Nama Peminjam: </label>
					<input type="text" name="nama_anggota" id="nama_anggota" required>
				</li>
				<li>
					<label for="nama_petugas">Nama Petugas: </label>
					<input type="text" name="jabatan" id="jabatan" required>
				</li>
				<li>
					<label for="judul">Judul Buku: </label>
					<input type="text" name="judul" id="judul" required>
				</li>
				<li>
					<label for="tanggal_peminjaman">Tanggal Awal:</label>
					<input type="date" id="tanggal_peminjaman" name="tanggal_peminjaman">
					<label for="tanggal_pengembalian">Tanggal Akhir:</label>
					<input type="date" id="tanggal_pengembalian" name="tanggal_pengembalian">
				</li>
				<li>
					<label for="status_pengembalian">Status peminjaman: </label>
					<select id="status_pengembalian" name="status_pengembalian">
						<option value="1">Sudah dikembalikan</option>
						<option value="2">Belum dikembalikan</option>
					</select>
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
            <h1>Edit Data peminjaman</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_peminjaman" value="<?= $currentData["id_peminjaman"]; ?>">
                <!-- <input type="hidden" name="fotoLama" value="<?= $currentData["foto"]; ?>"> -->
                <ul>
                    <li>
                        <label for="nama_peminjaman">Nama: </label>
                        <input type="text" name="nama_peminjaman" id="nama_peminjaman" value="<?= $currentData["nama_peminjaman"]; ?>" required>
                    </li>
                    <li>
                        <label for="jabatan">Jabatan: </label>
                        <input type="text" name="jabatan" id="jabatan" value="<?= $currentData["jabatan"]; ?>" required>
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
				history.pushState({ id: id }, '', `peminjaman.php?id=${id}`);
			});
		})

		window.addEventListener('load', () => {
		const urlParams = new URLSearchParams(window.location.search);
		const id = urlParams.get('id');
		
		if (id) {
			editData_overlay.classList.add('active'); 
			
			fetch(`peminjaman.php?id=${id}`)
				.then(response => response.json())
				.then(data => {
					document.getElementById('nama_peminjaman').value = data.nama_peminjaman;
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
        document.getElementById('start-date').value = '';
        document.getElementById('end-date').value = '';
    }
}
    </script>
</body>
</html>
