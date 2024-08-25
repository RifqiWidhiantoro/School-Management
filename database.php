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

// Create the 'users' table first
$createTableUsers = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('siswa', 'guru', 'admin') NOT NULL
)";
$conn->query($createTableUsers);

$createTableJenisKelamin = "CREATE TABLE IF NOT EXISTS jenis_kelamin (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    gender VARCHAR(20)
)";
$conn->query($createTableJenisKelamin);

$createTableAgama = "CREATE TABLE IF NOT EXISTS agama (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    agama VARCHAR(20)
)";
$conn->query($createTableAgama);

$createTableHobi = "CREATE TABLE IF NOT EXISTS hobi (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    hobi VARCHAR(50)
)";
$conn->query($createTableHobi);

$createTableKelas = "CREATE TABLE IF NOT EXISTS kelas (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    class VARCHAR(10),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
$conn->query($createTableKelas);

$createTableJurusan = "CREATE TABLE IF NOT EXISTS jurusan (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    jurusan VARCHAR(50)
)";
$conn->query($createTableJurusan);

$createTableDataSiswa = "CREATE TABLE IF NOT EXISTS data_siswa (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(35),
    gender_id INT,
    agama_id INT,
    kelas_id INT,
    jurusan_id INT,
    user_id INT,
    image VARCHAR(255),
    FOREIGN KEY (gender_id) REFERENCES jenis_kelamin(id),
    FOREIGN KEY (agama_id) REFERENCES agama(id),
    FOREIGN KEY (kelas_id) REFERENCES kelas(id),
    FOREIGN KEY (jurusan_id) REFERENCES jurusan(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
$conn->query($createTableDataSiswa);

$createTableSiswaHobi = "CREATE TABLE IF NOT EXISTS siswa_hobi (
    siswa_id INT,
    hobi_id INT,
    PRIMARY KEY (siswa_id, hobi_id),
    FOREIGN KEY (siswa_id) REFERENCES data_siswa(id),
    FOREIGN KEY (hobi_id) REFERENCES hobi(id)
)";
$conn->query($createTableSiswaHobi);

// Create table for 'guru'
$createTableGuru = "CREATE TABLE IF NOT EXISTS guru (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(35),
    gender_id INT,
    agama_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (gender_id) REFERENCES jenis_kelamin(id),
    FOREIGN KEY (agama_id) REFERENCES agama(id)
)";
$conn->query($createTableGuru);

// Insert data into 'jenis_kelamin' before inserting into 'guru'
$insertJenisKelamin = "INSERT INTO jenis_kelamin (gender) VALUES 
    ('Laki-laki'), 
    ('Perempuan')";
$conn->query($insertJenisKelamin);

// Insert data into 'agama' before inserting into 'guru'
$insertAgama = "INSERT INTO agama (agama) VALUES 
    ('Islam'), 
    ('Kristen'), 
    ('Hindu'), 
    ('Buddha'), 
    ('Kong Ho Cu')";
$conn->query($insertAgama);

// Insert default users (Admin1 - Admin5)
$hashedPasswordAdmin1 = password_hash('AdminPass1!', PASSWORD_DEFAULT);
$hashedPasswordAdmin2 = password_hash('AdminPass2!', PASSWORD_DEFAULT);
$hashedPasswordAdmin3 = password_hash('AdminPass3!', PASSWORD_DEFAULT);
$hashedPasswordAdmin4 = password_hash('AdminPass4!', PASSWORD_DEFAULT);
$hashedPasswordAdmin5 = password_hash('AdminPass5!', PASSWORD_DEFAULT);

$insertAdmins = "INSERT INTO users (username, password, role) VALUES 
    ('admin1', '$hashedPasswordAdmin1', 'admin'),
    ('admin2', '$hashedPasswordAdmin2', 'admin'),
    ('admin3', '$hashedPasswordAdmin3', 'admin'),
    ('admin4', '$hashedPasswordAdmin4', 'admin'),
    ('admin5', '$hashedPasswordAdmin5', 'admin')";
$conn->query($insertAdmins);

// Insert data for 'guru'
$insertGuru = "INSERT INTO guru (user_id, name, gender_id, agama_id) VALUES 
    ((SELECT id FROM users WHERE username = 'guru1'), 'Guru 1', 1, 1)";
$conn->query($insertGuru);

// Insert data into 'hobi', 'kelas', and 'jurusan'
$insertHobi = "INSERT INTO hobi (hobi) VALUES 
    ('Membaca'), 
    ('Menulis'), 
    ('Olahraga'), 
    ('Bermain Musik'), 
    ('Melukis')";
$conn->query($insertHobi);

$insertKelas = "INSERT INTO kelas (class, user_id) VALUES 
    ('X', NULL), 
    ('XI', NULL), 
    ('XII', NULL)";
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
