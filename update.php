<?php
include_once "connection.php";
session_start();

$id = $_POST['id'];
$full_name   = $_POST['full_name'];
$gender_id   = $_POST['gender_id'];
$agama_id    = $_POST['agama_id'];
$kelas_id    = $_POST['kelas_id'];
$jurusan_id  = $_POST['jurusan_id'];
$hobi        = isset($_POST['hobi']) ? $_POST['hobi'] : [];
$file        = $_FILES['gambar'];

// Validasi: Minimal satu hobi harus dicentang
if (count($hobi) < 1) {
    $_SESSION['error'] = "Anda harus memilih minimal satu hobi.";
    header("Location: edit.php?id=$id");
    exit();
}

// Cek apakah ID ada di database
$query = mysqli_query($conn, "SELECT * FROM data_siswa WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    // Jika ID tidak ditemukan, redirect ke profil.php dengan pesan error
    header("location: profil.php?message=data_user_tidak_ditemukan");
    exit();
}

// Proses gambar jika ada file gambar yang diupload
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
        header("location: edit.php?id=$id");
        exit();
    }

    // Hapus gambar lama dari folder jika ada gambar baru yang diunggah
    $result = mysqli_fetch_assoc($query);
    $old_image = $result['image'];

    if ($old_image) {
        if (file_exists("asset/original_image/" . $old_image)) {
            unlink("asset/original_image/" . $old_image);
        }
        if (file_exists("asset/thumbnail_image/" . $old_image)) {
            unlink("asset/thumbnail_image/" . $old_image);
        }
    }

    // Tentukan nama file baru yang unik
    $new_file_name = date('Y-m-d-H-i-s') . '.' . $file_extension;
    $original_file = "asset/original_image/" . $new_file_name;
    $thumbnail_file = "asset/thumbnail_image/" . $new_file_name;

    if (move_uploaded_file($file["tmp_name"], $original_file)) {
        // Buat thumbnail
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
            $thumbnail = imagescale($image, 300, 200);
            imagejpeg($thumbnail, $thumbnail_file);
        }

        $query = "UPDATE data_siswa SET 
            name = '$full_name', 
            gender_id = '$gender_id', 
            agama_id = '$agama_id', 
            kelas_id = '$kelas_id', 
            jurusan_id = '$jurusan_id', 
            image = '$new_file_name'
            WHERE id = $id";

        mysqli_query($conn, $query);
    } else {
        $_SESSION['error'] = "Gagal upload gambar.";
        header("location: edit.php?id=$id");
        exit();
    }
} else {
    $query = "UPDATE data_siswa SET 
        name = '$full_name', 
        gender_id = '$gender_id', 
        agama_id = '$agama_id', 
        kelas_id = '$kelas_id', 
        jurusan_id = '$jurusan_id'
        WHERE id = $id";

    mysqli_query($conn, $query);
}

// Hapus hobi lama dan tambahkan hobi baru
mysqli_query($conn, "DELETE FROM siswa_hobi WHERE siswa_id = $id");
foreach ($hobi as $h) {
    mysqli_query($conn, "INSERT INTO siswa_hobi (siswa_id, hobi_id) VALUES ($id, $h)");
}

// Redirect ke halaman profil dengan ID yang benar
header("location: profil.php?id=$id&message=data_berhasil_diperbarui");
exit();
