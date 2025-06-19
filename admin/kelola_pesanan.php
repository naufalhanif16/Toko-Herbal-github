<?php 
include '../includes/header_admin.php'; 

// Proses update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    
    echo "<div class='alert alert-success'>Status pesanan berhasil diperbarui.</div>";
}

// Proses hapus pesanan
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM pesanan WHERE id = ?");
    $stmt->execute([$id_to_delete]);
    echo "<div class='alert alert-danger'>Pesanan berhasil dihapus.</div>";
}

// Ambil semua data pesanan, yang terbaru di atas
$stmt = $pdo->prepare("SELECT * FROM pesanan ORDER BY tanggal_pesanan DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Kelola Pesanan</h2>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No. Pesanan</th>
                <th>Pemesan</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th>Detail Pesanan</th>
                <th>Tgl Pesan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['nomor_pesanan']); ?></td>
                    <td><?php echo htmlspecialchars($order['nama_pemesan']); ?></td>
                    <td><a href="https://wa.me/<?php echo htmlspecialchars($order['nomor_hp']); ?>" target="_blank"><?php echo htmlspecialchars($order['nomor_hp']); ?></a></td>
                    <td><?php echo htmlspecialchars($order['alamat']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($order['detail_pesanan'])); ?></td>
                    <td><?php echo date('d M Y H:i', strtotime($order['tanggal_pesanan'])); ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status" class="form-select form-select-sm">
                                <option value="request" <?php if($order['status'] == 'request') echo 'selected'; ?>>Request</option>
                                <option value="approved" <?php if($order['status'] == 'approved') echo 'selected'; ?>>Approved</option>
                                <option value="selesai" <?php if($order['status'] == 'selesai') echo 'selected'; ?>>Selesai</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-primary ms-1">U</button>
                        </form>
                    </td>
                     <td>
                        <a href="kelola_pesanan.php?action=delete&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Belum ada pesanan masuk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<?php include '../includes/footer_admin.php'; ?>