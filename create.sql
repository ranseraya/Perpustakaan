CREATE DATABASE Perpustakaan;

CREATE TABLE Anggota (
    id_anggota 		INT AUTO_INCREMENT PRIMARY KEY,
    nama_anggota	VARCHAR(100) NOT NULL,
    alamat 		TEXT NOT NULL,
    no_telepon 		VARCHAR(15) NOT NULL,
    jenis_kelamin 	ENUM('Laki-laki', 'Perempuan') NOT NULL,
    tanggal_registrasi 	DATE NOT NULL,
    STATUS 		ENUM('Aktif', 'Tidak Aktif') NOT NULL,
    foto		VARCHAR(50)
);

CREATE TABLE Petugas (
    id_petugas 		INT AUTO_INCREMENT PRIMARY KEY,
    nama_petugas	VARCHAR(100) NOT NULL,
    jabatan 		VARCHAR(50) NOT NULL
);

CREATE TABLE Buku (
    id_buku 		INT AUTO_INCREMENT PRIMARY KEY,
    judul		VARCHAR(200) NOT NULL,
    pengarang 		VARCHAR(100) NOT NULL,
    penerbit 		VARCHAR(100) NOT NULL,
    tahun_terbit 	YEAR NOT NULL,
    kategori 		VARCHAR(50) NOT NULL,
    jumlah 		INT NOT NULL,
    ISBN 		VARCHAR(20) UNIQUE NOT NULL,
    stok		INT(11) DEFAULT 1
);

CREATE TABLE Peminjaman (
    id_peminjaman 	INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota 		INT NOT NULL,
    id_buku 		INT NOT NULL,
    id_petugas 		INT NOT NULL,
    tanggal_peminjaman 	DATE NOT NULL,
    tanggal_pengembalian DATE,
    status_pengembalian ENUM('Belum Dikembalikan', 'Sudah Dikembalikan') NOT NULL,
    FOREIGN KEY (id_anggota) REFERENCES Anggota(id_anggota) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES Buku(id_buku) ON DELETE CASCADE,
    FOREIGN KEY (id_petugas) REFERENCES Petugas(id_petugas) ON DELETE CASCADE
);

CREATE TABLE Users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'petugas', 'anggota') NOT NULL,
    id_anggota INT NULL,
    id_petugas INT NULL,
    FOREIGN KEY (id_anggota) REFERENCES Anggota(id_anggota) ON DELETE CASCADE,
    FOREIGN KEY (id_petugas) REFERENCES Petugas(id_petugas) ON DELETE CASCADE
);