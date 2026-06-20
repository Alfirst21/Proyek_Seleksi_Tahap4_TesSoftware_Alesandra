<?php
include 'koneksi.php';

if(isset($_GET['id']) && isset($_GET['item_id'])){
    $id_transaksi = $_GET['id'];
    $item_id = $_GET['item_id'];
    
    // 1. Ubah status transaksi menjadi Dikembalikan
    $update_status = mysqli_query($conn, "UPDATE transactions SET status='Dikembalikan' WHERE id='$id_transaksi'");
    
    // 2. Kembalikan jumlah stok barang ke semula (+1)
    if($update_status){
        mysqli_query($conn, "UPDATE items SET stok = stok + 1 WHERE id='$item_id'");
    }
    
    // 3. Arahkan kembali ke halaman tabel peminjaman
    header("Location: transaksi.php");
}
?>