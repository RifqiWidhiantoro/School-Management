<?php
include_once "connection.php";

// Menghitung jumlah berdasarkan jenis kelamin
$query_gender = "SELECT jk.gender, COUNT(ds.id) AS count 
                 FROM jenis_kelamin jk 
                 LEFT JOIN data_siswa ds ON ds.gender_id = jk.id 
                 GROUP BY jk.id";
$result_gender = mysqli_query($conn, $query_gender);
$gender_counts = [];
while ($row = mysqli_fetch_assoc($result_gender)) {
    $gender_counts[$row['gender']] = $row['count'];
}

// Menghitung jumlah berdasarkan agama
$query_agama = "SELECT ag.agama, COUNT(ds.id) AS count 
                FROM agama ag 
                LEFT JOIN data_siswa ds ON ds.agama_id = ag.id 
                GROUP BY ag.id";
$result_agama = mysqli_query($conn, $query_agama);
$agama_counts = [];
while ($row = mysqli_fetch_assoc($result_agama)) {
    $agama_counts[$row['agama']] = $row['count'];
}

// Menghitung jumlah berdasarkan kelas
$query_kelas = "SELECT kl.class, COUNT(ds.id) AS count 
                FROM kelas kl 
                LEFT JOIN data_siswa ds ON ds.kelas_id = kl.id 
                GROUP BY kl.id";
$result_kelas = mysqli_query($conn, $query_kelas);
$kelas_counts = [];
while ($row = mysqli_fetch_assoc($result_kelas)) {
    $kelas_counts[$row['class']] = $row['count'];
}

// Menghitung jumlah berdasarkan jurusan
$query_jurusan = "SELECT jr.jurusan, COUNT(ds.id) AS count 
                  FROM jurusan jr 
                  LEFT JOIN data_siswa ds ON ds.jurusan_id = jr.id 
                  GROUP BY jr.id";
$result_jurusan = mysqli_query($conn, $query_jurusan);
$jurusan_counts = [];
while ($row = mysqli_fetch_assoc($result_jurusan)) {
    $jurusan_counts[$row['jurusan']] = $row['count'];
}

// Menghitung jumlah berdasarkan hobi
$query_hobi = "SELECT hb.hobi, COUNT(sh.siswa_id) AS count 
               FROM hobi hb 
               LEFT JOIN siswa_hobi sh ON sh.hobi_id = hb.id 
               GROUP BY hb.id";
$result_hobi = mysqli_query($conn, $query_hobi);
$hobi_counts = [];
while ($row = mysqli_fetch_assoc($result_hobi)) {
    $hobi_counts[$row['hobi']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Statistik</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4">
        <div class="text-center my-8">
            <h1 class="text-4xl font-bold text-gray-800">Statistik Data Siswa</h1>
        </div>

        <!-- Section: Jenis Kelamin -->
        <div class="my-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Jenis Kelamin:</h2>
            <ul class="space-y-2">
                <li class="flex justify-between items-center bg-white p-4 shadow rounded-lg">
                    <span class="text-lg text-gray-700">Laki-laki:</span>
                    <span class="text-lg font-bold text-gray-900"><?= isset($gender_counts['Laki-laki']) ? $gender_counts['Laki-laki'] : 0 ?> Orang</span>
                </li>
                <li class="flex justify-between items-center bg-white p-4 shadow rounded-lg">
                    <span class="text-lg text-gray-700">Perempuan:</span>
                    <span class="text-lg font-bold text-gray-900"><?= isset($gender_counts['Perempuan']) ? $gender_counts['Perempuan'] : 0 ?> Orang</span>
                </li>
            </ul>
            <div class="flex justify-center my-6">
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section: Agama -->
        <div class="my-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Agama:</h2>
            <ul class="space-y-2">
                <?php foreach ($agama_counts as $agama => $count): ?>
                    <li class="flex justify-between items-center bg-white p-4 shadow rounded-lg">
                        <span class="text-lg text-gray-700"><?= $agama ?>:</span>
                        <span class="text-lg font-bold text-gray-900"><?= $count ?> Orang</span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="flex justify-center my-6">
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <canvas id="agamaChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section: Kelas -->
        <div class="my-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Kelas:</h2>
            <ul class="space-y-2">
                <?php foreach ($kelas_counts as $kelas => $count): ?>
                    <li class="flex justify-between items-center bg-white p-4 shadow rounded-lg">
                        <span class="text-lg text-gray-700"><?= $kelas ?>:</span>
                        <span class="text-lg font-bold text-gray-900"><?= $count ?> Orang</span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="flex justify-center my-6">
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <canvas id="kelasChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section: Jurusan -->
        <div class="my-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Jurusan:</h2>
            <ul class="space-y-2">
                <?php foreach ($jurusan_counts as $jurusan => $count): ?>
                    <li class="flex justify-between items-center bg-white p-4 shadow rounded-lg">
                        <span class="text-lg text-gray-700"><?= $jurusan ?>:</span>
                        <span class="text-lg font-bold text-gray-900"><?= $count ?> Orang</span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="flex justify-center my-6">
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <canvas id="jurusanChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section: Hobi -->
        <div class="my-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Hobi:</h2>
            <ul class="space-y-2">
                <?php foreach ($hobi_counts as $hobi => $count): ?>
                    <li class="flex justify-between items-center bg-white p-4 shadow rounded-lg">
                        <span class="text-lg text-gray-700"><?= $hobi ?>:</span>
                        <span class="text-lg font-bold text-gray-900"><?= $count ?> Orang</span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="flex justify-center my-6">
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <canvas id="hobiChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <script>
        const genderData = <?= json_encode(array_values($gender_counts)) ?>;
        const agamaData = <?= json_encode(array_values($agama_counts)) ?>;
        const kelasData = <?= json_encode(array_values($kelas_counts)) ?>;
        const jurusanData = <?= json_encode(array_values($jurusan_counts)) ?>;
        const hobiData = <?= json_encode(array_values($hobi_counts)) ?>;

        const ctxGender = document.getElementById('genderChart').getContext('2d');
        new Chart(ctxGender, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_keys($gender_counts)) ?>,
                datasets: [{
                    data: genderData,
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            },
            options: {
                maintainAspectRatio: false
            }
        });

        const ctxAgama = document.getElementById('agamaChart').getContext('2d');
        new Chart(ctxAgama, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_keys($agama_counts)) ?>,
                datasets: [{
                    data: agamaData,
                    backgroundColor: ['#2b9054', '#7d4b9e', '#363636', '#f8981d', '#d73b2f']
                }]
            },
            options: {
                maintainAspectRatio: false
            }
        });

        const ctxKelas = document.getElementById('kelasChart').getContext('2d');
        new Chart(ctxKelas, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_keys($kelas_counts)) ?>,
                datasets: [{
                    data: kelasData,
                    backgroundColor: ['#800000', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                maintainAspectRatio: false
            }
        });

        const ctxJurusan = document.getElementById('jurusanChart').getContext('2d');
        new Chart(ctxJurusan, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_keys($jurusan_counts)) ?>,
                datasets: [{
                    data: jurusanData,
                    backgroundColor: ['#32CD32', '#FF6384', '#E7E9ED', '#3C99DC', '#FF9F40', '#9966FF', '#FFFF00', '#FF0000']
                }]
            },
            options: {
                maintainAspectRatio: false
            }
        });

        const ctxHobi = document.getElementById('hobiChart').getContext('2d');
        new Chart(ctxHobi, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_keys($hobi_counts)) ?>,
                datasets: [{
                    data: hobiData,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            },
            options: {
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>
