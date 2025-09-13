<?php
// File: import_excel.php
// Backend untuk import data Excel ke database (pasien, klaim, kepuasan)

require_once '../config.php';
require_once '../vendor/autoload.php'; // pastikan sudah install phpoffice/phpspreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

function response($status, $message) {
    echo json_encode(["status" => $status, "message" => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    response('error', 'Invalid request');
}

if (!isset($_FILES['file'])) {
    response('error', 'No file uploaded');
}

$file = $_FILES['file']['tmp_name'];
$type = $_POST['type'] ?? '';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    response('error', 'Database connection failed');
}

try {
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $header = array_map('strtolower', $rows[0]);
    unset($rows[0]);

    $inserted = 0;
    foreach ($rows as $row) {
        $data = array_combine($header, $row);
        if ($type === 'pasien') {
            // Import pasien
            $sql = "INSERT INTO pasien (no_rm, nama, tgl_lahir, jenis_kelamin, jenis_pasien) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssss', $data['no_rm'], $data['nama'], $data['tgl_lahir'], $data['jenis_kelamin'], $data['jenis_pasien']);
            $stmt->execute();
            $inserted++;
        } elseif ($type === 'klaim') {
            // Import klaim
            $sql = "INSERT INTO klaim_pasien (pasien_id, tgl_klaim, status, nominal, keterangan) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issds', $data['pasien_id'], $data['tgl_klaim'], $data['status'], $data['nominal'], $data['keterangan']);
            $stmt->execute();
            $inserted++;
        } elseif ($type === 'kepuasan') {
            // Import kepuasan
            $sql = "INSERT INTO kepuasan_pasien (pasien_id, tgl_survey, skor, komentar, petugas_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('isssi', $data['pasien_id'], $data['tgl_survey'], $data['skor'], $data['komentar'], $data['petugas_id']);
            $stmt->execute();
            $inserted++;
        }
    }
    response('success', "Imported $inserted rows.");
} catch (Exception $e) {
    response('error', $e->getMessage());
}
$conn->close();
