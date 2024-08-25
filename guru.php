<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

echo "Selamat datang, Guru!";
?>
