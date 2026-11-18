<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/r2.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES['file'];
$type = $_POST['type'] ?? 'general'; // 'slide', 'model', 'general'

// Validate file
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/pdf'];
if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type']);
    exit;
}

// Generate destination path
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '-' . time() . '.' . $ext;

switch ($type) {
    case 'slide':
        $destination = 'slides/' . $filename;
        break;
    case 'model':
        $destination = 'models/' . $filename;
        break;
    case 'brosur':
        $destination = 'brosur/' . $filename;
        break;
    default:
        $destination = 'media/' . $filename;
}

// Upload to R2
$result = uploadToR2($file, $destination);

if ($result['success']) {
    echo json_encode([
        'success' => true,
        'url' => $result['url'],
        'key' => $result['key'],
        'filename' => $filename,
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $result['error'] ?? 'Upload failed',
    ]);
}
