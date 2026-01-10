<?php
session_start();
require 'app/bootstrap.php';

if (isset($_GET['logout'])) {
    unset($_SESSION['admin_auth']);
    header("Location: /");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $config['admin_password']) {
        $_SESSION['admin_auth'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = $lang['error_invalid_password'];
    }
}

if (!isset($_SESSION['admin_auth'])) {
    $auth_type = 'admin';
    require 'views/auth.php';
    exit;
}

if (isset($_GET['delete'])) {
    deletePaste($pdo, $_GET['delete']);
    header("Location: admin.php");
    exit;
}

$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'DESC';
$pastes = getAllPastes($pdo, $sort, $order);
$stats = getStats($pdo);

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= pow(1024, $pow); 
    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

require 'views/admin.php';
