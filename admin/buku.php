<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}
require '../config/functions.php';

$default_id = query("SELECT id_buku FROM buku ORDER BY id_buku ASC LIMIT 1")[0]['id_buku'];
$id = isset($_GET["id"]) ? $_GET["id"] : $default_id;
$currentData = query("SELECT * FROM buku WHERE id_buku = $id")[0];

$jumlahDataPerHalaman = 10;
$jumlahData = count(query("SELECT * FROM buku"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = isset($_GET["halaman"]) ? $_GET["halaman"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$sortBy = isset($_POST['filter_by']) ? $_POST['filter_by'] : (isset($_GET['filter_by']) ? $_GET['filter_by'] : 'judul');
$order = 'ASC';

$buku = query("SELECT * FROM buku ORDER BY $sortBy $order LIMIT $awalData, $jumlahDataPerHalaman");


if (isset($_POST["cari"])) {
    if($_POST["keyword"] != ''){
		$buku = cariBuku($_POST["keyword"]);
	}
} else {
	$buku = query("SELECT * FROM buku ORDER BY $sortBy $order LIMIT $awalData, $jumlahDataPerHalaman");
}

if( isset($_POST["submitTambah"]) ) {
	
	if( tambahBuku($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil ditambahkan!');
				document.location.href = 'buku.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'buku.php';
			</script>
		";
	}
}

if( isset($_POST["submitUbah"]) ) {
	if( ubahBuku($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil diubah!');
				document.location.href = 'buku.php?';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal diubah!');
				document.location.href = 'buku.php';
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
    <link rel="stylesheet" href="../assest/buku.css">
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




<div class="menu-buku">
	<a id="tambahData">Tambah data buku</a>
	
	<br><br>
	
	<form action="" method="post">
		<input type="text" name="keyword" class="search-data" autofocus placeholder="masukkan keyword pencarian.." autocomplete="off">
	<button type="submit" name="cari" class="search-button">Cari!</button>
</form>
</div>

<br><br>

<div class="row">
<div class="page">
    <a href="?halaman=1&filter_by=<?= $sortBy; ?>">awal</a>

    <?php if ($halamanAktif > 1) : ?>
        <a href="?halaman=<?= $halamanAktif - 1; ?>&filter_by=<?= $sortBy; ?>">&laquo;</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : 
        if ($i == 1 || $i == $jumlahHalaman || ($i >= $halamanAktif - 1 && $i <= $halamanAktif + 1)) : ?>
            <?php if ($i == $halamanAktif) : ?>
                <a href="?halaman=<?= $i; ?>&filter_by=<?= $sortBy; ?>" style="font-weight: bold; color: red;"><?= $i; ?></a>
            <?php else : ?>
                <a href="?halaman=<?= $i; ?>&filter_by=<?= $sortBy; ?>"><?= $i; ?></a>
            <?php endif; ?>
        <?php elseif ($i == $halamanAktif - 2 || $i == $halamanAktif + 2) : ?>
            <span>...</span>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($halamanAktif < $jumlahHalaman) : ?>
        <a href="?halaman=<?= $halamanAktif + 1; ?>&filter_by=<?= $sortBy; ?>">&raquo;</a>
    <?php endif; ?>

    <a href="?halaman=<?= $jumlahHalaman; ?>&filter_by=<?= $sortBy; ?>">akhir</a>
</div>


    <div class="filter">
        <form method="post" action="" style="margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div>
                    <label for="filter-by">Sorting Berdasarkan:</label>
                    <select id="filter-by" name="filter_by">
                        <option value="">Pilih Sorting</option>
                        <option value="judul" <?php echo $sortBy === 'judul' ? 'selected' : ''; ?>>Judul Buku</option>
                        <option value="tahun_terbit" <?php echo $sortBy === 'tahun_terbit' ? 'selected' : ''; ?>>Tahun Terbit</option>
                        <option value="jumlah" <?php echo $sortBy === 'jumlah' ? 'selected' : ''; ?>>Jumlah halaman</option>
                        <option value="stok" <?php echo $sortBy === 'stok' ? 'selected' : ''; ?>>Stok</option>
						</select>
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
		<th>Judul</th>
		<th>Id buku</th>
		<th>Pengarang</th>
		<th>Penerbit</th>
		<th>Tahun Terbit</th>
		<th>Kategori</th>
		<th>Julah Halaman</th>
		<th>ISBN</th>
		<th>Stok</th>
		<th>Aksi</th>
	</tr>

	<?php $i = 1; ?>
	<?php foreach( $buku as $row ) : ?>
	<tr>
		<td><?= $i+$jumlahDataPerHalaman*($halamanAktif-1); ?></td>
		<td><?= $row["judul"]; ?></td>
		<td style="text-align: center;"><?= $row["id_buku"]; ?></td>
		<td><?= $row["pengarang"]; ?></td>
		<td><?= $row["penerbit"]; ?></td>
		<td><?= $row["tahun_terbit"]; ?></td>
		<td><?= $row["kategori"]; ?></td>
		<td><?= $row["jumlah"]; ?></td>
		<td><?= $row["ISBN"]; ?></td>
		<td><?= $row["stok"]; ?></td>
        <td >
			<div class="td-aksi">
				<!-- <a id="editData" data-id="<?= $buku["id_buku"]; ?>">ubah</a>  -->
				 <a id="editData" href="buku.php?halaman=<?= $halamanAktif; ?> &id=<?= $row["id_buku"]; ?>">Ubah</a>
				<a href="../config/hapus.php?aksi=buku&halaman=<?= $halamanAktif; ?> &id=<?= $row["id_buku"]; ?>" onclick="return confirm('yakin?');">hapus</a>
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
		<h1>Tambah Data Buku</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<ul>
				<li>
					<label for="judul">Judul: </label>
					<input type="text" name="judul" id="judul" required>
				</li>
				<li>
					<label for="pengarang">Pengarang: </label>
					<input type="text" name="pengarang" id="pengarang" required>
				</li>
				<li>
					<label for="penerbit">Penerbit: </label>
					<input type="text" name="penerbit" id="penerbit" required>
				</li>
				<li>
					<label for="tahun_terbit">Tahun terbit: </label>
					<input type="text" name="tahun_terbit" id="tahun_terbit" required>
				</li>
				<li>
					<label for="kategori">Kategori: </label>
					<select id="kategori" name="kategori">
						<option value="Fiksi">Laki-laki</option>
						<option value="Teknologi">Teknologi</option>
						<option value="Petualangan">Petualangan</option>
						<option value="Ilmu Pengetahuan">Ilmu Pengetahuan</option>
						<option value="Romantis">Romantis</option>
						<option value="Horor">Horor</option>
						<option value="Strategi">Strategi</option>
						<option value="Thriller">Thriller</option>
						<option value="Drama">Drama</option>
						<option value="Komik">Komik</option>
						<option value="Autobiografi">Autobiografi</option>
					</select>
				</li>
				<li>
					<label for="jumlah">Jumlah halaman: </label>
					<input type="text" name="jumlah" id="jumlah" required>
				</li>
				<li>
					<label for="ISBN">ISBN: </label>
					<input type="text" name="ISBN" id="ISBN" required>
				</li>
				<li>
					<label for="stok">Stok: </label>
					<input type="text" name="stok" id="stok" required>
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
            <h1>Edit Data Buku</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_buku" value="<?= $currentData["id_buku"]; ?>">
                <!-- <input type="hidden" name="coverLama" value="<?= $currentData["cover"]; ?>"> -->
                <ul>
                    <li>
                        <label for="judul">Judul: </label>
                        <input type="text" name="judul" id="judul" value="<?= $currentData["judul"]; ?>" required>
                    </li>
                    <li>
                        <label for="pengarang">Pengarang: </label>
                        <input type="text" name="pengarang" id="pengarang" value="<?= $currentData["pengarang"]; ?>" required>
                    </li>
                    <li>
                        <label for="penerbit">Penerbit: </label>
                        <input type="text" name="penerbit" id="penerbit" value="<?= $currentData["penerbit"]; ?>" required>
                    </li>
                    <li>
                        <label for="tahun_terbit">Tahun terbit: </label>
                        <input type="text" name="tahun_terbit" id="tahun_terbit" value="<?= $currentData["tahun_terbit"]; ?>" required>
                    </li>
					<li>
						<label for="kategori">Kategori: </label>
						<select id="kategori" name="kategori">
							<option value="Fiksi">Laki-laki</option>
							<option value="Teknologi">Teknologi</option>
							<option value="Petualangan">Petualangan</option>
							<option value="Ilmu Pengetahuan">Ilmu Pengetahuan</option>
							<option value="Romantis">Romantis</option>
							<option value="Horor">Horor</option>
							<option value="Strategi">Strategi</option>
							<option value="Thriller">Thriller</option>
							<option value="Drama">Drama</option>
							<option value="Komik">Komik</option>
							<option value="Autobiografi">Autobiografi</option>
						</select>
					</li>
                    <li>
                        <label for="jumlah">Jumlah halaman: </label>
                        <input type="text" name="jumlah" id="jumlah" value="<?= $currentData["jumlah"]; ?>" required>
                    </li>
                    <li>
                        <label for="ISBN">ISBN: </label>
                        <input type="text" name="ISBN" id="ISBN" value="<?= $currentData["ISBN"]; ?>" required>
                    </li>
                    <li>
                        <label for="stok">Stok: </label>
                        <input type="text" name="stok" id="stok" value="<?= $currentData["stok"]; ?>" required>
                    </li>
                    <!-- <li>
                        <label for="foto">Foto: </label> <br>
                        <img src="../assest/images/<?= $currentData['foto']; ?>" width="40"> <br>
                        <input type="file" name="foto" id="foto">
                    </li> -->
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
				history.pushState({ id: id }, '', `buku.php?id=${id}`);
			});
		})

		window.addEventListener('load', () => {
		const urlParams = new URLSearchParams(window.location.search);
		const id = urlParams.get('id');
		
		if (id) {
			editData_overlay.classList.add('active'); 
			
			fetch(`buku.php?id=${id}`)
				.then(response => response.json())
				.then(data => {
					document.getElementById('judul').value = data.judul;
					document.getElementById('pengarang').value = data.pengarang;
					document.getElementById('penerbit').value = data.penerbit;
					document.getElementById('tahun_terbit').value = data.tahun_terbit;
					document.getElementById('kategori').value = data.kategori;
					document.getElementById('jumlah').value = data.jumlah;
					document.getElementById('ISBN').value = data.ISBN;
					document.getElementById('stok').value = data.stok;
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


    </script>
</body>
</html>
