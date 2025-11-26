<?php
$host = "localhost";
$user = "root";        // ganti kalau pakai user lain
$pass = "";            // isi password MySQL kamu
$db   = "madupos";     // ganti sesuai nama database

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
