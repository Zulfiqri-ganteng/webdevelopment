<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- QR Scanner Library dengan fallback CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    // Fallback jika CDN gagal
    window.Html5Qrcode = window.Html5Qrcode || class {
        static getCameras() {
            return Promise.reject(new Error('Library not loaded'));
        }
        constructor() {
            this.isScanning = false;
            console.error('QR Scanner library failed to load');
        }
    };
</script>

<style>
    :root {
        --primary-color: #4361ee;
        --success-color: #06d6a0;
        --warning-color: #ffd166;
        --danger-color: #ef476f;
        --dark-color: #1a1a2e;
        --light-color: #f8f9fa;
        --border-radius: 12px;
        --transition-speed: 0.3s;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.15);
    }

    .scan-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .scan-card {
        background: var(--light-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .scan-header {
        background: linear-gradient(135deg, var(--primary-color), #3a0ca3);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .scan-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .scan-subtitle {
        opacity: 0.9;
        font-size: 0.875rem;
        margin: 0;
    }

    .camera-section {
        padding: 1.5rem;
    }

    .camera-container {
        position: relative;
        width: 100%;
        margin: 0 auto;
        max-width: 500px;
    }

    .camera-viewport {
        width: 100%;
        aspect-ratio: 1;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: var(--shadow-md);
    }

    #reader {
        width: 100% !important;
        height: 100% !important;
    }

    #reader__dashboard {
        display: none !important;
    }

    .scan-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        height: 70%;
        border: 2px solid var(--success-color);
        border-radius: 8px;
        pointer-events: none;
        z-index: 10;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
    }

    .scan-frame {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        pointer-events: none;
        z-index: 11;
    }

    .camera-controls {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1.25rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover:not(:disabled) {
        background: #3a56d4;
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .btn-success {
        background: var(--success-color);
        color: white;
    }

    .btn-success:hover:not(:disabled) {
        background: #05c493;
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .btn-danger {
        background: var(--danger-color);
        color: white;
    }

    .btn-danger:hover:not(:disabled) {
        background: #e63946;
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .btn-outline:hover:not(:disabled) {
        background: rgba(67, 97, 238, 0.1);
        transform: translateY(-1px);
    }

    .status-panel {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin: 1.5rem 0;
        border-left: 4px solid var(--primary-color);
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #adb5bd;
        transition: background-color var(--transition-speed);
    }

    .status-dot.active {
        background: var(--success-color);
        box-shadow: 0 0 0 3px rgba(6, 214, 160, 0.2);
    }

    .status-dot.warning {
        background: var(--warning-color);
        animation: pulse 1.5s infinite;
    }

    .status-dot.error {
        background: var(--danger-color);
    }

    .status-text {
        font-size: 0.875rem;
        color: #495057;
        font-weight: 500;
    }

    .camera-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.75rem;
    }

    .performance-metrics {
        display: flex;
        gap: 1rem;
    }

    .metric {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .notification-container {
        min-height: 48px;
        margin: 1rem 0;
    }

    .notification {
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin: 0.5rem 0;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideIn 0.3s ease;
        border-left: 4px solid;
    }

    .notification.info {
        background: #e7f5ff;
        border-left-color: #339af0;
        color: #1864ab;
    }

    .notification.success {
        background: #d3f9d8;
        border-left-color: #2b8a3e;
        color: #2b8a3e;
    }

    .notification.warning {
        background: #fff3bf;
        border-left-color: #f08c00;
        color: #e67700;
    }

    .notification.error {
        background: #ffe3e3;
        border-left-color: #e03131;
        color: #c92a2a;
    }

    .footer-note {
        background: #f8f9fa;
        padding: 1rem;
        text-align: center;
        font-size: 0.75rem;
        color: #6c757d;
        border-top: 1px solid #e9ecef;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scan {
        0% {
            transform: translateY(-100%);
        }

        100% {
            transform: translateY(400%);
        }
    }

    .scan-line {
        position: absolute;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--success-color), transparent);
        top: 0;
        left: 0;
        animation: scan 2s ease-in-out infinite;
        z-index: 12;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .scan-container {
            padding: 0 0.75rem;
            margin: 1rem auto;
        }

        .scan-header {
            padding: 1.25rem 1rem;
        }

        .camera-section {
            padding: 1rem;
        }

        .camera-controls {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .camera-viewport {
            aspect-ratio: 0.85;
        }

        .scan-title {
            font-size: 1.25rem;
        }

        .performance-metrics {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .scan-card {
            background: #212529;
            color: #e9ecef;
        }

        .status-panel {
            background: #2d3748;
            border-left-color: #4c6ef5;
        }

        .status-text {
            color: #e9ecef;
        }

        .footer-note {
            background: #2d3748;
            border-top-color: #495057;
            color: #adb5bd;
        }

        .notification.info {
            background: #1c7ed6;
            color: white;
        }

        .notification.success {
            background: #2b8a3e;
            color: white;
        }

        .notification.warning {
            background: #f08c00;
            color: white;
        }

        .notification.error {
            background: #e03131;
            color: white;
        }
    }
</style>

<div class="scan-container">
    <div class="scan-card">
        <!-- Header Section -->
        <div class="scan-header">
            <h1 class="scan-title">
                <i class="fas fa-qrcode"></i>
                QR Code Scanner
            </h1>
            <p class="scan-subtitle">Scan QR code untuk proses absensi yang cepat dan akurat</p>
        </div>

        <!-- Camera Section -->
        <div class="camera-section">
            <div class="camera-container">
                <div class="camera-viewport">
                    <div id="reader"></div>
                    <div class="scan-overlay"></div>
                    <div class="scan-frame"></div>
                    <div class="scan-line"></div>
                </div>

                <div class="camera-info">
                    <span id="cameraLabel">Kamera tidak terdeteksi</span>
                    <div class="performance-metrics">
                        <span class="metric" id="resolutionInfo">—</span>
                        <span class="metric" id="fpsCounter">— FPS</span>
                    </div>
                </div>
            </div>

            <!-- Status Panel -->
            <div class="status-panel">
                <div class="status-indicator">
                    <div class="status-dot" id="statusDot"></div>
                    <div class="status-text" id="statusMessage">Menginisialisasi sistem...</div>
                </div>
            </div>

            <!-- Controls -->
            <div class="camera-controls">
                <button id="startBtn" class="btn btn-success">
                    <i class="fas fa-play"></i> Mulai Scanning
                </button>
                <button id="swapBtn" class="btn btn-outline">
                    <i class="fas fa-sync-alt"></i> Ganti Kamera
                </button>
                <button id="stopBtn" class="btn btn-danger" disabled>
                    <i class="fas fa-stop"></i> Hentikan
                </button>
                <button id="settingsBtn" class="btn btn-outline">
                    <i class="fas fa-cog"></i> Pengaturan
                </button>
            </div>

            <!-- Notifications -->
            <div class="notification-container" id="notificationContainer"></div>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            <i class="fas fa-shield-alt me-1"></i>
            Sistem scanner menggunakan teknologi terenkripsi. Pastikan izin kamera diaktifkan.
        </div>
    </div>
</div>

<!-- Audio Feedback -->
<audio id="scanSuccessSound" preload="auto">
    <source src="<?= base_url('assets/sounds/success-beep.mp3') ?>" type="audio/mpeg">
</audio>
<audio id="scanErrorSound" preload="auto">
    <source src="<?= base_url('assets/sounds/error-beep.mp3') ?>" type="audio/mpeg">
</audio>

<!-- Settings Modal (Minimal) -->
<div id="settingsModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 10% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 400px;">
        <h3 style="margin-bottom: 15px;">Pengaturan Scanner</h3>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Quality Preset:</label>
            <select id="qualityPreset" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                <option value="performance">Prioritas Performa</option>
                <option value="balanced" selected>Seimbang</option>
                <option value="quality">Prioritas Kualitas</option>
            </select>
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeSettings()" class="btn btn-outline" style="padding: 8px 16px;">Batal</button>
            <button onclick="applySettings()" class="btn btn-primary" style="padding: 8px 16px;">Simpan</button>
        </div>
    </div>
</div>

<script>
    // ============================
    // MODULE: QR SCANNER MANAGER
    // ============================
    class QRScannerManager {
        constructor() {
            this.scanner = null;
            this.cameras = [];
            this.currentCameraIndex = 0;
            this.isScanning = false;
            this.lastScanTime = 0;
            this.scanCooldown = 1000; // 1 second cooldown
            this.fpsCounter = 0;
            this.lastFpsUpdate = Date.now();
            this.settings = {
                quality: 'balanced',
                soundEnabled: true,
                vibrationEnabled: true
            };

            this.elements = {
                startBtn: document.getElementById('startBtn'),
                stopBtn: document.getElementById('stopBtn'),
                swapBtn: document.getElementById('swapBtn'),
                settingsBtn: document.getElementById('settingsBtn'),
                cameraLabel: document.getElementById('cameraLabel'),
                statusDot: document.getElementById('statusDot'),
                statusMessage: document.getElementById('statusMessage'),
                resolutionInfo: document.getElementById('resolutionInfo'),
                fpsCounter: document.getElementById('fpsCounter'),
                notificationContainer: document.getElementById('notificationContainer')
            };

            this.sounds = {
                success: document.getElementById('scanSuccessSound'),
                error: document.getElementById('scanErrorSound')
            };

            this.init();
        }

        async init() {
            try {
                this.updateStatus('Menginisialisasi sistem...', 'info');
                await this.loadSettings();
                await this.setupEventListeners();
                await this.initializeCamera();
                this.updateStatus('Sistem siap digunakan', 'success');
            } catch (error) {
                console.error('Initialization failed:', error);
                this.showNotification('Gagal menginisialisasi sistem', 'error');
                this.updateStatus('Inisialisasi gagal', 'error');
            }
        }

        async initializeCamera() {
            try {
                // Check browser compatibility
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('Browser tidak mendukung akses kamera');
                }

                // Get available cameras
                this.cameras = await this.getAvailableCameras();

                if (this.cameras.length === 0) {
                    throw new Error('Tidak ada kamera yang terdeteksi');
                }

                // Select preferred camera (back camera first)
                this.currentCameraIndex = this.findPreferredCameraIndex();
                this.updateCameraInfo();

                // Initialize scanner
                this.scanner = new Html5Qrcode("reader");

            } catch (error) {
                console.error('Camera initialization error:', error);
                this.showNotification(`Error kamera: ${error.message}`, 'error');
                throw error;
            }
        }

        async getAvailableCameras() {
            try {
                const devices = await Html5Qrcode.getCameras();

                // Fallback for devices that don't support enumeration
                if (!devices || devices.length === 0) {
                    return [{
                            id: 'environment',
                            label: 'Kamera Belakang (Default)'
                        },
                        {
                            id: 'user',
                            label: 'Kamera Depan'
                        }
                    ];
                }

                return devices;
            } catch (error) {
                console.warn('Camera enumeration failed:', error);
                return [];
            }
        }

        findPreferredCameraIndex() {
            // Priority: Back camera > Front camera > First available
            const backIndex = this.cameras.findIndex(cam =>
                cam.label.toLowerCase().includes('back') ||
                cam.label.toLowerCase().includes('rear') ||
                cam.id === 'environment'
            );

            const frontIndex = this.cameras.findIndex(cam =>
                cam.label.toLowerCase().includes('front') ||
                cam.label.toLowerCase().includes('user')
            );

            return backIndex >= 0 ? backIndex :
                frontIndex >= 0 ? frontIndex : 0;
        }

        async startScanning() {
            if (this.isScanning || !this.scanner) return;

            try {
                const cameraId = this.cameras[this.currentCameraIndex].id;
                const config = this.getScannerConfig();

                this.updateStatus('Memulai scanning...', 'warning');
                this.toggleButtons(true);

                await this.scanner.start(
                    cameraId,
                    config,
                    this.onScanSuccess.bind(this),
                    this.onScanError.bind(this)
                );

                this.isScanning = true;
                this.startFpsCounter();
                this.updateStatus('Scanning aktif', 'success');
                this.showNotification('Scanner berhasil diaktifkan', 'success');

            } catch (error) {
                console.error('Failed to start scanning:', error);
                this.handleStartError(error);
                this.toggleButtons(false);
            }
        }

        async stopScanning() {
            if (!this.isScanning || !this.scanner) return;

            try {
                await this.scanner.stop();
                this.isScanning = false;
                this.stopFpsCounter();
                this.updateStatus('Scanning dihentikan', 'info');
                this.showNotification('Scanner dihentikan', 'info');
                this.toggleButtons(false);

            } catch (error) {
                console.error('Failed to stop scanning:', error);
                this.showNotification('Gagal menghentikan scanner', 'error');
            }
        }

        async switchCamera() {
            if (this.cameras.length < 2) {
                this.showNotification('Hanya satu kamera tersedia', 'info');
                return;
            }

            const wasScanning = this.isScanning;

            if (wasScanning) {
                await this.stopScanning();
            }

            this.currentCameraIndex = (this.currentCameraIndex + 1) % this.cameras.length;
            this.updateCameraInfo();
            this.showNotification(`Beralih ke: ${this.cameras[this.currentCameraIndex].label}`, 'info');

            if (wasScanning) {
                setTimeout(() => this.startScanning(), 100);
            }
        }

        getScannerConfig() {
            const presets = {
                performance: {
                    fps: 8,
                    qrbox: {
                        width: 200,
                        height: 200
                    },
                    aspectRatio: 1.0,
                    disableFlip: true
                },
                balanced: {
                    fps: 15,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1.0,
                    disableFlip: false
                },
                quality: {
                    fps: 30,
                    qrbox: {
                        width: 300,
                        height: 300
                    },
                    aspectRatio: 1.0,
                    disableFlip: false
                }
            };

            return presets[this.settings.quality] || presets.balanced;
        }

        onScanSuccess(decodedText, decodedResult) {
            const now = Date.now();

            // Prevent duplicate scans
            if (now - this.lastScanTime < this.scanCooldown) {
                return;
            }

            this.lastScanTime = now;
            this.fpsCounter++;

            // Process QR code
            this.processQRCode(decodedText);
        }

        onScanError(error) {
            // Ignore common "not found" errors to reduce console noise
            if (error && error.message && !error.message.includes('NotFoundException')) {
                console.debug('Scan error:', error);
            }
        }

        async processQRCode(decodedText) {
            try {
                const token = this.extractTokenFromQR(decodedText);

                if (!token) {
                    this.showNotification('QR Code tidak valid', 'warning');
                    this.playSound('error');
                    return;
                }

                // Visual and audio feedback
                this.updateStatus('QR Code terdeteksi!', 'success');
                this.playSound('success');
                if (this.settings.vibrationEnabled && navigator.vibrate) {
                    navigator.vibrate(100);
                }

                // Stop scanning and redirect
                await this.stopScanning();

                setTimeout(() => {
                    this.redirectToAttendance(token);
                }, 500);

            } catch (error) {
                console.error('QR processing error:', error);
                this.showNotification('Error memproses QR Code', 'error');
                this.playSound('error');
            }
        }

        extractTokenFromQR(text) {
            try {
                if (text.includes('token=')) {
                    const url = new URL(text.includes('://') ? text : `https://dummy.com?${text}`);
                    return url.searchParams.get('token');
                }

                if (/^[a-z0-9\-_]{8,64}$/i.test(text)) {
                    return text;
                }

                return null;
            } catch {
                return null;
            }
        }

        redirectToAttendance(token) {
            const redirectUrl = `<?= smart_url('absensi/scan') ?>?token=${encodeURIComponent(token)}`;
            window.location.href = redirectUrl;
        }

        updateCameraInfo() {
            if (this.cameras.length > 0) {
                const camera = this.cameras[this.currentCameraIndex];
                this.elements.cameraLabel.textContent = `Kamera: ${camera.label}`;
            }
        }

        updateStatus(message, type = 'info') {
            this.elements.statusMessage.textContent = message;
            this.elements.statusDot.className = 'status-dot';

            switch (type) {
                case 'success':
                    this.elements.statusDot.classList.add('active');
                    break;
                case 'warning':
                    this.elements.statusDot.classList.add('warning');
                    break;
                case 'error':
                    this.elements.statusDot.classList.add('error');
                    break;
            }
        }

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                ${message}
            `;

            this.elements.notificationContainer.appendChild(notification);

            // Auto-remove after timeout
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(() => notification.remove(), 300);
                }
            }, type === 'error' ? 5000 : 3000);
        }

        getNotificationIcon(type) {
            switch (type) {
                case 'success':
                    return 'check-circle';
                case 'warning':
                    return 'exclamation-triangle';
                case 'error':
                    return 'times-circle';
                default:
                    return 'info-circle';
            }
        }

        playSound(type) {
            if (!this.settings.soundEnabled) return;

            try {
                const sound = this.sounds[type];
                if (sound) {
                    sound.currentTime = 0;
                    sound.play().catch(() => {});
                }
            } catch (error) {
                console.warn('Sound playback failed:', error);
            }
        }

        startFpsCounter() {
            this.fpsCounter = 0;
            this.lastFpsUpdate = Date.now();

            this.fpsInterval = setInterval(() => {
                const now = Date.now();
                const elapsed = (now - this.lastFpsUpdate) / 1000;
                const fps = Math.round(this.fpsCounter / elapsed);

                this.elements.fpsCounter.textContent = `${fps} FPS`;
                this.fpsCounter = 0;
                this.lastFpsUpdate = now;
            }, 1000);
        }

        stopFpsCounter() {
            if (this.fpsInterval) {
                clearInterval(this.fpsInterval);
                this.fpsInterval = null;
            }
            this.elements.fpsCounter.textContent = '— FPS';
        }

        toggleButtons(isScanning) {
            this.elements.startBtn.disabled = isScanning;
            this.elements.stopBtn.disabled = !isScanning;
            this.elements.swapBtn.disabled = isScanning;
        }

        handleStartError(error) {
            let message = 'Gagal memulai scanner';

            if (error.name === 'NotAllowedError') {
                message = 'Izin kamera ditolak. Silakan berikan izin akses kamera.';
            } else if (error.name === 'NotFoundError') {
                message = 'Kamera tidak ditemukan pada perangkat ini.';
            } else if (error.name === 'NotSupportedError') {
                message = 'Browser tidak mendukung fitur kamera.';
            } else if (error.name === 'NotReadableError') {
                message = 'Kamera sedang digunakan oleh aplikasi lain.';
            }

            this.showNotification(message, 'error');
            this.updateStatus(message, 'error');
        }

        async loadSettings() {
            try {
                const saved = localStorage.getItem('qrScannerSettings');
                if (saved) {
                    this.settings = {
                        ...this.settings,
                        ...JSON.parse(saved)
                    };
                }
            } catch (error) {
                console.warn('Failed to load settings:', error);
            }
        }

        saveSettings() {
            try {
                localStorage.setItem('qrScannerSettings', JSON.stringify(this.settings));
            } catch (error) {
                console.warn('Failed to save settings:', error);
            }
        }

        async setupEventListeners() {
            this.elements.startBtn.addEventListener('click', () => this.startScanning());
            this.elements.stopBtn.addEventListener('click', () => this.stopScanning());
            this.elements.swapBtn.addEventListener('click', () => this.switchCamera());
            this.elements.settingsBtn.addEventListener('click', () => this.openSettings());

            // Handle page visibility changes
            document.addEventListener('visibilitychange', () => {
                if (document.hidden && this.isScanning) {
                    this.stopScanning();
                }
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                if (this.isScanning) {
                    this.scanner?.stop().catch(() => {});
                }
            });
        }

        openSettings() {
            document.getElementById('settingsModal').style.display = 'block';
            document.getElementById('qualityPreset').value = this.settings.quality;
        }
    }

    // ============================
    // MODULE: SETTINGS MANAGER
    // ============================
    window.closeSettings = function() {
        document.getElementById('settingsModal').style.display = 'none';
    };

    window.applySettings = function() {
        const quality = document.getElementById('qualityPreset').value;

        if (window.scannerManager) {
            window.scannerManager.settings.quality = quality;
            window.scannerManager.saveSettings();

            // Restart scanner if it's running
            if (window.scannerManager.isScanning) {
                window.scannerManager.stopScanning().then(() => {
                    setTimeout(() => window.scannerManager.startScanning(), 500);
                });
            }
        }

        closeSettings();
    };

    // ============================
    // APPLICATION INITIALIZATION
    // ============================
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize scanner manager
        window.scannerManager = new QRScannerManager();

        // Check for HTTPS requirement
        if (window.location.protocol !== 'https:' &&
            !window.location.hostname.includes('localhost') &&
            !window.location.hostname.includes('127.0.0.1')) {
            window.scannerManager.showNotification(
                'Untuk pengalaman terbaik, gunakan koneksi HTTPS',
                'warning'
            );
        }

        // Auto-start on page load
        setTimeout(() => {
            if (!window.scannerManager.isScanning) {
                window.scannerManager.startScanning().catch(() => {
                    // Silent fail - user can start manually
                });
            }
        }, 1000);
    });
</script>

<?= $this->endSection() ?>