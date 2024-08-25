<?php
// register_guru.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi dan penyimpanan data pendaftaran guru
}
?>
<form method="POST" action="">
    <label for="name">Nama:</label>
    <input type="text" id="name" name="name" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    
    <!-- Tambahkan input lain yang diperlukan -->
    
    <button type="submit">Register</button>
</form>
