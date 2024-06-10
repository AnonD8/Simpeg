<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: index.php");
  exit();
}

$id_pegawai = $_GET['id'];

// Ambil data pegawai untuk mendapatkan path foto
$query_pegawai = "SELECT foto_path FROM pegawai WHERE id_pegawai = $id_pegawai";
$result_pegawai = mysqli_query($koneksi, $query_pegawai);

if (mysqli_num_rows($result_pegawai) == 1) {
  $pegawai = mysqli_fetch_assoc($result_pegawai);

  // Hapus file foto jika ada
  if ($pegawai['foto_path'] && file_exists($_SERVER['DOCUMENT_ROOT'] . $pegawai['foto_path'])) {
    unlink($_SERVER['DOCUMENT_ROOT'] . $pegawai['foto_path']);
  }

  // Hapus data pegawai dari database
  $query_hapus = "DELETE FROM pegawai WHERE id_pegawai = $id_pegawai";
  if (mysqli_query($koneksi, $query_hapus)) {
    $pesan = "Pegawai berhasil dihapus.";
  } else {
    $pesan = "Gagal menghapus pegawai.";
  }
} else {
  $pesan = "Pegawai tidak ditemukan.";
}

// Redirect ke halaman index dengan pesan
header("Location: index.php?pesan=" . urlencode($pesan));
exit();
?>