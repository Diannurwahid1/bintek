<?php
// File: api_summary.php
// API untuk data summary card dashboard
require_once 'config.php';
header('Content-Type: application/json');
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Get date parameters
$start_date = $_GET['start_date'] ?? date('Y-m-d');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// 1. Total pasien BPJS (clear admin) dalam periode
$sql1 = "SELECT COUNT(*) as total FROM verifikasi_berkas_admin vb JOIN pasien p ON vb.pasien_id=p.id WHERE p.jenis_pasien='bpjs' AND vb.status='clear' AND tgl_verifikasi BETWEEN ? AND ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param('ss', $start_date, $end_date);
$stmt1->execute();
$res1 = $stmt1->get_result();
$row1 = $res1->fetch_assoc();

// 2. Total klaim pasien clear dalam periode
$sql2 = "SELECT COUNT(*) as total FROM klaim_pasien WHERE status='clear' AND tgl_klaim BETWEEN ? AND ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param('ss', $start_date, $end_date);
$stmt2->execute();
$res2 = $stmt2->get_result();
$row2 = $res2->fetch_assoc();

// 3. Total klaim pasien pending dalam periode
$sql3 = "SELECT COUNT(*) as total FROM klaim_pasien WHERE status='pending' AND tgl_klaim BETWEEN ? AND ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param('ss', $start_date, $end_date);
$stmt3->execute();
$res3 = $stmt3->get_result();
$row3 = $res3->fetch_assoc();

// 4. Jumlah survey kepuasan pasien dalam periode
$sql4 = "SELECT COUNT(*) as total FROM kepuasan_pasien WHERE tgl_survey BETWEEN ? AND ?";
$stmt4 = $conn->prepare($sql4);
$stmt4->bind_param('ss', $start_date, $end_date);
$stmt4->execute();
$res4 = $stmt4->get_result();
$row4 = $res4->fetch_assoc();

// Output summary
$data = [
    'bpjs_clear_today' => (int)$row1['total'],
    'klaim_clear_today' => (int)$row2['total'],
    'klaim_pending_today' => (int)$row3['total'],
    'survey_today' => (int)$row4['total']
];
echo json_encode($data);
$conn->close();
