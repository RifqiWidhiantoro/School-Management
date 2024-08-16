<?php
$conn = mysqli_connect('localhost', 'root', '', 'pelajar');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
