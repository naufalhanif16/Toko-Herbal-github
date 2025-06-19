<?php 
include '../includes/header_admin.php';

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_toko = $_POST['nama_toko'];
    $nomor_whatsapp = $_POST['nomor_whatsapp'];
    $alamat_toko = $_POST['alamat_toko'];
    $email_toko = $_POST['email_toko'];

    $stmt = $pdo->prepare("UPDATE profil_website SET nama_toko = ?, nomor_whatsapp = ?, alamat_toko = ?, email_toko = ? WHERE id = 1");
    
    if ($stmt->execute([$nama_toko, $nomor_whatsapp, $alamat_toko, $email_toko])) {
        $success_message = "Profil website berhasil diperbarui!";
    } else {
        $error_message = "Gagal memperbarui profil website.";
    }
}

// Ambil data profil saat ini untuk ditampilkan di form
$stmt = $pdo->prepare("SELECT * FROM profil_website WHERE id = 1");
$stmt->execute();
$profil_data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2>Kelola Profil Website</h2>
<p>Ubah informasi dasar yang akan ditampilkan di halaman depan website.</p>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>
<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label for="nama_toko" class="form-label">Nama Toko</label>
                <input type="text" class="form-control" id="nama_toko" name="nama_toko" value="<?php echo htmlspecialchars($profil_data['nama_toko']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="nomor_whatsapp" class="form-label">Nomor WhatsApp Admin (Format: 62...)</label>
                <input type="text" class="form-control" id="nomor_whatsapp" name="nomor_whatsapp" value="<?php echo htmlspecialchars($profil_data['nomor_whatsapp']); ?>" required>
            </div>
             <div class="mb-3">
                <label for="email_toko" class="form-label">Email Toko</label>
                <input type="email" class="form-control" id="email_toko" name="email_toko" value="<?php echo htmlspecialchars($profil_data['email_toko']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat_toko" class="form-label">Alamat Toko</label>
                <textarea class="form-control" id="alamat_toko" name="alamat_toko" rows="3" required><?php echo htmlspecialchars($profil_data['alamat_toko']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php include '../includes/footer_admin.php'; ?>