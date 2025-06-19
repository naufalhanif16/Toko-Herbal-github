<?php 
include '../includes/header_admin.php'; 

// Inisialisasi filter
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Bangun query dasar
$sql = "SELECT * FROM pesanan WHERE 1=1";
$params = [];

// Tambahkan filter tanggal jika ada
if (!empty($tgl_mulai)) {
    $sql .= " AND DATE(tanggal_pesanan) >= ?";
    $params[] = $tgl_mulai;
}
if (!empty($tgl_akhir)) {
    $sql .= " AND DATE(tanggal_pesanan) <= ?";
    $params[] = $tgl_akhir;
}

// Tambahkan filter status jika ada
if (!empty($status_filter)) {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}

$sql .= " ORDER BY tanggal_pesanan DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$laporan_pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0">Laporan Pesanan</h1>
        <p class="text-muted">Filter laporan pesanan berdasarkan rentang tanggal dan status.</p>
    </div>
    <!-- Tombol Ekspor ke Excel -->
    <a href="export_excel.php?tgl_mulai=<?php echo $tgl_mulai; ?>&tgl_akhir=<?php echo $tgl_akhir; ?>&status=<?php echo $status_filter; ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-excel-fill me-2"></i> Ekspor ke Excel
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-funnel-fill me-2"></i> Filter Laporan
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="tgl_mulai" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" value="<?php echo htmlspecialchars($tgl_mulai); ?>">
            </div>
            <div class="col-md-4">
                <label for="tgl_akhir" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?php echo htmlspecialchars($tgl_akhir); ?>">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="request" <?php if($status_filter == 'request') echo 'selected'; ?>>Request</option>
                    <option value="approved" <?php if($status_filter == 'approved') echo 'selected'; ?>>Approved</option>
                    <option value="selesai" <?php if($status_filter == 'selesai') echo 'selected'; ?>>Selesai</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-2"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="bi bi-table me-2"></i> Hasil Laporan (Ditemukan: <?php echo count($laporan_pesanan); ?> pesanan)
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pemesan</th>
                        <th>No. HP</th>
                        <th>Detail Pesanan</th>
                        <th>Tgl Pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($laporan_pesanan) > 0): ?>
                        <?php foreach ($laporan_pesanan as $pesanan): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pesanan['nomor_pesanan']); ?></td>
                            <td><?php echo htmlspecialchars($pesanan['nama_pemesan']); ?></td>
                            <td><?php echo htmlspecialchars($pesanan['nomor_hp']); ?></td>
                            <td style="min-width: 250px;"><?php echo nl2br(htmlspecialchars($pesanan['detail_pesanan'])); ?></td>
                            <td><?php echo date('d M Y, H:i', strtotime($pesanan['tanggal_pesanan'])); ?></td>
                            <td>
                                <span class="badge rounded-pill text-bg-<?php 
                                    switch($pesanan['status']){
                                        case 'request': echo 'warning'; break;
                                        case 'approved': echo 'info'; break;
                                        case 'selesai': echo 'success'; break;
                                        default: echo 'secondary';
                                    }
                                ?>">
                                    <?php echo ucfirst($pesanan['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data yang cocok dengan filter yang diterapkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer_admin.php'; ?>