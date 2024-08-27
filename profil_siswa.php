<?php
include_once "connection.php";
session_start();
$message = '';
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'] ?? null;
$id = $_GET['id'] ?? $user_id;

// Cek jika user sudah login
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    $user_id = $_SESSION['user_id'];
}

// Mengambil id dari query string, jika tidak ada, gunakan user_id
$id = isset($_GET['id']) ? $_GET['id'] : $user_id;

// Pesan kesalahan dari query string
if (isset($_GET['message'])) {
    if ($_GET['message'] === 'data_user_tidak_ditemukan') {
        $message = 'Data user tidak ditemukan!';
    } elseif ($_GET['message'] === 'data_berhasil_diperbarui') {
        $message = 'Data berhasil diperbarui!';
    } elseif ($_GET['message'] === 'tidak_berhak_mengedit') {
        $message = 'Anda tidak berhak mengedit data ini!';
    }
}

// Query untuk mengambil data siswa
$query = "SELECT 
            ds.name, 
            jk.gender, 
            ag.agama, 
            kl.class, 
            jr.jurusan, 
            GROUP_CONCAT(hb.hobi SEPARATOR ', ') AS hobi,
            ds.image
          FROM data_siswa ds
          JOIN jenis_kelamin jk ON ds.gender_id = jk.id
          JOIN agama ag ON ds.agama_id = ag.id
          JOIN kelas kl ON ds.kelas_id = kl.id
          JOIN jurusan jr ON ds.jurusan_id = jr.id
          LEFT JOIN siswa_hobi sh ON ds.id = sh.siswa_id
          LEFT JOIN hobi hb ON sh.hobi_id = hb.id
          WHERE ds.user_id = $id
          GROUP BY ds.name, jk.gender, ag.agama, kl.class, jr.jurusan, ds.image";
          
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    // Jika data tidak ditemukan, kembalikan ke halaman profil
    header("location: login.php?message=data_user_tidak_ditemukan");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-container {
            width: 50%;
            margin: 0 auto;
            text-align: center;
            position: relative;
        }
        img {
            border-radius: 50%;
            cursor: pointer;
        }
        .profile-details {
            margin-top: 20px;
        }
        .profile-details table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .profile-details th, .profile-details td {
            padding: 10px;
            text-align: left;
        }
        .profile-details th {
            background-color: #f2f2f2;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            text-align: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            max-width: 90%;
            max-height: 90%;
        }
        .modal-content img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            border-radius: 0;
        }
        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 25px;
        }
        .arrow-back {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 24px;
            cursor: pointer;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .small-box {
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .bg-warning {
            background-color: #f39c12;
        }
        .bg-danger {
            background-color: #e74c3c;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 18px;
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h1>Profil Siswa</h1>
        <?php
        if ($message) {
            echo "<div class='alert alert-success alert-box' role='alert'>{$message}</div>";
        }
        ?>
        <?php if (isset($row) && $row): ?>
            <img src='asset/thumbnail_image/<?= $row['image'] ?>' width='160' height='160' onclick="document.getElementById('myModal').style.display='block'">
            <div class="profile-details">
                <table border="1">
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><?= $row['name'] ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td><?= $row['gender'] ?></td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td><?= $row['agama'] ?></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td><?= $row['class'] ?></td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td><?= $row['jurusan'] ?></td>
                    </tr>
                    <tr>
                        <th>Hobi</th>
                        <td><?= $row['hobi'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="action-buttons">
                <a href="edit.php?id=<?= $id ?>" class="small-box bg-warning">Edit Data</a>
                <?php if ($role !== 'siswa'): // Tombol hapus hanya ditampilkan jika pengguna bukan siswa ?>
                    <a href="delete.php?id=<?= $id ?>" class="small-box bg-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus Profil Anda?')">Delete Profil</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p style="color: red;">Data tidak ditemukan.</p>
        <?php endif; ?>
    </div>

    <!-- Modal for Original Image -->
    <div id="myModal" class="modal">
        <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
        <div class="modal-content">
            <?php if (isset($row) && $row): ?>
                <img src='asset/original_image/<?= $row['image'] ?>'>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var alertBox = document.querySelector('.alert-box');
            if (alertBox) {
                setTimeout(function() {
                    alertBox.style.display = 'none';
                }, 5000); // Hide the alert box after 5 seconds
                
                // Auto-redirect after 8 seconds
                setTimeout(function() {
                    window.location.href = "data_siswa.php";
                }, 8000); // Redirect after 8 seconds
            }
        });
    </script>
</body>
</html>
