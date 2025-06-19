<?php
// Tidak perlu session, tapi perlu koneksi DB
require_once '../includes/config.php';

// Inisialisasi filter dari parameter GET
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Bangun query yang sama persis dengan di halaman laporan
$sql = "SELECT * FROM pesanan WHERE 1=1";
$params = [];

if (!empty($tgl_mulai)) {
    $sql .= " AND DATE(tanggal_pesanan) >= ?";
    $params[] = $tgl_mulai;
}
if (!empty($tgl_akhir)) {
    $sql .= " AND DATE(tanggal_pesanan) <= ?";
    $params[] = $tgl_akhir;
}
if (!empty($status_filter)) {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}
$sql .= " ORDER BY tanggal_pesanan DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data_pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- PROSES GENERATE FILE EXCEL ---

// Nama file yang akan didownload
$filename = "Laporan_Pesanan_" . date('Y-m-d') . ".xls";

// Set header untuk memberitahu browser bahwa ini adalah file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Mulai membuat tabel HTML yang akan dibaca sebagai Excel
echo '<table border="1">';
// Header tabel
echo '<tr>
        <th>No. Pesanan</th>
        <th>Nama Pemesan</th>
        <th>Nomor HP</th>
        <th>Alamat</th>
        <th>Detail Pesanan</th>
        <th>Tanggal Pesanan</th>
        <th>Status</th>
      </tr>';

// Isi data tabel
if (count($data_pesanan) > 0) {
    foreach ($data_pesanan as $row) {
        echo '<tr>
                <td>' . htmlspecialchars($row['nomor_pesanan']) . '</td>
                <td>' . htmlspecialchars($row['nama_pemesan']) . '</td>
                <td>' . htmlspecialchars($row['nomor_hp']) . '</td>
                <td>' . htmlspecialchars($row['alamat']) . '</td>
                <td>' . htmlspecialchars($row['detail_pesanan']) . '</td>
                <td>' . htmlspecialchars($row['tanggal_pesanan']) . '</td>
                <td>' . htmlspecialchars(ucfirst($row['status'])) . '</td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="7">Tidak ada data untuk diekspor.</td></tr>';
}

echo '</table>';

exit;
?>