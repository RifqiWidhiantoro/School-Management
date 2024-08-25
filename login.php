<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Query untuk mendapatkan user berdasarkan email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect sesuai role
        switch($user['role']) {
            case 'admin':
                header("Location: admin.php");
                exit();
            case 'guru':
                header("Location: guru.php");
                exit();
            case 'siswa':
                header("Location: siswa.php");
                exit();
        }
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!-- HTML Form untuk login -->
<form method="POST" action="">
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
<?php if(isset($error)): ?>
<p><?= $error ?></p>
<?php endif; ?>
