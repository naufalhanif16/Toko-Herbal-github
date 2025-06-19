<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor_hp = $_POST['nomor_hp'];
    $password = $_POST['password'];

    // Validasi
    if (empty($nama) || empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Semua field dengan tanda bintang (*) wajib diisi.";
        header('Location: auth.php');
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Format email tidak valid.";
        header('Location: auth.php');
        exit();
    }

    // Cek duplikasi email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error_message'] = "Email sudah terdaftar. Silakan login atau gunakan email lain.";
        header('Location: auth.php');
        exit();
    }

    // Hash password & simpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, nomor_hp) VALUES (?, ?, ?, ?)");
    
    if ($stmt->execute([$nama, $email, $hashed_password, $nomor_hp])) {
        $_SESSION['success_message'] = "Registrasi berhasil! Silakan login dengan akun Anda.";
        header('Location: auth.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Registrasi gagal. Terjadi kesalahan pada server.";
        header('Location: auth.php');
        exit();
    }
}
?>