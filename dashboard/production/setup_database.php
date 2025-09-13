<?php
// File: setup_database.php
// Script untuk setup database otomatis

require_once 'config.php';

echo "<h2>Setup Database Sistem Dashboard Kesehatan</h2>";

// Koneksi tanpa database untuk membuat database
$conn = new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "<p>✅ Database '$db_name' berhasil dibuat/sudah ada</p>";
} else {
    echo "<p>❌ Error membuat database: " . $conn->error . "</p>";
}

// Pilih database
$conn->select_db($db_name);

// Array SQL untuk membuat tabel
$tables = [
    'pasien' => "CREATE TABLE IF NOT EXISTS `pasien` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nama` varchar(100) NOT NULL,
        `no_bpjs` varchar(20) DEFAULT NULL,
        `jenis_pasien` enum('bpjs','umum') NOT NULL DEFAULT 'bpjs',
        `alamat` text,
        `telepon` varchar(15),
        `tgl_daftar` date NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `no_bpjs` (`no_bpjs`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'verifikasi_berkas_admin' => "CREATE TABLE IF NOT EXISTS `verifikasi_berkas_admin` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pasien_id` int(11) NOT NULL,
        `status` enum('clear','pending','rejected') NOT NULL DEFAULT 'pending',
        `tgl_verifikasi` date NOT NULL,
        `catatan` text,
        `petugas` varchar(50),
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `pasien_id` (`pasien_id`),
        FOREIGN KEY (`pasien_id`) REFERENCES `pasien` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'klaim_pasien' => "CREATE TABLE IF NOT EXISTS `klaim_pasien` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pasien_id` int(11) NOT NULL,
        `no_klaim` varchar(30) NOT NULL,
        `jenis_pelayanan` varchar(100),
        `biaya` decimal(12,2) DEFAULT 0.00,
        `status` enum('clear','pending','rejected') NOT NULL DEFAULT 'pending',
        `tgl_klaim` date NOT NULL,
        `tgl_proses` date DEFAULT NULL,
        `catatan` text,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `no_klaim` (`no_klaim`),
        KEY `pasien_id` (`pasien_id`),
        FOREIGN KEY (`pasien_id`) REFERENCES `pasien` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'kepuasan_pasien' => "CREATE TABLE IF NOT EXISTS `kepuasan_pasien` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pasien_id` int(11) NOT NULL,
        `skor` enum('sangat_puas','puas','cukup','kurang','tidak_puas') NOT NULL,
        `komentar` text,
        `tgl_survey` date NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `pasien_id` (`pasien_id`),
        FOREIGN KEY (`pasien_id`) REFERENCES `pasien` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

// Buat tabel-tabel
foreach ($tables as $table_name => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "<p>✅ Tabel '$table_name' berhasil dibuat</p>";
    } else {
        echo "<p>❌ Error membuat tabel '$table_name': " . $conn->error . "</p>";
    }
}

// Insert sample data
$sample_data = [
    "INSERT IGNORE INTO `pasien` (`nama`, `no_bpjs`, `jenis_pasien`, `alamat`, `telepon`, `tgl_daftar`) VALUES
    ('Ahmad Suryanto', '0001234567890', 'bpjs', 'Jl. Merdeka No. 123, Jakarta', '081234567890', '2024-01-15'),
    ('Siti Nurhaliza', '0001234567891', 'bpjs', 'Jl. Sudirman No. 456, Bandung', '081234567891', '2024-01-16'),
    ('Budi Santoso', '0001234567892', 'bpjs', 'Jl. Thamrin No. 789, Surabaya', '081234567892', '2024-01-17')",
    
    "INSERT IGNORE INTO `verifikasi_berkas_admin` (`pasien_id`, `status`, `tgl_verifikasi`, `catatan`, `petugas`) VALUES
    (1, 'clear', '2024-01-15', 'Berkas lengkap', 'Admin1'),
    (2, 'clear', '2024-01-16', 'Berkas lengkap', 'Admin1'),
    (3, 'pending', '2024-01-17', 'Menunggu dokumen tambahan', 'Admin2')",
    
    "INSERT IGNORE INTO `klaim_pasien` (`pasien_id`, `no_klaim`, `jenis_pelayanan`, `biaya`, `status`, `tgl_klaim`, `tgl_proses`, `catatan`) VALUES
    (1, 'KLM240115001', 'Rawat Jalan', 150000.00, 'clear', '2024-01-15', '2024-01-16', 'Klaim disetujui'),
    (2, 'KLM240116001', 'Rawat Inap', 2500000.00, 'clear', '2024-01-16', '2024-01-17', 'Klaim disetujui'),
    (3, 'KLM240117001', 'Rawat Jalan', 200000.00, 'pending', '2024-01-17', NULL, 'Menunggu verifikasi dokumen')",
    
    "INSERT IGNORE INTO `kepuasan_pasien` (`pasien_id`, `skor`, `komentar`, `tgl_survey`) VALUES
    (1, 'sangat_puas', 'Pelayanan sangat baik dan ramah', '2024-01-16'),
    (2, 'puas', 'Pelayanan baik, waktu tunggu agak lama', '2024-01-17'),
    (3, 'cukup', 'Pelayanan cukup baik, fasilitas perlu diperbaiki', '2024-01-18')"
];

foreach ($sample_data as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "<p>✅ Sample data berhasil diinsert</p>";
    } else {
        echo "<p>❌ Error insert data: " . $conn->error . "</p>";
    }
}

echo "<h3>Setup Database Selesai!</h3>";
echo "<p><a href='index.php'>🚀 Akses Dashboard</a></p>";
echo "<p><a href='api_summary.php'>🔍 Test API Summary</a></p>";

$conn->close();
?>
