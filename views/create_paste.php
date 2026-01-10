<?php ob_start(); ?>
<form id="form" action="/" method="post" enctype="multipart/form-data">
  <div class="container">
    <div class="content editor-container">
      <div class="editor-line-numbers" id="editor-lines">1</div>
      <textarea id="text" name="text" autocapitalize="off" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo htmlspecialchars($lang['editor_placeholder']); ?>" autofocus><?php echo htmlspecialchars($content ?? ''); ?></textarea>
    </div>
    <div class="controls">
      <div class="controls-group">
        <div class="controls-row">
          <select class="controls-row" name="extension" id="langs" aria-label="<?php echo $lang['label_language']; ?>">
            <?php
            $languages = getLanguages();
            $selected_lang = $syntax ?? 'txt';
            foreach ($languages as $val => $label) {
                $selected = ($val === $selected_lang) ? 'selected' : '';
                echo "<option value=\"$val\" $selected>$label</option>";
            }
            ?>
          </select>
          <input class="controls-row" type="search" id="filter" placeholder="<?php echo $lang['filter_placeholder']; ?>" style="display:none;"> <!-- Hide filter as standard select handles typing -->
        </div>
      </div>
      <div class="controls-group">
        <select name="expires" id="expiration-list" aria-label="<?php echo $lang['label_expires']; ?>">
          <?php
          $expiration_options = [
              'burn' => '🔥 ' . $lang['burn_label'],
              0 => $lang['exp_never'], 
              600 => $lang['exp_10min'], 
              3600 => $lang['exp_1h'], 
              86400 => $lang['exp_1d'],
              604800 => $lang['exp_1w'], 
              2592000 => $lang['exp_1m'], 
              31536000 => $lang['exp_1y']
          ];
          $selected_exp = $burn ? 'burn' : ($expires ?? 0);
          foreach ($expiration_options as $val => $label) {
               $selected = ($val == $selected_exp) ? 'selected' : '';
               echo "<option value=\"$val\" $selected>$label</option>";
          }
          ?>
        </select>
      </div>
      <div class="controls-group">
        <div class="controls-row">
          <input type="password" name="password" id="password" placeholder="<?php echo $lang['password_placeholder']; ?>">
        </div>
        <div class="controls-row">
          <input type="text" name="title" id="title" placeholder="<?php echo $lang['title_placeholder']; ?>" value="<?php echo htmlspecialchars($title ?? ''); ?>">
        </div>
      </div>
        <div class="controls-group">
          <div class="controls-row">
            <input type="text" name="custom_uid" id="custom_uid" placeholder="<?php echo $lang['custom_uid_placeholder']; ?>" value="<?php echo htmlspecialchars($custom_uid ?? ''); ?>">
          </div>
        </div>
        <div class="controls-group">
           <label style="font-size: 0.9em; margin-bottom: 5px; display:block;"><?php echo $lang['file_label']; ?></label>
           <label for="attachment" class="file-upload-label" id="file-label">
               <?php echo $lang['file_label']; ?>
           </label>
           <input type="file" name="attachment" id="attachment">
        </div>
      <div class="controls-group">
        <button type="submit" title="<?php echo $lang['submit_button']; ?>" class="button"><?php echo $lang['submit_button']; ?></button>
      </div>
      
      <?php if (!empty($recent_pastes)): ?>
      <div class="controls-group" style="margin-top: 20px; border-top: 1px solid var(--main-line-number-color); padding-top: 20px;">
        <div style="font-weight: bold; margin-bottom: 10px; color: var(--main-accent-color);"><?php echo $lang['recent_pastes']; ?></div>
        <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.9em;">
            <?php foreach ($recent_pastes as $rp): ?>
                <div>
                    <a href="/<?php echo htmlspecialchars($rp['uid']); ?>" class="text-link">
                        <?php echo htmlspecialchars($rp['title'] ?: $lang['untitled']); ?>
                    </a>
                    <span style="color: var(--main-line-number-color); font-size: 0.8em; margin-left: 5px;">
                        [<?php echo htmlspecialchars($rp['syntax']); ?>]
                    </span>
                    <div style="color: color-mix(in srgb, var(--main-fg-color) 40%, transparent); font-size: 0.75em;">
                        <?php echo htmlspecialchars($rp['created_at']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</form>

<?php $content_html = ob_get_clean(); ?>
<?php 
// Pass vars to layout
include 'layout.php'; 
?>
