<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'pelajar';

// Membuat koneksi ke database yang sudah ada
$conn = new mysqli($server, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
