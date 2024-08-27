<?php
$server = 'localhost';
$username = 'root';
$password = '';

// Membuat koneksi ke MySQL tanpa memilih database
$conn = new mysqli($server, $username, $password);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hapus database jika sudah ada, lalu buat ulang database 'pelajar'
$drop = "DROP DATABASE IF EXISTS pelajar";
$conn->query($drop);

$sql = "CREATE DATABASE pelajar";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Pilih database 'pelajar'
$database = "pelajar";
mysqli_select_db($conn, $database);

// Buat tabel 'users'
$createTableUsers = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('siswa', 'guru', 'admin') NOT NULL
)";
$conn->query($createTableUsers);

// Buat tabel 'jenis_kelamin'
$createTableJenisKelamin = "CREATE TABLE IF NOT EXISTS jenis_kelamin (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    gender VARCHAR(20)
)";
$conn->query($createTableJenisKelamin);

// Buat tabel 'agama'
$createTableAgama = "CREATE TABLE IF NOT EXISTS agama (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    agama VARCHAR(20)
)";
$conn->query($createTableAgama);

// Buat tabel 'hobi'
$createTableHobi = "CREATE TABLE IF NOT EXISTS hobi (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    hobi VARCHAR(50)
)";
$conn->query($createTableHobi);

// Buat tabel 'kelas'
$createTableKelas = "CREATE TABLE IF NOT EXISTS kelas (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    class VARCHAR(10)
)";
$conn->query($createTableKelas);

// Buat tabel 'jurusan'
$createTableJurusan = "CREATE TABLE IF NOT EXISTS jurusan (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    jurusan VARCHAR(50)
)";
$conn->query($createTableJurusan);

// Buat tabel 'data_siswa'
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

// Buat tabel 'siswa_hobi'
$createTableSiswaHobi = "CREATE TABLE IF NOT EXISTS siswa_hobi (
    siswa_id INT,
    hobi_id INT,
    PRIMARY KEY (siswa_id, hobi_id),
    FOREIGN KEY (siswa_id) REFERENCES data_siswa(id),
    FOREIGN KEY (hobi_id) REFERENCES hobi(id)
)";
$conn->query($createTableSiswaHobi);

// Buat tabel 'data_guru'
$createTableDataGuru = "CREATE TABLE IF NOT EXISTS data_guru (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(35) NOT NULL,
    gender_id INT NOT NULL,
    agama_id INT NOT NULL,
    alamat VARCHAR(255),
    nomor_telepon VARCHAR(15),
    jurusan VARCHAR(255),
    image VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (gender_id) REFERENCES jenis_kelamin(id),
    FOREIGN KEY (agama_id) REFERENCES agama(id)
)";
$conn->query($createTableDataGuru);

// Masukkan data ke dalam tabel 'jenis_kelamin'
$insertJenisKelamin = "INSERT INTO jenis_kelamin (gender) VALUES 
    ('Laki-laki'), 
    ('Perempuan')";
$conn->query($insertJenisKelamin);

// Masukkan data ke dalam tabel 'agama'
$insertAgama = "INSERT INTO agama (agama) VALUES 
    ('Islam'), 
    ('Kristen'), 
    ('Hindu'), 
    ('Buddha'), 
    ('Kong Ho Cu')";
$conn->query($insertAgama);

// Masukkan data default pengguna admin ke dalam tabel 'users'
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

// Masukkan data default guru ke dalam tabel 'data_guru'
$insertGuru = "INSERT INTO data_guru (user_id, name, gender_id, agama_id) VALUES 
    ((SELECT id FROM users WHERE username = 'admin1'), 'Guru 1', 1, 1)";
$conn->query($insertGuru);

// Masukkan data ke dalam tabel 'hobi'
$insertHobi = "INSERT INTO hobi (hobi) VALUES 
    ('Membaca'), 
    ('Menulis'), 
    ('Olahraga'), 
    ('Bermain Musik'), 
    ('Melukis')";
$conn->query($insertHobi);

// Masukkan data ke dalam tabel 'kelas'
$insertKelas = "INSERT INTO kelas (class) VALUES 
    ('X'), 
    ('XI'), 
    ('XII')";
$conn->query($insertKelas);

// Masukkan data ke dalam tabel 'jurusan'
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

echo "Database berhasil dibuat beserta tabel-tabelnya.";
$conn->close();
?>
