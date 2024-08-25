<?php
include_once "connection.php";
session_start();

$full_name   = $_POST['full_name'];
$gender_id   = $_POST['gender_id'];
$agama_id    = $_POST['agama_id'];
$kelas_id    = $_POST['kelas_id'];
$jurusan_id  = $_POST['jurusan_id'];
$hobi        = isset($_POST['hobi']) ? $_POST['hobi'] : [];
$file        = $_FILES['gambar'];

$_SESSION['form_data'] = $_POST;

$query = mysqli_query($conn, "SELECT * FROM data_siswa WHERE name = '$full_name'");
if (mysqli_num_rows($query) > 0) {
    $_SESSION['error'] = "Nama sudah ada. Silakan gunakan nama yang berbeda.";
    header("location: index.php");
    exit();
}

if ($file['error'] === UPLOAD_ERR_OK) {
    $allowed_image_extension = array("jpg", "jpeg", "png");
    $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);

    if (!file_exists($file["tmp_name"])) {
        $error_message = "Pilih File Gambar yang ingin diupload.";
    } elseif (!in_array($file_extension, $allowed_image_extension)) {
        $error_message = "Upload gambar yang valid. Hanya JPG, JPEG, dan PNG yang diperbolehkan.";
    } elseif ($file["size"] > 1048576) { // 10MB
        $error_message = "Ukuran gambar melebihi 10MB.";
    }

    if (isset($error_message)) {
        $_SESSION['error'] = $error_message;
        header("location: index.php");
        exit();
    }

    // Tentukan direktori penyimpanan
    $original_dir = "asset/original_image/";
    $thumbnail_dir = "asset/thumbnail_image/";

    // Tentukan nama file baru yang unik
    $new_file_name = date('Y-m-d-H-i-s') . '.' . $file_extension;
    $original_file = $original_dir . $new_file_name;
    $thumbnail_file = $thumbnail_dir . $new_file_name;

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

        mysqli_query($conn, "INSERT INTO data_siswa (name, gender_id, agama_id, kelas_id, jurusan_id, image) VALUES ('$full_name', '$gender_id', '$agama_id', '$kelas_id', '$jurusan_id', '$new_file_name')");

        $siswa_id = mysqli_insert_id($conn);
        foreach ($hobi as $h) {
            mysqli_query($conn, "INSERT INTO siswa_hobi (siswa_id, hobi_id) VALUES ('$siswa_id', '$h')");
        }

        unset($_SESSION['form_data']);
        header("location:siswa.php");
    } else {
        echo "File gagal diupload.";
    }
} else {
    echo "Error: " . $file['error'];
}
?>
