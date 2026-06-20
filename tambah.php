<?php 
include 'koneksi.php'; 

// Mode edit jika ada parameter id di URL
$edit_mode = false;
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nama_barang = '';
$kategori = '';
$lokasi = '';
$stok = '';
$foto_lama = '';
$page_title = 'Digitalisasi Aset Baru';
$submit_label = 'Proses Digitalisasi Aset';

if ($item_id > 0) {
    $query_item = mysqli_query($conn, "SELECT * FROM items WHERE id = $item_id");
    if ($query_item && mysqli_num_rows($query_item) > 0) {
        $edit_mode = true;
        $item = mysqli_fetch_assoc($query_item);
        $nama_barang = $item['nama_barang'];
        $kategori = $item['category_id'];
        $lokasi = $item['location_id'];
        $stok = $item['stok'];
        $foto_lama = $item['foto'];
        $page_title = 'Edit Aset';
        $submit_label = 'Update Aset';
    } else {
        header('Location: index.php?pesan=data_tidak_ditemukan');
        exit;
    }
}

// Proses ketika tombol simpan ditekan
if (isset($_POST['submit'])) {
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $edit_mode = $item_id > 0;
    $nama_barang = $_POST['nama_barang'];
    $kategori = $_POST['category_id'];
    $lokasi = $_POST['location_id'];
    $stok = $_POST['stok'];
    $foto_lama = isset($_POST['foto_lama']) ? $_POST['foto_lama'] : '';
    
    // Logika Upload Gambar
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $path = "uploads/" . $foto;
    
    // Cek apakah file diupload
    if ($edit_mode && empty($foto)) {
        $query = "UPDATE items SET nama_barang = '$nama_barang', category_id = '$kategori', location_id = '$lokasi', stok = '$stok' WHERE id = $item_id";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php?pesan=sukses");
        } else {
            header("Location: index.php?pesan=gagal_database");
        }
    } else {
        // Cek apakah file adalah gambar
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $x = explode('.', $foto);
        $ekstensi = strtolower(end($x));
        
        if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
            if (move_uploaded_file($tmp, $path)) {
                if ($edit_mode) {
                    $query = "UPDATE items SET nama_barang = '$nama_barang', category_id = '$kategori', location_id = '$lokasi', stok = '$stok', foto = '$foto' WHERE id = $item_id";
                } else {
                    $query = "INSERT INTO items (nama_barang, category_id, location_id, stok, foto) 
                              VALUES ('$nama_barang', '$kategori', '$lokasi', '$stok', '$foto')";
                }
                if(mysqli_query($conn, $query)){
                    header("Location: index.php?pesan=sukses");
                } else {
                    header("Location: index.php?pesan=gagal_database");
                }
            } else {
                header("Location: index.php?pesan=gagal_upload");
            }
        } else {
            header("Location: index.php?pesan=gagal_ekstensi");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penerimaan Barang - Lab IR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="d-flex">
    <nav id="sidebar" class="d-flex flex-column" style="height: 100vh;">
        <div class="sidebar-brand p-3">
            <i class="bi bi-box-seam text-primary"></i> Lab IR System
        </div>

        <ul class="nav flex-column mt-3 flex-grow-1">
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="master_data.php"><i class="bi bi-tags"></i> Master Data</a></li>
            <li class="nav-item"><a class="nav-link" href="tambah.php"><i class="bi bi-cloud-arrow-up"></i> Penerimaan Barang</a></li>
            <li class="nav-item"><a class="nav-link" href="transaksi.php"><i class="bi bi-arrow-left-right"></i> Peminjaman</a></li>
            <li class="nav-item"><a class="nav-link" href="pemeliharaan.php"><i class="bi bi-tools"></i> Pemeliharaan</a></li>
            <li class="nav-item"><a class="nav-link" href="laporan.php"><i class="bi bi-printer"></i> Laporan</a></li>
        </ul>
    </nav>

    <main id="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Penerimaan Barang</h3>
            <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        
                        <div class="col-md-5 mb-4">
                            <h6 class="fw-bold mb-3 text-secondary">Visual Aset Fisik</h6>
                            
                            <label for="file-upload" class="upload-area w-100 d-block position-relative overflow-hidden">
                                
                                <div id="upload-placeholder"<?php if ($edit_mode && !empty($foto_lama)) echo ' style="display:none;"'; ?>>
                                    <i class="bi bi-cloud-arrow-up text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 fw-bold">Upload Foto Barang</h5>
                                    <p class="text-muted small">Klik area ini untuk memilih file dari komputer Anda.<br>Mendukung: JPG, JPEG, PNG.</p>
                                    <span class="btn btn-primary btn-sm mt-2">Pilih File</span>
                                </div>

                                <img id="image-preview" src="<?php echo $edit_mode && !empty($foto_lama) ? 'uploads/' . $foto_lama : ''; ?>" alt="Preview Foto" class="img-fluid rounded" style="<?php echo $edit_mode && !empty($foto_lama) ? 'display: block;' : 'display: none;'; ?> max-height: 250px; width: 100%; object-fit: cover;">
                                
                                <input id="file-upload" type="file" name="foto" class="d-none" accept="image/png, image/jpeg, image/jpg" <?php if(!$edit_mode) echo 'required'; ?> onchange="previewFoto(event)">
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                    <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($foto_lama); ?>">
                                <?php endif; ?>
                            </label>
                            
                            <div class="alert alert-info mt-3 small">
                                <i class="bi bi-info-circle"></i> <strong>Tips:</strong> Pastikan foto terang dan fokus agar mudah diidentifikasi saat inventarisasi tahunan.
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h6 class="fw-bold mb-3 text-secondary">Identifikasi Spesifikasi</h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Barang / Model</label>
                                <input type="text" name="nama_barang" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: Raspberry Pi 4 Model B" value="<?php echo htmlspecialchars($nama_barang); ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Kategori Perangkat</label>
                                    <select name="category_id" class="form-select bg-light border-0" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php
                                        // Mengambil data Kategori langsung dari Database
                                        $q_kategori = mysqli_query($conn, "SELECT * FROM categories");
                                        while($k = mysqli_fetch_assoc($q_kategori)){
                                            $selected = $k['id'] == $kategori ? ' selected' : '';
                                            echo "<option value='".$k['id']."'$selected>".$k['nama_kategori']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Lokasi Penyimpanan</label>
                                    <select name="location_id" class="form-select bg-light border-0" required>
                                        <option value="">-- Pilih Lemari/Rak --</option>
                                        <?php
                                        // Mengambil data Lokasi langsung dari Database
                                        $q_lokasi = mysqli_query($conn, "SELECT * FROM locations");
                                        while($l = mysqli_fetch_assoc($q_lokasi)){
                                            $selected = $l['id'] == $lokasi ? ' selected' : '';
                                            echo "<option value='".$l['id']."'$selected>".$l['nama_lokasi']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Kuantitas / Stok Masuk</label>
                                <div class="input-group">
                                    <input type="number" name="stok" class="form-control bg-light border-0" placeholder="0" value="<?php echo htmlspecialchars($stok); ?>" required min="1">
                                    <span class="input-group-text border-0 bg-light text-muted">Unit</span>
                                </div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="reset" class="btn btn-light me-2">Reset Form</button>
                                <button type="submit" name="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i> <?php echo $submit_label; ?></button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function previewFoto(event) {
    var placeholder = document.getElementById('upload-placeholder');
    var preview = document.getElementById('image-preview');
    
    // Cek apakah ada file yang diunggah
    if(event.target.files.length > 0) {
        var src = URL.createObjectURL(event.target.files[0]);
        
        // Sembunyikan teks & ikon upload
        placeholder.style.display = "none";
        
        // Tampilkan gambar preview
        preview.src = src;
        preview.style.display = "block";
    }
}
</script>

</body>
</html>
</body>
</html>