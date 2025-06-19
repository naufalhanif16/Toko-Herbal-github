<?php
include 'includes/header.php';

// Pesan flash
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);
?>

<div class="auth-wrapper">
    <div class="auth-left">
        <div class="auth-left-content text-center text-white">
            <h2 class="mb-3 fw-bold display-5">Lupa Password?</h2>
            <p class="lead">Jangan khawatir. Cukup verifikasi akun Anda dengan memasukkan email dan nomor telepon yang terdaftar.</p>
        </div>
    </div>
    <div class="auth-right">
        <div class="auth-form-container">
            <div class="text-center mb-5">
                <h3 class="fw-bold">Verifikasi Akun</h3>
                <p class="text-muted">Masukkan email dan nomor HP Anda.</p>
            </div>

            <?php if ($error_message): ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php endif; ?>

            <form action="proses_lupa_password.php" method="POST">
                <div class="form-group-icon mb-3">
                    <i class="bi bi-envelope"></i>
                    <input type="email" class="form-control" name="email" placeholder="Alamat Email Terdaftar" required>
                </div>
                <div class="form-group-icon mb-4">
                    <i class="bi bi-phone"></i>
                    <input type="tel" class="form-control" name="nomor_hp" placeholder="Nomor HP Terdaftar" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Verifikasi & Lanjutkan</button>
                </div>
            </form>
             <div class="text-center mt-4">
                <a href="auth.php" class="text-decoration-none">← Kembali ke Halaman Login</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>