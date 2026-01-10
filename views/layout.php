<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . $lang['title_separator'] . $lang['page_title'] : $lang['page_title']; ?></title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="/favicon.ico" type="image/png">
    
    <?php if(isset($enable_highlight) && $enable_highlight): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        hljs.highlightAll();
        hljs.initLineNumbersOnLoad();
    </script>
    <?php endif; ?>
    
    <script>
        window.lang = {
            copied: "<?php echo $lang['js_copied']; ?>",
            qrFailed: "<?php echo $lang['js_qr_failed']; ?>",
            networkError: "<?php echo $lang['js_network_error']; ?>",
            uploading: "<?php echo $lang['js_uploading']; ?>"
        };
    </script>
    <script defer src="assets/js/script.js"></script>
  </head>
  <body data-drag-msg="<?php echo isset($lang['drag_drop_text']) ? $lang['drag_drop_text'] : 'DROP FILE'; ?>">
    <div id="main-container">
      <header>
        <div class="nav-group">
          <?php if(!isset($admin_mode)): ?>
          <div class="nav-item">
            <a href="/" class="nav-button" title="<?php echo $lang['nav_home']; ?>" aria-label="home">
              <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
              </svg>
            </a>
          </div>
          <?php endif; ?>
          
          <?php if(isset($admin_mode)): ?>
             <div class="nav-title" style="font-weight: bold;"><?php echo $lang['admin_title']; ?></div>
          <?php elseif(isset($view_mode) && $view_mode && $paste['title']): ?>
            <div class="nav-title">
                <?php echo htmlspecialchars($paste['title']); ?>
                <span class="nav-title-expiration" style="font-size: 0.8em; margin-left: 10px;">
                    👁️ <?php echo $paste['views']; ?>
                </span>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="nav-group" id="nav-group-actions">
           <?php if(isset($view_mode) && $view_mode): ?>
             <div class="nav-item">
               <button id="copy-btn" class="nav-button" title="<?php echo $lang['nav_copy']; ?>" aria-label="copy">
                 <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M9 8v3a1 1 0 0 1-1 1H5m11 4h2a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1h-7a1 1 0 0 0-1 1v1m4 3v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-7.13a1 1 0 0 1 .24-.65L7.7 8.35A1 1 0 0 1 8.46 8H13a1 1 0 0 1 1 1Z"/>
                 </svg>
               </button>
             </div>
             <div class="nav-item">
               <a href="/raw/<?php echo htmlspecialchars($uid); ?>" target="_blank" class="nav-button" title="<?php echo $lang['nav_raw']; ?>" aria-label="raw">
                 <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                   <polyline points="14 2 14 8 20 8"></polyline>
                   <line x1="16" y1="13" x2="8" y2="13"></line>
                   <line x1="16" y1="17" x2="8" y2="17"></line>
                   <polyline points="10 9 9 9 8 9"></polyline>
                 </svg>
               </a>
             </div>
             <div class="nav-item">
               <a href="/download.php?id=<?php echo htmlspecialchars($uid); ?>" target="_blank" class="nav-button" title="<?php echo $lang['nav_download']; ?>" aria-label="download">
                 <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5 5-5m-5-7v12"/>
                 </svg>
               </a>
             </div>
             <div class="nav-item">
               <button id="qr-btn" class="nav-button" title="<?php echo $lang['nav_qrcode']; ?>" aria-label="qrcode">
                 <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                   <rect x="3" y="3" width="7" height="7"></rect>
                   <rect x="14" y="3" width="7" height="7"></rect>
                   <rect x="14" y="14" width="7" height="7"></rect>
                   <path d="M3 14h7v7H3z"></path>
                 </svg>
               </button>
             </div>
             <div class="nav-item">
                <a href="/" class="nav-button" title="<?php echo $lang['nav_new']; ?>" aria-label="new">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v9m-5 0H5a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2M8 9l4-5 4 5m1 8h.01"/>
                    </svg>
                </a>
             </div>
           <?php endif; ?>
           
           <?php if(isset($admin_mode)): ?>
                <a href="/" class="nav-button"><?php echo $lang['nav_home']; ?></a>
                <a href="?logout=1" class="nav-button"><?php echo $lang['nav_logout']; ?></a>
           <?php endif; ?>
        </div>
      </header>
      <main>
        <?php if (isset($error) && $error): ?>
            <div class="toast shown" style="background: var(--main-highlight-color); color: #fff; margin-bottom: 20px; position:static; transform:none;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (isset($message) && $message): ?>
            <div class="toast shown" style="background: var(--main-accent-color); color: #111; margin-bottom: 20px; position:static; transform:none;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php echo $content_html; ?>

      </main>
    </div>
    
    <div id="toast" class="toast hidden"></div>
    <div id="qr-overlay" class="hidden">
         <div id="qr-target"></div>
         <button class="button" style="width:auto; min-width:100px;" onclick="document.getElementById('qr-overlay').classList.add('hidden'); document.getElementById('qr-target').innerHTML = '';"><?php echo $lang['close_button']; ?></button>
    </div>
    <div id="upload-progress" class="hidden">
        <div style="background: var(--main-bg-color); padding: 30px; border-radius: 8px; border: 1px solid var(--main-line-number-color); width: 300px; text-align: center;">
            <div style="margin-bottom: 15px; font-weight: bold; color: var(--main-fg-color);"><?php echo $lang['js_uploading']; ?></div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" id="progress-fill" style="width: 0%;"></div>
            </div>
            <div id="progress-text" style="margin-top: 10px; font-size: 0.9em; color: var(--main-line-number-color);">0%</div>
        </div>
    </div>
  </body>
</html>
