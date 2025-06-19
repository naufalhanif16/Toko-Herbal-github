<?php
include '../includes/header_admin.php';

$admin_id = $_SESSION['admin_id'];

// Logika untuk memproses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses Update Profil
    if (isset($_POST['action']) && $_POST['action'] == 'update_profile') {
        $username = $_POST['username'];
        $stmt = $pdo->prepare("UPDATE admins SET username = ? WHERE id = ?");
        if ($stmt->execute([$username, $admin_id])) {
            $_SESSION['success_message'] = 'Username berhasil diperbarui!';
            $_SESSION['admin_username'] = $username;
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui username.';
        }
        header('Location: profil_admin.php');
        exit();
    }
    
    // Proses Upload Foto
    if (isset($_POST['action']) && $_POST['action'] == 'update_photo') {
        // Logika upload foto sama persis dengan di profil.php user,
        // hanya tabel dan kolomnya yang berbeda.
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
            $target_dir = "../assets/img/profil/";
            $image_name = "admin_" . time() . '_' . basename($_FILES["foto_profil"]["name"]);
            $target_file = $target_dir . $image_name;

            // Validasi file
            if (in_array(strtolower(pathinfo($target_file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
                $stmt = $pdo->prepare("SELECT foto_profil FROM admins WHERE id = ?");
                $stmt->execute([$admin_id]);
                $old_photo = $stmt->fetchColumn();

                if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
                    $stmt_update = $pdo->prepare("UPDATE admins SET foto_profil = ? WHERE id = ?");
                    if($stmt_update->execute([$image_name, $admin_id])) {
                        if ($old_photo && $old_photo != 'default-admin.png' && file_exists($target_dir . $old_photo)) {
                            unlink($target_dir . $old_photo);
                        }
                        $_SESSION['admin_foto'] = $image_name;
                        $_SESSION['success_message'] = 'Foto profil berhasil diperbarui!';
                    }
                }
            }
        }
        header('Location: profil_admin.php');
        exit();
    }
}

// Ambil data admin terbaru
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Simpan foto ke session jika belum ada
if (!isset($_SESSION['admin_foto'])) {
    $_SESSION['admin_foto'] = $admin['foto_profil'];
}

// Ambil pesan flash
$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>

<div class="content-header">
    <h1 class="h3 mb-0">Profil Admin</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card profile-card text-center">
            <div class="card-body">
                <form action="profil_admin.php" method="post" enctype="multipart/form-data" id="photoForm">
                    <input type="hidden" name="action" value="update_photo">
                    <div class="avatar-upload mx-auto">
                        <label for="photo-input">
                            <img src="../assets/img/profil/<?php echo htmlspecialchars($admin['foto_profil']); ?>" class="profile-avatar" alt="Avatar">
                            <div class="avatar-edit-overlay"><i class="bi bi-camera fs-2"></i></div>
                        </label>
                        <input type="file" id="photo-input" name="foto_profil" accept=".png, .jpg, .jpeg" class="d-none">
                    </div>
                </form>
                <h4 class="mt-3 mb-0"><?php echo htmlspecialchars($admin['username']); ?></h4>
                <p class="text-muted">Administrator</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <?php if ($error_message): ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php endif; ?>
        <?php if ($success_message): ?><div class="alert alert-success"><?php echo $success_message; ?></div><?php endif; ?>
        <div class="card profile-card">
            <div class="card-header fw-bold">Edit Profil</div>
            <div class="card-body">
                <form action="profil_admin.php" method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('photo-input').onchange = function() {
    document.getElementById('photoForm').submit();
};
</script>

<?php include '../includes/footer_admin.php'; ?>