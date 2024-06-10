<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

include 'koneksi.php';

// Ambil daftar jabatan dan departemen untuk dropdown
$query_jabatan = "SELECT id_jabatan, nama_jabatan FROM jabatan";
$result_jabatan = mysqli_query($koneksi, $query_jabatan);

$query_departemen = "SELECT id_departemen, nama_departemen FROM departemen";
$result_departemen = mysqli_query($koneksi, $query_departemen);

if (isset($_POST['tambah'])) {
  $nama = $_POST['nama'];
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];
  $id_jabatan = $_POST['id_jabatan'];
  $id_departemen = $_POST['id_departemen'];

  // Handle file upload
  $foto_path = null;
  if ($_FILES['foto']['error'] == 0) {
    $foto_name = uniqid() . '_' . $_FILES['foto']['name'];
    $foto_path = '/PWL/proyek/images/pegawai/' . $foto_name;
    move_uploaded_file($_FILES['foto']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $foto_path);
  }

  $query = "INSERT INTO pegawai (nama_pegawai, tanggal_lahir, jenis_kelamin, alamat, id_jabatan, id_departemen, foto_path) 
            VALUES ('$nama', '$tanggal_lahir', '$jenis_kelamin', '$alamat', $id_jabatan, $id_departemen, '$foto_path')";
  mysqli_query($koneksi, $query);

  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Pegawai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea,
        input[type="file"],
        input[type="submit"],
        .back-button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
            font-size: 16px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            background: url('data:image/svg+xml;utf8,<svg fill="%23333" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat;
            background-position: right 10px top 50%;
            background-size: 20px;
            padding-right: 30px;
        }

        input[type="file"] {
            background-color: #f9f9f9;
            cursor: pointer;
        }

        input[type="submit"],
        .back-button {
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-button {
            background-color: #0066cc;
            color: #fff;
            text-decoration: none;
            text-align: center;
            display: block;
        }

        .back-button:hover {
            background-color: #005299;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Tambah Pegawai</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama">Nama Pegawai</label>
                <input type="text" id="nama" name="nama" required>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>

            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat"></textarea>
            </div>

            <div class="form-group">
                <label for="id_jabatan">Jabatan</label>
                <select id="id_jabatan" name="id_jabatan" required>
                    <option value="">Pilih Jabatan</option>
                    <?php while ($row = mysqli_fetch_assoc($result_jabatan)): ?>
                    <option value="<?php echo $row['id_jabatan']; ?>"><?php echo $row['nama_jabatan']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_departemen">Departemen</label>
                <select id="id_departemen" name="id_departemen" required>
                    <option value="">Pilih Departemen</option>
                    <?php while ($row = mysqli_fetch_assoc($result_departemen)): ?>
                    <option value="<?php echo $row['id_departemen']; ?>"><?php echo $row['nama_departemen']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="foto">Foto Pegawai</label>
                <input type="file" id="foto" name="foto" accept=".jpg, .jpeg, .png">
            </div>

            <input type="submit" name="tambah" value="Tambah Pegawai">
        </form>

        <button onclick="location.href='index.php'" class="back-button">← Kembali ke Daftar Pegawai</button>
    </div>
</body>

</html>