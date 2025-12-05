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

<!-- qr library (UMD) -->
<script src="<?= smart_url('assets/qr/qr-scanner.umd.min.js') ?>"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        --transition: all 0.25s ease;
    }

    .scan-container {
        max-width: 900px;
        margin: 1.8rem auto;
        padding: 0 1rem;
    }

    .scan-card {
        background: var(--dark-gradient);
        padding: 1.8rem;
        border-radius: 18px;
        color: white;
        box-shadow: var(--glass-shadow);
        backdrop-filter: blur(8px);
        border: 1px solid var(--glass-border);
        position: relative;
        overflow: hidden;
    }

    .scan-card::before {
        content: '';
        position: absolute;
        top: -45%;
        left: -45%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.06) 0%, rgba(255, 255, 255, 0) 70%);
        z-index: 0;
        pointer-events: none;
    }

    .scan-header {
        z-index: 1;
        text-align: center;
        margin-bottom: 1rem;
    }

    .scan-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        background: linear-gradient(45deg, #fff, #a8edea);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .scan-subtitle {
        font-size: 0.95rem;
        opacity: 0.9;
        color: #e0f7fa;
    }

    .camera-section {
        position: relative;
        z-index: 1;
        margin-bottom: 1rem;
    }

    .camera-box {
        width: 100%;
        aspect-ratio: 1;
        background: rgba(0, 0, 0, 0.28);
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        border: 2px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.18);
    }

    #video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }

    .scan-overlay {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        height: 70%;
        border-radius: 12px;
        border: 3px solid rgba(0, 255, 180, 0.85);
        box-shadow: 0 0 30px rgba(0, 255, 160, 0.22);
        pointer-events: none;
        z-index: 2;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
        margin: .9rem 0;
        padding: .65rem;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ff4757;
        animation: blink 1.6s infinite;
    }

    .status-dot.active {
        background: #2ed573;
        animation: none;
    }

    .status-message {
        font-size: .92rem;
        font-weight: 500;
    }

    .controls {
        display: flex;
        gap: .6rem;
        justify-content: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .btn-scan {
        padding: .6rem 1.1rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: .5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
    }

    .btn-scan:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
    }

    .btn-primary-scan {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-success-scan {
        background: var(--success-gradient);
        color: white;
    }

    .btn-danger-scan {
        background: var(--danger-gradient);
        color: white;
    }

    .btn-outline-scan {
        background: rgba(255, 255, 255, 0.06);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(6px);
    }

    .notification-area {
        min-height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: .6rem;
    }

    .scan-toast {
        padding: .6rem 1rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.06);
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        gap: .5rem;
        max-width: 86%;
        text-align: center;
    }

    .scan-toast.success {
        background: rgba(46, 213, 115, 0.12);
        border-color: rgba(46, 213, 115, 0.22);
    }

    .scan-toast.error {
        background: rgba(255, 71, 87, 0.12);
        border-color: rgba(255, 71, 87, 0.22);
    }

    .scan-toast.info {
        background: rgba(52, 152, 219, 0.12);
        border-color: rgba(52, 152, 219, 0.22);
    }

    .footer-note {
        text-align: center;
        font-size: .82rem;
        color: rgba(255, 255, 255, 0.72);
        margin-top: .9rem;
        padding: .8rem;
        background: rgba(0, 0, 0, 0.14);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.04);
    }

    .camera-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: .45rem;
        font-size: .82rem;
        color: rgba(255, 255, 255, 0.72);
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 255, 160, 0.35);
        }

        70% {
            box-shadow: 0 0 0 6px rgba(0, 255, 160, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(0, 255, 160, 0);
        }
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1
        }

        50% {
            opacity: .45
        }
    }

    @keyframes scan-line {
        0% {
            top: 8%
        }

        100% {
            top: 92%
        }
    }

    .scan-line {
        position: absolute;
        height: 2px;
        width: 100%;
        background: linear-gradient(90deg, transparent, #00ffb4, transparent);
        top: 10%;
        animation: scan-line 3s linear infinite;
        z-index: 2;
    }

    /* Responsive */
    @media (max-width:768px) {
        .scan-container {
            padding: 0 .6rem;
        }

        .scan-card {
            padding: 1.4rem;
        }

        .scan-title {
            font-size: 1.35rem;
        }

        .controls {
            flex-direction: column;
        }

        .btn-scan {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width:480px) {
        .camera-box {
            aspect-ratio: 0.95;
        }

        .scan-title {
            font-size: 1.1rem;
        }
    }
</style>

<div class="scan-container">
    <div class="scan-card">
        <div class="scan-header">
            <h1 class="scan-title">
                <i class="fas fa-qrcode me-2"></i>Scanner QR Absensi
            </h1>
            <p class="scan-subtitle">Arahkan kamera ke QR Code untuk melakukan absensi secara otomatis</p>
        </div>

        <div class="camera-section">
            <div class="camera-box" id="cameraBox">
                <video id="video" playsinline muted></video>
                <div class="scan-overlay" aria-hidden="true"></div>
                <div class="scan-line" aria-hidden="true"></div>
            </div>

            <div class="camera-info">
                <span id="cameraLabel">Kamera: Menunggu inisialisasi</span>
                <span id="fpsCounter">— FPS</span>
            </div>
        </div>

        <div class="status-indicator">
            <div class="status-dot" id="statusDot"></div>
            <div class="status-message" id="statusMessage">Mempersiapkan scanner...</div>
        </div>

        <div class="controls">
            <button id="startBtn" class="btn-scan btn-success-scan"><i class="fas fa-play"></i> Mulai Scan</button>
            <button id="swapBtn" class="btn-scan btn-outline-scan"><i class="fas fa-sync-alt"></i> Ganti Kamera</button>
            <button id="stopBtn" class="btn-scan btn-danger-scan"><i class="fas fa-stop"></i> Hentikan</button>
        </div>

        <div class="notification-area" id="notifArea"></div>

        <div class="footer-note">
            <i class="fas fa-info-circle me-1"></i>
            Pastikan browser memiliki akses ke kamera. Untuk hasil terbaik, gunakan HTTPS.
        </div>
    </div>
</div>

<!-- Suara beep scan berhasil -->
<audio id="beepSound" preload="auto">
    <source src="<?= base_url('assets/sounds/beep.mp3') ?>" type="audio/mpeg">
</audio>

<script>
    const videoElem = document.getElementById('video');
    const statusMessage = document.getElementById('statusMessage');
    const statusDot = document.getElementById('statusDot');
    const notifArea = document.getElementById('notifArea');
    const swapBtn = document.getElementById('swapBtn');
    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');
    const cameraLabel = document.getElementById('cameraLabel');
    const fpsCounter = document.getElementById('fpsCounter');
    const beepSound = document.getElementById('beepSound');

    let scanner = null;
    let availableCameras = [];
    let cameraIndex = 0;
    let usingFront = true;
    let lastDecodeAt = 0;
    let fpsIntervalId = null;
    let lastFpsUpdate = performance.now();
    let framesSinceLast = 0;

    // Lightweight toast - replace content only when different
    let lastToast = '';

    function showToast(message, type = 'info') {
        if (message === lastToast) return; // avoid reflowing same toast multiple times
        lastToast = message;
        const toastClass = `scan-toast ${type}`;
        notifArea.innerHTML = `<div class="${toastClass}"><i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i> ${message}</div>`;
        const duration = type === 'error' ? 4500 : 2600;
        setTimeout(() => {
            if (notifArea.firstChild && notifArea.firstChild.textContent.trim() === message.trim()) {
                notifArea.innerHTML = '';
                lastToast = '';
            }
        }, duration);
    }

    function showStatus(message, type = 'info') {
        statusMessage.textContent = message;
        statusDot.className = 'status-dot';
        if (type === 'success') {
            statusDot.classList.add('active');
        } else if (type === 'error') {
            statusDot.style.animation = 'blink 0.8s infinite';
        } else {
            statusDot.style.animation = 'blink 1.6s infinite';
        }
    }

    async function listCameras() {
        try {
            const cams = await QrScanner.listCameras(true);
            availableCameras = cams || [];
            return availableCameras;
        } catch (e) {
            console.debug('listCameras error', e);
            availableCameras = [];
            return [];
        }
    }

    function parseTokenFromText(text) {
        try {
            if (typeof text !== 'string') return null;
            if (text.indexOf('token=') !== -1) {
                const m = text.match(/[?&]token=([^&]+)/);
                if (m) return decodeURIComponent(m[1]);
            }
            if (/^[a-z0-9\-]{8,}$/i.test(text)) return text;
            return null;
        } catch (e) {
            return null;
        }
    }

    function updateFpsCounter() {
        const now = performance.now();
        const delta = now - lastFpsUpdate;
        const fps = Math.round((framesSinceLast / (delta / 1000)) || 0);
        fpsCounter.textContent = fps ? `${fps} FPS` : '— FPS';
        lastFpsUpdate = now;
        framesSinceLast = 0;
    }

    function startFpsTicker() {
        if (fpsIntervalId) return;
        lastFpsUpdate = performance.now();
        framesSinceLast = 0;
        fpsIntervalId = setInterval(updateFpsCounter, 800);
    }

    function stopFpsTicker() {
        if (fpsIntervalId) {
            clearInterval(fpsIntervalId);
            fpsIntervalId = null;
            fpsCounter.textContent = '— FPS';
        }
    }

    async function startScanner() {
        if (!window.QrScanner) {
            showStatus('Library QR tidak ditemukan.', 'error');
            showToast('Error: Library QR Scanner tidak terdeteksi', 'error');
            return;
        }

        // Stop any previous scanner cleanly
        if (scanner) {
            try {
                await scanner.stop();
            } catch (e) {
                console.debug('stop error', e);
            }
            scanner = null;
            stopFpsTicker();
        }

        showStatus('Menyiapkan kamera...');

        try {
            await listCameras();

            // prefer deviceId based on label or fallback to facing mode
            let pref = usingFront ? 'user' : 'environment';
            let prefDevice = null;

            if (availableCameras.length) {
                for (let i = 0; i < availableCameras.length; i++) {
                    const c = availableCameras[i];
                    if (usingFront && /front|user|face/i.test(c.label)) {
                        prefDevice = c.id;
                        cameraIndex = i;
                        break;
                    }
                    if (!usingFront && /back|rear|environment/i.test(c.label)) {
                        prefDevice = c.id;
                        cameraIndex = i;
                        break;
                    }
                }
                if (!prefDevice) {
                    prefDevice = availableCameras[0].id;
                    cameraIndex = 0;
                }
                cameraLabel.textContent = `Kamera: ${availableCameras[cameraIndex].label || 'Unknown'}`;
            } else {
                cameraLabel.textContent = 'Kamera: Default';
            }

            // Use constraints to request a lighter resolution by default (helps low-end devices)
            const constraints = {
                audio: false,
                video: {
                    width: {
                        ideal: 640
                    },
                    height: {
                        ideal: 360
                    },
                    facingMode: usingFront ? 'user' : 'environment'
                }
            };

            // Create scanner with optimized settings
            scanner = new QrScanner(videoElem, result => {
                // counting frames for FPS ticker
                framesSinceLast++;

                // throttle rapid duplicate decodes: ignore if within 700ms
                const now = performance.now();
                if (now - lastDecodeAt < 700) return;
                lastDecodeAt = now;

                let text = (typeof result === 'string') ? result : (result && (result.data || result.rawValue || result.text || result.data?.data));
                if (!text) return;

                const token = parseTokenFromText(String(text));
                if (!token) {
                    showToast('QR Code tidak valid atau tidak mengandung token', 'error');
                    return;
                }

                // feedback
                try {
                    if (beepSound) {
                        beepSound.currentTime = 0;
                        beepSound.volume = 0.7;
                        beepSound.play().catch(() => {});
                    }
                } catch (e) {}
                showStatus('QR Code terdeteksi!', 'success');
                showToast('QR Code berhasil dipindai. Mengarahkan...', 'success');

                // stop scanner and redirect shortly
                (async () => {
                    try {
                        await scanner.stop();
                    } catch (e) {}
                    stopFpsTicker();
                    setTimeout(() => {
                        window.location.href = "<?= smart_url('absensi/scan') ?>?token=" + encodeURIComponent(token);
                    }, 300);
                })();

            }, {
                preferredCamera: prefDevice || pref,
                highlightScanRegion: true,
                highlightCodeOutline: true,

                // REDUCED scans per second to ease CPU
                maxScansPerSecond: 3,

                calculateScanRegion: (video) => {
                    const w = video.videoWidth || 640;
                    const h = video.videoHeight || 360;
                    const size = Math.round(Math.min(w, h) * 0.72);
                    return {
                        x: Math.round((w - size) / 2),
                        y: Math.round((h - size) / 2),
                        width: size,
                        height: size,

                        // reduce downscale for decoding performance
                        downScaledWidth: 320,
                        downScaledHeight: 320
                    };
                },

                onDecodeError: (err) => {
                    // normal to have many decode errors; keep quiet to avoid console spam
                    // only log unexpected types for debugging
                    if (err && typeof err === 'object' && err.name && err.name !== 'NotFoundException') {
                        console.debug('QR decode error', err);
                    }
                }
            });

            // Try to set camera via deviceId with constraints for lighter footprint
            if (prefDevice) {
                try {
                    await scanner.setCamera(prefDevice);
                } catch (e) {
                    console.debug('setCamera failed, will rely on constraints', e);
                }
            }

            // attempt to apply constraints to the video track for lower resolution if possible
            try {
                const track = scanner.$video?.srcObject?.getVideoTracks?.()[0] || (videoElem && videoElem.srcObject && videoElem.srcObject.getVideoTracks && videoElem.srcObject.getVideoTracks()[0]);
                if (track && track.applyConstraints) {
                    await track.applyConstraints({
                        width: 640,
                        height: 360
                    });
                }
            } catch (e) {
                /* ignore */
            }

            await scanner.start();
            showStatus('Kamera aktif - siap memindai QR Code', 'success');
            showToast('Scanner berhasil diaktifkan', 'success');
            startFpsTicker();

        } catch (e) {
            console.error('Scanner error:', e);
            if (e && e.name === 'NotAllowedError') {
                showStatus('Izin kamera ditolak oleh pengguna', 'error');
                showToast('Error: Akses kamera ditolak. Silakan berikan izin akses kamera.', 'error');
            } else if (e && e.name === 'NotFoundError') {
                showStatus('Tidak ada kamera yang ditemukan', 'error');
                showToast('Error: Tidak ada kamera yang terdeteksi pada perangkat ini.', 'error');
            } else {
                showStatus('Gagal mengakses kamera: ' + (e.message || e), 'error');
                showToast('Error: Gagal mengakses kamera. ' + (e.message || ''), 'error');
            }
        }
    }

    // swap camera handler
    swapBtn.addEventListener('click', async () => {
        await listCameras();
        if (!availableCameras.length) {
            usingFront = !usingFront;
            showToast('Mengganti tipe kamera');
        } else {
            cameraIndex = (cameraIndex + 1) % availableCameras.length;
            const selectedCamera = availableCameras[cameraIndex];
            if (scanner) {
                try {
                    await scanner.setCamera(selectedCamera.id);
                } catch (e) {
                    console.debug('setCamera swap failed', e);
                }
            } else {
                usingFront = /front|user|face/i.test(selectedCamera.label);
            }
            cameraLabel.textContent = `Kamera: ${selectedCamera.label || 'Unknown'}`;
            showToast(`Mengganti ke: ${selectedCamera.label || 'Kamera ' + (cameraIndex + 1)}`);
        }
        try {
            await startScanner();
        } catch (e) {
            console.debug('Error after camera swap:', e);
        }
    });

    startBtn.addEventListener('click', async () => {
        await startScanner();
    });

    stopBtn.addEventListener('click', async () => {
        if (scanner) {
            try {
                await scanner.stop();
            } catch (e) {
                console.debug('stop error', e);
            }
            showStatus('Scanner dihentikan');
            showToast('Scanner dihentikan', 'info');
            statusDot.className = 'status-dot';
            cameraLabel.textContent = 'Kamera: Nonaktif';
            stopFpsTicker();
        } else {
            showStatus('Scanner tidak aktif');
        }
    });

    window.addEventListener('load', () => {
        if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
            showStatus('Peringatan: Jalankan melalui HTTPS untuk pengalaman terbaik', 'error');
            showToast('Peringatan: Beberapa browser mungkin membatasi akses kamera pada HTTP', 'error');
        }
        startScanner().catch((e) => {
            console.debug('Failed to auto-start scanner:', e);
        });
    });

    // Cleanup
    window.addEventListener('beforeunload', () => {
        if (scanner) {
            try {
                scanner.stop();
            } catch (e) {
                console.debug('Error stopping scanner on unload:', e);
            }
        }
    });

    // Expose small helper for debugging (optional)
    window.__scannerDebug = () => ({
        cameras: availableCameras,
        cameraIndex,
        usingFront
    });
</script>

<?= $this->endSection() ?>