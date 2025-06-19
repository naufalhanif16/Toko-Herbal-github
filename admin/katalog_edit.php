<?php 
include '../includes/header_admin.php';

$id = $_GET['id'];
if (empty($id)) {
    header("Location: kelola_katalog.php");
    exit();
}

$error = '';
// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $komposisi = $_POST['komposisi'];
    $manfaat = $_POST['manfaat'];
    $aturan_pakai = $_POST['aturan_pakai'];
    $harga = $_POST['harga'];
    $kemasan = $_POST['kemasan'];
    $izin_edar = $_POST['izin_edar'];
    $gambar_lama = $_POST['gambar_lama'];
    
    $gambar = $gambar_lama;
    // Cek apakah ada file gambar baru yang diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../assets/img/";
        $gambar = time() . '_' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $gambar;
        
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Hapus gambar lama jika ada
            if ($gambar_lama && file_exists($target_dir . $gambar_lama)) {
                unlink($target_dir . $gambar_lama);
            }
        } else {
            $error = "Maaf, terjadi kesalahan saat mengupload file gambar baru.";
            $gambar = $gambar_lama; // Kembalikan ke gambar lama jika gagal
        }
    }

    if (empty($error)) {
        $sql = "UPDATE produk SET nama_produk=?, kategori=?, komposisi=?, manfaat=?, aturan_pakai=?, harga=?, kemasan=?, izin_edar=?, gambar=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$nama_produk, $kategori, $komposisi, $manfaat, $aturan_pakai, $harga, $kemasan, $izin_edar, $gambar, $id])) {
            header("Location: kelola_katalog.php");
            exit();
        } else {
            $error = "Gagal memperbarui data produk.";
        }
    }
}

// Ambil data produk yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: kelola_katalog.php");
    exit();
}
?>

<h2>Edit Produk</h2>

<?php if(!empty($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($product['gambar']); ?>">
            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($product['nama_produk']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo htmlspecialchars($product['kategori']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga (Angka saja)</label>
                <input type="number" class="form-control" id="harga" name="harga" value="<?php echo htmlspecialchars($product['harga']); ?>" required>
            </div>
             <div class="mb-3">
                <label for="gambar" class="form-label">Ganti Gambar Produk (Opsional)</label>
                <input class="form-control" type="file" id="gambar" name="gambar">
                <?php if ($product['gambar']): ?>
                    <small>Gambar saat ini:</small><br>
                    <img src="../assets/img/<?php echo htmlspecialchars($product['gambar']); ?>" width="150" class="mt-2">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="komposisi" class="form-label">Komposisi</label>
                <textarea class="form-control" id="komposisi" name="komposisi" rows="3"><?php echo htmlspecialchars($product['komposisi']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="manfaat" class="form-label">Manfaat</label>
                <textarea class="form-control" id="manfaat" name="manfaat" rows="3"><?php echo htmlspecialchars($product['manfaat']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="aturan_pakai" class="form-label">Aturan Pakai</label>
                <textarea class="form-control" id="aturan_pakai" name="aturan_pakai" rows="3"><?php echo htmlspecialchars($product['aturan_pakai']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="kemasan" class="form-label">Kemasan</label>
                <input type="text" class="form-control" id="kemasan" name="kemasan" value="<?php echo htmlspecialchars($product['kemasan']); ?>">
            </div>
            <div class="mb-3">
                <label for="izin_edar" class="form-label">Izin Edar (BPOM, Halal, dll)</label>
                <input type="text" class="form-control" id="izin_edar" name="izin_edar" value="<?php echo htmlspecialchars($product['izin_edar']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Produk</button>
            <a href="kelola_katalog.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php include '../includes/footer_admin.php'; ?>