<?php ob_start(); ?>

<div class="admin-container">
    <div class="stats-box">
        <div class="stat-item">
            <div class="stat-value"><?php echo $stats['info_count']; ?></div>
            <div><?php echo $lang['stat_pastes']; ?></div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo $stats['total_views'] ?? 0; ?></div>
            <div><?php echo $lang['stat_views']; ?></div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo formatBytes($stats['upload_size']); ?></div>
            <div><?php echo $lang['stat_space']; ?></div>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th><a href="?sort=uid&order=<?php echo $order==='ASC'?'DESC':'ASC'; ?>"><?php echo $lang['table_uid']; ?></a></th>
                    <th><a href="?sort=title&order=<?php echo $order==='ASC'?'DESC':'ASC'; ?>"><?php echo $lang['table_title']; ?></a></th>
                    <th><?php echo $lang['table_type']; ?></th>
                    <th><a href="?sort=views&order=<?php echo $order==='ASC'?'DESC':'ASC'; ?>"><?php echo $lang['table_views']; ?></a></th>
                    <th><a href="?sort=created_at&order=<?php echo $order==='ASC'?'DESC':'ASC'; ?>"><?php echo $lang['table_date']; ?></a></th>
                    <th><?php echo $lang['table_file']; ?></th>
                    <th><?php echo $lang['table_options']; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pastes as $p): ?>
                <tr>
                    <td><a href="/<?php echo $p['uid']; ?>" target="_blank" class="text-link"><?php echo $p['uid']; ?></a></td>
                    <td><?php echo htmlspecialchars($p['title'] ?: $lang['untitled']); ?></td>
                    <td><?php echo $p['syntax']; ?></td>
                    <td><?php echo $p['views']; ?></td>
                    <td><?php echo $p['created_at']; ?></td>
                    <td><?php echo $p['attachment'] ? formatBytes(filesize($p['attachment'])) : '-'; ?></td>
                    <td>
                        <a href="?delete=<?php echo $p['id']; ?>" class="action-del" onclick="return confirm('<?php echo $lang['confirm_delete']; ?>')"><?php echo $lang['action_delete']; ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content_html = ob_get_clean(); ?>
<?php 
$admin_mode = true;
include 'layout.php'; 
?>
