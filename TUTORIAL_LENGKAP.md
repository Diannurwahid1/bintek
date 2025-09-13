# Tutorial Lengkap: Sistem Dashboard Manajemen Kesehatan BPJS

## Daftar Isi
1. [Persiapan Environment](#1-persiapan-environment)
2. [Setup Database](#2-setup-database)
3. [Struktur Project](#3-struktur-project)
4. [Konfigurasi Database](#4-konfigurasi-database)
5. [Membuat API Backend](#5-membuat-api-backend)
6. [Membuat Dashboard Frontend](#6-membuat-dashboard-frontend)
7. [Fitur Import Excel](#7-fitur-import-excel)
8. [Testing Lokal](#8-testing-lokal)
9. [Deployment ke Server](#9-deployment-ke-server)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. Persiapan Environment

### 1.1 Download dan Install XAMPP
1. Download XAMPP dari https://www.apachefriends.org/
2. Install XAMPP dengan komponen:
   - Apache
   - MySQL
   - PHP
   - phpMyAdmin

### 1.2 Menjalankan XAMPP
1. Buka XAMPP Control Panel
2. Start Apache dan MySQL
3. Pastikan status keduanya "Running" (hijau)

### 1.3 Test Environment
1. Buka browser, akses `http://localhost`
2. Harus muncul halaman welcome XAMPP
3. Akses `http://localhost/phpmyadmin` untuk test database

---

## 2. Setup Database

### 2.1 Membuat Database
1. Buka phpMyAdmin (`http://localhost/phpmyadmin`)
2. Klik "New" untuk membuat database baru
3. Nama database: `bintex`
4. Collation: `utf8mb4_general_ci`
5. Klik "Create"

### 2.2 Membuat Tabel-tabel

#### Tabel Pasien
```sql
CREATE TABLE `pasien` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Tabel Verifikasi Berkas Admin
```sql
CREATE TABLE `verifikasi_berkas_admin` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Tabel Klaim Pasien
```sql
CREATE TABLE `klaim_pasien` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Tabel Kepuasan Pasien
```sql
CREATE TABLE `kepuasan_pasien` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pasien_id` int(11) NOT NULL,
  `skor` enum('sangat_puas','puas','cukup','kurang','tidak_puas') NOT NULL,
  `komentar` text,
  `tgl_survey` date NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pasien_id` (`pasien_id`),
  FOREIGN KEY (`pasien_id`) REFERENCES `pasien` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 3. Struktur Project

### 3.1 Membuat Folder Project
1. Buka folder `C:\xampp\htdocs\`
2. Buat folder baru: `bintek`
3. Di dalam folder `bintek`, buat struktur:
```
bintek/
├── dashboard/
│   ├── production/
│   ├── vendor/
│   │   ├── bootstrap/
│   │   ├── jquery/
│   │   ├── chart.js/
│   │   └── font-awesome/
│   └── dist/
│       ├── css/
│       └── js/
└── data/
```

### 3.2 Download Dependencies
1. **Bootstrap 3.3.7**: Download dari https://getbootstrap.com/docs/3.3/
2. **jQuery 3.6.0**: Download dari https://jquery.com/
3. **Chart.js**: Download dari https://www.chartjs.org/
4. **Font Awesome**: Download dari https://fontawesome.com/
5. **SB Admin 2 Theme**: Download dari https://startbootstrap.com/theme/sb-admin-2

Ekstrak semua ke folder `vendor/` yang sesuai.

---

## 4. Konfigurasi Database

### 4.1 File config.php
Buat file `dashboard/production/config.php`:

```php
<?php
// Konfigurasi koneksi database untuk aplikasi
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'bintex';

// Test koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
```

### 4.2 File penghubung.inc.php
Buat file `dashboard/production/penghubung.inc.php`:

```php
<?php
$APLICATION_ROOT = "../";
$ROOT = "../";
$MASTER_APP = "../../";
$LIB = "../lib/";
$LAY = "../layouts/";
$CS = "../lib/css/";
$js = "../lib/javascript";
?>
```

---

## 5. Membuat API Backend

### 5.1 API Summary (api_summary.php)
File ini sudah ada di sistem Anda. Ini menyediakan data summary untuk dashboard cards.

### 5.2 API Dashboard (api_dashboard.php)
Buat file `dashboard/production/api_dashboard.php`:

```php
<?php
require_once 'config.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch($action) {
    case 'jumlah_pasien_bpjs':
        $sql = "SELECT DATE(vb.tgl_verifikasi) as tgl_verifikasi, COUNT(*) as jumlah 
                FROM verifikasi_berkas_admin vb 
                JOIN pasien p ON vb.pasien_id = p.id 
                WHERE p.jenis_pasien = 'bpjs' AND vb.status = 'clear'
                AND vb.tgl_verifikasi >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(vb.tgl_verifikasi)
                ORDER BY tgl_verifikasi";
        break;
        
    case 'status_klaim':
        $sql = "SELECT DATE(tgl_klaim) as tgl_klaim, status, COUNT(*) as jumlah 
                FROM klaim_pasien 
                WHERE tgl_klaim >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(tgl_klaim), status
                ORDER BY tgl_klaim";
        break;
        
    case 'kepuasan_pasien':
        $sql = "SELECT DATE(tgl_survey) as tgl_survey, skor, COUNT(*) as jumlah 
                FROM kepuasan_pasien 
                WHERE tgl_survey >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(tgl_survey), skor
                ORDER BY tgl_survey";
        break;
        
    default:
        echo json_encode(['error' => 'Action tidak valid']);
        exit;
}

$result = $conn->query($sql);
$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
```

---

## 6. Membuat Dashboard Frontend

### 6.1 File index.php
File dashboard utama sudah ada di sistem Anda dengan fitur:
- Summary cards
- Charts untuk visualisasi data
- Form import Excel

### 6.2 Customisasi Dashboard
Anda bisa memodifikasi tampilan dengan mengubah:
- Warna tema di CSS
- Layout cards
- Jenis chart yang digunakan

---

## 7. Fitur Import Excel

### 7.1 Install PHPSpreadsheet
1. Download PHPSpreadsheet dari https://github.com/PHPOffice/PhpSpreadsheet
2. Ekstrak ke folder `vendor/phpspreadsheet/`

### 7.2 File import_excel.php
Buat file `dashboard/production/import_excel.php`:

```php
<?php
require_once 'config.php';
require_once '../vendor/phpspreadsheet/src/Bootstrap.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan']);
    exit;
}

$type = $_POST['type'] ?? '';
$uploadedFile = $_FILES['file'] ?? null;

if (!$uploadedFile || $uploadedFile['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'File upload gagal']);
    exit;
}

try {
    $spreadsheet = IOFactory::load($uploadedFile['tmp_name']);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    // Skip header row
    array_shift($rows);
    
    $success = 0;
    $errors = 0;
    
    foreach ($rows as $row) {
        if (empty(array_filter($row))) continue; // Skip empty rows
        
        switch($type) {
            case 'pasien':
                $sql = "INSERT INTO pasien (nama, no_bpjs, jenis_pasien, alamat, telepon, tgl_daftar) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssssss', $row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
                break;
                
            case 'klaim':
                $sql = "INSERT INTO klaim_pasien (pasien_id, no_klaim, jenis_pelayanan, biaya, status, tgl_klaim) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('issdss', $row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
                break;
                
            case 'kepuasan':
                $sql = "INSERT INTO kepuasan_pasien (pasien_id, skor, komentar, tgl_survey) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('isss', $row[0], $row[1], $row[2], $row[3]);
                break;
        }
        
        if ($stmt->execute()) {
            $success++;
        } else {
            $errors++;
        }
    }
    
    echo json_encode([
        'status' => 'success', 
        'message' => "Import berhasil: $success data, gagal: $errors data"
    ]);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
?>
```

---

## 8. Testing Lokal

### 8.1 Test Database Connection
1. Akses `http://localhost/bintek/dashboard/production/config.php`
2. Pastikan tidak ada error koneksi

### 8.2 Test API
1. Test API Summary: `http://localhost/bintek/dashboard/production/api_summary.php`
2. Test API Dashboard: `http://localhost/bintek/dashboard/production/api_dashboard.php?action=jumlah_pasien_bpjs`

### 8.3 Test Dashboard
1. Akses `http://localhost/bintek/dashboard/production/`
2. Pastikan dashboard loading dengan benar
3. Test semua fitur termasuk charts dan import

---

## 9. Deployment ke Server

### 9.1 Persiapan File
1. Compress seluruh folder `bintek` menjadi ZIP
2. Pastikan semua file PHP, CSS, JS sudah termasuk
3. Backup database dengan export SQL

### 9.2 Upload via cPanel File Manager
1. Login ke cPanel hosting Anda
2. Buka File Manager
3. Navigasi ke folder `public_html`
4. Upload file ZIP dan extract
5. Set permission folder ke 755, file ke 644

### 9.3 Upload via FTP
1. Gunakan FileZilla atau FTP client lainnya
2. Connect ke server dengan kredensial FTP
3. Upload folder `bintek` ke `public_html/`
4. Pastikan struktur folder tetap sama

### 9.4 Setup Database di Server
1. Buka cPanel → MySQL Databases
2. Buat database baru (misal: `username_bintex`)
3. Buat user database dan assign ke database
4. Import file SQL backup via phpMyAdmin

### 9.5 Update Konfigurasi
Edit file `config.php` di server:
```php
<?php
$db_host = 'localhost';
$db_user = 'username_dbuser';  // sesuaikan
$db_pass = 'password_database'; // sesuaikan  
$db_name = 'username_bintex';   // sesuaikan
?>
```

---

## 10. Troubleshooting

### 10.1 Error Database Connection
- Periksa kredensial database di `config.php`
- Pastikan MySQL service running
- Cek nama database sudah benar

### 10.2 Error 404 Not Found
- Periksa struktur folder
- Pastikan file ada di lokasi yang benar
- Cek permission file/folder

### 10.3 Charts Tidak Muncul
- Periksa console browser untuk error JavaScript
- Pastikan Chart.js library sudah ter-load
- Cek API endpoint mengembalikan data JSON valid

### 10.4 Import Excel Gagal
- Pastikan PHPSpreadsheet ter-install
- Cek format file Excel sesuai template
- Periksa permission folder upload

### 10.5 Error di Server Production
- Enable error reporting sementara untuk debugging
- Cek error log di cPanel
- Pastikan PHP version compatibility

---

## Kesimpulan

Tutorial ini memberikan panduan lengkap untuk membangun sistem dashboard manajemen kesehatan dari nol hingga deployment. Sistem ini mencakup:

- ✅ Manajemen pasien BPJS
- ✅ Tracking klaim dan verifikasi
- ✅ Survey kepuasan pasien  
- ✅ Visualisasi data dengan charts
- ✅ Import data dari Excel
- ✅ Dashboard responsif

Untuk pengembangan lebih lanjut, Anda bisa menambahkan fitur seperti:
- Sistem login/authentication
- Export laporan PDF
- Notifikasi real-time
- Mobile app integration

**Selamat mencoba!** 🚀
