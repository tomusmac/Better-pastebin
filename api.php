<?php
require 'app/bootstrap.php';

header('Content-Type: text/plain');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Only POST allowed');
}

if ($config['require_auth']) {
    $pass = $_POST['site_password'] ?? $_SERVER['HTTP_X_PASS'] ?? '';
    if ($pass !== $config['site_password']) {
        http_response_code(403);
        die('Forbidden: Invalid site password');
    }
}

$content = $_POST['text'] ?? '';
if (trim($content) === '') {
    die('Error: No text provided');
}

$title = $_POST['title'] ?? '';
$syntax = $_POST['extension'] ?? 'txt';
$password = $_POST['password'] ?? '';
$burn = isset($_POST['burn']) || isset($_POST['burn_after_reading']);
$expires = (int)($_POST['expires'] ?? 0);

try {
    $uid = createPaste($pdo, $content, $title, $syntax, $password, $expires, $burn);
    
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    echo "$protocol://$host/$uid";

} catch (Exception $e) {
    http_response_code(500);
    die('Error: ' . $e->getMessage());
}
