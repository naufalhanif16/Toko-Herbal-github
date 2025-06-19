<?php
require_once 'includes/config.php';

// Pesan flash
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang - <?php echo htmlspecialchars($profil['nama_toko']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left">
            <div class="auth-left-content text-center text-white">
                <a href="index.php" class="text-white text-decoration-none"><h1 class="auth-brand-title mb-4"><i class="bi bi-leaf me-2"></i>Toko Herbal</h1></a>
                <h2 class="mb-3 fw-bold display-5">Sehat Berawal dari Alam.</h2>
                <p class="lead">Gabung bersama kami dan temukan solusi kesehatan herbal terbaik untuk Anda dan keluarga.</p>
            </div>
        </div>
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="text-center mb-5">
                    <h3 class="fw-bold">Selamat Datang!</h3>
                    <p class="text-muted">Silakan masuk atau buat akun baru.</p>
                </div>

                <?php if ($error_message): ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php endif; ?>
                <?php if ($success_message): ?><div class="alert alert-success"><?php echo $success_message; ?></div><?php endif; ?>
                
                <ul class="nav nav-tabs nav-fill mb-4" id="myTab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login">Login</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#register">Registrasi</button></li>
                </ul>

                <div class="tab-content">
                    <!-- Login Form (USER) -->
                    <div class="tab-pane fade show active" id="login" role="tabpanel">
                        <form action="proses_login.php" method="POST">
                            <div class="form-group-icon mb-3"><i class="bi bi-envelope"></i><input type="email" class="form-control" name="email" placeholder="Alamat Email" required></div>
                            <div class="form-group-icon mb-3"><i class="bi bi-lock"></i><input type="password" class="form-control" name="password" placeholder="Password" required></div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div><a href="lupa_password.php" class="text-decoration-none small">Lupa Password?</a></div>
                            </div>
                            <div class="d-grid"><button type="submit" class="btn btn-primary btn-lg">Login</button></div>
                        </form>
                    </div>
                    
                    <!-- Register Form (USER) -->
                    <div class="tab-pane fade" id="register" role="tabpanel">
                        <form action="proses_register.php" method="POST">
                            <div class="form-group-icon mb-3"><i class="bi bi-person"></i><input type="text" class="form-control" name="nama" placeholder="Nama Lengkap *" required></div>
                            <div class="form-group-icon mb-3"><i class="bi bi-envelope"></i><input type="email" class="form-control" name="email" placeholder="Alamat Email *" required></div>
                            <div class="form-group-icon mb-3"><i class="bi bi-phone"></i><input type="tel" class="form-control" name="nomor_hp" placeholder="Nomor HP (Opsional)"></div>
                            <div class="form-group-icon mb-3"><i class="bi bi-lock"></i><input type="password" class="form-control" name="password" placeholder="Password *" required></div>
                            <div class="d-grid"><button type="submit" class="btn btn-primary btn-lg">Buat Akun</button></div>
                        </form>
                    </div>
                </div>
                 <div class="text-center mt-5">
                    <a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>