<?php
declare(strict_types=1);
include_once __DIR__ . "/../../config.php";


$allowed_name = ["statuten.pdf"];

try {
    $file = isset($_GET['file']) && is_string($_GET['file'])
        ? basename($_GET['file'])
        : null;
    $filepath = BASE_PATH . "/downloads/" . $file;

    if (!in_array($file, $allowed_name, true)) {
        http_response_code(404);
        header('Content-Type: text/html; charset=utf-8');
        require BASE_PATH . '/public/pages/error.php';
        exit;
    }

    if (!is_file($filepath)) {
        http_response_code(404);
        header('Content-Type: text/html; charset=utf-8');
        require BASE_PATH . '/public/pages/error.php';
        exit;
    }
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));

    flush();
    readfile($filepath);
    exit;
} catch (\Exception $e) {
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    require BASE_PATH . '/public/pages/error.php';
}
