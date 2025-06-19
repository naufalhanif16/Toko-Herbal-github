<?php 
include 'includes/header.php';

// Pastikan ada ID produk yang valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="container my-5 text-center"><div class="alert alert-danger">Produk tidak ditemukan.</div><a href="produk.php" class="btn btn-primary">Kembali ke Katalog</a></div>';
    include 'includes/footer.php';
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo '<div class="container my-5 text-center"><div class="alert alert-danger">Produk tidak ditemukan.</div><a href="produk.php" class="btn btn-primary">Kembali ke Katalog</a></div>';
    include 'includes/footer.php';
    exit();
}

// Ambil 4 produk terkait dari kategori yang sama (kecuali produk ini sendiri)
$stmt_related = $pdo->prepare("SELECT * FROM produk WHERE kategori = ? AND id != ? ORDER BY RAND() LIMIT 4");
$stmt_related->execute([$product['kategori'], $id]);
$related_products = $stmt_related->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="produk.php">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['nama_produk']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5 product-detail-page">
    <div class="row">
        <!-- Kolom Kiri: Galeri Gambar Produk -->
        <div class="col-lg-6 mb-4" data-aos="fade-right">
            <div class="product-gallery">
                <img src="assets/img/<?php echo htmlspecialchars($product['gambar'] ? $product['gambar'] : 'placeholder.jpg'); ?>" class="img-fluid main-product-image" alt="<?php echo htmlspecialchars($product['nama_produk']); ?>">
            </div>
        </div>

        <!-- Kolom Kanan: Informasi Produk -->
        <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
            <div class="product-info-wrapper">
                <div>
                    <span class="badge bg-primary product-category-badge mb-2"><?php echo htmlspecialchars($product['kategori']); ?></span>
                    <h1 class="product-title"><?php echo htmlspecialchars($product['nama_produk']); ?></h1>
                    
                    <!-- Deskripsi Singkat (PENGISI RUANG KOSONG) -->
                    <p class="text-muted mb-3"><?php echo htmlspecialchars($product['deskripsi_singkat']); ?></p>
                    
                    <p class="product-price h2 text-success fw-bold mb-4">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></p>
                    
                    <!-- Tab Informasi Detail -->
                    <div class="product-info-tabs">
                        <ul class="nav nav-pills mb-3" id="pills-tab">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-manfaat">Manfaat</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-komposisi">Komposisi</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-aturan">Aturan Pakai</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-info">Info Lain</button></li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-manfaat"><p class="mb-0"><?php echo nl2br(htmlspecialchars($product['manfaat'])); ?></p></div>
                            <div class="tab-pane fade" id="pills-komposisi"><p class="mb-0"><?php echo nl2br(htmlspecialchars($product['komposisi'])); ?></p></div>
                            <div class="tab-pane fade" id="pills-aturan"><p class="mb-0"><?php echo nl2br(htmlspecialchars($product['aturan_pakai'])); ?></p></div>
                            <div class="tab-pane fade" id="pills-info"><ul class="list-unstyled mb-0"><li><strong>Kemasan:</strong> <?php echo htmlspecialchars($product['kemasan']); ?></li><li class="mt-2"><strong>Izin Edar:</strong> <?php echo htmlspecialchars($product['izin_edar']); ?></li></ul></div>
                        </div>
                    </div>
                </div>
                <!-- Tombol Pesan -->
                <div class="d-grid">
                    <a href="pesan.php?produk_id=<?php echo $product['id']; ?>" class="btn btn-primary btn-lg btn-pesan"><i class="bi bi-whatsapp me-2"></i> Pesan Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Produk Terkait -->
    <?php if (count($related_products) > 0): ?>
    <div class="row mt-5 pt-5 border-top">
        <div class="col-12">
            <div class="section-title text-center" data-aos="fade-up">
                <h2>Anda Mungkin Juga Suka</h2>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php foreach ($related_products as $related): ?>
                    <div class="col" data-aos="fade-up">
                        <div class="card product-card h-100">
                            <a href="detail_produk.php?id=<?php echo $related['id']; ?>"><img src="assets/img/<?php echo htmlspecialchars($related['gambar'] ? $related['gambar'] : 'placeholder.jpg'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($related['nama_produk']); ?>"></a>
                            <div class="card-body d-flex flex-column">
                                <div>
                                    <p class="card-category mb-1"><?php echo htmlspecialchars($related['kategori']); ?></p>
                                    <h5 class="card-title"><?php echo htmlspecialchars($related['nama_produk']); ?></h5>
                                    <p class="card-price mb-3">Rp <?php echo number_format($related['harga'], 0, ',', '.'); ?></p>
                                </div>
                                <div class="mt-auto"><a href="detail_produk.php?id=<?php echo $related['id']; ?>" class="btn btn-outline-primary w-100">Lihat Detail</a></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>