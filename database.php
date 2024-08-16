<?php
$server = 'localhost';
$username = 'root';
$password = '';

$conn = new mysqli($server, $username, $password);

$drop = "DROP DATABASE IF EXISTS pelajar";
$conn->query($drop);

$sql = "CREATE DATABASE pelajar";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$database = "pelajar";
mysqli_select_db($conn, $database);

$createTableJenisKelamin = "CREATE TABLE IF NOT EXISTS jenis_kelamin (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    gender VARCHAR(20)
)";

$createTableAgama = "CREATE TABLE IF NOT EXISTS agama (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    agama VARCHAR(20)
)";

$createTableHobi = "CREATE TABLE IF NOT EXISTS hobi (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    hobi VARCHAR(50)
)";

$createTableKelas = "CREATE TABLE IF NOT EXISTS kelas (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    class VARCHAR(10)
)";

$createTableJurusan = "CREATE TABLE IF NOT EXISTS jurusan (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    jurusan VARCHAR(50)
)";

$createTableDataSiswa = "CREATE TABLE IF NOT EXISTS data_siswa (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(35),
    gender_id INT,
    agama_id INT,
    kelas_id INT,
    jurusan_id INT,
    image VARCHAR(255),
    FOREIGN KEY (gender_id) REFERENCES jenis_kelamin(id),
    FOREIGN KEY (agama_id) REFERENCES agama(id),
    FOREIGN KEY (kelas_id) REFERENCES kelas(id),
    FOREIGN KEY (jurusan_id) REFERENCES jurusan(id)
)";


$createTableSiswaHobi = "CREATE TABLE IF NOT EXISTS siswa_hobi (
    siswa_id INT,
    hobi_id INT,
    PRIMARY KEY (siswa_id, hobi_id),
    FOREIGN KEY (siswa_id) REFERENCES data_siswa(id),
    FOREIGN KEY (hobi_id) REFERENCES hobi(id)
)";

if (
    $conn->query($createTableJenisKelamin) === TRUE &&
    $conn->query($createTableAgama) === TRUE &&
    $conn->query($createTableHobi) === TRUE &&
    $conn->query($createTableKelas) === TRUE &&
    $conn->query($createTableJurusan) === TRUE &&
    $conn->query($createTableDataSiswa) === TRUE &&
    $conn->query($createTableSiswaHobi) === TRUE
) {
    echo "Tables created successfully";
} else {
    echo "Error creating tables: " . $conn->error;
}

$insertJenisKelamin = "INSERT INTO jenis_kelamin (gender) VALUES 
    ('Laki-laki'), 
    ('Perempuan')";
$conn->query($insertJenisKelamin);

$insertAgama = "INSERT INTO agama (agama) VALUES 
    ('Islam'), 
    ('Kristen'), 
    ('Hindu'), 
    ('Buddha'), 
    ('Kong Ho Cu')";
$conn->query($insertAgama);

$insertHobi = "INSERT INTO hobi (hobi) VALUES 
    ('Membaca'), 
    ('Menulis'), 
    ('Olahraga'), 
    ('Bermain Musik'), 
    ('Melukis')";
$conn->query($insertHobi);

$insertKelas = "INSERT INTO kelas (class) VALUES 
    ('X'), 
    ('XI'), 
    ('XII')";
$conn->query($insertKelas);

$insertJurusan = "INSERT INTO jurusan (jurusan) VALUES 
    ('Akuntansi'), 
    ('Busana'), 
    ('Kuliner'), 
    ('Multimedia'), 
    ('Pemasaran'), 
    ('Perkantoran'), 
    ('Perangkat Lunak'), 
    ('Teknik Komputer')";
$conn->query($insertJurusan);
?>
