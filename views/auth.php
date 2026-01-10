<?php ob_start(); ?>

<?php if (isset($auth_type) && $auth_type === 'site'): ?>
<div class="flex-center">
    <h2><?php echo $lang['auth_h2']; ?></h2>
    <?php if(isset($login_error) && $login_error): ?><div style="color:var(--main-highlight-color); margin-bottom:10px;"><?php echo $login_error; ?></div><?php endif; ?>
    <form action="" method="post" style="width: 300px;">
        <div class="controls-row">
            <input type="password" name="site_password" placeholder="<?php echo $lang['access_password_ph']; ?>" required autofocus>
        </div>
        <br>
        <button type="submit" class="button"><?php echo $lang['enter_button']; ?></button>
    </form>
</div>
<?php elseif (isset($auth_type) && $auth_type === 'paste'): ?>
<div class="flex-center">
    <h2><?php echo $lang['password_h2']; ?></h2>
    <form action="" method="post" style="width: 300px;">
        <div class="controls-row">
            <input type="password" name="unlock_password" placeholder="<?php echo $lang['password_unlock_ph']; ?>" required autofocus>
        </div>
        <br>
        <button type="submit" class="button"><?php echo $lang['unlock_button']; ?></button>
    </form>
</div>
<?php elseif (isset($auth_type) && $auth_type === 'admin'): ?>
<div class="flex-center">
    <h2><?php echo $lang['admin_login_h2']; ?></h2>
    <?php if(isset($error)) echo "<div style='color:var(--main-highlight-color)'>$error</div>"; ?>
    <form method="post" style="width: 300px;">
        <div class="controls-row">
            <input type="password" name="password" placeholder="<?php echo $lang['admin_pass_ph']; ?>" autofocus required>
        </div>
        <br>
        <button class="button"><?php echo $lang['admin_login_btn']; ?></button>
    </form>
    <br>
    <a href="/" class="text-link"><?php echo $lang['admin_back_link']; ?></a>
</div>
<?php endif; ?>

<?php $content_html = ob_get_clean(); ?>
<?php include 'layout.php'; ?>
