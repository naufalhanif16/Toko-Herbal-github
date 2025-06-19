<?php 
include 'includes/header.php';

// 1. Ambil semua kategori unik dari database
$stmt_categories = $pdo->query("SELECT DISTINCT kategori FROM produk WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori ASC");
$categories = $stmt_categories->fetchAll(PDO::FETCH_COLUMN);

// 2. Ambil semua produk
$stmt_products = $pdo->query("SELECT * FROM produk ORDER BY nama_produk ASC");
$all_products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Header Halaman Produk -->
<div class="page-header">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="page-title">PRODUK KAMI</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Produk</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    
    <!-- Filter Kategori -->
    <div class="row mb-5">
        <div class="col-12 text-center" data-aos="fade-up">
            <div class="product-filters">
                <button class="filter-btn active" data-filter="*">Semua Produk</button>
                <?php foreach ($categories as $category): ?>
                    <button class="filter-btn" data-filter=".<?php echo str_replace(' ', '-', strtolower(htmlspecialchars($category))); ?>">
                        <?php echo htmlspecialchars($category); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Grid Produk -->
    <div class="row product-grid">
        <?php foreach ($all_products as $product): 
            // Buat class dari nama kategori untuk filtering
            $category_class = str_replace(' ', '-', strtolower(htmlspecialchars($product['kategori'])));
        ?>
            <div class="col-lg-3 col-md-4 col-sm-6 grid-item <?php echo $category_class; ?>">
                <div class="card product-card h-100" data-aos="fade-up">
                    <a href="detail_produk.php?id=<?php echo $product['id']; ?>">
                        <img src="assets/img/<?php echo htmlspecialchars($product['gambar'] ? $product['gambar'] : 'placeholder.jpg'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['nama_produk']); ?>">
                    </a>
                    <div class="card-body d-flex flex-column text-center">
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

<!-- Membutuhkan Library Isotope untuk filtering & layout -->
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inisialisasi Isotope pada grid produk
    var grid = document.querySelector('.product-grid');
    var iso = new Isotope(grid, {
        itemSelector: '.grid-item',
        layoutMode: 'fitRows'
    });

    // Fungsi untuk filtering saat tombol diklik
    var filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Hapus class 'active' dari semua tombol
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Tambah class 'active' ke tombol yang diklik
            this.classList.add('active');

            var filterValue = this.getAttribute('data-filter');
            iso.arrange({ filter: filterValue });
        });
    });
});
</script>

<?php 
include 'includes/footer.php'; 
?>