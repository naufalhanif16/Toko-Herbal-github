<?php 
include 'includes/header.php';

// Ambil HANYA 4 produk terbaru/unggulan untuk ditampilkan di beranda
$stmt = $pdo->prepare("SELECT * FROM produk ORDER BY id DESC LIMIT 4");
$stmt->execute();
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section V3 (Lebih Luas & Elegan) -->
<section class="hero-section-v3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-left" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4">Kesehatan Alami, Hidup Berkualitas.</h1>
                <p class="lead mb-4">
                    Selamat datang di <?php echo htmlspecialchars($profil['nama_toko']); ?>. Kami menyediakan produk herbal pilihan yang terjamin kualitas dan khasiatnya.
                </p>
                <a href="produk.php" class="btn btn-primary btn-lg">Jelajahi Semua Produk <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
            <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left" data-aos-delay="200">
                <div class="hero-image-container">
                    <img src="assets/img/hero-image.jpg" class="img-fluid" alt="Produk Herbal Alami">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Keunggulan (Terpisah & Lebih Jelas) -->
<section class="features-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="feature-item-v2">
                    <div class="icon"><i class="bi bi-leaf"></i></div>
                    <h5 class="mt-3">100% Alami</h5>
                    <p>Terbuat dari bahan-bahan herbal pilihan terbaik dari alam.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                 <div class="feature-item-v2">
                    <div class="icon"><i class="bi bi-shield-check"></i></div>
                    <h5 class="mt-3">Terdaftar BPOM</h5>
                    <p>Semua produk kami telah teruji dan aman untuk dikonsumsi.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                 <div class="feature-item-v2">
                    <div class="icon"><i class="bi bi-truck"></i></div>
                    <h5 class="mt-3">Pengiriman Cepat</h5>
                    <p>Kami menjangkau seluruh wilayah di Indonesia dengan cepat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Produk Unggulan -->
<div class="container my-5">
    <div class="section-title text-center" data-aos="fade-up">
        <h2>Produk Unggulan Kami</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($featured_products as $product): ?>
            <div class="col" data-aos="fade-up" data-aos-delay="<?php echo $product['id'] * 50; ?>">
                <div class="card product-card h-100">
                    <a href="detail_produk.php?id=<?php echo $product['id']; ?>">
                        <img src="assets/img/<?php echo htmlspecialchars($product['gambar'] ? $product['gambar'] : 'placeholder.jpg'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['nama_produk']); ?>">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <div>
                            <p class="card-category mb-1"><?php echo htmlspecialchars($product['kategori']); ?></p>
                            <h5 class="card-title"><?php echo htmlspecialchars($product['nama_produk']); ?></h5>
                            <p class="card-price mb-3">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="mt-auto">
                            <a href="detail_produk.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>