# Simpeg

Sebelum menjalankan aplikasi ini, pastikan telah melakukan langkah-langkah berikut:


1. **Membuat Database**

   - Buat database baru di server MySQL.
   - Jalankan skrip SQL berikut untuk membuat tabel-tabel yang dibutuhkan:

```sql
-- Tabel Departemen
CREATE TABLE departemen (
    id_departemen INT AUTO_INCREMENT PRIMARY KEY,
    nama_departemen VARCHAR(100) NOT NULL
);

-- Tabel Jabatan
CREATE TABLE jabatan (
    id_jabatan INT AUTO_INCREMENT PRIMARY KEY,
    nama_jabatan VARCHAR(100) NOT NULL,
    gaji_pokok DECIMAL(10, 2) NOT NULL
);

-- Tabel Pegawai
CREATE TABLE pegawai (
    id_pegawai INT AUTO_INCREMENT PRIMARY KEY,
    nama_pegawai VARCHAR(100) NOT NULL,
    tanggal_lahir DATE,
    jenis_kelamin CHAR(1) CHECK (jenis_kelamin IN ('L', 'P')),
    alamat VARCHAR(200),
    id_departemen INT,
    id_jabatan INT,
    foto_path VARCHAR(255),
    FOREIGN KEY (id_departemen) REFERENCES departemen(id_departemen),
    FOREIGN KEY (id_jabatan) REFERENCES jabatan(id_jabatan)
);
```

2. **Menyesuaikan Folder Gambar**
   - Buat folder `images` di dalam folder `htdocs`, tepatnya pada server web (misal, `C:\xampp\htdocs\nama_web\images` untuk XAMPP).
   - Di dalam folder `images`, buat subfolder `pegawai` untuk menyimpan foto pegawai.
   - Sesuaikan nilai `$foto_path` dalam kode Anda dengan lokasi folder `pegawai` yang baru dibuat.

```php
$foto_path = '/nama_web/images/pegawai/' . $foto_name;
```

Namun jika ingin menggunakan path pada kode, buat folder dan subfolder pada `htdocs` seperti ini `/PWL/proyek/images/pegawai/`
