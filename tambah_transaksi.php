<?php 
include 'koneksi.php'; 

// ====================================================
// 1. LOGIKA UNTUK MENYIMPAN TRANSAKSI PEMINJAMAN BARU
// ====================================================
if (isset($_POST['submit_transaksi'])) {
    $user_id = $_POST['user_id'];
    $item_id = $_POST['item_id'];
    $tanggal = $_POST['tanggal_pinjam'];
    $status = 'Dipinjam'; 
    
    // Simpan ke tabel transactions
    $query = "INSERT INTO transactions (user_id, item_id, tanggal_pinjam, status) 
              VALUES ('$user_id', '$item_id', '$tanggal', '$status')";
              
    if(mysqli_query($conn, $query)){
        // Kurangi stok barang di tabel items secara otomatis
        mysqli_query($conn, "UPDATE items SET stok = stok - 1 WHERE id='$item_id'");
        
        // Pindah ke halaman riwayat peminjaman
        header("Location: transaksi.php");
        exit;
    }
}

// ====================================================
// 2. LOGIKA UNTUK MENYIMPAN USER BARU DARI POP-UP
// ====================================================
if (isset($_POST['submit_user'])) {
    $nama = $_POST['nama_baru'];
    $role = $_POST['role_baru'];
    
    // Simpan orang baru ke tabel users
    $query_user = "INSERT INTO users (nama, role) VALUES ('$nama', '$role')";
    if(mysqli_query($conn, $query_user)){
        // Refresh halaman ini agar nama barunya langsung muncul di pilihan
        header("Location: tambah_transaksi.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pinjam Barang - Lab IR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm w-50 mx-auto border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-bold p-3">
            <i class="bi bi-cart-plus"></i> Form Peminjaman Aset
        </div>
        <div class="card-body p-4">
            
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Peminjam</label>
                    <div class="input-group">
                        <select name="user_id" class="form-select bg-light" required>
                            <option value="">-- Pilih Peminjam (Dosen/Asisten/Mhs) --</option>
                            <?php
                            // Ambil data orang dari database (urut sesuai abjad)
                            $q_user = mysqli_query($conn, "SELECT * FROM users ORDER BY nama ASC");
                            while($u = mysqli_fetch_assoc($q_user)){
                                echo "<option value='".$u['id']."'>".$u['nama']." (".$u['role'].")</option>";
                            }
                            ?>
                        </select>
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahUser" title="Daftarkan Peminjam Baru">
                            <i class="bi bi-person-plus-fill"></i> Baru
                        </button>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Aset yang Dipinjam</label>
                    <select name="item_id" class="form-select bg-light" required>
                        <option value="">-- Daftar Barang Tersedia --</option>
                        <?php
                        // Ambil data barang (hanya yang stoknya masih ada)
                        $q_item = mysqli_query($conn, "SELECT * FROM items WHERE stok > 0");
                        while($i = mysqli_fetch_assoc($q_item)){
                            echo "<option value='".$i['id']."'>".$i['nama_barang']." (Sisa: ".$i['stok'].")</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Tanggal Peminjaman</label>
                    <input type="date" name="tanggal_pinjam" class="form-control bg-light" required>
                </div>

                <button type="submit" name="submit_transaksi" class="btn btn-primary px-4">
                    <i class="bi bi-check2-square"></i> Proses Peminjaman
                </button>
                <a href="transaksi.php" class="btn btn-outline-secondary">Batal</a>
            </form>
            
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold" id="modalLabel"><i class="bi bi-person-plus"></i> Daftarkan Peminjam Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="" method="POST">
          <div class="modal-body p-4">
              <div class="mb-3">
                  <label class="form-label fw-semibold">Nama Lengkap</label>
                  <input type="text" name="nama_baru" class="form-control bg-light" placeholder="Contoh: Bapak Budi Santoso" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Status / Jabatan</label>
                  <select name="role_baru" class="form-select bg-light" required>
                      <option value="">-- Pilih Jabatan --</option>
                      <option value="Dosen">Dosen</option>
                      <option value="Asisten">Asisten</option>
                      <option value="Mahasiswa">Mahasiswa</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer bg-light border-0">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="submit_user" class="btn btn-success"><i class="bi bi-save"></i> Simpan Peminjam</button>
          </div>
      </form>
      
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>