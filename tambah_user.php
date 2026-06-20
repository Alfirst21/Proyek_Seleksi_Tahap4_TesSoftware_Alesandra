<?php 
include 'koneksi.php'; 

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    
    $query = "INSERT INTO users (nama, role) VALUES ('$nama', '$role')";
    if(mysqli_query($conn, $query)){
        // Jika berhasil, arahkan langsung ke halaman tambah transaksi
        header("Location: tambah_transaksi.php"); 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengguna - Lab IR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm w-50 mx-auto border-0 rounded-4">
        <div class="card-header bg-success text-white fw-bold p-3">
            Daftarkan Peminjam Baru
        </div>
        <div class="card-body p-4">
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control bg-light" placeholder="Masukkan nama..." required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Status / Jabatan</label>
                    <select name="role" class="form-select bg-light" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Dosen">Dosen</option>
                        <option value="Asisten">Asisten</option>
                        <option value="Mahasiswa">Mahasiswa</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-success px-4">Simpan Pengguna</button>
                <a href="tambah_transaksi.php" class="btn btn-outline-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>