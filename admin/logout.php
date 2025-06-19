<?php
session_start();
$_SESSION = array();
session_destroy();
 
// Arahkan ke halaman login utama, bukan admin/login.php
header("location: ../auth.php"); 
exit;
?>