<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $nomor_hp = $_POST['nomor_hp'];

    // Cek apakah ada user dengan kombinasi email DAN nomor HP yang cocok
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ? AND nomor_hp = ?");
    $stmt->execute([$email, $nomor_hp]);
    $user = $stmt->fetch();

    if ($user) {
        // Jika cocok, buat 'token' sementara di session untuk keamanan
        // Ini untuk memastikan pengguna tidak bisa langsung ke halaman reset tanpa verifikasi
        $_SESSION['reset_email'] = $user['email'];
        $_SESSION['reset_allowed'] = true;

        // Langsung arahkan ke halaman reset password
        header('Location: reset_password.php');
        exit();
    } else {
        // Jika tidak cocok, kembalikan dengan pesan error
        $_SESSION['error_message'] = "Kombinasi Email dan Nomor HP tidak cocok atau tidak ditemukan.";
        header('Location: lupa_password.php');
        exit();
    }
}
?>