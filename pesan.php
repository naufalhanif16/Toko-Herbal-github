<?php 
include 'includes/header.php';

// Ambil data produk yang dipilih (jika ada)
$produk_id = isset($_GET['produk_id']) ? $_GET['produk_id'] : '';
$product_name = 'Beberapa Produk'; // Teks default jika tidak ada produk spesifik

if ($produk_id) {
    $stmt = $pdo->prepare("SELECT nama_produk FROM produk WHERE id = ?");
    $stmt->execute([$produk_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if($product) {
        $product_name = $product['nama_produk'];
    }
}

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_pemesan = trim($_POST['nama']);
    $nomor_hp_pemesan = trim($_POST['nomor_hp']); // Gunakan nama variabel yang berbeda
    $alamat = trim($_POST['alamat']);
    $detail_pesanan = trim($_POST['detail_pesanan']);
    
    // Validasi sederhana
    if (!empty($nama_pemesan) && !empty($nomor_hp_pemesan) && !empty($alamat) && !empty($detail_pesanan)) {
        // Buat nomor pesanan unik
        $nomor_pesanan = "TH-" . strtoupper(uniqid());

        // Simpan pesanan ke database
        $stmt_insert = $pdo->prepare("INSERT INTO pesanan (nomor_pesanan, nama_pemesan, nomor_hp, alamat, detail_pesanan, status) VALUES (?, ?, ?, ?, ?, 'request')");
        $stmt_insert->execute([$nomor_pesanan, $nama_pemesan, $nomor_hp_pemesan, $alamat, $detail_pesanan]);

        // === BAGIAN KRITIS YANG DIPERBAIKI ===

        // Ambil nomor WhatsApp admin dari variabel $profil (yang dari database)
        $nomor_wa_tujuan = $profil['nomor_whatsapp'];

        // Buat pesan untuk WhatsApp
        $pesan_wa = "Halo " . htmlspecialchars($profil['nama_toko']) . ", saya mau pesan:\n\n";
        $pesan_wa .= "Produk: " . $detail_pesanan . "\n\n";
        $pesan_wa .= "Nama: " . $nama_pemesan . "\n";
        $pesan_wa .= "No. HP: " . $nomor_hp_pemesan . "\n";
        $pesan_wa .= "Alamat Pengiriman:\n" . $alamat . "\n\n";
        $pesan_wa .= "Nomor Pesanan saya: *" . $nomor_pesanan . "*\n\n";
        $pesan_wa .= "Mohon diinfokan total biaya dan ongkos kirimnya. Terima kasih.";

        // Encode pesan untuk URL
        $pesan_wa_encoded = urlencode($pesan_wa);
        
        // Buat link WhatsApp yang benar
        $wa_link = "https://wa.me/" . $nomor_wa_tujuan . "?text=" . $pesan_wa_encoded;

        // Redirect ke WhatsApp
        header("Location: " . $wa_link);
        exit();

        // ===================================
    } else {
        $error = "Semua field wajib diisi!";
    }
}
?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="page-title">FORM PEMESANAN</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pesan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h3 class="card-title text-center mb-4">Lengkapi Data Pengiriman</h3>
                    <p class="text-center text-muted mb-4">Setelah mengisi form ini, Anda akan diarahkan ke WhatsApp untuk mengirim pesanan langsung ke Admin kami.</p>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-lg" id="nama" name="nama" value="<?php echo isset($_SESSION['user_nama']) ? htmlspecialchars($_SESSION['user_nama']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_hp" class="form-label fw-bold">Nomor HP/WhatsApp (Aktif)</label>
                            <input type="tel" class="form-control form-control-lg" id="nomor_hp" name="nomor_hp" placeholder="Contoh: 08123456789" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold">Alamat Lengkap Pengiriman</label>
                            <textarea class="form-control form-control-lg" id="alamat" name="alamat" rows="4" placeholder="Contoh: Jl. Sehat Selalu No. 123, RT 01/RW 02, Kel. Bahagia, Kec. Sentosa, Kota Damai, 12345" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="detail_pesanan" class="form-label fw-bold">Produk yang Dipesan</label>
                            <textarea class="form-control form-control-lg" id="detail_pesanan" name="detail_pesanan" rows="3" required><?php echo htmlspecialchars($product_name); ?> (1 Pcs)</textarea>
                            <small class="form-text text-muted">Anda bisa mengubah atau menambahkan produk lain di sini.</small>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg btn-pesan">
                                <i class="bi bi-whatsapp me-2"></i> Kirim Pesanan ke Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>