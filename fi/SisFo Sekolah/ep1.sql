-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 03 Jan 2017 pada 14.22
-- Versi Server: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ep1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `email` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `hak_akses` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`email`, `password`, `nama_lengkap`, `hak_akses`) VALUES
('ichsan.clay@gmail.com', 'gurukomputer', 'Mohammad Ichsan', 'pengajar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri`
--

CREATE TABLE `galeri` (
  `kategori` varchar(20) NOT NULL,
  `nama_album` varchar(30) NOT NULL,
  `lokasi` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `nip` varchar(11) NOT NULL,
  `nama_guru` varchar(30) NOT NULL,
  `alamat_guru` varchar(50) NOT NULL,
  `no_tlp` varchar(13) NOT NULL,
  `email` varchar(45) NOT NULL,
  `tempat_lahir` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `mulai_mengajar` date NOT NULL,
  `status_pengajar` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`nip`, `nama_guru`, `alamat_guru`, `no_tlp`, `email`, `tempat_lahir`, `tanggal_lahir`, `mulai_mengajar`, `status_pengajar`) VALUES
('12145296', 'Mohammad Ichsan', 'Jatiwaringin', '085711444410', 'ichsan.clay@gmail.com', 'Jakarta', '1997-06-17', '2016-12-19', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `nama_kelas` varchar(10) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jum''at') NOT NULL,
  `nama_mapel` varchar(25) NOT NULL,
  `nama_guru` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `jadwal_pelajaran`
--

INSERT INTO `jadwal_pelajaran` (`nama_kelas`, `hari`, `nama_mapel`, `nama_guru`) VALUES
('Kelas 1A', 'Senin', 'Bahasa Indonesia', 'Mohammad Ichsan'),
('Kelas 1A', 'Senin', 'Bahasa Inggris', 'Mohammad Ichsan'),
('Kelas 1A', 'Senin', 'Bahasa Indonesia', 'Mohammad Ichsan'),
('Kelas 1A', 'Senin', 'Bahasa Inggris', 'Mohammad Ichsan'),
('Kelas 1A', 'Senin', 'Matematika', 'Mohammad Ichsan'),
('Kelas 1A', 'Selasa', 'Ilmu Pengetahuan Alam', 'Mohammad Ichsan'),
('Kelas 1A', 'Selasa', 'Ilmu Pengetahuan Sosial', 'Mohammad Ichsan'),
('Kelas 1A', 'Selasa', 'Pendidikan Agama Islam', 'Mohammad Ichsan'),
('Kelas 1A', 'Selasa', 'Pendidikan Kewarganegaraa', 'Mohammad Ichsan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `kode_kelas` varchar(10) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`kode_kelas`, `nama_kelas`) VALUES
('1_a', 'Kelas 1A'),
('1_b', 'Kelas 1B'),
('1_c', 'Kelas 1C'),
('1_d', 'Kelas 1D'),
('2_a', 'Kelas 2A'),
('2_b', 'Kelas 2B'),
('2_c', 'Kelas 2C'),
('2_d', 'Kelas 2D'),
('2_e', 'Kelas 2E');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `komentar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `komentar`
--

INSERT INTO `komentar` (`nama`, `email`, `komentar`) VALUES
('IC', 'ichsan.clay@gmail.com', 'H'),
('MOHAMMAD ICHSAN', 'ichsan.clay@gmail.com', 'HMM'),
('', '', ''),
('', '', ''),
('', '', ''),
('', '', ''),
('', '', ''),
('', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapel`
--

CREATE TABLE `mapel` (
  `kode_mapel` varchar(11) NOT NULL,
  `nama_mapel` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mapel`
--

INSERT INTO `mapel` (`kode_mapel`, `nama_mapel`) VALUES
('BIndo', 'Bahasa Indonesia'),
('BIng', 'Bahasa Inggris'),
('IPA', 'Ilmu Pengetahuan Alam'),
('IPS', 'Ilmu Pengetahuan Sosial'),
('MTK', 'Matematika'),
('PAI', 'Pendidikan Agama Islam'),
('PKN', 'Pendidikan Kewarganegaraa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`kategori`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`nip`),
  ADD KEY `nama_guru` (`nama_guru`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`kode_kelas`),
  ADD KEY `nama_kelas` (`nama_kelas`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`kode_mapel`),
  ADD KEY `nama_mapel` (`nama_mapel`),
  ADD KEY `nama_mapel_2` (`nama_mapel`),
  ADD KEY `nama_mapel_3` (`nama_mapel`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
