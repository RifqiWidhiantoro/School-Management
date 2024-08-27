<?php
include_once "connection.php";
session_start();

$full_name   = $_POST['full_name'];
$gender_id   = $_POST['gender_id'];
$agama_id    = $_POST['agama_id'];
$kelas_id    = $_POST['kelas_id'];
$jurusan_id  = $_POST['jurusan_id'];
$password    = $_POST['password'];
$hobi        = isset($_POST['hobi']) ? $_POST['hobi'] : [];
$file        = $_FILES['gambar'];

$_SESSION['form_data'] = $_POST;

// Cek apakah nama sudah ada di database
$query = mysqli_query($conn, "SELECT * FROM data_siswa WHERE name = '$full_name'");
if (mysqli_num_rows($query) > 0) {
    $_SESSION['error'] = "Nama sudah ada. Silakan gunakan nama yang berbeda.";
    header("location: register_siswa.php");
    exit();
}

// Proses upload gambar jika tidak ada error
if ($file['error'] === UPLOAD_ERR_OK) {
    $allowed_image_extension = array("jpg", "jpeg", "png");
    $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);

    if (!file_exists($file["tmp_name"])) {
        $error_message = "Pilih File Gambar yang ingin diupload.";
    } elseif (!in_array($file_extension, $allowed_image_extension)) {
        $error_message = "Upload gambar yang valid. Hanya JPG, JPEG, dan PNG yang diperbolehkan.";
    } elseif ($file["size"] > 1048576) { // 1MB
        $error_message = "Ukuran gambar melebihi 1MB.";
    }

    if (isset($error_message)) {
        $_SESSION['error'] = $error_message;
        header("location: register_siswa.php");
        exit();
    }

    // Tentukan direktori penyimpanan
    $original_dir = "asset/original_image/";
    $thumbnail_dir = "asset/thumbnail_image/";

    // Tentukan nama file baru yang unik
    $new_file_name = date('Y-m-d-H-i-s') . '.' . $file_extension;
    $original_file = $original_dir . $new_file_name;
    $thumbnail_file = $thumbnail_dir . $new_file_name;

    // Pindahkan file yang diupload ke direktori yang ditentukan
    if (move_uploaded_file($file["tmp_name"], $original_file)) {
        $image = null;
        switch ($file_extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($original_file);
                break;
            case 'png':
                $image = imagecreatefrompng($original_file);
                break;
        }

        if ($image) {
            // Buat thumbnail
            $thumb_width = 200;
            $thumb_height = 200;
            $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
            imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, imagesx($image), imagesy($image));
            switch ($file_extension) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($thumb, $thumbnail_file);
                    break;
                case 'png':
                    imagepng($thumb, $thumbnail_file);
                    break;
            }
            imagedestroy($image);
            imagedestroy($thumb);
        }

        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data user (username adalah full_name) dan password ke tabel users
        mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$full_name', '$hashed_password', 'siswa')");
        $user_id = mysqli_insert_id($conn);

        // Simpan data siswa ke database
        mysqli_query($conn, "INSERT INTO data_siswa (name, gender_id, agama_id, kelas_id, jurusan_id, image, user_id) VALUES ('$full_name', '$gender_id', '$agama_id', '$kelas_id', '$jurusan_id', '$new_file_name', '$user_id')");

        $siswa_id = mysqli_insert_id($conn);

        // Simpan hobi siswa ke tabel siswa_hobi
        foreach ($hobi as $h) {
            mysqli_query($conn, "INSERT INTO siswa_hobi (siswa_id, hobi_id) VALUES ('$siswa_id', '$h')");
        }

        unset($_SESSION['form_data']);
        
        // Setelah data siswa berhasil disimpan, arahkan ke halaman profil siswa
        header("Location: profil_siswa.php?id=$siswa_id&view-only=true");
        exit();
    } else {
        echo "File gagal diupload.";
    }
} else {
    echo "Error: " . $file['error'];
}
?>
