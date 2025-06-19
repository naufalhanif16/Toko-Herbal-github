<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi 'izin' dari session sekali lagi
    if (!isset($_SESSION['reset_allowed']) || $_SESSION['reset_allowed'] !== true) {
        header('Location: lupa_password.php');
        exit();
    }

    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Validasi password
    if ($password_baru !== $konfirmasi_password) {
        $_SESSION['error_message'] = "Konfirmasi password tidak cocok.";
        header('Location: reset_password.php');
        exit();
    }
    if (strlen($password_baru) < 6) {
        $_SESSION['error_message'] = "Password minimal harus 6 karakter.";
        header('Location: reset_password.php');
        exit();
    }

    // Ambil email dari session
    $email = $_SESSION['reset_email'];
    $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
    
    $stmt_update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    if ($stmt_update->execute([$hashed_password, $email])) {
        // Hapus session 'izin' setelah berhasil
        unset($_SESSION['reset_allowed']);
        unset($_SESSION['reset_email']);

        // Arahkan ke halaman login dengan pesan sukses
        $_SESSION['success_message'] = "Selamat! Password Anda telah berhasil diubah. Silakan login kembali.";
        header('Location: auth.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat memperbarui password.";
        header('Location: reset_password.php');
        exit();
    }
}
?>