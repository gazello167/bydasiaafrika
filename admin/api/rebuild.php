<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$projectRoot = dirname(__DIR__, 2);

// Change to project directory
chdir($projectRoot);

// Run npm run build
$output = [];
$returnCode = 0;

exec('npm run build 2>&1', $output, $returnCode);

if ($returnCode === 0) {
    echo json_encode([
        'success' => true,
        'message' => 'Build completed successfully',
        'output' => implode("\n", $output),
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Build failed',
        'output' => implode("\n", $output),
    ]);
}
