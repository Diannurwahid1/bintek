<?php
// File: import_excel_advanced.php
// Backend canggih untuk import Excel dengan validasi dan preview

require_once 'config.php';
header('Content-Type: application/json');

function response($status, $message, $data = null) {
    echo json_encode(["status" => $status, "message" => $message, "data" => $data]);
    exit;
}

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    response('error', 'Database connection failed');
}

$action = $_POST['action'] ?? '';

if ($action === 'preview') {
    // Preview data Excel sebelum import
    if (!isset($_FILES['file'])) {
        response('error', 'No file uploaded');
    }
    
    $file = $_FILES['file']['tmp_name'];
    $type = $_POST['type'] ?? '';
    
    try {
        // Baca file CSV
        $handle = fopen($file, 'r');
        $preview = [];
        $lineCount = 0;
        $headers = [];
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE && $lineCount < 11) {
            if ($lineCount === 0) {
                $headers = $data;
            } else {
                $row = [];
                foreach ($headers as $index => $header) {
                    $row[$header] = isset($data[$index]) ? $data[$index] : '';
                }
                $preview[] = $row;
            }
            $lineCount++;
        }
        fclose($handle);
        
        response('success', 'Preview ready', $preview);
        
    } catch (Exception $e) {
        response('error', 'Failed to read file: ' . $e->getMessage());
    }
    
} elseif ($action === 'import') {
    // Import data setelah konfirmasi
    if (!isset($_FILES['file'])) {
        response('error', 'No file uploaded');
    }
    
    $file = $_FILES['file']['tmp_name'];
    $type = $_POST['type'] ?? '';
    
    try {
        $handle = fopen($file, 'r');
        $headers = fgetcsv($handle, 1000, ",");
        $imported = 0;
        $errors = [];
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $rowData = array_combine($headers, $data);
            
            // Validasi dan insert berdasarkan type
            if ($type === 'pasien') {
                if (empty($rowData['no_rm']) || empty($rowData['nama'])) {
                    $errors[] = "Baris " . ($imported + 2) . ": no_rm dan nama wajib diisi";
                    continue;
                }
                
                $sql = "INSERT INTO pasien (no_rm, nama, tgl_lahir, jenis_kelamin, jenis_pasien) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssss', 
                    $rowData['no_rm'], 
                    $rowData['nama'], 
                    $rowData['tgl_lahir'], 
                    $rowData['jenis_kelamin'], 
                    $rowData['jenis_pasien']
                );
                
            } elseif ($type === 'klaim') {
                if (empty($rowData['pasien_id']) || empty($rowData['tgl_klaim'])) {
                    $errors[] = "Baris " . ($imported + 2) . ": pasien_id dan tgl_klaim wajib diisi";
                    continue;
                }
                
                $sql = "INSERT INTO klaim_pasien (pasien_id, tgl_klaim, status, nominal, keterangan) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('issds', 
                    $rowData['pasien_id'], 
                    $rowData['tgl_klaim'], 
                    $rowData['status'], 
                    $rowData['nominal'], 
                    $rowData['keterangan']
                );
                
            } elseif ($type === 'kepuasan') {
                if (empty($rowData['pasien_id']) || empty($rowData['tgl_survey'])) {
                    $errors[] = "Baris " . ($imported + 2) . ": pasien_id dan tgl_survey wajib diisi";
                    continue;
                }
                
                $sql = "INSERT INTO kepuasan_pasien (pasien_id, tgl_survey, skor, komentar, petugas_id) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('isssi', 
                    $rowData['pasien_id'], 
                    $rowData['tgl_survey'], 
                    $rowData['skor'], 
                    $rowData['komentar'], 
                    $rowData['petugas_id']
                );
            }
            
            if ($stmt->execute()) {
                $imported++;
            } else {
                $errors[] = "Baris " . ($imported + 2) . ": " . $stmt->error;
            }
        }
        fclose($handle);
        
        $message = "Berhasil import $imported data";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', array_slice($errors, 0, 3));
        }
        
        response('success', $message, ['imported' => $imported, 'errors' => $errors]);
        
    } catch (Exception $e) {
        response('error', 'Import failed: ' . $e->getMessage());
    }
}

$conn->close();
