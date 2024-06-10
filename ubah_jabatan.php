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

// Ambil data jabatan
$query_jabatan = "SELECT * FROM jabatan WHERE id_jabatan = $id_jabatan";
$result_jabatan = mysqli_query($koneksi, $query_jabatan);

if (mysqli_num_rows($result_jabatan) != 1) {
  header("Location: daftar_jabatan.php");
  exit();
}

$jabatan = mysqli_fetch_assoc($result_jabatan);

if (isset($_POST['ubah'])) {
    $nama_jabatan = $_POST['nama_jabatan'];
    $gaji_pokok = $_POST['gaji_pokok'];

    $query = "UPDATE jabatan SET 
              nama_jabatan = '$nama_jabatan',
              gaji_pokok = $gaji_pokok
              WHERE id_jabatan = $id_jabatan";

    if (mysqli_query($koneksi, $query)) {
        header("Location: daftar_jabatan.php?pesan=" . urlencode("Jabatan berhasil diubah."));
    } else {
        $error = "Gagal mengubah data jabatan. " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Ubah Jabatan - Sistem Informasi Kepegawaian</title>
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

        input[type="submit"],
        .back-button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            display: block;
            margin-top: 20px;
        }

        input[type="submit"]:hover,
        .back-button:hover {
            background-color: #45a049;
        }

        .back-button {
            background-color: #0066cc;
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
        <h2>Ubah Jabatan</h2>

        <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_jabatan">Nama Jabatan</label>
                <input type="text" id="nama_jabatan" name="nama_jabatan" value="<?php echo $jabatan['nama_jabatan']; ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="gaji_pokok">Gaji Pokok</label>
                <input type="number" id="gaji_pokok" name="gaji_pokok" value="<?php echo $jabatan['gaji_pokok']; ?>"
                    required min="0" step="1000">
            </div>

            <input type="submit" name="ubah" value="Simpan Perubahan">
        </form>

        <button onclick="location.href='daftar_jabatan.php'" class="back-button">← Kembali ke Daftar Jabatan</button>
    </div>
</body>

</html>