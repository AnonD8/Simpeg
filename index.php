<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sistem Informasi Kepegawaian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .sidebar a:not(:last-child):hover {
            background-color: #0066cc;
        }

        .sidebar a:last-child:hover {
            background-color: #ff0000;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        tr {
            line-height: 1;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .btn {
            display: inline-block;
            padding: 6px 10px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
        }

        .btn-detail {
            background-color: #0066cc;
        }

        .btn-detail:hover {
            background-color: #005299;
        }

        .btn-ubah {
            background-color: #4CAF50;
        }

        .btn-ubah:hover {
            background-color: #45a049;
        }

        .btn-hapus {
            background-color: #ff0000;
        }

        .btn-hapus:hover {
            background-color: #cc0000;
        }

        .btn-tambah {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
        }

        .btn-tambah:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="index.php">Daftar Pegawai</a>
        <a href="daftar_jabatan.php">Daftar Jabatan</a>
        <a href="daftar_departemen.php">Daftar Departemen</a>
        <a href="logout.php" onclick="return confirm('Ingin Logout?')">Logout</a>
    </div>

    <div class="content">
        <h2>Daftar Pegawai</h2>
        <?php if (isset($_GET['pesan'])): ?>
        <p
            style="color: <?php echo strpos($_GET['pesan'], 'berhasil') !== false ? '#4CAF50' : '#ff0000'; ?>; font-weight: bold; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_GET['pesan']); ?>
        </p>
        <?php endif; ?>

        <a href="tambah_pegawai.php" class="btn-tambah">+ Tambah Pegawai</a>

        <?php
        include 'koneksi.php';

        $query = "SELECT p.id_pegawai, p.nama_pegawai, p.tanggal_lahir, p.jenis_kelamin, 
                         p.alamat, p.foto_path, j.nama_jabatan, d.nama_departemen 
                  FROM pegawai p 
                  JOIN jabatan j ON p.id_jabatan = j.id_jabatan 
                  JOIN departemen d ON p.id_departemen = d.id_departemen
                  ORDER BY p.nama_pegawai";
        $result = mysqli_query($koneksi, $query);
        
        if (mysqli_num_rows($result) > 0) {
        ?>
        <table>
            <tr>
                <th>Foto</th>
                <th>Nama Pegawai</th>
                <th>Jabatan</th>
                <th>Departemen</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <?php if ($row['foto_path']): ?>
                    <img src="<?php echo $row['foto_path']; ?>" alt="Foto <?php echo $row['nama_pegawai']; ?>"
                        style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                    <span style="color: #888;">-</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $row['nama_pegawai']; ?></td>
                <td><?php echo $row['nama_jabatan']; ?></td>
                <td><?php echo $row['nama_departemen']; ?></td>
                <td class="action-buttons">
                    <a href="detail_pegawai.php?id=<?php echo $row['id_pegawai']; ?>" class="btn btn-detail">Detail</a>
                    <a href="ubah_pegawai.php?id=<?php echo $row['id_pegawai']; ?>" class="btn btn-ubah">Ubah</a>
                    <a href="hapus_pegawai.php?id=<?php echo $row['id_pegawai']; ?>"
                        onclick="return confirm('Yakin ingin menghapus pegawai ini?')" class="btn btn-hapus">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php } else { ?>
        <p>Belum ada data pegawai.</p>
        <?php } ?>
    </div>
</body>

</html>