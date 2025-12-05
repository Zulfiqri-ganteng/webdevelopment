<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<script src="<?= smart_url('assets/qr/qr-scanner.umd.min.js') ?>"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        --transition: .25s ease;
    }

    .scan-container {
        max-width: 480px;
        margin: 1.5rem auto;
        padding: 0 .8rem;
    }

    .scan-card {
        background: var(--dark-gradient);
        padding: 1.2rem;
        border-radius: 18px;
        color: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .scan-title {
        font-size: 1.45rem;
        font-weight: 700;
        margin-bottom: .35rem;
        text-align: center;

        /* gradient text - vendor prefixed first, then standard */
        -webkit-background-clip: text;
        background-clip: text;

        -webkit-text-fill-color: transparent;
        color: transparent;

        background: linear-gradient(45deg, #fff, #a8edea);
    }


    .scan-subtitle {
        text-align: center;
        font-size: .9rem;
        opacity: .9;
    }

    .camera-box {
        width: 100%;
        aspect-ratio: 3/4;
        background: #000;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        margin-top: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.12);
    }

    #video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(var(--flip, 1));
    }

    .scan-overlay {
        position: absolute;
        inset: 0;
        width: 70%;
        height: 70%;
        margin: auto;
        border: 3px solid rgba(0, 255, 160, 0.9);
        border-radius: 12px;
        pointer-events: none;
        box-shadow: 0 0 25px rgba(0, 255, 160, 0.3);
    }

    .controls {
        display: flex;
        gap: .6rem;
        flex-wrap: wrap;
        margin-top: .9rem;
        justify-content: center;
    }

    .btn-scan {
        padding: .6rem 1rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: .4rem;
        cursor: pointer;
        font-size: .9rem;
    }

    .btn-success-scan {
        background: var(--success-gradient);
        color: white;
    }

    .btn-outline-scan {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.28);
        color: white;
    }

    .btn-danger-scan {
        background: var(--danger-gradient);
        color: white;
    }

    .status-indicator {
        margin-top: 1rem;
        display: flex;
        justify-content: center;
        gap: .6rem;
        color: white;
    }

    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: red;
        animation: blink 1.3s infinite;
    }

    .status-dot.active {
        background: #2ed573;
        animation: none;
    }

    .notification-area {
        text-align: center;
        min-height: 40px;
        margin-top: .8rem;
    }

    .scan-toast {
        background: rgba(255, 255, 255, 0.12);
        padding: .6rem;
        border-radius: 10px;
        display: inline-block;
        color: white;
        backdrop-filter: blur(6px);
    }

    .footer-note {
        margin-top: 1rem;
        text-align: center;
        font-size: .82rem;
        opacity: .7;
    }

    @media (max-width:480px) {
        .camera-box {
            aspect-ratio: 0.85;
        }
    }
</style>

<div class="scan-container">
    <div class="scan-card">

        <h1 class="scan-title">
            <i class="fas fa-qrcode me-2"></i> Scanner QR Absensi
        </h1>
        <p class="scan-subtitle">Arahkan kamera ke QR Code</p>

        <div class="camera-box">
            <video id="video" playsinline muted></video>
            <div class="scan-overlay"></div>
        </div>

        <div class="status-indicator">
            <div class="status-dot" id="statusDot"></div>
            <div id="statusMessage">Menyiapkan kamera...</div>
        </div>

        <div class="controls">
            <button id="startBtn" class="btn-scan btn-success-scan">
                <i class="fas fa-play"></i> Mulai Scan
            </button>

            <button id="swapBtn" class="btn-scan btn-outline-scan">
                <i class="fas fa-sync"></i> Ganti Kamera
            </button>

            <button id="stopBtn" class="btn-scan btn-danger-scan">
                <i class="fas fa-stop"></i> Stop
            </button>
        </div>

        <div class="notification-area" id="notifArea"></div>

        <div class="footer-note">
            Untuk iPhone wajib HTTPS agar kamera berfungsi.
        </div>
    </div>
</div>

<audio id="beepSound">
    <source src="<?= base_url('assets/sounds/beep.mp3') ?>" type="audio/mpeg">
</audio>

<script>
    const videoElem = document.getElementById("video");
    const statusDot = document.getElementById("statusDot");
    const statusMessage = document.getElementById("statusMessage");
    const notifArea = document.getElementById("notifArea");
    const beepSound = document.getElementById("beepSound");

    let scanner = null;
    let cameras = [];
    let camIndex = 0;
    let usingFront = false;

    /* Toast */
    function toast(msg, type = "info") {
        notifArea.innerHTML = `<div class="scan-toast">${msg}</div>`;
        setTimeout(() => notifArea.innerHTML = "", 2500);
    }

    /* Status */
    function setStatus(msg, ok = false) {
        statusMessage.textContent = msg;
        statusDot.classList.toggle("active", ok);
    }

    /* List Kamera */
    async function loadCameras() {
        cameras = await QrScanner.listCameras(true);
    }

    /* Start Scanner */
    async function startScanner() {
        if (scanner) {
            try {
                await scanner.stop();
            } catch {}
            scanner = null;
        }

        await loadCameras();
        if (!cameras.length) {
            toast("Tidak ada kamera ditemukan", "error");
            setStatus("Tidak ada kamera", false);
            return;
        }

        // Default ke kamera belakang jika ada
        const cam = cameras[camIndex];
        usingFront = /front|user|face/i.test(cam.label);
        videoElem.style.setProperty("--flip", usingFront ? "-1" : "1");

        scanner = new QrScanner(videoElem, res => onScan(res), {
            preferredCamera: cam.id,
            maxScansPerSecond: 3,
            highlightScanRegion: true,
            highlightCodeOutline: true,
        });

        await scanner.start();
        setStatus("Kamera aktif — siap scan", true);
        toast("Scanner aktif");
    }

    /* Saat Scan Berhasil */
    function onScan(result) {
        const text = result.data || result;
        if (!text) return;

        if (beepSound) {
            beepSound.currentTime = 0;
            beepSound.play().catch(() => {});
        }

        toast("QR Terdeteksi", "success");

        scanner.stop();
        setTimeout(() => {
            window.location.href = "<?= smart_url('absensi/scan') ?>?token=" + encodeURIComponent(text);
        }, 300);
    }

    /* Swap Kamera */
    document.getElementById("swapBtn").onclick = async () => {
        if (!cameras.length) return;

        camIndex = (camIndex + 1) % cameras.length;
        usingFront = /front|user|face/i.test(cameras[camIndex].label);

        videoElem.style.setProperty("--flip", usingFront ? "-1" : "1");

        toast("Mengganti kamera...");
        await startScanner();
    };

    /* Start Button */
    document.getElementById("startBtn").onclick = startScanner;

    /* Stop Button */
    document.getElementById("stopBtn").onclick = () => {
        if (scanner) scanner.stop();
        setStatus("Scanner berhenti");
        toast("Scanner dihentikan");
    };

    window.onload = startScanner;
</script>

<?= $this->endSection() ?>