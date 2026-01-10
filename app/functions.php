<?php
function generateUid($length = 6) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars_len = strlen($chars);
    $random = '';
    for ($i = 0; $i < $length; $i++) {
        $random .= $chars[rand(0, $chars_len - 1)];
    }
    return $random;
}

function getPaste($pdo, $uid) {
    $stmt = $pdo->prepare("SELECT * FROM pastes WHERE uid = :uid AND (expires_at IS NULL OR expires_at > NOW())");
    $stmt->execute(['uid' => $uid]);
    $paste = $stmt->fetch(PDO::FETCH_ASSOC);

    return $paste;
}

function incrementViews($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE pastes SET views = views + 1 WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

function getAllPastes($pdo, $sort_by = 'created_at', $order = 'DESC') {
    $allowed_cols = ['created_at', 'views', 'title', 'uid', 'syntax'];
    if (!in_array($sort_by, $allowed_cols)) $sort_by = 'created_at';
    $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
    
    $sql = "SELECT * FROM pastes ORDER BY $sort_by $order";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deletePaste($pdo, $id) {
    $stmt = $pdo->prepare("SELECT attachment FROM pastes WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row && $row['attachment'] && file_exists($row['attachment'])) {
        unlink($row['attachment']);
    }

    $stmt = $pdo->prepare("DELETE FROM pastes WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

function getStats($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as info_count, SUM(views) as total_views FROM pastes");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $upload_size = 0;
    if (is_dir('uploads')) {
        foreach (scandir('uploads') as $file) {
            if ($file !== '.' && $file !== '..') {
                $upload_size += filesize('uploads/' . $file);
            }
        }
    }
    $stats['upload_size'] = $upload_size;
    return $stats;
}

function createPaste($pdo, $content, $title, $syntax, $password, $expires, $burn, $custom_uid = null, $attachment = null) {
    if ($custom_uid) {
        $uid = $custom_uid;
    } else {
        $uid = generateUid();
    }
    
    $expires_at = null;
    if ($expires > 0) {
        $expires_at = date('Y-m-d H:i:s', time() + $expires);
    }
    
    $password_hash = null;
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    $stmt = $pdo->prepare("INSERT INTO pastes (uid, content, title, syntax, password_hash, attachment, burn_after_reading, expires_at) VALUES (:uid, :content, :title, :syntax, :password_hash, :attachment, :burn, :expires_at)");
    
    $stmt->execute([
        'uid' => $uid,
        'content' => $content,
        'title' => $title,
        'syntax' => $syntax,
        'password_hash' => $password_hash,
        'attachment' => $attachment,
        'burn' => $burn ? 1 : 0,
        'expires_at' => $expires_at
    ]);

    return $uid;
}

function getRecentPastes($pdo, $limit = 10) {
    $stmt = $pdo->prepare("SELECT uid, title, created_at, syntax FROM pastes 
                           WHERE burn_after_reading = 0 
                           AND (expires_at IS NULL OR expires_at > NOW()) 
                           ORDER BY created_at DESC LIMIT :limit");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLanguages() {
    return [
        'txt' => 'Plain Text', 'php' => 'PHP', 'js' => 'JavaScript', 'css' => 'CSS', 'html' => 'HTML',
        'sql' => 'SQL', 'python' => 'Python', 'java' => 'Java', 'cpp' => 'C++', 'csharp' => 'C#',
        'bash' => 'Bash', 'json' => 'JSON', 'xml' => 'XML', 'markdown' => 'Markdown',
        'go' => 'Go', 'rust' => 'Rust', 'typescript' => 'TypeScript', 'yaml' => 'YAML'
    ];
}
