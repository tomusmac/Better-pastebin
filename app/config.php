<?php
$config = [
    'db_host' => 'localhost',
    'db_name' => 'projekt_paste',
    'db_user' => 'projekt_paste',
    'db_pass' => '',
    'require_auth' => true,
    'site_password' => 'view',
    'admin_password' => 'admin',
    'language' => 'pl'
];

try {
    $pdo = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", $config['db_user'], $config['db_pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Database connection failed');
}
