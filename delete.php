<?php
include_once "connection.php";

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("location: profil.php?message=data_user_tidak_ditemukan");
    exit();
}

// Mendapatkan informasi gambar untuk menghapus file fisik
$result = mysqli_query($conn, "SELECT image FROM data_siswa WHERE id = $id");
$data = mysqli_fetch_assoc($result);
$file = $data['image'];

if ($data) {
    // Hapus relasi hobi siswa
    mysqli_query($conn, "DELETE FROM siswa_hobi WHERE siswa_id = $id");

    // Hapus data siswa
    $deleteResult = mysqli_query($conn, "DELETE FROM data_siswa WHERE id = $id");

    if ($deleteResult) {
        // Hapus file gambar jika ada
        $original_file = "asset/original_image/" . $file;
        $thumbnail_file = "asset/thumbnail_image/" . $file;

        if (file_exists($original_file)) {
            unlink($original_file);
        }
        if (file_exists($thumbnail_file)) {
            unlink($thumbnail_file);
        }

        // Redirect ke halaman profil dengan pesan bahwa data telah dihapus
        header("location: profil.php?deleted=true");
    } else {
        header("location: profil.php?message=error_hapus_data");
    }
} else {
    header("location: profil.php?message=data_user_tidak_ditemukan");
}
?>
