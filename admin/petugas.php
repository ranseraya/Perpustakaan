<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}
require '../config/functions.php';

$id = isset($_GET["id"]) ? $_GET["id"] : 1;
$currentData = query("SELECT * FROM petugas WHERE id_petugas = $id")[0];

$jumlahDataPerHalaman = 10;
$jumlahData = count(query("SELECT * FROM petugas"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
$awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;
$petugas = query("SELECT * FROM petugas LIMIT $awalData, $jumlahDataPerHalaman");


if (isset($_POST["cari"])) {
    $petugas = caripetugas($_POST["keyword"]);
} else {
    $buku = query("SELECT * FROM petugas LIMIT $awalData, $jumlahDataPerHalaman");
}

if( isset($_POST["submitTambah"]) ) {
	
	if( tambahpetugas($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil ditambahkan!');
				document.location.href = 'petugas.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'petugas.php';
			</script>
		";
	}
}

if( isset($_POST["submitUbah"]) ) {
	if( ubahpetugas($_POST) > 0 ) {
		echo "
			<script>
				alert('data berhasil diubah!');
				document.location.href = 'petugas.php?';
			</script>
		";
	} else {
		echo "
			<script>
				alert('data gagal diubah!');
				document.location.href = 'petugas.php';
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
    <link rel="stylesheet" href="../assest/petugas.css">
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




<div class="menu-petugas">
	<a id="tambahData">Tambah data petugas</a>
	
	<br><br>
	
	<form action="" method="post">
		<input type="text" name="keyword" class="search-data" autofocus placeholder="masukkan keyword pencarian.." autocomplete="off">
	<button type="submit" name="cari" class="search-button">Cari!</button>
</form>
</div>

<br><br>

<div class="page">
	<a href="?halaman=1">awal</a>
	
	<?php if( $halamanAktif > 1 ) : ?>
		<a href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
		<?php endif; ?>
		
		<?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : 
        if( $i == 1 || $i == $jumlahHalaman || ($i >= $halamanAktif - 1 && $i <= $halamanAktif + 1)) : ?>
            <?php if( $i == $halamanAktif ) : ?>
                <a href="?halaman=<?= $i; ?>" style="font-weight: bold; color: red;"><?= $i; ?></a>
            <?php else : ?>
                <a href="?halaman=<?= $i; ?>"><?= $i; ?></a>
            <?php endif; ?>
        <?php 
        elseif( $i == $halamanAktif - 2 || $i == $halamanAktif + 2) : ?>
            <span>...</span>
        <?php endif; ?>
    <?php endfor; ?>
					
					<?php if( $halamanAktif < $jumlahHalaman ) : ?>
						<a href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
						<?php endif; ?>
						
						<a href="?halaman=<?= $jumlahHalaman; ?>">akhir</a>
					</div>

<br>
<table border="1" cellpadding="10" cellspacing="0">

	<tr>
		<th>No.</th>
		<!-- <th>Foto</th> -->
		<th>Foto</th>
		<th>Id Petugas</th>
		<th>Nama</th>
		<th>Jabatan</th>
		<th>Alamat</th>
		<th>No Telepon</th>
		<th>Jenis Kelamin</th>
		<th>Bertugas Sejak</th>
		<th>Aksi</th>
	</tr>

	<?php $i = 1; ?>
	<?php foreach( $petugas as $row ) : ?>
	<tr>
		<td><?= $i+$jumlahDataPerHalaman*($halamanAktif-1); ?></td>
		<td><img src="../assest/images/<?= $row["foto"];?>" width="100" style="border-radius:50%;"></td>
		<td style="text-align: center;"><?= $row["id_petugas"]; ?></td>
		<td><?= $row["nama_petugas"]; ?></td>
		<td><?= $row["jabatan"]; ?></td>
		<td><?= $row["alamat"]; ?></td>
		<td><?= $row["no_telepon"]; ?></td>
		<td><?= $row["jenis_kelamin"]; ?></td>
		<td><?= $row["bertugas_sejak"]; ?></td>
        <td >
			<div class="td-aksi">
				<!-- <a id="editData" data-id="<?= $petugas["id_petugas"]; ?>">ubah</a>  -->
				 <a id="editData" href="petugas.php?halaman=<?= $halamanAktif; ?> &id=<?= $row["id_petugas"]; ?>">Ubah</a>
				<a href="../config/hapus.php?aksi=petugas&halaman=<?= $halamanAktif; ?> &id=<?= $row["id_petugas"]; ?>" onclick="return confirm('yakin?');">hapus</a>
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
		<h1>Tambah Data Petugas</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<ul>
				<li>
					<label for="nama_petugas">Nama: </label>
					<input type="text" name="nama_petugas" id="nama_petugas" required>
				</li>
				<li>
					<label for="jabatan">Jabatan: </label>
					<input type="text" name="jabatan" id="jabatan" required>
				</li>
				<li>
					<label for="alamat">Alamat: </label>
					<input type="text" name="alamat" id="alamat" required>
				</li>
				<li>
					<label for="no_telepon">No Telepon: </label>
					<input type="text" name="no_telepon" id="no_telepon" required>
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
            <h1>Edit Data petugas</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_petugas" value="<?= $currentData["id_petugas"]; ?>">
                <!-- <input type="hidden" name="fotoLama" value="<?= $currentData["foto"]; ?>"> -->
                <ul>
                    <li>
                        <label for="nama_petugas">Nama: </label>
                        <input type="text" name="nama_petugas" id="nama_petugas" value="<?= $currentData["nama_petugas"]; ?>" required>
                    </li>
                    <li>
                        <label for="jabatan">Jabatan: </label>
                        <input type="text" name="jabatan" id="jabatan" value="<?= $currentData["jabatan"]; ?>" required>
                    </li>
					<li>
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
				history.pushState({ id: id }, '', `petugas.php?id=${id}`);
			});
		})

		window.addEventListener('load', () => {
		const urlParams = new URLSearchParams(window.location.search);
		const id = urlParams.get('id');
		
		if (id) {
			editData_overlay.classList.add('active'); 
			
			fetch(`petugas.php?id=${id}`)
				.then(response => response.json())
				.then(data => {
					document.getElementById('nama_petugas').value = data.nama_petugas;
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



    </script>
</body>
</html>
