<?php 
include '../includes/header_admin.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $komposisi = $_POST['komposisi'];
    $manfaat = $_POST['manfaat'];
    $aturan_pakai = $_POST['aturan_pakai'];
    $harga = $_POST['harga'];
    $kemasan = $_POST['kemasan'];
    $izin_edar = $_POST['izin_edar'];
    
    // Proses upload gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../assets/img/";
        $gambar = time() . '_' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $gambar;
        
        // Pindahkan file ke folder tujuan
        if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $error = "Maaf, terjadi kesalahan saat mengupload file gambar.";
            $gambar = ''; // Kosongkan jika gagal upload
        }
    }

    if (empty($error)) {
        $sql = "INSERT INTO produk (nama_produk, kategori, komposisi, manfaat, aturan_pakai, harga, kemasan, izin_edar, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$nama_produk, $kategori, $komposisi, $manfaat, $aturan_pakai, $harga, $kemasan, $izin_edar, $gambar])) {
            header("Location: kelola_katalog.php");
            exit();
        } else {
            $error = "Gagal menyimpan data produk ke database.";
        }
    }
}
?>

<h2>Tambah Produk Baru</h2>

<?php if(!empty($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="kategori" name="kategori" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga (Angka saja)</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
            </div>
             <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Produk</label>
                <input class="form-control" type="file" id="gambar" name="gambar">
            </div>
            <div class="mb-3">
                <label for="komposisi" class="form-label">Komposisi</label>
                <textarea class="form-control" id="komposisi" name="komposisi" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="manfaat" class="form-label">Manfaat</label>
                <textarea class="form-control" id="manfaat" name="manfaat" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="aturan_pakai" class="form-label">Aturan Pakai</label>
                <textarea class="form-control" id="aturan_pakai" name="aturan_pakai" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="kemasan" class="form-label">Kemasan</label>
                <input type="text" class="form-control" id="kemasan" name="kemasan">
            </div>
            <div class="mb-3">
                <label for="izin_edar" class="form-label">Izin Edar (BPOM, Halal, dll)</label>
                <input type="text" class="form-control" id="izin_edar" name="izin_edar">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Produk</button>
            <a href="kelola_katalog.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php include '../includes/footer_admin.php'; ?>