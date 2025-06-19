<?php 
include '../includes/header_admin.php'; 

// Data untuk Stat Cards
$total_produk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$pesanan_baru = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE status = 'request'")->fetchColumn();
$pesanan_selesai = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE status = 'selesai'")->fetchColumn();

// Data untuk Tabel Aktivitas Terbaru
$stmt_recent = $pdo->query("SELECT * FROM pesanan ORDER BY tanggal_pesanan DESC LIMIT 5");
$recent_orders = $stmt_recent->fetchAll(PDO::FETCH_ASSOC);

// Data untuk Grafik (7 hari terakhir)
$chart_labels = [];
$chart_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('d M', strtotime($date));
    $count = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE DATE(tanggal_pesanan) = '$date'")->fetchColumn();
    $chart_data[] = $count;
}

?>

<!-- Header Konten Utama -->
<div class="content-header">
    <h1 class="h3 mb-0">Dashboard</h1>
    <p class="text-muted">Selamat datang kembali, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
</div>

<!-- Stat Cards -->
<div class="row">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon bg-primary"><i class="bi bi-box-seam-fill"></i></div>
            <div class="stat-card-info">
                <h5 class="stat-card-title"><?php echo $total_produk; ?> Produk</h5>
                <p class="stat-card-text">Total Produk</p>
            </div>
            <a href="kelola_katalog.php" class="stat-card-link"><i class="bi bi-arrow-right-circle"></i></a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon bg-warning"><i class="bi bi-bell-fill"></i></div>
            <div class="stat-card-info">
                <h5 class="stat-card-title"><?php echo $pesanan_baru; ?> Pesanan</h5>
                <p class="stat-card-text">Pesanan Baru</p>
            </div>
            <a href="kelola_pesanan.php" class="stat-card-link"><i class="bi bi-arrow-right-circle"></i></a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-icon bg-success"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-card-info">
                <h5 class="stat-card-title"><?php echo $pesanan_selesai; ?> Pesanan</h5>
                <p class="stat-card-text">Pesanan Selesai</p>
            </div>
            <a href="laporan.php" class="stat-card-link"><i class="bi bi-arrow-right-circle"></i></a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Grafik Penjualan -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h6 class="m-0 fw-bold">Tren Pesanan (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                 <h6 class="m-0 fw-bold">Aktivitas Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (count($recent_orders) > 0): ?>
                        <?php foreach($recent_orders as $order): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($order['nama_pemesan']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($order['nomor_pesanan']); ?></small>
                                </div>
                                <span class="badge bg-<?php echo $order['status'] == 'request' ? 'warning' : 'info'; ?> text-dark"><?php echo ucfirst($order['status']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                         <li class="list-group-item text-center">Belum ada aktivitas.</li>
                    <?php endif; ?>
                </ul>
            </div>
             <div class="card-footer text-center">
                <a href="kelola_pesanan.php" class="text-decoration-none">Lihat Semua Pesanan →</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer_admin.php'; ?>