<?php include 'koneksi.php'; 

// Logika Tambah Kategori
if(isset($_POST['tambah_kategori'])){
    $kategori = $_POST['nama_kategori'];
    mysqli_query($conn, "INSERT INTO categories (nama_kategori) VALUES ('$kategori')");
    header("Location: master_data.php");
}

// Logika Tambah Lokasi
if(isset($_POST['tambah_lokasi'])){
    $lokasi = $_POST['nama_lokasi'];
    mysqli_query($conn, "INSERT INTO locations (nama_lokasi) VALUES ('$lokasi')");
    header("Location: master_data.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Data - Lab IR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="bg-light">

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

    <main id="main-content" class="p-4 w-100">
        <h3 class="fw-bold mb-4">Pengaturan Master Data</h3>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold"><i class="bi bi-tags"></i> Kategori Barang</h5>
                        <form action="" method="POST" class="d-flex gap-2 my-3">
                            <input type="text" name="nama_kategori" class="form-control bg-light" placeholder="Nama kategori baru..." required>
                            <button type="submit" name="tambah_kategori" class="btn btn-primary">Tambah</button>
                        </form>
                        <ul class="list-group list-group-flush">
                            <?php
                            $qk = mysqli_query($conn, "SELECT * FROM categories");
                            while($k = mysqli_fetch_assoc($qk)) { 
                                echo "<li class='list-group-item bg-transparent'>".$k['nama_kategori']."</li>"; 
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="fw-bold"><i class="bi bi-pin-map"></i> Lokasi Penyimpanan</h5>
                        <form action="" method="POST" class="d-flex gap-2 my-3">
                            <input type="text" name="nama_lokasi" class="form-control bg-light" placeholder="Nama lokasi/rak baru..." required>
                            <button type="submit" name="tambah_lokasi" class="btn btn-success">Tambah</button>
                        </form>
                        <ul class="list-group list-group-flush">
                            <?php
                            $ql = mysqli_query($conn, "SELECT * FROM locations");
                            while($l = mysqli_fetch_assoc($ql)) { 
                                echo "<li class='list-group-item bg-transparent'>".$l['nama_lokasi']."</li>"; 
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>