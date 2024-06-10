<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $nama_jabatan = $_POST['nama_jabatan'];
    $gaji_pokok = $_POST['gaji_pokok'];

    // Cek apakah jabatan sudah ada
    $query_cek = "SELECT COUNT(*) as count FROM jabatan WHERE nama_jabatan = '$nama_jabatan'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_assoc($result_cek);

    if ($row_cek['count'] > 0) {
        $error = "Jabatan dengan nama tersebut sudah ada.";
    } else {
        $query = "INSERT INTO jabatan (nama_jabatan, gaji_pokok) VALUES ('$nama_jabatan', $gaji_pokok)";
        if (mysqli_query($koneksi, $query)) {
            header("Location: daftar_jabatan.php?pesan=" . urlencode("Jabatan baru berhasil ditambahkan."));
            exit();
        } else {
            $error = "Gagal menambahkan jabatan baru. " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Jabatan - Sistem Informasi Kepegawaian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
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
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
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

        .error-message {
            color: #ff0000;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Tambah Jabatan</h2>

        <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_jabatan">Nama Jabatan</label>
                <input type="text" id="nama_jabatan" name="nama_jabatan" required>
            </div>

            <div class="form-group">
                <label for="gaji_pokok">Gaji Pokok</label>
                <input type="number" id="gaji_pokok" name="gaji_pokok" required min="0" step="1000">
            </div>

            <input type="submit" name="tambah" value="Tambah Jabatan">
        </form>

        <button onclick="location.href='daftar_jabatan.php'" class="back-button">← Kembali ke Daftar Jabatan</button>
    </div>
</body>

</html>