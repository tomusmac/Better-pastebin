<?php ob_start(); ?>
<?php 
    $att_ext = $paste['attachment'] ? strtolower(pathinfo($paste['attachment'], PATHINFO_EXTENSION)) : '';
    $is_media = in_array($att_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf']);
    $container_style = $is_media ? "flex-direction:column; padding: 0; gap: 0;" : "flex-direction:column;";
    $content_style = $is_media ? "flex: 1; border: none; margin: 0; padding: 0; background: #000; overflow: hidden;" : "flex: 0; margin-bottom: 20px; flex-direction: column;";
?>

<div class="container" style="<?php echo $container_style; ?>">
    <?php if ($paste['attachment']): ?>
        <div class="content" style="<?php echo $content_style; ?>">
            <?php if (in_array($att_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                <div style="display:flex; align-items:center; justify-content:center; width:100%; height:100%; background:#000;">
                    <img src="/<?php echo htmlspecialchars($paste['attachment']); ?>" alt="Zalacznik" style="max-width: 100%; max-height: 100%; object-fit: contain; border: none;">
                </div>
            <?php elseif ($att_ext === 'pdf'): ?>
                <embed src="/<?php echo htmlspecialchars($paste['attachment']); ?>" type="application/pdf" width="100%" height="100%" style="border: none;">
            <?php elseif (in_array($att_ext, ['mp4', 'webm', 'ogg'])): ?>
                <video controls style="max-width: 100%; border-radius: 4px; border: 1px solid var(--main-line-number-color);">
                    <source src="/<?php echo htmlspecialchars($paste['attachment']); ?>" type="video/<?php echo $att_ext === 'mp4' ? 'mp4' : $att_ext; ?>">
                    <?php echo $lang['browser_no_video']; ?>
                </video>
            <?php elseif (in_array($att_ext, ['mp3', 'wav', 'ogg'])): ?>
                <audio controls style="width: 100%; margin-top: 10px;">
                    <source src="/<?php echo htmlspecialchars($paste['attachment']); ?>" type="audio/<?php echo $att_ext === 'mp3' ? 'mpeg' : $att_ext; ?>">
                    <?php echo $lang['browser_no_audio']; ?>
                </audio>
            <?php else: ?>
                <div style="padding: 20px; border: 1px solid var(--main-line-number-color); border-radius: 4px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <span style="font-size: 1.5em; margin-right: 10px;">📦</span>
                        <?php echo $lang['attached_file']; ?> <strong><?php echo htmlspecialchars(basename($paste['attachment'])); ?></strong>
                    </div>
                    <a href="/<?php echo htmlspecialchars($paste['attachment']); ?>" download class="button" style="display:inline-block; width:auto; padding: 10px 20px; font-size: 0.9em; text-decoration:none;"><?php echo $lang['download_button']; ?></a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!$is_media): ?>
    <div class="content">
        <pre><code class="language-<?php echo htmlspecialchars($paste['syntax']); ?>" style="background:transparent; padding:0;"><?php echo htmlspecialchars($paste['content']); ?></code></pre>
    </div>
    <?php endif; ?>
</div>

<?php $content_html = ob_get_clean(); ?>
<?php 
$enable_highlight = !$is_media; 
include 'layout.php'; 
?>
