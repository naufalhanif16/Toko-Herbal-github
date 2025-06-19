<?php
include 'includes/header.php';

// 1. Pastikan pengguna sudah login, jika tidak, tendang ke halaman auth
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Logika untuk memproses form saat disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A. Proses Update Profil Dasar (Nama, HP, Alamat)
    if (isset($_POST['action']) && $_POST['action'] == 'update_profile') {
        $nama = $_POST['nama'];
        $nomor_hp = $_POST['nomor_hp'];
        $alamat = $_POST['alamat'];
        
        $stmt = $pdo->prepare("UPDATE users SET nama = ?, nomor_hp = ?, alamat = ? WHERE id = ?");
        if ($stmt->execute([$nama, $nomor_hp, $alamat, $user_id])) {
            $_SESSION['success_message'] = 'Profil berhasil diperbarui!';
            $_SESSION['user_nama'] = $nama; // Update session nama juga
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui profil.';
        }
        header('Location: profil.php');
        exit();
    }

    // B. Proses Ganti Password
    if (isset($_POST['action']) && $_POST['action'] == 'change_password') {
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $konfirmasi_password = $_POST['konfirmasi_password'];

        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($password_lama, $user['password'])) {
            if ($password_baru === $konfirmasi_password) {
                if(strlen($password_baru) >= 6) {
                    $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
                    $stmt_update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    if($stmt_update->execute([$hashed_password, $user_id])) {
                         $_SESSION['success_message'] = 'Password berhasil diubah!';
                    } else {
                        $_SESSION['error_message'] = 'Gagal mengubah password.';
                    }
                } else {
                    $_SESSION['error_message'] = 'Password baru minimal harus 6 karakter.';
                }
            } else {
                $_SESSION['error_message'] = 'Konfirmasi password baru tidak cocok.';
            }
        } else {
            $_SESSION['error_message'] = 'Password lama Anda salah.';
        }
        header('Location: profil.php');
        exit();
    }
    
    // C. Proses Upload Foto Profil
    if (isset($_POST['action']) && $_POST['action'] == 'update_photo') {
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
            $target_dir = "assets/img/profil/";
            $image_name = time() . '_' . basename($_FILES["foto_profil"]["name"]);
            $target_file = $target_dir . $image_name;

            // Validasi file
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Ambil foto lama untuk dihapus
                $stmt = $pdo->prepare("SELECT foto_profil FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $old_photo = $stmt->fetchColumn();

                if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
                    // Update database
                    $stmt_update = $pdo->prepare("UPDATE users SET foto_profil = ? WHERE id = ?");
                    if($stmt_update->execute([$image_name, $user_id])) {
                         // Hapus foto lama (jika bukan default.png)
                        if ($old_photo && $old_photo != 'default.png' && file_exists($target_dir . $old_photo)) {
                            unlink($target_dir . $old_photo);
                        }
                        $_SESSION['success_message'] = 'Foto profil berhasil diperbarui!';
                    } else {
                         $_SESSION['error_message'] = 'Gagal menyimpan foto ke database.';
                    }
                } else {
                    $_SESSION['error_message'] = 'Gagal mengupload file.';
                }
            } else {
                $_SESSION['error_message'] = 'Hanya file JPG, JPEG, PNG & GIF yang diizinkan.';
            }
        } else {
            $_SESSION['error_message'] = 'Tidak ada file yang dipilih atau terjadi error.';
        }
        header('Location: profil.php');
        exit();
    }
}

// 3. Ambil data pengguna terbaru untuk ditampilkan
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil pesan flash
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="page-title">PROFIL SAYA</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <!-- Kolom Kiri: Avatar & Info Dasar -->
        <div class="col-lg-4 mb-4">
            <div class="card profile-card text-center">
                <div class="card-body">
                    <form action="profil.php" method="post" enctype="multipart/form-data" id="photoForm">
                        <input type="hidden" name="action" value="update_photo">
                        <div class="avatar-upload mx-auto">
    <label for="photo-input">
        <img src="..." class="profile-avatar" alt="Avatar">
                                <img src="assets/img/profil/<?php echo htmlspecialchars($user['foto_profil']); ?>" class="profile-avatar" alt="Avatar">
                                <div class="avatar-edit-overlay">
                                    <i class="bi bi-camera fs-2"></i>
                                </div>
                            </label>
                            <input type="file" id="photo-input" name="foto_profil" accept=".png, .jpg, .jpeg" class="d-none">
                        </div>
                    </form>
                    <h4 class="mt-3 mb-0"><?php echo htmlspecialchars($user['nama']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Edit -->
        <div class="col-lg-8">
             <!-- Menampilkan Pesan Error/Sukses -->
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <div class="card profile-card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile" type="button">Edit Profil</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button">Ubah Password</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-4">
                        <!-- Tab Edit Profil -->
                        <div class="tab-pane fade show active" id="edit-profile" role="tabpanel">
                             <form action="profil.php" method="POST">
                                <input type="hidden" name="action" value="update_profile">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email (tidak bisa diubah)</label>
                                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" name="nomor_hp" value="<?php echo htmlspecialchars($user['nomor_hp']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3"><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                        <!-- Tab Ubah Password -->
                        <div class="tab-pane fade" id="change-password" role="tabpanel">
                            <form action="profil.php" method="POST">
                                <input type="hidden" name="action" value="change_password">
                                <div class="mb-3">
                                    <label class="form-label">Password Lama</label>
                                    <input type="password" class="form-control" name="password_lama" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" name="password_baru" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" name="konfirmasi_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script untuk auto-submit form saat gambar dipilih
document.getElementById('photo-input').onchange = function() {
    document.getElementById('photoForm').submit();
};
</script>

<?php include 'includes/footer.php'; ?>