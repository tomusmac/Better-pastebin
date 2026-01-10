<?php
require 'app/bootstrap.php';

if (!isset($_GET['id'])) {
    die('No ID provided');
}

$uid = $_GET['id'];
$paste = getPaste($pdo, $uid);

if (!$paste) {
    header("HTTP/1.0 404 Not Found");
    die($lang['error_not_found']);
}

if (isset($_POST['unlock_password'])) {
    if (password_verify($_POST['unlock_password'], $paste['password_hash'])) {
        // Correct
    } else {
         die($lang['error_invalid_password']);
    }
} elseif ($paste['password_hash']) {
     ?>
    <!DOCTYPE html>
    <form method="post">
        Password: <input type="password" name="unlock_password" autofocus>
        <button>Unlock Download</button>
    </form>
    <?php
    exit;
}

if ($paste['burn_after_reading']) {
    $stmt = $pdo->prepare("DELETE FROM pastes WHERE id = :id");
    $stmt->execute(['id' => $paste['id']]);
    if ($paste['attachment'] && file_exists($paste['attachment'])) {
        unlink($paste['attachment']);
    }
}

$filename = 'paste_' . $uid . '.' . $paste['syntax'];

// Logic for attachment download vs text download?
// If it has attachment and user clicked download link for attachment, normally just direct link works.
// But if this download.php serves text as file:

if ($paste['attachment'] && file_exists($paste['attachment'])) {
    // Actually our previous download.lib logic was serving content as file.
} 

// Let's stick to serving content as file if no attachment logic was specific in download.php (Actually I overwrote it).
// Original download.php logic:
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($paste['content']));
echo $paste['content'];
