<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gunakan alamat IP 127.0.0.1 daripada localhost untuk bypass konfigurasi DNS
$host = "127.0.0.1"; 
$user = "root";
$pass = ""; 
$db   = "lab_inventory";

// Kita gunakan @ untuk menyembunyikan warning bawaan PHP
$conn = mysqli_connect("localhost", "root", "", "lab_inventory");

if (!$conn) {
    // Jika masih gagal, kita tampilkan pesan error yang sangat spesifik
    die("Koneksi gagal total! Pesan MySQL: " . mysqli_connect_error());
}
?>