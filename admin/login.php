<?php
require_once '../includes/config.php';
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("location: index.php");
    exit;
}

// Logika proses login admin dipindahkan ke sini
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && $password === $admin['password']) { // Perbandingan plain text
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_foto'] = $admin['foto_profil'];
            header("location: index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - <?php echo htmlspecialchars($profil['nama_toko']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left">
             <div class="auth-left-content text-center text-white">
                <a href="../index.php" class="text-white text-decoration-none"><h1 class="auth-brand-title mb-4"><i class="bi bi-shield-lock-fill me-2"></i>Admin Panel</h1></a>
                <h2 class="mb-3 fw-bold display-5">Area Terbatas</h2>
                <p class="lead">Silakan login untuk mengelola konten website Toko Herbal.</p>
            </div>
        </div>
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="text-center mb-5">
                    <h3 class="fw-bold">Admin Login</h3>
                    <p class="text-muted">Masukkan kredensial Anda.</p>
                </div>
                 <?php if(!empty($error)){ echo '<div class="alert alert-danger">' . $error . '</div>'; } ?>
                 <form action="login.php" method="post">
                    <div class="form-group-icon mb-3">
                        <i class="bi bi-person-badge"></i>
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>    
                    <div class="form-group-icon mb-4">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                    </div>
                </form>
                 <div class="text-center mt-5">
                    <a href="../index.php" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke Website Utama</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>