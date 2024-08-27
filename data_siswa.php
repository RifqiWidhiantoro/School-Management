<?php
include_once "connection.php";

// Warna jurusan
$jurusan_colors = [
    'Akuntansi' => '#32CD32',
    'Busana' => '#FF6384',
    'Kuliner' => '#E7E9ED',
    'Multimedia' => '#3C99DC',
    'Pemasaran' => '#FF9F40',
    'Perkantoran' => '#9966FF',
    'Perangkat Lunak' => '#FFFF00',
    'Teknik Komputer' => '#FF0000',
];

$query = "SELECT 
            ds.id, 
            ds.name,
            jr.jurusan
          FROM data_siswa ds
          JOIN jurusan jr ON ds.jurusan_id = jr.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 to-purple-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-5xl font-bold text-center mb-12 text-gray-800">Data Siswa</h1>
        <div class="flex flex-wrap justify-center gap-8">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $backgroundColor = $jurusan_colors[$row['jurusan']] ?? '#f4f4f4'; // Default color if jurusan not in array
                echo "<div class='w-64 p-6 text-center rounded-lg shadow-lg transform transition duration-500 hover:scale-105' style='background-color: $backgroundColor; color: white;'>
                        <h3 class='text-2xl font-semibold mb-4'>{$row['name']}</h3>
                        <button onclick=\"window.location.href='profil_siswa.php?id={$row['id']}'\" class='px-5 py-3 mt-6 bg-white text-gray-800 font-semibold rounded-full hover:bg-gray-200 transition duration-300 shadow-md'>Lihat Profil</button>
                      </div>";
            }
            ?>
        </div>
        <div class="text-center mt-12">
            <a href="chart.php" class="inline-block px-8 py-4 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 transition duration-300 shadow-lg transform hover:scale-105">Lihat Statistik Data Siswa</a>
        </div>
    </div>
</body>
</html>
