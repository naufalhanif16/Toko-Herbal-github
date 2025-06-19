<?php require_once 'config.php'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($profil['nama_toko']); ?> - Solusi Herbal Anda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
</head>
<body>

<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><strong><?php echo htmlspecialchars($profil['nama_toko']); ?></strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
           
<ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item">
        <a class="nav-link <?php if($current_page == 'index.php') echo 'active'; ?>" href="index.php">Beranda</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if($current_page == 'produk.php' || $current_page == 'detail_produk.php') echo 'active'; ?>" href="produk.php">Produk</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if($current_page == 'cek_status.php') echo 'active'; ?>" href="cek_status.php">Cek Status Pesanan</a>
    </li>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Menu jika PENGGUNA SUDAH LOGIN -->
        <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="assets/img/profil/<?php echo htmlspecialchars($_SESSION['user_foto']); ?>" alt="Avatar" class="navbar-avatar me-2">
        Halo, <?php echo htmlspecialchars($_SESSION['user_nama']); ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
    </ul>
</li>
    <?php else: ?>
        <!-- Menu jika PENGGUNA BELUM LOGIN (HANYA SATU) -->
        <li class="nav-item">
            <a class="btn btn-outline-primary ms-lg-3" href="auth.php">Login / Register</a>
        </li>
    <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

