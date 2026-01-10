<?php
session_start();
require 'app/bootstrap.php';

$error = '';
$message = '';
$view_mode = false;
$paste = null;
$show_password_form = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['unlock_password']) && !isset($_POST['site_password'])) {
    $content = $_POST['text'] ?? '';
    $title = $_POST['title'] ?? '';
    $syntax = $_POST['extension'] ?? 'txt';
    $password = $_POST['password'] ?? '';
    $expires_val = $_POST['expires'] ?? 0;
    
    if ($expires_val === 'burn') {
        $burn = true;
        $expires = 0;
    } else {
        $burn = false;
        $expires = (int)$expires_val;
    }
    $custom_uid = trim($_POST['custom_uid'] ?? '');
    $attachment_path = null;

    if ($custom_uid && !preg_match('/^[a-zA-Z0-9_-]+$/', $custom_uid)) {
        $error = $lang['error_cli_alias'];
    } else {
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $filename = $_FILES['attachment']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            $blacklist = ['php', 'phtml', 'php3', 'php4', 'php5', 'phps', 'exe', 'bat', 'cmd', 'sh'];
            
            if (in_array($ext, $blacklist)) {
                 $error = $lang['error_file_format'];
            } else {
                 $new_filename = uniqid() . '.' . $ext;
                 $upload_dir = 'uploads/';
                 if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                 if (move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_dir . $new_filename)) {
                     $attachment_path = $upload_dir . $new_filename;
                     
                     $valid_text_exts = array_keys(getLanguages());
                     if (trim($content) === '' && in_array($ext, $valid_text_exts)) {
                         $file_content = file_get_contents($attachment_path);
                         if ($file_content !== false) {
                             $content = $file_content;
                             if ($syntax === 'txt' && $ext !== 'txt') {
                                 $syntax = $ext;
                             }
                         }
                     }
                 } else {
                     $error = $lang['error_upload_failed'];
                 }
            }
        }
    }

    if (!$error && trim($content) === '' && !$attachment_path) {
        $error = $lang['error_no_content'];
    }

    if (!$error) {
        try {
            $uid = createPaste($pdo, $content, $title, $syntax, $password, $expires, $burn, $custom_uid ?: null, $attachment_path);
            header("Location: /$uid");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = $lang['error_alias_taken'];
            } else {
                $error = $lang['error_db'] . $e->getMessage();
            }
        } catch (Exception $e) {
             $error = $lang['error_general'] . $e->getMessage();
        }
    }
}

if (isset($_GET['id'])) {
    $uid = $_GET['id'];
    $paste = getPaste($pdo, $uid);

    if (!$paste) {
        $error = $lang['error_not_found'];
    } else {
        $view_mode = true;
        incrementViews($pdo, $paste['id']);
        $paste['views']++; 
        
        if ($paste['password_hash']) {
            if (isset($_POST['unlock_password'])) {
                if (password_verify($_POST['unlock_password'], $paste['password_hash'])) {
                    
                } else {
                    $error = $lang['error_invalid_password'];
                    $show_password_form = true;
                    $view_mode = false; 
                }
            } else {
                $show_password_form = true;
                $view_mode = false;
            }
        }

        if ($view_mode) {
             if ($paste['burn_after_reading']) {
                 $stmt = $pdo->prepare("DELETE FROM pastes WHERE id = :id");
                 $stmt->execute(['id' => $paste['id']]);
                 
                 if ($paste['attachment'] && file_exists($paste['attachment'])) {
                     unlink($paste['attachment']);
                 }
                 
                 $message = $lang['message_burned'];
             }
        }
    }
}

if (!$view_mode && !isset($_SESSION['site_auth']) && $config['require_auth']) {
    $login_error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['site_password'])) {
        if ($_POST['site_password'] === $config['site_password']) {
            $_SESSION['site_auth'] = true;
            header("Location: /");
            exit;
        } else {
            $login_error = $lang['error_invalid_password'];
        }
    }
    
    $auth_type = 'site';
    require 'views/auth.php';
    exit;
} elseif ($show_password_form) {
    $auth_type = 'paste';
    require 'views/auth.php';
    exit;
} elseif ($view_mode) {
    require 'views/view_paste.php';
} else {
    $recent_pastes = getRecentPastes($pdo, 10);
    require 'views/create_paste.php';
}
