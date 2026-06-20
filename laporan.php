<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Aset Fisik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container bg-white p-4 shadow-sm rounded">
    <h3 class="fw-bold">Laporan Inventaris Aset Fisik</h3>
    <p class="text-muted">Laboratorium X</p>
    
    <div class="d-flex justify-content-end mb-3">
        <a href="index.php" class="btn btn-secondary me-2">← Kembali</a>
        <button onclick="window.print()" class="btn btn-primary">Cetak / PDF</button>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Spesifikasi</th>
                <th>Kategori</th>
                <th>Lokasi Rak</th>
                <th>Sisa Stok Fisik</th>
                <th>Jadwal Maintenance</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            
            // Kueri yang sudah diperbaiki typo-nya sesuai nama tabel dan kolom di database kamu
            $query = "SELECT items.*, 
                             categories.nama_kategori AS nama_kategori, 
                             locations.nama_lokasi AS nama_lokasi,
                             pemeliharaan.tanggal_lapor AS tgl_masuk_servis
                      FROM items 
                      LEFT JOIN categories ON items.category_id = categories.id 
                      LEFT JOIN locations ON items.location_id = locations.id
                      LEFT JOIN pemeliharaan ON items.id = pemeliharaan.item_id"; 
            
            $data = mysqli_query($conn, $query); 
            
            if(mysqli_num_rows($data) > 0) {
                while($d = mysqli_fetch_assoc($data)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $d['nama_barang']; ?></td>
                <td><?php echo $d['nama_kategori']; ?></td>
                <td><?php echo $d['nama_lokasi']; ?></td>
                <td><?php echo $d['stok']; ?> Unit</td>
                
                <td class="<?php echo (!empty($d['tgl_masuk_servis'])) ? 'text-danger fw-bold' : ''; ?>">
                    <?php 
                        // Memanggil tanggal_lapor dari tabel pemeliharaan
                        if(!empty($d['tgl_masuk_servis'])) {
                            echo date('d M Y', strtotime($d['tgl_masuk_servis'])); 
                        } else {
                            echo "-";
                        }
                    ?>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='6' class='text-center'>Tidak ada data aset.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="mt-4 text-end">
        <p>Dicetak pada: <?php echo date('d M Y'); ?></p>
        <p><strong>Admin Lab IR</strong></p>
    </div>
</div>

</body>
</html>