<?php
include 'koneksi.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // 1. Ambil nama file foto dari database untuk dihapus fisik filenya
    $query_foto = mysqli_query($conn, "SELECT foto FROM items WHERE id='$id'");
    $data = mysqli_fetch_assoc($query_foto);
    $foto_lama = $data['foto'];
    
    // Hapus file fisik dari folder uploads jika ada
    if($foto_lama != "" && file_exists("uploads/" . $foto_lama)){
        unlink("uploads/" . $foto_lama);
    }
    
    // 2. Hapus data dari database
    $hapus = mysqli_query($conn, "DELETE FROM items WHERE id='$id'");
    
    if($hapus){
        header("Location: index.php?pesan=sukses_hapus");
    } else {
        header("Location: index.php?pesan=gagal_hapus");
    }
}
?>