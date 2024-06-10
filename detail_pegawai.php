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

$query = "SELECT p.id_pegawai, p.nama_pegawai, p.tanggal_lahir, p.jenis_kelamin, 
                 p.alamat, p.foto_path, j.nama_jabatan, j.gaji_pokok,
                 d.nama_departemen 
          FROM pegawai p 
          JOIN jabatan j ON p.id_jabatan = j.id_jabatan 
          JOIN departemen d ON p.id_departemen = d.id_departemen
          WHERE p.id_pegawai = $id_pegawai";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) != 1) {
  header("Location: index.php");
  exit();
}

$pegawai = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Detail Pegawai - <?php echo $pegawai['nama_pegawai']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #1a1a1a;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: bold;
            color: #4a4a4a;
            display: block;
            margin-bottom: 5px;
        }

        .foto-pegawai {
            max-width: 200px;
            height: auto;
            border-radius: 50%;
            display: block;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .back-button {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #0066cc;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
        }

        .back-button:hover {
            background-color: #005299;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Detail Pegawai</h1>

        <?php if ($pegawai['foto_path']): ?>
        <img src="<?php echo $pegawai['foto_path']; ?>" alt="Foto <?php echo $pegawai['nama_pegawai']; ?>"
            class="foto-pegawai">
        <?php else: ?>
        <p style="text-align: center; color: #888;">Tidak ada foto</p>
        <?php endif; ?>

        <div class="detail-item">
            <span class="detail-label">Nama:</span>
            <?php echo $pegawai['nama_pegawai']; ?>
        </div>

        <div class="detail-item">
            <span class="detail-label">Tanggal Lahir:</span>
            <?php echo date("d F Y", strtotime($pegawai['tanggal_lahir'])); ?>
        </div>

        <div class="detail-item">
            <span class="detail-label">Jenis Kelamin:</span>
            <?php echo $pegawai['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?>
        </div>

        <div class="detail-item">
            <span class="detail-label">Alamat:</span>
            <?php echo $pegawai['alamat'] ?: 'Tidak ada data'; ?>
        </div>

        <div class="detail-item">
            <span class="detail-label">Departemen:</span>
            <?php echo $pegawai['nama_departemen']; ?>
        </div>

        <div class="detail-item">
            <span class="detail-label">Jabatan:</span>
            <?php echo $pegawai['nama_jabatan']; ?>
        </div>

        <div class="detail-item">
            <span class="detail-label">Gaji Pokok:</span>
            Rp <?php echo number_format($pegawai['gaji_pokok'], 0, ',', '.'); ?>
        </div>

        <button onclick="location.href='index.php'" class="back-button">← Kembali ke Daftar Pegawai</button>
    </div>
</body>

</html>