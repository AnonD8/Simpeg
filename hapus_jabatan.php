<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: daftar_jabatan.php");
  exit();
}

$id_jabatan = $_GET['id'];

// Cek apakah jabatan masih digunakan oleh pegawai
$query_check = "SELECT COUNT(*) as pegawai_count FROM pegawai WHERE id_jabatan = $id_jabatan";
$result_check = mysqli_query($koneksi, $query_check);
$row_check = mysqli_fetch_assoc($result_check);

if ($row_check['pegawai_count'] > 0) {
  $pesan = "Tidak dapat menghapus jabatan karena masih digunakan oleh " . $row_check['pegawai_count'] . " pegawai.";
  header("Location: daftar_jabatan.php?pesan=" . urlencode($pesan));
  exit();
}

// Jika tidak ada pegawai yang menggunakan jabatan ini, proses penghapusan
$query_jabatan = "SELECT nama_jabatan FROM jabatan WHERE id_jabatan = $id_jabatan";
$result_jabatan = mysqli_query($koneksi, $query_jabatan);

if (mysqli_num_rows($result_jabatan) != 1) {
  header("Location: daftar_jabatan.php");
  exit();
}

$jabatan = mysqli_fetch_assoc($result_jabatan);

// Jika konfirmasi diterima, hapus jabatan
if (isset($_POST['confirm']) && $_POST['confirm'] == 'ya') {
    $query_hapus = "DELETE FROM jabatan WHERE id_jabatan = $id_jabatan";
    if (mysqli_query($koneksi, $query_hapus)) {
        $pesan = "Jabatan '{$jabatan['nama_jabatan']}' berhasil dihapus.";
    } else {
        $pesan = "Gagal menghapus jabatan. " . mysqli_error($koneksi);
    }
    header("Location: daftar_jabatan.php?pesan=" . urlencode($pesan));
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Hapus Jabatan - Sistem Informasi Kepegawaian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 30px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-yes {
            background-color: #ff0000;
            color: #fff;
            margin-right: 10px;
        }

        .btn-yes:hover {
            background-color: #cc0000;
        }

        .btn-no {
            background-color: #0066cc;
            color: #fff;
        }

        .btn-no:hover {
            background-color: #005299;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Konfirmasi Penghapusan Jabatan</h2>
        <p>Apakah Anda yakin ingin menghapus jabatan <strong><?php echo $jabatan['nama_jabatan']; ?></strong>?</p>

        <div class="buttons">
            <form method="POST" action="">
                <input type="hidden" name="confirm" value="ya">
                <button type="submit" class="btn btn-yes">Ya, Hapus</button>
            </form>
            <button onclick="location.href='daftar_jabatan.php'" class="btn btn-no">Tidak, Kembali</button>
        </div>
    </div>
</body>

</html>