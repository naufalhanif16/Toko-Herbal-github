<?php 
include 'includes/header.php';

$nomor_pesanan = '';
$result = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['nomor_pesanan'])) {
    $nomor_pesanan = trim($_POST['nomor_pesanan'] ?? $_GET['nomor_pesanan']);
    if (!empty($nomor_pesanan)) {
        $stmt = $pdo->prepare("SELECT * FROM pesanan WHERE nomor_pesanan = ?");
        $stmt->execute([$nomor_pesanan]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            $error = "Nomor pesanan **" . htmlspecialchars($nomor_pesanan) . "** tidak ditemukan. Mohon periksa kembali nomor pesanan Anda.";
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $error = "Silakan masukkan nomor pesanan Anda.";
    }
}
?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cek Status Pesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <img src="assets/img/tracking.svg" alt="Lacak Pesanan" class="mb-4" style="max-width: 250px;">
            <h1 class="fw-bold">Lacak Pesanan Anda</h1>
            <p class="lead text-muted mb-4">Masukkan nomor pesanan yang Anda dapatkan saat melakukan pemesanan melalui WhatsApp.</p>
            
            <form method="POST" class="mx-auto" style="max-width: 500px;">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" name="nomor_pesanan" placeholder="Contoh: TH-655A123BC" value="<?php echo htmlspecialchars($nomor_pesanan); ?>" required>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cek</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($result): ?>
    <div class="row justify-content-center mt-5">
        <div class="col-lg-10">
            <div class="card shadow-sm status-card">
                <div class="card-header text-center bg-light">
                    <h5 class="mb-0">Detail Pesanan: <strong><?php echo htmlspecialchars($result['nomor_pesanan']); ?></strong></h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Nama Pemesan:</strong><br>
                            <?php echo htmlspecialchars($result['nama_pemesan']); ?>
                        </div>
                         <div class="col-md-6 text-md-end">
                            <strong>Tanggal Pesan:</strong><br>
                            <?php echo date('d F Y, H:i', strtotime($result['tanggal_pesanan'])); ?>
                        </div>
                    </div>
                    
                    <!-- Timeline Status -->
                    <h6 class="fw-bold mb-3">Status Pengiriman:</h6>
                    <div class="status-timeline">
                        <?php
                            $status = $result['status'];
                            $is_request_active = true;
                            $is_approved_active = ($status == 'approved' || $status == 'selesai');
                            $is_selesai_active = ($status == 'selesai');
                        ?>
                        <div class="timeline-item <?php echo $is_request_active ? 'active' : ''; ?>">
                            <div class="timeline-icon"><i class="bi bi-receipt"></i></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pesanan Dibuat</h6>
                                <small class="text-muted">Kami telah menerima pesanan Anda.</small>
                            </div>
                        </div>
                         <div class="timeline-item <?php echo $is_approved_active ? 'active' : ''; ?>">
                            <div class="timeline-icon"><i class="bi bi-box-seam"></i></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pesanan Diproses</h6>
                                <small class="text-muted">Pesanan Anda sedang disiapkan oleh tim kami.</small>
                            </div>
                        </div>
                         <div class="timeline-item <?php echo $is_selesai_active ? 'active' : ''; ?>">
                            <div class="timeline-icon"><i class="bi bi-truck"></i></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Selesai / Dikirim</h6>
                                <small class="text-muted">Pesanan Anda telah selesai diproses atau dalam perjalanan.</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <p class="text-center text-muted mb-0">Jika ada pertanyaan lebih lanjut, silakan hubungi admin melalui WhatsApp.</p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>