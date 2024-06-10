<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $nama_departemen = $_POST['nama_departemen'];

    // Cek apakah departemen sudah ada
    $query_cek = "SELECT COUNT(*) as count FROM departemen WHERE nama_departemen = '$nama_departemen'";
    $result_cek = mysqli_query($koneksi, $query_cek);
    $row_cek = mysqli_fetch_assoc($result_cek);

    if ($row_cek['count'] > 0) {
        $error = "Departemen dengan nama tersebut sudah ada.";
    } else {
        $query = "INSERT INTO departemen (nama_departemen) VALUES ('$nama_departemen')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: daftar_departemen.php?pesan=" . urlencode("Departemen baru berhasil ditambahkan."));
            exit();
        } else {
            $error = "Gagal menambahkan departemen baru. " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Departemen - Sistem Informasi Kepegawaian</title>
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

        input[type="text"] {
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
        <h2>Tambah Departemen</h2>

        <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_departemen">Nama Departemen</label>
                <input type="text" id="nama_departemen" name="nama_departemen" required>
            </div>

            <input type="submit" name="tambah" value="Tambah Departemen">
        </form>

        <button onclick="location.href='daftar_departemen.php'" class="back-button">← Kembali ke Daftar
            Departemen</button>
    </div>
</body>

</html>