<?php
// Wajib ada di setiap halaman admin
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../auth.php"); // Arahkan ke auth.php jika belum login
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);

// Ambil foto profil admin dari session, jika tidak ada, gunakan default
$admin_photo = $_SESSION['admin_foto'] ?? 'default-admin.png';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - <?php echo htmlspecialchars($profil['nama_toko']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-brand">
                <i class="bi bi-leaf"></i>
                <span>Toko Herbal</span>
            </a>
        </div>
        
        <!-- Navigasi Sidebar dengan struktur yang benar (tanpa ul/li) -->
        <nav class="sidebar-nav">
            <a href="index.php" class="sidebar-link <?php if($current_page == 'index.php') echo 'active'; ?>">
                <i class="bi bi-grid-fill"></i><span>Dashboard</span>
            </a>
            <a href="kelola_katalog.php" class="sidebar-link <?php if(in_array($current_page, ['kelola_katalog.php', 'katalog_tambah.php', 'katalog_edit.php'])) echo 'active'; ?>">
                <i class="bi bi-journal-album"></i><span>Kelola Katalog</span>
            </a>
            <a href="kelola_pesanan.php" class="sidebar-link <?php if($current_page == 'kelola_pesanan.php') echo 'active'; ?>">
                <i class="bi bi-cart-check-fill"></i><span>Kelola Pesanan</span>
            </a>
            <a href="laporan.php" class="sidebar-link <?php if($current_page == 'laporan.php') echo 'active'; ?>">
                <i class="bi bi-file-earmark-bar-graph-fill"></i><span>Laporan</span>
            </a>
            <a href="kelola_profil.php" class="sidebar-link <?php if($current_page == 'kelola_profil.php') echo 'active'; ?>">
                <i class="bi bi-gear-fill"></i><span>Profil Website</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="profil_admin.php" class="admin-user-info">
                <img src="../assets/img/profil/<?php echo htmlspecialchars($admin_photo); ?>" alt="Admin" class="admin-avatar">
                <div class="admin-user-text">
                    <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
                    <small>Administrator</small>
                </div>
            </a>
            <a href="logout.php" class="btn-logout" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">