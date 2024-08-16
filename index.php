<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    unset($_SESSION['form_data']);
}

// Inisialisasi variabel $error dengan nilai kosong jika tidak ada dalam sesi
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

if (!isset($_SESSION['form_data']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $form_data = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

    <?php if ($error): ?>
        <div id="alert-box" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-red-600 font-bold mb-4"><?= $error ?></p>
                <div class="flex justify-end space-x-4">
                    <button onclick="handleCancel()" class="bg-blue-500 text-white px-4 py-2 rounded">Ok</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg mt-10">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 pt-6">Form Data Siswa</h2>

        <form action="simpan.php" method="post" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateForm()">
            <input type="hidden" name="from" value="index">

            <div>
                <label for="full_name" class="block text-gray-700 font-semibold">Nama Lengkap:</label>
                <input type="text" id="full_name" name="full_name" value="<?= isset($form_data['full_name']) ? $form_data['full_name'] : '' ?>" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
            </div>

            <div>
                <label for="gender_id" class="block text-gray-700 font-semibold">Jenis Kelamin:</label>
                <select id="gender_id" name="gender_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
                    <?php
                    include_once "connection.php";
                    $result = mysqli_query($conn, "SELECT * FROM jenis_kelamin");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = isset($form_data['gender_id']) && $form_data['gender_id'] == $row['id'] ? 'selected' : '';
                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['gender'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="agama_id" class="block text-gray-700 font-semibold">Agama:</label>
                <select id="agama_id" name="agama_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM agama");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = isset($form_data['agama_id']) && $form_data['agama_id'] == $row['id'] ? 'selected' : '';
                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['agama'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="kelas_id" class="block text-gray-700 font-semibold">Kelas:</label>
                <select id="kelas_id" name="kelas_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM kelas");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = isset($form_data['kelas_id']) && $form_data['kelas_id'] == $row['id'] ? 'selected' : '';
                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['class'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="jurusan_id" class="block text-gray-700 font-semibold">Jurusan:</label>
                <select id="jurusan_id" name="jurusan_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM jurusan");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = isset($form_data['jurusan_id']) && $form_data['jurusan_id'] == $row['id'] ? 'selected' : '';
                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['jurusan'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Hobi:</label>
                <div class="flex flex-wrap gap-4 mt-1">
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM hobi");
                    $selected_hobbies = isset($form_data['hobi']) ? $form_data['hobi'] : [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $checked = in_array($row['id'], $selected_hobbies) ? 'checked' : '';
                        echo '<label class="inline-flex items-center"><input type="checkbox" name="hobi[]" value="'.$row['id'].'" '.$checked.' class="form-checkbox h-5 w-5 text-blue-600"><span class="ml-2">'.$row['hobi'].'</span></label>';
                    }
                    ?>
                </div>
            </div>

            <div>
                <label for="gambar" class="block text-gray-700 font-semibold">Pilih Gambar:</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
            </div>

            <div class="flex justify-end">
                <input type="submit" value="Kirim" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>
        </form>
    </div>

    <script>
        // Validasi saat submit form
        function validateForm() {
            // Cek apakah minimal satu hobi dipilih
            const hobi = document.querySelectorAll('input[name="hobi[]"]:checked');
            if (hobi.length === 0) {
                alert('Anda wajib memilih minimal satu hobi.');
                return false;
            }

            // Cek apakah gambar diupload
            const gambar = document.getElementById('gambar').value;
            if (!gambar) {
                alert('Anda wajib mengupload gambar.');
                return false;
            }

            return true; // Lanjutkan submit jika semua validasi lolos
        }

        // Fungsi untuk menangani tombol OK di alert box
        function handleOk() {
            document.getElementById('alert-box').style.display = 'none';
            window.location.href = 'index.php';
        }

        // Fungsi untuk menangani tombol Cancel di alert box
        function handleCancel() {
            document.getElementById('alert-box').style.display = 'none';
        }
    </script>
</body>
</html>
