<?php 
include '../includes/header_admin.php';

// Proses hapus produk
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];

    // 1. Ambil nama file gambar untuk dihapus dari folder
    $stmt_select = $pdo->prepare("SELECT gambar FROM produk WHERE id = ?");
    $stmt_select->execute([$id_to_delete]);
    $product = $stmt_select->fetch(PDO::FETCH_ASSOC);
    $image_filename = $product['gambar'];

    // 2. Hapus data dari database
    $stmt_delete = $pdo->prepare("DELETE FROM produk WHERE id = ?");
    if($stmt_delete->execute([$id_to_delete])) {
        // 3. Hapus file gambar jika ada
        if ($image_filename && file_exists("../assets/img/" . $image_filename)) {
            unlink("../assets/img/" . $image_filename);
        }
        $message = "<div class='alert alert-success'>Produk berhasil dihapus.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal menghapus produk.</div>";
    }
}

// Ambil semua data produk
$stmt = $pdo->prepare("SELECT * FROM produk ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Kelola Katalog Produk</h2>
<a href="katalog_tambah.php" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Tambah Produk Baru</a>

<?php if (isset($message)) echo $message; ?>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><img src="../assets/img/<?php echo htmlspecialchars($product['gambar']); ?>" alt="" width="80"></td>
                    <td><?php echo htmlspecialchars($product['nama_produk']); ?></td>
                    <td><?php echo htmlspecialchars($product['kategori']); ?></td>
                    <td>Rp <?php echo number_format($product['harga']); ?></td>
                    <td>
                        <a href="katalog_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                        <a href="kelola_katalog.php?action=delete&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');"><i class="bi bi-trash"></i> Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer_admin.php'; ?>