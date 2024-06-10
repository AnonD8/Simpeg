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

// Ambil data jabatan dan departemen untuk dropdown
$query_jabatan = "SELECT id_jabatan, nama_jabatan FROM jabatan";
$result_jabatan = mysqli_query($koneksi, $query_jabatan);

$query_departemen = "SELECT id_departemen, nama_departemen FROM departemen";
$result_departemen = mysqli_query($koneksi, $query_departemen);

// Ambil data pegawai
$query_pegawai = "SELECT * FROM pegawai WHERE id_pegawai = $id_pegawai";
$result_pegawai = mysqli_query($koneksi, $query_pegawai);

if (mysqli_num_rows($result_pegawai) != 1) {
  header("Location: index.php");
  exit();
}

$pegawai = mysqli_fetch_assoc($result_pegawai);

if (isset($_POST['ubah'])) {
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $id_jabatan = $_POST['id_jabatan'];
    $id_departemen = $_POST['id_departemen'];

    $foto_path = $pegawai['foto_path']; // Gunakan foto yang sudah ada jika tidak ada yang baru
    if ($_FILES['foto']['error'] == 0) {
        // Hapus foto lama jika ada
        if ($pegawai['foto_path'] && file_exists($_SERVER['DOCUMENT_ROOT'] . $pegawai['foto_path'])) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $pegawai['foto_path']);
        }

        $foto_name = uniqid() . '_' . $_FILES['foto']['name'];
        $foto_path = '/PWL/proyek/images/pegawai/' . $foto_name;
        move_uploaded_file($_FILES['foto']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $foto_path);
    }

    $query = "UPDATE pegawai SET 
              nama_pegawai = '$nama',
              tanggal_lahir = '$tanggal_lahir',
              jenis_kelamin = '$jenis_kelamin',
              alamat = '$alamat',
              id_jabatan = $id_jabatan,
              id_departemen = $id_departemen,
              foto_path = '$foto_path'
              WHERE id_pegawai = $id_pegawai";

    if (mysqli_query($koneksi, $query)) {
        header("Location: detail_pegawai.php?id=$id_pegawai");
    } else {
        $error = "Gagal mengubah data pegawai";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Ubah Data Pegawai</title>
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
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        input[type="file"] {
            background-color: #f9f9f9;
            cursor: pointer;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
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

        .current-photo {
            text-align: center;
            margin-bottom: 20px;
        }

        .current-photo img {
            max-width: 150px;
            height: auto;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Ubah Data Pegawai</h2>
        <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="current-photo">
                <?php if ($pegawai['foto_path']): ?>
                <img src="<?php echo $pegawai['foto_path']; ?>" alt="Foto <?php echo $pegawai['nama_pegawai']; ?>">
                <?php else: ?>
                <p style="color: #888;">Tidak ada foto</p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="nama">Nama Pegawai</label>
                <input type="text" id="nama" name="nama" value="<?php echo $pegawai['nama_pegawai']; ?>" required>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                    value="<?php echo $pegawai['tanggal_lahir']; ?>" required>
            </div>

            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="L" <?php echo $pegawai['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki
                    </option>
                    <option value="P" <?php echo $pegawai['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat"><?php echo $pegawai['alamat']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="id_jabatan">Jabatan</label>
                <select id="id_jabatan" name="id_jabatan" required>
                    <?php while ($row = mysqli_fetch_assoc($result_jabatan)): ?>
                    <option value="<?php echo $row['id_jabatan']; ?>"
                        <?php echo $row['id_jabatan'] == $pegawai['id_jabatan'] ? 'selected' : ''; ?>>
                        <?php echo $row['nama_jabatan']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_departemen">Departemen</label>
                <select id="id_departemen" name="id_departemen" required>
                    <?php while ($row = mysqli_fetch_assoc($result_departemen)): ?>
                    <option value="<?php echo $row['id_departemen']; ?>"
                        <?php echo $row['id_departemen'] == $pegawai['id_departemen'] ? 'selected' : ''; ?>>
                        <?php echo $row['nama_departemen']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="foto">Ganti Foto</label>
                <input type="file" id="foto" name="foto" accept=".jpg, .jpeg, .png">
            </div>

            <input type="submit" name="ubah" value="Simpan Perubahan">
        </form>

        <button onclick="location.href='detail_pegawai.php?id=<?php echo $id_pegawai; ?>'" class="back-button">← Kembali
            ke Detail Pegawai</button>
    </div>
</body>

</html>