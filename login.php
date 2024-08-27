<?php
// login.php
session_start();
require 'login_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mendapatkan user berdasarkan username
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Validasi username dan password
    if (!$user) {
        $error = "Username tidak ditemukan!";
    } elseif (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Ambil id dari data_siswa berdasarkan user_id
        $querySiswa = "SELECT id FROM data_siswa WHERE user_id = ?";
        $stmtSiswa = $conn->prepare($querySiswa);
        $stmtSiswa->bind_param("i", $user['id']);
        $stmtSiswa->execute();
        $resultSiswa = $stmtSiswa->get_result();
        $siswa = $resultSiswa->fetch_assoc();

        if ($siswa) {
            $_SESSION['siswa_id'] = $siswa['id'];
            header("Location: profil_siswa.php"); // Arahkan ke halaman profil siswa
        } else {
            // Jika data_siswa tidak ditemukan, arahkan ke halaman edit untuk melengkapi profil
            header("Location: edit.php");
        }
        exit();
    } else {
        $error = "Password salah!";
    }
}
?>

<!-- HTML Form untuk login -->
<form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<?php if (isset($error)): ?>
<p><?= $error ?></p>
<?php endif; ?>
