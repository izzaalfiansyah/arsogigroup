-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jul 2022 pada 05.10
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arsogi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id`, `nama`, `kategori`, `stok`, `harga_jual`, `harga_beli`, `created_at`, `updated_at`, `foto`) VALUES
(1, 'Indomilk Botol', 'Coffe Bear', 30, 3500, 3000, '2022-07-05 05:48:10', '2022-07-05 05:48:10', 'b8fdcbd73ea353f60c68.png'),
(2, 'Madilog', 'Temulawak', 10, 20000, 18000, '2022-07-05 05:51:22', '2022-07-05 08:23:15', '94b1e24971e32194b6e8.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Delivery', '2022-07-05 05:43:42', '2022-07-05 05:43:42'),
(2, 'Manager', '2022-07-05 05:43:42', '2022-07-05 05:43:42'),
(3, 'Sales', '2022-07-05 05:43:42', '2022-07-05 05:43:42'),
(4, 'Sales Supervisor', '2022-07-05 05:43:42', '2022-07-05 05:43:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Coffe Bear', '2022-07-05 05:43:42', '2022-07-05 05:43:42'),
(2, 'Temulawak', '2022-07-05 05:43:42', '2022-07-05 05:43:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2022_07_02_033740_create_barang_table', 1),
(3, '2022_07_02_033837_create_jabatan_table', 1),
(4, '2022_07_02_033901_create_kategori_table', 1),
(5, '2022_07_02_034056_create_pelanggan_table', 1),
(6, '2022_07_02_034350_create_penjualan_table', 1),
(7, '2022_07_02_034638_create_penjualan_item_table', 1),
(8, '2022_07_02_035204_create_user_table', 1),
(9, '2022_07_02_035442_make_first_relationship', 1),
(10, '2022_07_02_040257_add_foto_to_barang_table', 1),
(11, '2022_07_03_072642_add_provinsi_to_pelanggan_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_toko` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pemilik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_alamat` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelurahan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kabupaten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hp1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hp2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `foto_toko` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diskon_produk` double NOT NULL DEFAULT 0,
  `total_pinjaman_botol` int(11) DEFAULT NULL,
  `total_pinjaman_krat` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `provinsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama_toko`, `nama_pemilik`, `alamat`, `keterangan_alamat`, `kelurahan`, `kecamatan`, `kabupaten`, `hp1`, `hp2`, `sales_id`, `foto_toko`, `latitude`, `longitude`, `diskon_produk`, `total_pinjaman_botol`, `total_pinjaman_krat`, `created_at`, `updated_at`, `provinsi`) VALUES
(1, 'Toko Fian', 'Azizatuz Zahro', 'Dusun Karanganyar RT 2 RW 8', NULL, '{\"id\":\"3509020008\",\"district_id\":\"3509020\",\"name\":\"KARANGREJO\"}', '{\"id\":\"3509020\",\"regency_id\":\"3509\",\"name\":\"GUMUK MAS\"}', '{\"id\":\"3509\",\"province_id\":\"35\",\"name\":\"KABUPATEN JEMBER\"}', '082330538264', NULL, 2, 'eee9af17f4bfcde32d74.jpg', '-8.2969924741894', '113.4146976455', 5, NULL, NULL, '2022-07-05 05:58:40', '2022-07-06 18:56:02', '{\"id\":\"35\",\"name\":\"JAWA TIMUR\"}'),
(2, 'Toko Amanah', 'Imam Taufiq', 'Karanganyar', NULL, '{\"id\":\"3509010001\",\"district_id\":\"3509010\",\"name\":\"PASEBAN\"}', '{\"id\":\"3509010\",\"regency_id\":\"3509\",\"name\":\"KENCONG\"}', '{\"id\":3509,\"province_id\":35,\"name\":\"KABUPATEN JEMBER\"}', '085745795895', NULL, 2, '402b86c94685364a0cb8.jpg', '-7.2574719', '112.7520883', 0, NULL, NULL, '2022-07-06 05:55:39', '2022-07-06 05:55:39', '{\"id\":35,\"name\":\"JAWA TIMUR\"}');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pelanggan_id` bigint(20) UNSIGNED NOT NULL,
  `nama_sales` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_delivery` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_awal` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1: purchase-order, 2: take-order, 3: penjualan',
  `status` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1: purchase-order, 2: take-order, 3: penjualan',
  `status_penjualan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`id`, `pelanggan_id`, `nama_sales`, `nama_delivery`, `status_awal`, `status`, `status_penjualan`, `created_at`, `updated_at`) VALUES
('20220706141934', 1, 'Mohammad Rafly Azzuhri', 'Ricky Ahmad Mahbubi', '2', '3', 'TTP', '2022-07-06 07:19:34', '2022-07-06 20:11:16'),
('20220706154310', 1, 'Mohammad Rafly Azzuhri', 'Ricky Ahmad Mahbubi', '2', '2', 'TTP', '2022-07-06 08:43:10', '2022-07-06 08:43:10'),
('20220707015155', 2, 'Mohammad Rafly Azzuhri', 'Ricky Ahmad Mahbubi', '2', '3', 'COD', '2022-07-06 18:51:55', '2022-07-06 20:10:56'),
('20220707031305', 1, 'Mohammad Rafly Azzuhri', 'Ricky Ahmad Mahbubi', '2', '3', 'TTP', '2022-07-06 20:13:05', '2022-07-06 21:15:56'),
('20220707034644', 1, 'Mohammad Rafly Azzuhri', NULL, '1', '1', 'TTP', '2022-07-06 20:46:44', '2022-07-06 20:46:44'),
('20220707034807', 2, 'Mohammad Rafly Azzuhri', 'Ricky Ahmad Mahbubi', '1', '3', 'TTP', '2022-07-06 20:48:07', '2022-07-06 21:16:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan_item`
--

CREATE TABLE `penjualan_item` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `penjualan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual_awal` int(11) NOT NULL,
  `diskon` double NOT NULL DEFAULT 0,
  `jumlah` int(11) NOT NULL,
  `harga_jual_akhir` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penjualan_item`
--

INSERT INTO `penjualan_item` (`id`, `penjualan_id`, `barang_id`, `harga_beli`, `harga_jual_awal`, `diskon`, `jumlah`, `harga_jual_akhir`, `sub_total`, `created_at`, `updated_at`) VALUES
(5, '20220706141934', 1, 3000, 3500, 5, 4, 3325, 13300, '2022-07-06 07:24:53', '2022-07-06 07:24:53'),
(6, '20220706141934', 2, 18000, 20000, 5, 1, 19000, 19000, '2022-07-06 07:25:02', '2022-07-06 07:25:02'),
(7, '20220706154310', 1, 3000, 3500, 5, 4, 3325, 13300, '2022-07-06 08:43:36', '2022-07-06 08:43:36'),
(8, '20220706154310', 2, 18000, 20000, 5, 1, 19000, 19000, '2022-07-06 08:43:47', '2022-07-06 08:43:47'),
(9, '20220707015155', 1, 3000, 3500, 0, 8, 3500, 28000, '2022-07-06 18:52:22', '2022-07-06 18:52:22'),
(10, '20220707031305', 2, 18000, 20000, 5, 2, 19000, 38000, '2022-07-06 20:14:58', '2022-07-06 20:14:58'),
(11, '20220707034644', 1, 3000, 3500, 5, 1, 3325, 3325, '2022-07-06 20:46:55', '2022-07-06 20:46:55'),
(12, '20220707034807', 2, 18000, 20000, 0, 1, 20000, 20000, '2022-07-06 20:48:15', '2022-07-06 20:48:15'),
(15, '20220707034644', 2, 18000, 20000, 5, 1, 19000, 19000, '2022-07-07 19:30:21', '2022-07-07 19:30:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_domisili` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hp1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hp2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `nama`, `alamat_ktp`, `alamat_domisili`, `hp1`, `hp2`, `jabatan`, `foto_ktp`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', '$2y$10$wi5gIFY/VrhFwsfM.p2EG.7JLnY82NX6KR76kFJsyMrEr0AFY7LxO', 'Muhammad Izza Alfiansyah', 'Dusun Karanganyar RT 2 RW 8', 'Jl. Cendrawasih, Wunguan, Kencong', '081231921351', NULL, 'Manager', '8978ac74a1034da2a06d.png', '2022-07-05 05:43:42', '2022-07-05 06:00:19'),
(2, 'rafelrafel', '$2y$10$dyrCi2tqby3yK5Bf5DXz3.qbmnJlnvRVJTJpqdb2xBSFLUVTWIvsS', 'Mohammad Rafly Azzuhri', NULL, NULL, '082330538264', NULL, 'Sales', 'ad50b359b13d3f650791.png', '2022-07-05 05:57:08', '2022-07-06 06:41:02'),
(3, 'rikiriki', '$2y$10$VAOqFc6yGc/daj7ZrnWuG.7nLERZF49i4rq/j/mG.eNs6fY0V5pIm', 'Ricky Ahmad Mahbubi', NULL, NULL, '082330538264', NULL, 'Delivery', '868f65e5bb1fdeec840e.png', '2022-07-05 08:24:50', '2022-07-06 06:40:18');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjualan_pelanggan_id` (`pelanggan_id`);

--
-- Indeks untuk tabel `penjualan_item`
--
ALTER TABLE `penjualan_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjualan_item_penjualan_id` (`penjualan_id`),
  ADD KEY `penjualan_item_barang_id` (`barang_id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `penjualan_item`
--
ALTER TABLE `penjualan_item`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_pelanggan_id` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE NO ACTION;

--
-- Ketidakleluasaan untuk tabel `penjualan_item`
--
ALTER TABLE `penjualan_item`
  ADD CONSTRAINT `penjualan_item_barang_id` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `penjualan_item_penjualan_id` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
