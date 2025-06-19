<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Proses login user (hanya user)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nama'] = $user['nama'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_foto'] = $user['foto_profil']; 
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Email atau password Anda salah.";
        header('Location: auth.php');
        exit();
    }
}
?>