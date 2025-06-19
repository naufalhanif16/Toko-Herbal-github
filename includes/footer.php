<!-- Footer Modern -->
<footer class="footer-v2 mt-5">
    <div class="container">
        <div class="row">
            <!-- Kolom 1: Tentang Toko -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="footer-title"><?php echo htmlspecialchars($profil['nama_toko']); ?></h5>
                <p class="footer-text">Solusi kesehatan alami terpercaya untuk Anda dan keluarga. Kami menyediakan produk herbal berkualitas yang terjamin keaslian dan khasiatnya.</p>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <!-- Kolom 2: Link Cepat -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title">Navigasi</h5>
                <ul class="footer-links">
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="produk.php">Produk</a></li>
                    <li><a href="cek_status.php">Cek Pesanan</a></li>
                    <li><a href="auth.php">Login</a></li>
                </ul>
            </div>
            <!-- Kolom 3: Kontak -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-title">Kontak Kami</h5>
                <ul class="footer-contact">
                    <li><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($profil['alamat_toko']); ?></li>
                    <li><i class="bi bi-envelope-fill"></i> <?php echo htmlspecialchars($profil['email_toko']); ?></li>
                    <li><i class="bi bi-whatsapp"></i> <?php echo htmlspecialchars($profil['nomor_whatsapp']); ?></li>
                </ul>
            </div>
            <!-- Kolom 4: Newsletter -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-title">Berlangganan</h5>
                <p class="footer-text">Dapatkan info produk terbaru dan promo spesial dari kami.</p>
                <form>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email Anda">
                        <button class="btn btn-primary" type="button">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?php echo date('Y'); ?> Hak Cipta: <a href="index.php"><?php echo htmlspecialchars($profil['nama_toko']); ?></a>. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Library Animasi AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Inisialisasi AOS
    AOS.init({
        duration: 800,
        once: true
    });

    // Script untuk Navbar Transparan
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.getElementById('mainNavbar');
        if (navbar) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        }
    });
</script>

</body>
</html>