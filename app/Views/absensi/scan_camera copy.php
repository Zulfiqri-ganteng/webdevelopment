<?php
// ============================================
// FILE: scan_camera.php
// DESCRIPTION: QR Scanner untuk Sistem Absensi
// VERSION: 3.0 - Professional Edition
// OPTIMIZED FOR: Localhost & RumahWeb Hosting
// ============================================

// Enable error reporting untuk debugging (matikan di production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah sudah login (sesuaikan dengan sistem autentikasi Anda)
// if (!isset($_SESSION['user_id'])) {
//     header('Location: /login');
//     exit();
// }

// Set header untuk security
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Permissions-Policy: camera=(self), microphone=(self)");

// Force HTTPS untuk localhost jika diperlukan
if ($_SERVER['SERVER_NAME'] === 'localhost' || 
    $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    
    // Redirect ke HTTPS untuk halaman scanner
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        exit();
    }
}
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
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

<!-- Debug Info (Hanya tampil di localhost) -->
<?php if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1'): ?>
<div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-info-circle me-2 fs-5"></i>
        <div>
            <strong>Mode Development</strong><br>
            <small>Server: <?= $_SERVER['SERVER_NAME'] ?> | Protocol: <?= $_SERVER['HTTPS'] ? 'HTTPS' : 'HTTP' ?></small>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<style>
    /* ============================================
       CSS VARIABLES & RESET
    ============================================ */
    :root {
        --primary-color: #4361ee;
        --primary-dark: #3a56d4;
        --success-color: #06d6a0;
        --warning-color: #ffd166;
        --danger-color: #ef476f;
        --dark-color: #1a1a2e;
        --light-color: #f8f9fa;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-600: #6c757d;
        --gray-800: #343a40;
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --border-radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
        --shadow-lg: 0 8px 32px rgba(0,0,0,0.15);
        --shadow-xl: 0 12px 48px rgba(0,0,0,0.18);
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    /* ============================================
       MAIN CONTAINER
    ============================================ */
    .scan-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    /* ============================================
       SCAN CARD
    ============================================ */
    .scan-card {
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--gray-200);
        position: relative;
        transition: var(--transition);
    }

    .scan-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    /* ============================================
       HEADER SECTION
    ============================================ */
    .scan-header {
        background: linear-gradient(135deg, var(--primary-color), #3a0ca3);
        color: white;
        padding: 1.75rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .scan-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.3;
        z-index: 0;
    }

    .scan-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        position: relative;
        z-index: 1;
        letter-spacing: -0.5px;
    }

    .scan-title i {
        margin-right: 0.75rem;
        font-size: 1.5rem;
    }

    .scan-subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
        margin: 0;
        position: relative;
        z-index: 1;
        line-height: 1.5;
    }

    /* ============================================
       CAMERA SECTION
    ============================================ */
    .camera-section {
        padding: 2rem;
    }

    @media (max-width: 768px) {
        .camera-section {
            padding: 1.5rem;
        }
    }

    /* Camera Container */
    .camera-container {
        position: relative;
        width: 100%;
        margin: 0 auto;
        max-width: 600px;
    }

    .camera-viewport {
        width: 100%;
        aspect-ratio: 1;
        background: #000;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        position: relative;
        box-shadow: var(--shadow-lg);
        border: 2px solid var(--gray-800);
    }

    #reader {
        width: 100% !important;
        height: 100% !important;
    }

    #reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    #reader__dashboard {
        display: none !important;
    }

    /* Scan Overlay */
    .scan-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        height: 70%;
        border: 3px solid var(--success-color);
        border-radius: var(--border-radius-sm);
        pointer-events: none;
        z-index: 10;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);
    }

    .scan-frame {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: var(--border-radius-sm);
        pointer-events: none;
        z-index: 11;
    }

    /* Scan Line Animation */
    @keyframes scan-line {
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
        height: 3px;
        background: linear-gradient(90deg, 
            transparent, 
            var(--success-color), 
            transparent);
        top: 0;
        left: 0;
        animation: scan-line 2s ease-in-out infinite;
        z-index: 12;
        filter: drop-shadow(0 0 8px var(--success-color));
    }

    /* Camera Info */
    .camera-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding: 0.75rem 1rem;
        background: var(--gray-100);
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-200);
    }

    .camera-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .camera-label i {
        color: var(--primary-color);
    }

    .performance-metrics {
        display: flex;
        gap: 1.25rem;
        align-items: center;
    }

    .metric {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.75rem;
        color: var(--gray-600);
    }

    .metric i {
        font-size: 0.875rem;
    }

    /* ============================================
       STATUS PANEL
    ============================================ */
    .status-panel {
        background: linear-gradient(135deg, var(--gray-100), white);
        border-radius: var(--border-radius-sm);
        padding: 1.25rem;
        margin: 1.5rem 0;
        border-left: 4px solid var(--primary-color);
        box-shadow: var(--shadow-sm);
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--gray-600);
        transition: all 0.3s ease;
        position: relative;
    }

    .status-dot::after {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0;
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% {
            transform: scale(0.8);
            opacity: 0.5;
        }
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    .status-dot.active {
        background: var(--success-color);
    }

    .status-dot.active::after {
        color: var(--success-color);
        opacity: 0.4;
    }

    .status-dot.warning {
        background: var(--warning-color);
    }

    .status-dot.error {
        background: var(--danger-color);
    }

    .status-text {
        font-size: 0.95rem;
        color: var(--gray-800);
        font-weight: 500;
        flex: 1;
    }

    .status-time {
        font-size: 0.75rem;
        color: var(--gray-600);
        font-family: 'Courier New', monospace;
    }

    /* ============================================
       CONTROLS
    ============================================ */
    .controls-container {
        margin: 2rem 0;
    }

    .controls-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .btn {
        padding: 0.875rem 1.5rem;
        border-radius: var(--border-radius-sm);
        border: none;
        font-weight: 500;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-align: center;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn:active {
        transform: translateY(1px);
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
        transform: none !important;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover:not(:disabled) {
        background: var(--primary-dark);
        box-shadow: var(--shadow-md);
    }

    .btn-success {
        background: var(--success-color);
        color: white;
    }

    .btn-success:hover:not(:disabled) {
        background: #05c493;
        box-shadow: var(--shadow-md);
    }

    .btn-danger {
        background: var(--danger-color);
        color: white;
    }

    .btn-danger:hover:not(:disabled) {
        background: #e63946;
        box-shadow: var(--shadow-md);
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-outline:hover:not(:disabled) {
        background: var(--primary-color);
        color: white;
        box-shadow: var(--shadow-md);
    }

    /* ============================================
       NOTIFICATIONS
    ============================================ */
    .notification-container {
        min-height: 60px;
        margin: 1.5rem 0;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification {
        padding: 1rem 1.25rem;
        border-radius: var(--border-radius-sm);
        margin: 0.75rem 0;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .notification:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .notification.info {
        background: linear-gradient(135deg, #e7f5ff, #d0ebff);
        border-left-color: #339af0;
        color: #1864ab;
    }

    .notification.success {
        background: linear-gradient(135deg, #d3f9d8, #b2f2bb);
        border-left-color: #2b8a3e;
        color: #2b8a3e;
    }

    .notification.warning {
        background: linear-gradient(135deg, #fff3bf, #ffec99);
        border-left-color: #f08c00;
        color: #e67700;
    }

    .notification.error {
        background: linear-gradient(135deg, #ffe3e3, #ffc9c9);
        border-left-color: #e03131;
        color: #c92a2a;
    }

    .notification i {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* ============================================
       LOG PANEL
    ============================================ */
    .log-panel {
        background: var(--gray-100);
        border-radius: var(--border-radius-sm);
        margin: 1.5rem 0;
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }

    .log-header {
        background: var(--gray-200);
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid var(--gray-300);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .log-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .log-controls {
        display: flex;
        gap: 0.5rem;
    }

    .log-controls button {
        background: transparent;
        border: 1px solid var(--gray-400);
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        color: var(--gray-600);
        cursor: pointer;
        transition: var(--transition);
    }

    .log-controls button:hover {
        background: var(--gray-300);
        color: var(--gray-800);
    }

    .log-body {
        max-height: 200px;
        overflow-y: auto;
        padding: 1rem;
        font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Courier New', monospace;
        font-size: 0.85rem;
        background: white;
    }

    .log-entry {
        padding: 0.375rem 0.5rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .log-entry:last-child {
        border-bottom: none;
    }

    .log-time {
        color: var(--gray-600);
        font-size: 0.75rem;
        min-width: 85px;
    }

    .log-message {
        flex: 1;
        color: var(--gray-800);
    }

    .log-type {
        font-size: 0.75rem;
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-weight: 500;
        min-width: 70px;
        text-align: center;
    }

    .log-type.info {
        background: rgba(52, 152, 219, 0.1);
        color: #2980b9;
    }

    .log-type.success {
        background: rgba(46, 213, 115, 0.1);
        color: #27ae60;
    }

    .log-type.warning {
        background: rgba(255, 193, 7, 0.1);
        color: #f39c12;
    }

    .log-type.error {
        background: rgba(231, 76, 60, 0.1);
        color: #c0392b;
    }

    /* ============================================
       SETTINGS PANEL
    ============================================ */
    .settings-panel {
        background: white;
        border-radius: var(--border-radius-sm);
        padding: 1.5rem;
        margin: 1.5rem 0;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .setting-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .setting-group label {
        font-weight: 500;
        color: var(--gray-800);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .setting-group label i {
        color: var(--primary-color);
    }

    .setting-group select,
    .setting-group input[type="range"] {
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-sm);
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .setting-group select:focus,
    .setting-group input[type="range"]:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .range-value {
        font-size: 0.875rem;
        color: var(--gray-600);
        text-align: center;
        margin-top: 0.25rem;
    }

    /* ============================================
       FOOTER
    ============================================ */
    .footer-note {
        background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
        padding: 1.25rem;
        text-align: center;
        font-size: 0.875rem;
        color: var(--gray-700);
        border-top: 1px solid var(--gray-300);
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .footer-icons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .footer-icons i {
        font-size: 1.25rem;
        color: var(--primary-color);
        opacity: 0.7;
        transition: var(--transition);
    }

    .footer-icons i:hover {
        opacity: 1;
        transform: translateY(-2px);
    }

    /* ============================================
       MODAL
    ============================================ */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: white;
        border-radius: var(--border-radius-lg);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
        box-shadow: var(--shadow-xl);
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--gray-800);
    }

    .modal-close {
        background: transparent;
        border: none;
        font-size: 1.5rem;
        color: var(--gray-600);
        cursor: pointer;
        padding: 0.25rem;
        line-height: 1;
        border-radius: 4px;
        transition: var(--transition);
    }

    .modal-close:hover {
        background: var(--gray-200);
        color: var(--gray-800);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--gray-200);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* ============================================
       LOADING STATES
    ============================================ */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        border-radius: var(--border-radius-sm);
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: var(--primary-color);
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* ============================================
       RESPONSIVE DESIGN
    ============================================ */
    @media (max-width: 768px) {
        .scan-container {
            margin: 1rem auto;
            padding: 0 0.75rem;
        }
        
        .scan-header {
            padding: 1.5rem 1rem;
        }
        
        .scan-title {
            font-size: 1.5rem;
        }
        
        .camera-section {
            padding: 1rem;
        }
        
        .camera-viewport {
            aspect-ratio: 0.85;
        }
        
        .controls-grid {
            grid-template-columns: 1fr;
        }
        
        .camera-info {
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }
        
        .performance-metrics {
            justify-content: space-between;
        }
        
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .scan-title {
            font-size: 1.25rem;
        }
        
        .scan-subtitle {
            font-size: 0.85rem;
        }
        
        .btn {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .modal-content {
            width: 95%;
            margin: 1rem;
        }
    }

    /* ============================================
       DARK MODE
    ============================================ */
    @media (prefers-color-scheme: dark) {
        .scan-card {
            background: #1e1e2e;
            border-color: #2d3748;
        }
        
        .camera-viewport {
            border-color: #4a5568;
        }
        
        .camera-info {
            background: #2d3748;
            border-color: #4a5568;
        }
        
        .camera-label,
        .metric {
            color: #cbd5e0;
        }
        
        .status-panel {
            background: #2d3748;
            border-left-color: #4c6ef5;
        }
        
        .status-text {
            color: #e2e8f0;
        }
        
        .log-panel {
            background: #2d3748;
            border-color: #4a5568;
        }
        
        .log-header {
            background: #4a5568;
            border-color: #718096;
        }
        
        .log-header h6 {
            color: #e2e8f0;
        }
        
        .log-body {
            background: #1a202c;
        }
        
        .log-entry {
            border-color: #4a5568;
        }
        
        .log-time {
            color: #a0aec0;
        }
        
        .log-message {
            color: #e2e8f0;
        }
        
        .settings-panel {
            background: #2d3748;
            border-color: #4a5568;
        }
        
        .setting-group label {
            color: #e2e8f0;
        }
        
        .setting-group select,
        .setting-group input[type="range"] {
            background: #4a5568;
            border-color: #718096;
            color: #e2e8f0;
        }
        
        .footer-note {
            background: #2d3748;
            border-color: #4a5568;
            color: #cbd5e0;
        }
        
        .modal-content {
            background: #2d3748;
            color: #e2e8f0;
        }
        
        .modal-header {
            border-color: #4a5568;
        }
        
        .modal-header h5 {
            color: #e2e8f0;
        }
        
        .modal-close {
            color: #cbd5e0;
        }
        
        .modal-close:hover {
            background: #4a5568;
        }
        
        .modal-footer {
            border-color: #4a5568;
        }
        
        .notification.info {
            background: linear-gradient(135deg, #1c7ed6, #1971c2);
            color: white;
        }
        
        .notification.success {
            background: linear-gradient(135deg, #2b8a3e, #1c6b2d);
            color: white;
        }
        
        .notification.warning {
            background: linear-gradient(135deg, #f08c00, #e67700);
            color: white;
        }
        
        .notification.error {
            background: linear-gradient(135deg, #e03131, #c92a2a);
            color: white;
        }
    }
</style>

<!-- ============================================
     HTML STRUCTURE
============================================ -->
<div class="scan-container">
    <div class="scan-card">
        <!-- Header -->
        <div class="scan-header">
            <h1 class="scan-title">
                <i class="fas fa-qrcode"></i>
                QR Code Scanner System
            </h1>
            <p class="scan-subtitle">
                Arahkan kamera ke QR Code untuk proses absensi yang cepat dan akurat
            </p>
        </div>

        <!-- Main Content -->
        <div class="camera-section">
            <!-- Camera Container -->
            <div class="camera-container">
                <div class="camera-viewport" id="cameraViewport">
                    <!-- QR Scanner will be injected here -->
                    <div id="reader"></div>
                    
                    <!-- Overlay Elements -->
                    <div class="scan-overlay"></div>
                    <div class="scan-frame"></div>
                    <div class="scan-line"></div>
                    
                    <!-- Loading Overlay -->
                    <div class="loading-overlay" id="cameraLoading">
                        <div class="spinner"></div>
                    </div>
                </div>
                
                <!-- Camera Info -->
                <div class="camera-info">
                    <div class="camera-label">
                        <i class="fas fa-video"></i>
                        <span id="cameraLabel">Menunggu inisialisasi kamera...</span>
                    </div>
                    
                    <div class="performance-metrics">
                        <div class="metric">
                            <i class="fas fa-tachometer-alt"></i>
                            <span id="fpsCounter">0 FPS</span>
                        </div>
                        <div class="metric">
                            <i class="fas fa-expand-alt"></i>
                            <span id="resolutionInfo">—</span>
                        </div>
                        <div class="metric">
                            <i class="fas fa-bolt"></i>
                            <span id="scanCount">0 scans</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Panel -->
            <div class="status-panel">
                <div class="status-indicator">
                    <div class="status-dot" id="statusDot"></div>
                    <div class="status-text" id="statusMessage">
                        Menyiapkan sistem scanner...
                    </div>
                    <div class="status-time" id="statusTime">
                        00:00:00
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="controls-container">
                <div class="controls-grid">
                    <button id="startBtn" class="btn btn-success">
                        <i class="fas fa-play-circle"></i>
                        <span>Mulai Scanning</span>
                    </button>
                    
                    <button id="stopBtn" class="btn btn-danger" disabled>
                        <i class="fas fa-stop-circle"></i>
                        <span>Hentikan</span>
                    </button>
                    
                    <button id="switchBtn" class="btn btn-outline">
                        <i class="fas fa-sync-alt"></i>
                        <span>Ganti Kamera</span>
                    </button>
                    
                    <button id="settingsBtn" class="btn btn-outline">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </button>
                </div>
                
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <button id="flashBtn" class="btn btn-outline" style="padding: 0.5rem 1rem;">
                        <i class="fas fa-lightbulb"></i>
                        Flash
                    </button>
                    <button id="zoomInBtn" class="btn btn-outline" style="padding: 0.5rem 1rem;">
                        <i class="fas fa-search-plus"></i>
                    </button>
                    <button id="zoomOutBtn" class="btn btn-outline" style="padding: 0.5rem 1rem;">
                        <i class="fas fa-search-minus"></i>
                    </button>
                </div>
            </div>

            <!-- Notifications -->
            <div class="notification-container" id="notificationContainer">
                <!-- Notifications will be inserted here -->
            </div>

            <!-- Log Panel -->
            <div class="log-panel">
                <div class="log-header">
                    <h6>
                        <i class="fas fa-history"></i>
                        Scanner Log
                    </h6>
                    <div class="log-controls">
                        <button id="clearLogBtn">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                        <button id="exportLogBtn">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="log-body" id="logBody">
                    <!-- Log entries will be inserted here -->
                </div>
            </div>

            <!-- Settings Panel (Collapsible) -->
            <div class="settings-panel" id="settingsPanel" style="display: none;">
                <h6 class="mb-3">
                    <i class="fas fa-sliders-h me-2"></i>
                    Pengaturan Scanner
                </h6>
                
                <div class="settings-grid">
                    <div class="setting-group">
                        <label for="qualityPreset">
                            <i class="fas fa-chart-line"></i>
                            Preset Kualitas
                        </label>
                        <select id="qualityPreset" class="form-select">
                            <option value="performance">Prioritas Performa</option>
                            <option value="balanced" selected>Seimbang</option>
                            <option value="quality">Prioritas Kualitas</option>
                        </select>
                    </div>
                    
                    <div class="setting-group">
                        <label for="scanSpeed">
                            <i class="fas fa-tachometer-alt"></i>
                            Kecepatan Scan
                        </label>
                        <input type="range" id="scanSpeed" min="1" max="30" value="15" class="form-range">
                        <div class="range-value">
                            <span id="scanSpeedValue">15</span> FPS
                        </div>
                    </div>
                    
                    <div class="setting-group">
                        <label for="soundToggle">
                            <i class="fas fa-volume-up"></i>
                            Suara Feedback
                        </label>
                        <select id="soundToggle" class="form-select">
                            <option value="true">Aktif</option>
                            <option value="false">Nonaktif</option>
                        </select>
                    </div>
                    
                    <div class="setting-group">
                        <label for="vibrationToggle">
                            <i class="fas fa-vibrate"></i>
                            Getar
                        </label>
                        <select id="vibrationToggle" class="form-select">
                            <option value="true">Aktif</option>
                            <option value="false">Nonaktif</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button id="saveSettingsBtn" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan Pengaturan
                    </button>
                    <button id="resetSettingsBtn" class="btn btn-outline btn-sm">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            <div>
                <i class="fas fa-shield-alt me-1"></i>
                Sistem scanner menggunakan teknologi terenkripsi untuk keamanan data
            </div>
            <small>
                Pastikan izin kamera diaktifkan dan QR Code dalam kondisi baik
            </small>
            <div class="footer-icons">
                <i class="fas fa-mobile-alt" title="Mobile Compatible"></i>
                <i class="fas fa-desktop" title="Desktop Compatible"></i>
                <i class="fas fa-bolt" title="High Performance"></i>
                <i class="fas fa-lock" title="Secure"></i>
            </div>
        </div>
    </div>
</div>

<!-- Audio Elements -->
<audio id="scanSuccessSound" preload="auto">
    <source src="<?= base_url('assets/sounds/success.mp3') ?>" type="audio/mpeg">
    <source src="https://assets.mixkit.co/sfx/preview/mixkit-correct-answer-tone-2870.mp3" type="audio/mpeg">
</audio>

<audio id="scanErrorSound" preload="auto">
    <source src="<?= base_url('assets/sounds/error.mp3') ?>" type="audio/mpeg">
    <source src="https://assets.mixkit.co/sfx/preview/mixkit-wrong-answer-fail-notification-946.mp3" type="audio/mpeg">
</audio>

<audio id="cameraStartSound" preload="auto">
    <source src="https://assets.mixkit.co/sfx/preview/mixkit-camera-shutter-click-1133.mp3" type="audio/mpeg">
</audio>

<!-- Help Modal -->
<div class="modal-overlay" id="helpModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><i class="fas fa-question-circle me-2"></i>Bantuan Scanner</h5>
            <button class="modal-close" onclick="closeModal('helpModal')">&times;</button>
        </div>
        <div class="modal-body">
            <h6 class="mb-3">Pemecahan Masalah Kamera</h6>
            
            <div class="mb-4">
                <h6 class="text-primary mb-2">1. Kamera Tidak Terdeteksi</h6>
                <ul class="mb-0">
                    <li>Pastikan kamera tidak sedang digunakan aplikasi lain</li>
                    <li>Restart browser Anda</li>
                    <li>Periksa pengaturan izin kamera di browser</li>
                </ul>
            </div>
            
            <div class="mb-4">
                <h6 class="text-primary mb-2">2. Untuk Localhost (Development)</h6>
                <ul class="mb-0">
                    <li><strong>Chrome:</strong> Buka <code>chrome://flags/#unsafely-treat-insecure-origin-as-secure</code> dan tambahkan <code>http://localhost</code></li>
                    <li><strong>Firefox:</strong> Buka <code>about:config</code> dan set <code>media.devices.insecure.enabled</code> ke <code>true</code></li>
                    <li>Atau gunakan <a href="#" onclick="switchToHttps()" class="text-primary">https://localhost</a></li>
                </ul>
            </div>
            
            <div class="mb-3">
                <h6 class="text-primary mb-2">3. Tips Scanning</h6>
                <ul class="mb-0">
                    <li>Pastikan pencahayaan cukup</li>
                    <li>Jaga jarak 15-30 cm dari QR Code</li>
                    <li>QR Code harus dalam fokus kamera</li>
                    <li>Hindari pantulan cahaya berlebihan</li>
                </ul>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('helpModal')">Tutup</button>
            <button class="btn btn-primary" onclick="testCamera()">
                <i class="fas fa-video me-1"></i> Test Kamera
            </button>
        </div>
    </div>
</div>

<!-- Stats Modal -->
<div class="modal-overlay" id="statsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><i class="fas fa-chart-bar me-2"></i>Statistik Scanner</h5>
            <button class="modal-close" onclick="closeModal('statsModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row text-center mb-4">
                <div class="col-6">
                    <div class="p-3 bg-light rounded">
                        <h2 id="totalScans" class="mb-0">0</h2>
                        <small class="text-muted">Total Scan</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded">
                        <h2 id="successRate" class="mb-0">0%</h2>
                        <small class="text-muted">Success Rate</small>
                    </div>
                </div>
            </div>
            
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td><i class="fas fa-clock text-primary me-2"></i> Waktu Aktif</td>
                        <td id="uptime">00:00:00</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-camera text-primary me-2"></i> Kamera Aktif</td>
                        <td id="activeCamera">—</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-tachometer-alt text-primary me-2"></i> Rata-rata FPS</td>
                        <td id="avgFps">0</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-bolt text-primary me-2"></i> Resolusi</td>
                        <td id="currentResolution">—</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================
     JAVASCRIPT - PROFESSIONAL QR SCANNER
============================================ -->
<script>
// ============================================
// CONFIGURATION
// ============================================
const CONFIG = {
    // Scanner Settings
    DEFAULT_FPS: 15,
    SCAN_COOLDOWN: 1500, // ms
    QR_BOX_SIZE: 250,
    
    // Camera Constraints
    CONSTRAINTS: {
        performance: {
            video: { width: { ideal: 640 }, height: { ideal: 480 } }
        },
        balanced: {
            video: { width: { ideal: 1280 }, height: { ideal: 720 } }
        },
        quality: {
            video: { width: { ideal: 1920 }, height: { ideal: 1080 } }
        }
    },
    
    // Audio Settings
    AUDIO_VOLUME: 0.7,
    
    // Storage Keys
    STORAGE_KEYS: {
        SETTINGS: 'qr_scanner_settings_v3',
        STATS: 'qr_scanner_stats',
        LOGS: 'qr_scanner_logs'
    }
};

// ============================================
// QR SCANNER MANAGER CLASS
// ============================================
class QRScannerManager {
    constructor() {
        this.scanner = null;
        this.cameras = [];
        this.currentCameraIndex = 0;
        this.isScanning = false;
        this.isInitialized = false;
        
        // Performance tracking
        this.stats = {
            totalScans: 0,
            successfulScans: 0,
            startTime: null,
            lastScanTime: null,
            fps: 0,
            frameCount: 0,
            lastFpsUpdate: Date.now()
        };
        
        // Settings
        this.settings = this.loadSettings();
        
        // State
        this.activeStream = null;
        this.zoomLevel = 1;
        this.flashEnabled = false;
        
        // Elements
        this.elements = {
            // Scanner
            reader: document.getElementById('reader'),
            cameraViewport: document.getElementById('cameraViewport'),
            cameraLoading: document.getElementById('cameraLoading'),
            
            // Info displays
            cameraLabel: document.getElementById('cameraLabel'),
            fpsCounter: document.getElementById('fpsCounter'),
            resolutionInfo: document.getElementById('resolutionInfo'),
            scanCount: document.getElementById('scanCount'),
            statusDot: document.getElementById('statusDot'),
            statusMessage: document.getElementById('statusMessage'),
            statusTime: document.getElementById('statusTime'),
            
            // Controls
            startBtn: document.getElementById('startBtn'),
            stopBtn: document.getElementById('stopBtn'),
            switchBtn: document.getElementById('switchBtn'),
            settingsBtn: document.getElementById('settingsBtn'),
            flashBtn: document.getElementById('flashBtn'),
            zoomInBtn: document.getElementById('zoomInBtn'),
            zoomOutBtn: document.getElementById('zoomOutBtn'),
            
            // Settings
            qualityPreset: document.getElementById('qualityPreset'),
            scanSpeed: document.getElementById('scanSpeed'),
            scanSpeedValue: document.getElementById('scanSpeedValue'),
            soundToggle: document.getElementById('soundToggle'),
            vibrationToggle: document.getElementById('vibrationToggle'),
            saveSettingsBtn: document.getElementById('saveSettingsBtn'),
            resetSettingsBtn: document.getElementById('resetSettingsBtn'),
            settingsPanel: document.getElementById('settingsPanel'),
            
            // Logs
            logBody: document.getElementById('logBody'),
            clearLogBtn: document.getElementById('clearLogBtn'),
            exportLogBtn: document.getElementById('exportLogBtn'),
            
            // Notifications
            notificationContainer: document.getElementById('notificationContainer')
        };
        
        // Audio
        this.audio = {
            success: document.getElementById('scanSuccessSound'),
            error: document.getElementById('scanErrorSound'),
            cameraStart: document.getElementById('cameraStartSound')
        };
        
        // Initialize
        this.init();
    }
    
    // ============================================
    // INITIALIZATION
    // ============================================
    async init() {
        try {
            this.log('System initialization started', 'info');
            this.updateStatus('Menginisialisasi sistem...', 'warning');
            
            // Check environment
            await this.checkEnvironment();
            
            // Load scanner library
            await this.loadScannerLibrary();
            
            // Setup event listeners
            this.setupEventListeners();
            
            // Initialize camera
            await this.initializeCamera();
            
            // Load saved logs
            this.loadLogs();
            
            // Update status
            this.updateStatus('Sistem siap digunakan', 'success');
            this.log('System initialized successfully', 'success');
            
            this.isInitialized = true;
            
            // Start scanner automatically if enabled
            if (this.settings.autoStart) {
                setTimeout(() => this.startScanner(), 1000);
            }
            
        } catch (error) {
            console.error('Initialization failed:', error);
            this.log(`Initialization failed: ${error.message}`, 'error');
            this.updateStatus('Inisialisasi gagal', 'error');
            this.showNotification('Gagal menginisialisasi sistem scanner', 'error');
        }
    }
    
    // ============================================
    // ENVIRONMENT CHECK
    // ============================================
    async checkEnvironment() {
        const isLocalhost = window.location.hostname === 'localhost' || 
                           window.location.hostname === '127.0.0.1';
        const isHttps = window.location.protocol === 'https:';
        
        // Log environment
        this.log(`Environment: ${isLocalhost ? 'Localhost' : 'Production'} | ${isHttps ? 'HTTPS' : 'HTTP'}`, 'info');
        
        // Show warning for HTTP on localhost
        if (isLocalhost && !isHttps) {
            this.showNotification(
                '⚠️ Kamera memerlukan HTTPS. Gunakan https://localhost untuk pengalaman terbaik.',
                'warning'
            );
        }
        
        // Check browser compatibility
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error('Browser tidak mendukung akses kamera');
        }
    }
    
    // ============================================
    // SCANNER LIBRARY LOADING
    // ============================================
    async loadScannerLibrary() {
        return new Promise((resolve, reject) => {
            // Check if library already loaded
            if (typeof Html5Qrcode !== 'undefined') {
                this.log('QR Scanner library already loaded', 'info');
                resolve();
                return;
            }
            
            this.log('Loading QR Scanner library...', 'info');
            
            // Try CDN first
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
            script.async = true;
            
            script.onload = () => {
                this.log('QR Scanner library loaded from CDN', 'success');
                resolve();
            };
            
            script.onerror = () => {
                // Try local fallback
                const fallbackScript = document.createElement('script');
                fallbackScript.src = '<?= base_url("assets/js/html5-qrcode.min.js") ?>';
                fallbackScript.async = true;
                
                fallbackScript.onload = () => {
                    this.log('QR Scanner library loaded from local', 'success');
                    resolve();
                };
                
                fallbackScript.onerror = () => {
                    this.log('Failed to load QR Scanner library', 'error');
                    reject(new Error('Tidak dapat memuat library scanner'));
                };
                
                document.head.appendChild(fallbackScript);
            };
            
            document.head.appendChild(script);
        });
    }
    
    // ============================================
    // CAMERA MANAGEMENT
    // ============================================
    async initializeCamera() {
        try {
            this.updateStatus('Mendeteksi kamera...', 'warning');
            
            // Get available cameras
            await this.getAvailableCameras();
            
            if (this.cameras.length === 0) {
                throw new Error('Tidak ada kamera yang terdeteksi');
            }
            
            // Create scanner instance
            this.scanner = new Html5Qrcode("reader");
            
            // Update camera info
            this.updateCameraInfo();
            this.updateStatus('Kamera siap', 'success');
            this.log(`Found ${this.cameras.length} camera(s)`, 'success');
            
        } catch (error) {
            this.log(`Camera initialization failed: ${error.message}`, 'error');
            throw error;
        }
    }
    
    async getAvailableCameras() {
        try {
            // First get user media to ensure permissions
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: true,
                audio: false 
            });
            
            // Stop the stream immediately
            stream.getTracks().forEach(track => track.stop());
            
            // Now enumerate devices
            const devices = await navigator.mediaDevices.enumerateDevices();
            this.cameras = devices.filter(device => device.kind === 'videoinput');
            
            // If no cameras found, provide fallback options
            if (this.cameras.length === 0) {
                this.cameras = [
                    { deviceId: 'environment', label: 'Kamera Belakang (Default)' },
                    { deviceId: 'user', label: 'Kamera Depan' }
                ];
            }
            
            // Set initial camera based on settings or preference
            this.currentCameraIndex = this.findPreferredCameraIndex();
            
        } catch (error) {
            this.log(`Camera enumeration failed: ${error.message}`, 'error');
            throw error;
        }
    }
    
    findPreferredCameraIndex() {
        // Check settings first
        if (this.settings.preferredCamera) {
            const index = this.cameras.findIndex(cam => 
                cam.deviceId === this.settings.preferredCamera
            );
            if (index >= 0) return index;
        }
        
        // Try to find back camera
        const backIndex = this.cameras.findIndex(cam => 
            cam.label.toLowerCase().includes('back') || 
            cam.label.toLowerCase().includes('rear') ||
            cam.deviceId === 'environment'
        );
        
        if (backIndex >= 0) return backIndex;
        
        // Try to find front camera
        const frontIndex = this.cameras.findIndex(cam => 
            cam.label.toLowerCase().includes('front') || 
            cam.label.toLowerCase().includes('user')
        );
        
        if (frontIndex >= 0) return frontIndex;
        
        // Default to first camera
        return 0;
    }
    
    updateCameraInfo() {
        if (this.cameras.length > 0) {
            const camera = this.cameras[this.currentCameraIndex];
            this.elements.cameraLabel.textContent = camera.label || 'Kamera Tidak Dikenal';
        }
    }
    
    // ============================================
    // SCANNER CONTROL
    // ============================================
    async startScanner() {
        if (this.isScanning || !this.scanner) return;
        
        try {
            this.updateStatus('Memulai scanner...', 'warning');
            this.showNotification('Mengaktifkan kamera...', 'info');
            
            // Update UI
            this.elements.startBtn.disabled = true;
            this.elements.stopBtn.disabled = false;
            this.elements.cameraLoading.style.display = 'flex';
            
            // Get camera config
            const cameraId = this.cameras[this.currentCameraIndex].deviceId;
            const config = this.getScannerConfig();
            
            // Start scanner
            await this.scanner.start(
                cameraId,
                config,
                (decodedText) => this.onScanSuccess(decodedText),
                (error) => this.onScanError(error)
            );
            
            // Update state
            this.isScanning = true;
            this.stats.startTime = Date.now();
            this.stats.lastScanTime = null;
            
            // Update UI
            this.elements.cameraLoading.style.display = 'none';
            this.updateStatus('Scanner aktif - Siap memindai', 'success');
            this.showNotification('Scanner berhasil diaktifkan', 'success');
            this.log('Scanner started successfully', 'success');
            
            // Play sound if enabled
            if (this.settings.soundEnabled && this.audio.cameraStart) {
                this.audio.cameraStart.currentTime = 0;
                this.audio.cameraStart.play().catch(() => {});
            }
            
            // Start FPS counter
            this.startFpsCounter();
            
            // Start uptime counter
            this.startUptimeCounter();
            
        } catch (error) {
            console.error('Failed to start scanner:', error);
            this.handleStartError(error);
            
            // Reset UI
            this.elements.startBtn.disabled = false;
            this.elements.stopBtn.disabled = true;
            this.elements.cameraLoading.style.display = 'none';
        }
    }
    
    async stopScanner() {
        if (!this.isScanning || !this.scanner) return;
        
        try {
            this.updateStatus('Menghentikan scanner...', 'warning');
            
            // Stop scanner
            await this.scanner.stop();
            
            // Update state
            this.isScanning = false;
            
            // Update UI
            this.elements.startBtn.disabled = false;
            this.elements.stopBtn.disabled = true;
            this.updateStatus('Scanner dihentikan', 'info');
            this.showNotification('Scanner dihentikan', 'info');
            this.log('Scanner stopped', 'info');
            
            // Stop counters
            this.stopFpsCounter();
            this.stopUptimeCounter();
            
            // Save stats
            this.saveStats();
            
        } catch (error) {
            console.error('Failed to stop scanner:', error);
            this.log(`Error stopping scanner: ${error.message}`, 'error');
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
            await this.stopScanner();
        }
        
        // Switch to next camera
        this.currentCameraIndex = (this.currentCameraIndex + 1) % this.cameras.length;
        this.updateCameraInfo();
        
        const cameraName = this.cameras[this.currentCameraIndex].label || `Kamera ${this.currentCameraIndex + 1}`;
        this.showNotification(`Beralih ke: ${cameraName}`, 'info');
        this.log(`Switched to camera: ${cameraName}`, 'info');
        
        // Save preferred camera
        this.settings.preferredCamera = this.cameras[this.currentCameraIndex].deviceId;
        this.saveSettings();
        
        if (wasScanning) {
            setTimeout(() => this.startScanner(), 500);
        }
    }
    
    getScannerConfig() {
        const fps = parseInt(this.elements.scanSpeed.value) || CONFIG.DEFAULT_FPS;
        const quality = this.elements.qualityPreset.value;
        
        return {
            fps: fps,
            qrbox: {
                width: CONFIG.QR_BOX_SIZE,
                height: CONFIG.QR_BOX_SIZE
            },
            aspectRatio: 1.0,
            disableFlip: quality === 'performance',
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: false
            }
        };
    }
    
    // ============================================
    // SCAN HANDLERS
    // ============================================
    onScanSuccess(decodedText) {
        // Update FPS counter
        this.stats.frameCount++;
        
        // Check cooldown
        const now = Date.now();
        if (this.stats.lastScanTime && (now - this.stats.lastScanTime) < CONFIG.SCAN_COOLDOWN) {
            return;
        }
        
        this.stats.lastScanTime = now;
        this.stats.totalScans++;
        
        this.log(`QR Code detected: ${decodedText.substring(0, 50)}...`, 'success');
        
        try {
            // Process QR code
            const token = this.extractTokenFromQR(decodedText);
            
            if (!token) {
                this.showNotification('QR Code tidak valid', 'warning');
                this.playSound('error');
                return;
            }
            
            // Update stats
            this.stats.successfulScans++;
            
            // Provide feedback
            this.updateStatus('QR Code valid!', 'success');
            this.showNotification('QR Code berhasil dipindai', 'success');
            this.playSound('success');
            
            if (this.settings.vibrationEnabled && navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }
            
            // Stop scanner and redirect
            setTimeout(async () => {
                await this.stopScanner();
                
                // Add slight delay for better UX
                setTimeout(() => {
                    this.redirectToAttendance(token);
                }, 800);
            }, 500);
            
        } catch (error) {
            this.log(`Error processing QR: ${error.message}`, 'error');
            this.showNotification('Error memproses QR Code', 'error');
            this.playSound('error');
        }
    }
    
    onScanError(error) {
        // Update FPS counter
        this.stats.frameCount++;
        
        // Suppress common "not found" errors
        if (error && error.message && !error.message.includes('NotFoundException')) {
            this.log(`Scan error: ${error.message}`, 'error');
        }
    }
    
    extractTokenFromQR(text) {
        try {
            // Handle URL format
            if (text.includes('token=')) {
                let urlString = text;
                if (!text.includes('://')) {
                    urlString = `https://dummy.com/?${text}`;
                }
                
                const url = new URL(urlString);
                return url.searchParams.get('token');
            }
            
            // Handle raw token format
            if (/^[a-z0-9\-_]{8,64}$/i.test(text)) {
                return text;
            }
            
            return null;
        } catch (error) {
            return null;
        }
    }
    
    redirectToAttendance(token) {
        const baseUrl = "<?= smart_url('absensi/scan') ?>";
        const redirectUrl = `${baseUrl}?token=${encodeURIComponent(token)}`;
        
        this.log(`Redirecting to: ${redirectUrl}`, 'info');
        window.location.href = redirectUrl;
    }
    
    // ============================================
    // ERROR HANDLING
    // ============================================
    handleStartError(error) {
        let message = 'Gagal memulai kamera';
        let notificationType = 'error';
        
        switch(error.name) {
            case 'NotAllowedError':
                message = 'Izin kamera ditolak. Silakan berikan izin akses kamera.';
                notificationType = 'error';
                break;
                
            case 'NotFoundError':
                message = 'Tidak ada kamera yang ditemukan pada perangkat ini.';
                notificationType = 'error';
                break;
                
            case 'NotReadableError':
                message = 'Kamera sedang digunakan oleh aplikasi lain.';
                notificationType = 'warning';
                break;
                
            case 'OverconstrainedError':
                message = 'Kamera tidak mendukung resolusi yang diminta.';
                notificationType = 'warning';
                break;
                
            case 'TypeError':
                message = 'Parameter kamera tidak valid.';
                notificationType = 'error';
                break;
        }
        
        this.updateStatus(message, 'error');
        this.showNotification(message, notificationType);
        this.log(`Camera start error: ${error.message}`, 'error');
    }
    
    // ============================================
    // UI UPDATES
    // ============================================
    updateStatus(message, type = 'info') {
        const dot = this.elements.statusDot;
        const text = this.elements.statusMessage;
        
        text.textContent = message;
        
        // Reset classes
        dot.className = 'status-dot';
        
        switch(type) {
            case 'success':
                dot.classList.add('active');
                break;
            case 'warning':
                dot.classList.add('warning');
                break;
            case 'error':
                dot.classList.add('error');
                break;
        }
    }
    
    showNotification(message, type = 'info') {
        const container = this.elements.notificationContainer;
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        // Set icon based on type
        let icon = 'info-circle';
        switch(type) {
            case 'success': icon = 'check-circle'; break;
            case 'warning': icon = 'exclamation-triangle'; break;
            case 'error': icon = 'times-circle'; break;
        }
        
        notification.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
        `;
        
        // Add to container
        container.appendChild(notification);
        
        // Auto-remove after timeout
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, type === 'error' ? 5000 : 3000);
    }
    
    // ============================================
    // LOGGING SYSTEM
    // ============================================
    log(message, type = 'info') {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        
        const dateString = now.toLocaleDateString('id-ID');
        
        // Create log entry
        const logEntry = document.createElement('div');
        logEntry.className = 'log-entry';
        
        logEntry.innerHTML = `
            <div class="log-time">${dateString} ${timeString}</div>
            <div class="log-message">${message}</div>
            <div class="log-type ${type}">${type.toUpperCase()}</div>
        `;
        
        // Add to log body
        const logBody = this.elements.logBody;
        logBody.insertBefore(logEntry, logBody.firstChild);
        
        // Limit log entries
        const maxEntries = 50;
        while (logBody.children.length > maxEntries) {
            logBody.removeChild(logBody.lastChild);
        }
        
        // Save to localStorage
        this.saveLogEntry({
            timestamp: now.toISOString(),
            message: message,
            type: type
        });
    }
    
    saveLogEntry(entry) {
        try {
            const logs = this.loadLogsFromStorage();
            logs.push(entry);
            
            // Keep only last 100 entries
            if (logs.length > 100) {
                logs.splice(0, logs.length - 100);
            }
            
            localStorage.setItem(CONFIG.STORAGE_KEYS.LOGS, JSON.stringify(logs));
        } catch (error) {
            console.error('Failed to save log:', error);
        }
    }
    
    loadLogs() {
        try {
            const logs = this.loadLogsFromStorage();
            
            logs.forEach(log => {
                const date = new Date(log.timestamp);
                const timeString = date.toLocaleTimeString('id-ID', { 
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const dateString = date.toLocaleDateString('id-ID');
                
                const logEntry = document.createElement('div');
                logEntry.className = 'log-entry';
                
                logEntry.innerHTML = `
                    <div class="log-time">${dateString} ${timeString}</div>
                    <div class="log-message">${log.message}</div>
                    <div class="log-type ${log.type}">${log.type.toUpperCase()}</div>
                `;
                
                this.elements.logBody.appendChild(logEntry);
            });
            
        } catch (error) {
            console.error('Failed to load logs:', error);
        }
    }
    
    loadLogsFromStorage() {
        try {
            const logs = localStorage.getItem(CONFIG.STORAGE_KEYS.LOGS);
            return logs ? JSON.parse(logs) : [];
        } catch (error) {
            return [];
        }
    }
    
    clearLogs() {
        this.elements.logBody.innerHTML = '';
        localStorage.removeItem(CONFIG.STORAGE_KEYS.LOGS);
        this.log('Logs cleared', 'info');
    }
    
    exportLogs() {
        try {
            const logs = this.loadLogsFromStorage();
            const logText = logs.map(log => 
                `[${new Date(log.timestamp).toLocaleString('id-ID')}] ${log.type.toUpperCase()}: ${log.message}`
            ).join('\n');
            
            const blob = new Blob([logText], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = `scanner_logs_${new Date().toISOString().slice(0,10)}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            this.log('Logs exported successfully', 'success');
            
        } catch (error) {
            this.log(`Failed to export logs: ${error.message}`, 'error');
        }
    }
    
    // ============================================
    // PERFORMANCE COUNTERS
    // ============================================
    startFpsCounter() {
        this.fpsInterval = setInterval(() => {
            const now = Date.now();
            const elapsed = (now - this.stats.lastFpsUpdate) / 1000;
            
            if (elapsed > 0) {
                this.stats.fps = Math.round(this.stats.frameCount / elapsed);
                this.elements.fpsCounter.textContent = `${this.stats.fps} FPS`;
            }
            
            this.stats.frameCount = 0;
            this.stats.lastFpsUpdate = now;
        }, 1000);
    }
    
    stopFpsCounter() {
        if (this.fpsInterval) {
            clearInterval(this.fpsInterval);
            this.fpsInterval = null;
            this.elements.fpsCounter.textContent = '0 FPS';
        }
    }
    
    startUptimeCounter() {
        this.uptimeInterval = setInterval(() => {
            if (this.stats.startTime) {
                const elapsed = Date.now() - this.stats.startTime;
                const hours = Math.floor(elapsed / 3600000);
                const minutes = Math.floor((elapsed % 3600000) / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);
                
                this.elements.statusTime.textContent = 
                    `${hours.toString().padStart(2, '0')}:` +
                    `${minutes.toString().padStart(2, '0')}:` +
                    `${seconds.toString().padStart(2, '0')}`;
                    
                this.elements.scanCount.textContent = `${this.stats.totalScans} scans`;
            }
        }, 1000);
    }
    
    stopUptimeCounter() {
        if (this.uptimeInterval) {
            clearInterval(this.uptimeInterval);
            this.uptimeInterval = null;
        }
    }
    
    // ============================================
    // AUDIO FEEDBACK
    // ============================================
    playSound(type) {
        if (!this.settings.soundEnabled) return;
        
        try {
            const audio = this.audio[type];
            if (audio) {
                audio.currentTime = 0;
                audio.volume = CONFIG.AUDIO_VOLUME;
                audio.play().catch(() => {
                    // Silent fail for audio
                });
            }
        } catch (error) {
            console.warn('Audio playback failed:', error);
        }
    }
    
    // ============================================
    // SETTINGS MANAGEMENT
    // ============================================
    loadSettings() {
        try {
            const saved = localStorage.getItem(CONFIG.STORAGE_KEYS.SETTINGS);
            const defaultSettings = {
                quality: 'balanced',
                scanSpeed: 15,
                soundEnabled: true,
                vibrationEnabled: true,
                autoStart: true,
                preferredCamera: null
            };
            
            return saved ? { ...defaultSettings, ...JSON.parse(saved) } : defaultSettings;
            
        } catch (error) {
            console.error('Failed to load settings:', error);
            return {
                quality: 'balanced',
                scanSpeed: 15,
                soundEnabled: true,
                vibrationEnabled: true,
                autoStart: true,
                preferredCamera: null
            };
        }
    }
    
    saveSettings() {
        try {
            this.settings.quality = this.elements.qualityPreset.value;
            this.settings.scanSpeed = parseInt(this.elements.scanSpeed.value);
            this.settings.soundEnabled = this.elements.soundToggle.value === 'true';
            this.settings.vibrationEnabled = this.elements.vibrationToggle.value === 'true';
            
            localStorage.setItem(CONFIG.STORAGE_KEYS.SETTINGS, JSON.stringify(this.settings));
            
            this.showNotification('Pengaturan disimpan', 'success');
            this.log('Settings saved', 'success');
            
        } catch (error) {
            console.error('Failed to save settings:', error);
            this.showNotification('Gagal menyimpan pengaturan', 'error');
        }
    }
    
    loadStats() {
        try {
            const saved = localStorage.getItem(CONFIG.STORAGE_KEYS.STATS);
            return saved ? JSON.parse(saved) : {
                totalScans: 0,
                successfulScans: 0,
                totalUptime: 0
            };
        } catch (error) {
            return {
                totalScans: 0,
                successfulScans: 0,
                totalUptime: 0
            };
        }
    }
    
    saveStats() {
        try {
            if (this.stats.startTime) {
                const stats = this.loadStats();
                stats.totalScans += this.stats.totalScans;
                stats.successfulScans += this.stats.successfulScans;
                stats.totalUptime += Date.now() - this.stats.startTime;
                
                localStorage.setItem(CONFIG.STORAGE_KEYS.STATS, JSON.stringify(stats));
            }
        } catch (error) {
            console.error('Failed to save stats:', error);
        }
    }
    
    // ============================================
    // EVENT LISTENERS
    // ============================================
    setupEventListeners() {
        // Scanner controls
        this.elements.startBtn.addEventListener('click', () => this.startScanner());
        this.elements.stopBtn.addEventListener('click', () => this.stopScanner());
        this.elements.switchBtn.addEventListener('click', () => this.switchCamera());
        
        // Settings controls
        this.elements.settingsBtn.addEventListener('click', () => {
            const panel = this.elements.settingsPanel;
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        });
        
        this.elements.saveSettingsBtn.addEventListener('click', () => this.saveSettings());
        this.elements.resetSettingsBtn.addEventListener('click', () => this.resetSettings());
        
        // Range inputs
        this.elements.scanSpeed.addEventListener('input', (e) => {
            this.elements.scanSpeedValue.textContent = e.target.value;
        });
        
        // Log controls
        this.elements.clearLogBtn.addEventListener('click', () => this.clearLogs());
        this.elements.exportLogBtn.addEventListener('click', () => this.exportLogs());
        
        // Camera controls
        this.elements.flashBtn.addEventListener('click', () => this.toggleFlash());
        this.elements.zoomInBtn.addEventListener('click', () => this.adjustZoom(1.2));
        this.elements.zoomOutBtn.addEventListener('click', () => this.adjustZoom(0.8));
        
        // Page visibility
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && this.isScanning) {
                this.stopScanner();
            }
        });
        
        // Before unload
        window.addEventListener('beforeunload', () => {
            if (this.isScanning) {
                this.scanner?.stop().catch(() => {});
            }
            this.saveStats();
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') return;
            
            switch(e.key) {
                case ' ':
                    e.preventDefault();
                    if (this.isScanning) this.stopScanner();
                    else this.startScanner();
                    break;
                    
                case 's':
                case 'S':
                    if (e.ctrlKey) {
                        e.preventDefault();
                        this.elements.settingsPanel.style.display = 
                            this.elements.settingsPanel.style.display === 'none' ? 'block' : 'none';
                    }
                    break;
                    
                case 'c':
                case 'C':
                    if (e.ctrlKey) {
                        e.preventDefault();
                        this.switchCamera();
                    }
                    break;
                    
                case 'Escape':
                    if (this.isScanning) {
                        this.stopScanner();
                    }
                    break;
            }
        });
    }
    
    // ============================================
    // CAMERA CONTROLS
    // ============================================
    async toggleFlash() {
        if (!this.activeStream) return;
        
        try {
            const track = this.activeStream.getVideoTracks()[0];
            const capabilities = track.getCapabilities();
            
            if (capabilities.torch) {
                await track.applyConstraints({
                    advanced: [{ torch: !this.flashEnabled }]
                });
                
                this.flashEnabled = !this.flashEnabled;
                this.elements.flashBtn.innerHTML = this.flashEnabled ? 
                    '<i class="fas fa-lightbulb text-warning"></i> Flash ON' :
                    '<i class="fas fa-lightbulb"></i> Flash';
                    
                this.showNotification(
                    `Flash ${this.flashEnabled ? 'diaktifkan' : 'dimatikan'}`,
                    this.flashEnabled ? 'info' : 'warning'
                );
            }
        } catch (error) {
            this.log(`Flash toggle failed: ${error.message}`, 'error');
        }
    }
    
    async adjustZoom(factor) {
        if (!this.activeStream) return;
        
        try {
            const track = this.activeStream.getVideoTracks()[0];
            const capabilities = track.getCapabilities();
            
            if (capabilities.zoom) {
                this.zoomLevel *= factor;
                this.zoomLevel = Math.max(
                    capabilities.zoom.min || 1,
                    Math.min(this.zoomLevel, capabilities.zoom.max || 10)
                );
                
                await track.applyConstraints({
                    advanced: [{ zoom: this.zoomLevel }]
                });
                
                this.showNotification(`Zoom: ${this.zoomLevel.toFixed(1)}x`, 'info');
            }
        } catch (error) {
            this.log(`Zoom adjustment failed: ${error.message}`, 'error');
        }
    }
    
    // ============================================
    // UTILITY METHODS
    // ============================================
    resetSettings() {
        if (confirm('Reset semua pengaturan ke default?')) {
            localStorage.removeItem(CONFIG.STORAGE_KEYS.SETTINGS);
            localStorage.removeItem(CONFIG.STORAGE_KEYS.STATS);
            
            // Reload page
            window.location.reload();
        }
    }
    
    showStats() {
        const stats = this.loadStats();
        
        document.getElementById('totalScans').textContent = stats.totalScans;
        document.getElementById('successRate').textContent = 
            stats.totalScans > 0 ? 
            Math.round((stats.successfulScans / stats.totalScans) * 100) + '%' : 
            '0%';
            
        document.getElementById('activeCamera').textContent = 
            this.cameras[this.currentCameraIndex]?.label || '—';
            
        document.getElementById('avgFps').textContent = this.stats.fps;
        
        openModal('statsModal');
    }
}

// ============================================
// GLOBAL FUNCTIONS
// ============================================
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function switchToHttps() {
    if (window.location.protocol === 'http:') {
        const httpsUrl = window.location.href.replace('http://', 'https://');
        window.location.href = httpsUrl;
    }
}

async function testCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'environment' } 
        });
        
        alert('✓ Kamera berfungsi dengan baik!');
        
        stream.getTracks().forEach(track => track.stop());
        
    } catch (error) {
        alert(`✗ Error kamera: ${error.message}`);
    }
}

// ============================================
// INITIALIZE ON LOAD
// ============================================
let scannerManager;

document.addEventListener('DOMContentLoaded', () => {
    // Initialize scanner
    scannerManager = new QRScannerManager();
    
    // Add global helper functions
    window.testCamera = testCamera;
    window.switchToHttps = switchToHttps;
    window.showScannerStats = () => scannerManager?.showStats();
    
    // Add help button to footer
    const footer = document.querySelector('.footer-note');
    const helpBtn = document.createElement('button');
    helpBtn.className = 'btn btn-sm btn-outline mt-2';
    helpBtn.innerHTML = '<i class="fas fa-question-circle me-1"></i> Bantuan';
    helpBtn.onclick = () => openModal('helpModal');
    footer.appendChild(helpBtn);
    
    // Add stats button to footer
    const statsBtn = document.createElement('button');
    statsBtn.className = 'btn btn-sm btn-outline mt-2 ms-2';
    statsBtn.innerHTML = '<i class="fas fa-chart-bar me-1"></i> Stats';
    statsBtn.onclick = () => scannerManager?.showStats();
    footer.appendChild(statsBtn);
});
</script>

<?= $this->endSection() ?>