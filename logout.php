<?php
session_start();

// Hapus semua session yang ada
session_unset();
session_destroy();

// Arahkan kembali ke halaman utama
header("Location: index.php");
exit();
?>