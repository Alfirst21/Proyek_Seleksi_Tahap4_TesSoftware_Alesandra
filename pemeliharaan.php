<?php include 'koneksi.php'; 

// 1. Logika Lapor Barang Rusak
if(isset($_POST['lapor_rusak'])){
    $item_id = $_POST['item_id'];
    $tanggal = $_POST['tanggal_lapor'];
    $kendala = $_POST['kendala'];
    
    // Simpan ke tabel pemeliharaan dan kurangi stok barang
    mysqli_query($conn, "INSERT INTO pemeliharaan (item_id, tanggal_lapor, kendala, status) VALUES ('$item_id', '$tanggal', '$kendala', 'Perbaikan')");
    mysqli_query($conn, "UPDATE items SET stok = stok - 1 WHERE id='$item_id'");
    header("Location: pemeliharaan.php");
    exit;
}

// 2. Logika Selesai Servis (Barang kembali ke inventaris)
if(isset($_GET['selesai_id']) && isset($_GET['item_id'])){
    $id_servis = $_GET['selesai_id'];
    $item_id = $_GET['item_id'];
    
    // Ubah status jadi selesai dan kembalikan stok +1
    mysqli_query($conn, "UPDATE pemeliharaan SET status='Selesai' WHERE id='$id_servis'");
    mysqli_query($conn, "UPDATE items SET stok = stok + 1 WHERE id='$item_id'");
    header("Location: pemeliharaan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemeliharaan - Lab IR</title>
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
        <h3 class="fw-bold mb-4">Pemeliharaan & Servis Aset</h3>
        <a href="index.php" class="btn btn-sm btn-outline-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
        
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4 bg-white">
                <h5 class="fw-bold text-danger mb-3"><i class="bi bi-exclamation-triangle"></i> Lapor Barang Rusak</h5>
                <form action="" method="POST" class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Pilih Barang</label>
                        <select name="item_id" class="form-select bg-light" required>
                            <option value="">-- Barang Bermasalah --</option>
                            <?php
                            $qi = mysqli_query($conn, "SELECT * FROM items WHERE stok > 0");
                            while($i = mysqli_fetch_assoc($qi)) { 
                                echo "<option value='".$i['id']."'>".$i['nama_barang']."</option>"; 
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Masalah</label>
                        <input type="date" name="tanggal_lapor" class="form-control bg-light" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kendala / Kerusakan</label>
                        <input type="text" name="kendala" class="form-control bg-light" placeholder="Misal: Blue screen saat booting" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="lapor_rusak" class="btn btn-danger w-100"><i class="bi bi-send-exclamation"></i> Laporkan</button>
                    </div>
                </form>
            </div>
        </div>

        <h5 class="fw-bold mt-5 mb-3">Daftar Barang Dalam Perbaikan</h5>
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-danger">
                        <tr>
                            <th class="ps-4">Barang</th>
                            <th>Tanggal Masuk</th>
                            <th>Keterangan Rusak</th>
                            <th>Status</th>
                            <th class="pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $qp = mysqli_query($conn, "SELECT pemeliharaan.*, items.nama_barang FROM pemeliharaan JOIN items ON pemeliharaan.item_id = items.id ORDER BY pemeliharaan.id DESC");
                        if(mysqli_num_rows($qp) > 0){
                            while($p = mysqli_fetch_assoc($qp)){
                        ?>
                        <tr>
                            <td class='fw-bold ps-4'><?= $p['nama_barang']; ?></td>
                            <td><?= date('d M Y', strtotime($p['tanggal_lapor'])); ?></td>
                            <td><?= $p['kendala']; ?></td>
                            <td>
                                <?php if($p['status'] == 'Perbaikan'): ?>
                                    <span class='badge bg-warning text-dark'><i class='bi bi-wrench'></i> Sedang Diservis</span>
                                <?php else: ?>
                                    <span class='badge bg-success'><i class='bi bi-check-circle'></i> Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4">
                                <?php if($p['status'] == 'Perbaikan'): ?>
                                    <a href="?selesai_id=<?= $p['id']; ?>&item_id=<?= $p['item_id']; ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Tandai barang ini sudah selesai diperbaiki? Stok akan dikembalikan.');">
                                        <i class="bi bi-check2-all"></i> Selesai
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Dikembalikan ke inventaris</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Tidak ada barang yang sedang rusak.</td></tr>";
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