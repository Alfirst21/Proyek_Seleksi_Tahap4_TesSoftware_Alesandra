<?php 
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman Aset - Lab X</title>
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
            <div>
                <h3 class="fw-bold">Manajemen Peminjaman</h3>
                <p class="text-muted mb-0">Catatan lalu lintas keluar-masuk perangkat keras laboratorium.</p>
            </div>
            <a href="tambah_transaksi.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg"></i> Catat Peminjaman Baru</a>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Status Role</th>
                            <th>Aset yang Dipinjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status Transaksi</th>
                            <th>Aksi</th> </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query canggih menghubungkan 3 tabel!
                        $query = "SELECT transactions.*, users.nama, users.role, items.nama_barang 
                                  FROM transactions 
                                  JOIN users ON transactions.user_id = users.id 
                                  JOIN items ON transactions.item_id = items.id
                                  ORDER BY transactions.id DESC";
                        $result = mysqli_query($conn, $query);
                        $no = 1;
                        
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="fw-bold text-dark"><?= $row['nama']; ?></td>
                            <td><span class="badge bg-secondary"><?= $row['role']; ?></span></td>
                            <td><i class="bi bi-pc-display text-primary me-2"></i><?= $row['nama_barang']; ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_pinjam'])); ?></td>
                           
                            <td>
                                <?php if($row['status'] == 'Dipinjam'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Sedang Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Dikembalikan</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($row['status'] == 'Dipinjam'): ?>
                                    <div class="d-flex gap-2">
                                        <a href="kembalikan.php?id=<?= $row['id']; ?>&item_id=<?= $row['item_id']; ?>" class="btn btn-sm btn-success shadow-sm" title="Kembalikan Barang" onclick="return confirm('Proses pengembalian barang ini?');">
                                            <i class="bi bi-check2-circle"></i> Selesai
                                        </a>
                                        <a href="batal_pinjam.php?id=<?= $row['id']; ?>&item_id=<?= $row['item_id']; ?>" class="btn btn-sm btn-danger shadow-sm" title="Batalkan Transaksi" onclick="return confirm('Yakin membatalkan transaksi ini? Data riwayat akan dihapus.');">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small"><i class="bi bi-check2-all"></i> Selesai (Dikembalikan)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } 
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Belum ada riwayat peminjaman.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>