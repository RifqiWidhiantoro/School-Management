<?php
session_start();
include_once "connection.php";
$id = $_GET['id'];

// Mengambil data siswa berdasarkan id
$query = mysqli_query($conn, "SELECT * FROM data_siswa WHERE id = $id");
$data = mysqli_fetch_object($query);

// Mengambil hobi yang terkait dengan siswa
$hobi_query = mysqli_query($conn, "SELECT hobi_id FROM siswa_hobi WHERE siswa_id = $id");
$hobi = [];
while ($row = mysqli_fetch_assoc($hobi_query)) {
    $hobi[] = $row['hobi_id'];
}

$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <?php if ($error): ?>
        <div id="alert-box" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-red-600 font-bold mb-4"><?= $error ?></p>
                <div class="flex justify-end space-x-4">
                    <button onclick="handleOk()" class="bg-blue-500 text-white px-4 py-2 rounded">OK</button>
                    <button onclick="handleCancel()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg mt-12">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 pt-6">Edit Profil Siswa</h2>
        <form id="editForm" action="update.php" method="post" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateForm()">
            <input type="hidden" name="id" value="<?= $data->id ?>">

            <div>
                <label for="full_name" class="block text-gray-700 font-semibold">Nama Lengkap:</label>
                <input type="text" id="full_name" name="full_name" value="<?= $data->name ?>" required class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
            </div>
            
            <div>
                <label for="gender_id" class="block text-gray-700 font-semibold">Jenis Kelamin:</label>
                <select id="gender_id" name="gender_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
                    <?php
                    $gender_query = mysqli_query($conn, "SELECT * FROM jenis_kelamin");
                    while ($row = mysqli_fetch_assoc($gender_query)) {
                        $selected = ($data->gender_id == $row['id']) ? 'selected' : '';
                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['gender'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="agama_id" class="block text-gray-700 font-semibold">Agama:</label>
                <select id="agama_id" name="agama_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
                    <?php
                    $agama_query = mysqli_query($conn, "SELECT * FROM agama");
                    while ($row = mysqli_fetch_assoc($agama_query)) {
                        $selected = ($data->agama_id == $row['id']) ? 'selected' : '';
                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['agama'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="kelas_id" class="block text-gray-700 font-semibold">Kelas:</label>
                <select id="kelas_id" name="kelas_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
                    <?php
                    $kelas_query = mysqli_query($conn, "SELECT * FROM kelas");
                    while ($row = mysqli_fetch_assoc($kelas_query)) {
                        $selected = ($data->kelas_id == $row['id']) ? 'selected' : '';
                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['class'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="jurusan_id" class="block text-gray-700 font-semibold">Jurusan:</label>
                <select id="jurusan_id" name="jurusan_id" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
                    <?php
                    $jurusan_query = mysqli_query($conn, "SELECT * FROM jurusan");
                    while ($row = mysqli_fetch_assoc($jurusan_query)) {
                        $selected = ($data->jurusan_id == $row['id']) ? 'selected' : '';
                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['jurusan'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Hobi:</label>
                <div class="flex flex-wrap gap-4 mt-1">
                    <?php
                    $hobi_query = mysqli_query($conn, "SELECT * FROM hobi");
                    while ($row = mysqli_fetch_assoc($hobi_query)) {
                        $checked = in_array($row['id'], $hobi) ? 'checked' : '';
                        echo '<label class="inline-flex items-center"><input type="checkbox" name="hobi[]" value="'.$row['id'].'" '.$checked.' class="form-checkbox h-5 w-5 text-blue-600"><span class="ml-2">'.$row['hobi'].'</span></label>';
                    }
                    ?>
                </div>
            </div>
            
            <div>
                <label for="gambar" class="block text-gray-700 font-semibold">Pilih Gambar (Kosongkan jika tidak ingin mengganti gambar):</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" class="mt-1 p-2 block w-full border border-gray-300 rounded-lg focus:border-blue-500">
            </div>
            
            <div class="flex justify-end">
                <input type="submit" value="Update" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            const fullName = document.getElementById('full_name').value.trim();
            if (fullName.length < 3) {
                alert('Nama lengkap harus terdiri dari minimal 3 karakter.');
                return false;
            }
            return true;
        }

        function handleOk() {
            document.getElementById('alert-box').style.display = 'none';
            window.location.href = 'edit.php?id=<?= $data->id ?>';
        }
        
        function handleCancel() {
            document.getElementById('alert-box').style.display = 'none';
        }

        // Menambahkan event listener untuk submit form
        document.getElementById('editForm').onsubmit = validateForm;
    </script>
</body>
</html>
