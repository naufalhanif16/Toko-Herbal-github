<?php
include 'includes/header.php';

// Validasi 'izin' dari session
// Jika tidak ada 'izin', tendang kembali ke halaman lupa password
if (!isset($_SESSION['reset_allowed']) || $_SESSION['reset_allowed'] !== true) {
    header('Location: lupa_password.php');
    exit();
}

// Pesan flash
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);
?>

<div class="auth-wrapper">
    <div class="auth-left">
        <div class="auth-left-content text-center text-white">
            <h2 class="mb-3 fw-bold display-5">Atur Password Baru</h2>
            <p class="lead">Verifikasi berhasil! Sekarang, buat password baru yang kuat dan mudah Anda ingat.</p>
        </div>
    </div>
    <div class="auth-right">
        <div class="auth-form-container">
            <div class="text-center mb-5">
                <h3 class="fw-bold">Buat Password Baru</h3>
                <p class="text-muted">Masukkan password baru Anda di bawah ini.</p>
            </div>
            
            <?php if ($error_message): ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php endif; ?>

            <form action="proses_reset_password.php" method="POST">
                <div class="form-group-icon mb-3">
                    <i class="bi bi-lock"></i>
                    <input type="password" class="form-control" name="password_baru" placeholder="Password Baru" required>
                </div>
                <div class="form-group-icon mb-4">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" class="form-control" name="konfirmasi_password" placeholder="Konfirmasi Password Baru" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Simpan Password Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>