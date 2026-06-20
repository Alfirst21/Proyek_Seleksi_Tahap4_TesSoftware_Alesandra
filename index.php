<?php 
include 'koneksi.php'; 
// Taruh ini di dalam blok <?php paling atas
$query_kategori = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories"); 
$data_kategori = mysqli_fetch_assoc($query_kategori);

// Cek jika ada parameter pesan dari halaman lain (Error Handling)
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Inventaris Lab X</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="d-flex">
    <nav id="sidebar" class="d-flex flex-column" style="height: 100vh;">
        <div class="sidebar-brand p-3">
            <i class="bi bi-box-seam text-primary"></i> Lab X System
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
        <?php if($pesan == 'gagal_upload'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Format file tidak didukung atau ukuran terlalu besar.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif($pesan == 'sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data barang berhasil ditambahkan ke dalam sistem.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="welcome-box d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold">Selamat Datang di Sistem Inventaris Terpadu</h3>
                <p class="mb-0">Kelola aset perangkat keras Laboratorium Information Retrieval dengan akurat dan efisien.</p>
            </div>
        </div>

        <h5 class="fw-bold text-dark mb-3">Ringkasan Statistik</h5>
        <div class="row mb-4">
            <?php 
                $q_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM items");
                $total_barang = mysqli_fetch_assoc($q_total)['total'];
            ?>
            <div class="col-md-3">
                <div class="stat-card">
                    <p class="text-muted mb-1 fw-bold">Aset Terdaftar</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0"><?= $total_barang; ?></h2>
                        <i class="bi bi-pc-display stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <p class="text-muted mb-1 fw-bold">Kategori</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0 text-start"><?php echo $data_kategori['total']; ?></h2>
                        <i class="bi bi-tags stat-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div id="tabel-aset" class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Daftar Inventaris Terbaru</h5>
                    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Aset Baru</a>
                </div>
                
               <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Nama Spesifikasi</th>
                            <th>Kategori</th>
                            <th>Lokasi / Rak</th>
                            <th>Sisa Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Mengambil data barang, kategori, dan lokasi
                        $query = "SELECT items.*, categories.nama_kategori, locations.nama_lokasi 
                                FROM items 
                                LEFT JOIN categories ON items.category_id = categories.id 
                                LEFT JOIN locations ON items.location_id = locations.id
                                ORDER BY items.id DESC";
                        $data = mysqli_query($conn, $query);
                        
                        if(mysqli_num_rows($data) > 0) {
                            while($d = mysqli_fetch_assoc($data)){
                        ?>
                        <tr>
                            <td>
                                <?php
                                $namaFoto = !empty($d['foto']) ? basename($d['foto']) : '';
                                $pathFoto = __DIR__ . '/uploads/' . $namaFoto;
                                $urlFoto = './uploads/' . rawurlencode($namaFoto);
                                ?>

                                <?php if ($namaFoto != '' && file_exists($pathFoto)): ?>
                                    <img 
                                        src="<?php echo $urlFoto; ?>" 
                                        alt="<?php echo htmlspecialchars($d['nama_barang']); ?>"
                                        style="width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;"
                                    >
                                <?php else: ?>
                                    <div style="width: 55px; height: 55px; background-color: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-image text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?php echo $d['nama_barang']; ?></td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                    <?php echo $d['nama_kategori']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-danger"><i class="bi bi-geo-alt"></i></span> 
                                <?php echo $d['nama_lokasi']; ?>
                            </td>
                            <td class="fw-bold"><?php echo $d['stok']; ?> Unit</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm text-white me-1" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $d['id']; ?>">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <button type="button" onclick="window.location.href='tambah.php?id=<?php echo $d['id']; ?>'" class="btn btn-warning btn-sm text-white me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                
                                <a href="hapus.php?id=<?php echo $d['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>

                                <div class="modal fade" id="viewModal<?php echo $d['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $d['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewModalLabel<?php echo $d['id']; ?>">Detail Inventaris</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <strong>Nama Spesifikasi</strong>
                                                        <p class="mb-2"><?php echo $d['nama_barang']; ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Kategori</strong>
                                                        <p class="mb-2"><?php echo $d['nama_kategori']; ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Lokasi / Rak</strong>
                                                        <p class="mb-2"><?php echo $d['nama_lokasi']; ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Sisa Stok</strong>
                                                        <p class="mb-2"><?php echo $d['stok']; ?> Unit</p>
                                                    </div>
                                                    <?php if(isset($d['kode_barang'])): ?>
                                                    <div class="col-md-6">
                                                        <strong>Kode Barang</strong>
                                                        <p class="mb-2"><?php echo $d['kode_barang']; ?></p>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if(isset($d['kondisi'])): ?>
                                                    <div class="col-md-6">
                                                        <strong>Kondisi</strong>
                                                        <p class="mb-2"><?php echo $d['kondisi']; ?></p>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if(isset($d['deskripsi'])): ?>
                                                    <div class="col-12">
                                                        <strong>Deskripsi</strong>
                                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($d['deskripsi'])); ?></p>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-3'>Belum ada data barang.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>