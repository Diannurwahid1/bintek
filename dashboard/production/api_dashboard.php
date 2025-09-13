<?php
// File: api_dashboard.php
// Endpoint untuk data dashboard RS: pasien BPJS harian, status klaim, kepuasan pasien

require_once 'config.php';
header('Content-Type: application/json');

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$action = $_GET['action'] ?? '';
$start_date = $_GET['start_date'] ?? date('Y-m-d');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

switch ($action) {
    case 'jumlah_pasien_bpjs':
        // Statistik jumlah pasien BPJS per hari dengan filter periode
        $sql = "SELECT tgl_verifikasi, COUNT(*) as jumlah FROM verifikasi_berkas_admin vb
                JOIN pasien p ON vb.pasien_id = p.id
                WHERE p.jenis_pasien = 'bpjs' AND vb.status = 'clear'
                AND tgl_verifikasi BETWEEN ? AND ?
                GROUP BY tgl_verifikasi ORDER BY tgl_verifikasi DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;
    case 'status_klaim':
        // Statistik klaim pasien (clear/pending per hari) dengan filter periode
        $sql = "SELECT tgl_klaim, status, COUNT(*) as jumlah FROM klaim_pasien
                WHERE tgl_klaim BETWEEN ? AND ?
                GROUP BY tgl_klaim, status ORDER BY tgl_klaim DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;
    case 'kepuasan_pasien':
        // Statistik tingkat kepuasan pasien per hari dengan filter periode
        $sql = "SELECT tgl_survey, skor, COUNT(*) as jumlah FROM kepuasan_pasien
                WHERE tgl_survey BETWEEN ? AND ?
                GROUP BY tgl_survey, skor ORDER BY tgl_survey DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;
    default:
        echo json_encode(["error" => "Invalid action"]);
}
$conn->close();
