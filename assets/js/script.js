document.addEventListener('DOMContentLoaded', () => {
    const copyBtn = document.getElementById('copy-btn');
    if (copyBtn) {
        copyBtn.addEventListener('click', async () => {
            const codeBlock = document.querySelector('code');
            if (codeBlock) {
                await navigator.clipboard.writeText(codeBlock.textContent);
                showToast(window.lang ? window.lang.copied : 'Copied!');
            }
        });
    }

    window.showToast = function(message) {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.textContent = message;
            toast.classList.remove('hidden');
            toast.classList.add('shown');
            setTimeout(() => {
                toast.classList.remove('shown');
                toast.classList.add('hidden');
            }, 3000);
        }
    };

    const textarea = document.getElementById('text');
    const lineNumbers = document.getElementById('editor-lines');
    if (textarea && lineNumbers) {
        const updateLineNumbers = () => {
            const lines = textarea.value.split('\n').length;
            lineNumbers.textContent = Array.from({ length: lines }, (_, i) => i + 1).join('\n');
        };
        const syncScroll = () => {
            lineNumbers.scrollTop = textarea.scrollTop;
        };
        textarea.addEventListener('input', updateLineNumbers);
        textarea.addEventListener('scroll', syncScroll);
        updateLineNumbers();
    }

    const qrBtn = document.getElementById('qr-btn');
    if (qrBtn) {
        qrBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const overlay = document.getElementById('qr-overlay');
            const target = document.getElementById('qr-target');
            if (overlay && target) {
                target.innerHTML = '';
                try {
                    new QRCode(target, {
                        text: window.location.href,
                        width: 256,
                        height: 256,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                    overlay.classList.remove('hidden');
                } catch (err) {
                    console.error(err);
                    alert(window.lang ? window.lang.qrFailed : "QR Error");
                }
            }
        });
    }

    const dropZone = document.body;
    const fileInput = document.getElementById('attachment');

    if (fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('drag-over');
        }

        function unhighlight(e) {
            dropZone.classList.remove('drag-over');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                updateFileLabel(files[0].name);
            }
        }
    }

    // Handle manual file selection
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                updateFileLabel(this.files[0].name);
            }
        });
    }

    function updateFileLabel(fileName) {
        const fileLabel = document.getElementById('file-label');
        if (fileLabel) {
            fileLabel.style.color = '#00ff00';
            fileLabel.style.borderColor = '#00ff00';
            fileLabel.innerHTML = fileName; // Remove default text, show filename
        }
        if (window.showToast) {
            showToast(`${window.lang ? window.lang.uploading : 'File attached'}: ${fileName}`);
        }
    }

    const form = document.getElementById('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('attachment');
            if (fileInput && fileInput.files.length > 0) {
                e.preventDefault();
                const progressOverlay = document.getElementById('upload-progress');
                const progressFill = document.getElementById('progress-fill');
                const progressText = document.getElementById('progress-text');
                
                if (progressOverlay) progressOverlay.classList.remove('hidden');
                
                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                
                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        if (progressFill) progressFill.style.width = percentComplete + '%';
                        if (progressText) progressText.innerText = Math.round(percentComplete) + '%';
                    }
                };
                
                xhr.onload = function() {
                    const responseURL = xhr.responseURL;
                    const currentURL = window.location.href.split('#')[0];
                    if (responseURL && responseURL !== currentURL && !responseURL.includes('index.php')) {
                        window.location.href = responseURL;
                    } else {
                        document.open();
                        document.write(xhr.responseText);
                        document.close();
                    }
                };
                
                xhr.onerror = function() {
                    alert(window.lang ? window.lang.networkError : "Network Error");
                    if (progressOverlay) progressOverlay.classList.add('hidden');
                };
                
                xhr.send(formData);
            }
        });
    }
});
