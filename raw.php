<?php
require 'app/bootstrap.php';

if (!isset($_GET['id'])) {
    die('No ID provided');
}

$uid = $_GET['id'];
$paste = getPaste($pdo, $uid);

if (!$paste) {
    header("HTTP/1.0 404 Not Found");
    die('Paste not found');
}

if (isset($_POST['unlock_password'])) {
    if (password_verify($_POST['unlock_password'], $paste['password_hash'])) {
        // Correct password
    } else {
         die('Invalid password');
    }
} elseif ($paste['password_hash']) {
    // Show simple raw password form
    ?>
    <!DOCTYPE html>
    <form method="post">
        Password: <input type="password" name="unlock_password" autofocus>
        <button>Unlock Raw</button>
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

header('Content-Type: text/plain');
echo $paste['content'];
