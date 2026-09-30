<?php
declare(strict_types=1);
include_once __DIR__ . "/../../config.php";


$allowed_name = ["statuten.pdf"];

try {
    $file = isset($_GET['file'])
        ? basename($_GET['file'])
        : null;
    $filepath = BASE_PATH . "/downloads/" . $file;

    if (!in_array($file, $allowed_name)) {
        http_response_code(405);
        header("HTTP/1.1 405 Not Found");
        header('Content-Type: html/plain');
        echo require_once "error.php";
        exit;
    }

    if (!file_exists($filepath)) {
        http_response_code(404);
        header("HTTP/1.1 404 Not Found");
        header('Content-Type: html/plain');
        echo require_once "error.php";
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
    header("HTTP/1.1 500 Internal Server Error");
    header('Content-Type: html/plain');
    echo require_once "error.php";
}