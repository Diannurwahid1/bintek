# Panduan Instalasi Cepat - Sistem Dashboard Kesehatan

## Langkah-langkah Instalasi

### 1. Persiapan Environment
```bash
# Download dan install XAMPP
# Pastikan Apache dan MySQL running
```

### 2. Setup Database
1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Import file: `dummy_data_complete.sql`
3. Database `bintex` akan terbuat otomatis dengan data sample

### 3. Test Aplikasi
1. Akses: `http://localhost/bintek/dashboard/production/`
2. Dashboard harus menampilkan data dan charts

### 4. Struktur File yang Diperlukan
```
bintek/
├── dashboard/
│   └── production/
│       ├── index.php (✓ sudah ada)
│       ├── config.php (✓ sudah ada)
│       ├── api_summary.php (✓ sudah ada)
│       ├── api_dashboard.php (✓ sudah ada)
│       ├── import_excel.php (✓ sudah ada)
│       ├── penghubung.inc.php (✓ sudah ada)
│       ├── dummy_data_complete.sql (✓ baru dibuat)
│       └── template_import.xlsx (✓ sudah ada)
├── TUTORIAL_LENGKAP.md (✓ baru dibuat)
└── INSTALL_GUIDE.md (✓ file ini)
```

### 5. Dependencies yang Dibutuhkan
- Bootstrap 3.3.7
- jQuery 3.6.0
- Chart.js 2.9.4
- Font Awesome 4.7.0
- PHPSpreadsheet (untuk import Excel)

### 6. Troubleshooting Cepat
- **Database error**: Periksa config.php
- **Charts tidak muncul**: Periksa console browser
- **Import gagal**: Install PHPSpreadsheet via Composer

## Quick Start Commands
```bash
# Clone/copy project ke htdocs
# Import database
mysql -u root -p bintex < dummy_data_complete.sql

# Test akses
curl http://localhost/bintek/dashboard/production/api_summary.php
```
