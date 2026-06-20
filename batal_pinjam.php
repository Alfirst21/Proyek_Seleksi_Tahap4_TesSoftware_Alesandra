<?php
include 'koneksi.php';

if(isset($_GET['id']) && isset($_GET['item_id'])){
    $id_transaksi = $_GET['id'];
    $item_id = $_GET['item_id'];
    
    // 1. Hapus permanen data transaksi karena dianggap salah input/batal
    $hapus = mysqli_query($conn, "DELETE FROM transactions WHERE id='$id_transaksi'");
    
    // 2. Jika berhasil dihapus, kembalikan stok barang ke semula (+1)
    if($hapus){
        mysqli_query($conn, "UPDATE items SET stok = stok + 1 WHERE id='$item_id'");
    }
    
    // 3. Arahkan kembali ke halaman tabel peminjaman
    header("Location: transaksi.php");
    exit;
}
?>