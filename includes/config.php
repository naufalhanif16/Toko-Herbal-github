<?php
// Mulai session di semua halaman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_toko_herbal');

// Buat Koneksi
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set PDO error mode ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Tidak dapat terhubung ke database. " . $e->getMessage());
}

// Fungsi helper untuk mengambil data profil website
function get_website_profile($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM profil_website WHERE id = 1");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ambil data profil sekali untuk digunakan di banyak halaman
$profil = get_website_profile($pdo);
?>