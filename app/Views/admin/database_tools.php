<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* ------------------------------------------
       PREMIUM GRADIENT THEME
       ------------------------------------------ */
    .db-tools {
        max-width: 100% !important;
        width: 100% !important;
        margin: 1.6rem auto;
        padding: 0 0.6rem !important;
        /* lebih lebar di mobile */
    }

    .card-gradient {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.04) 100%);
        border: 1px solid rgba(118, 75, 162, 0.08);
        box-shadow: 0 8px 28px rgba(29, 38, 62, 0.04);
        border-radius: 14px;
        padding: 18px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 10px 28px rgba(12, 22, 60, 0.04);
        width: 100%;
    }

    .tools-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: .6rem;
        padding: 0 .2rem;
    }

    .h-title {
        display: flex;
        gap: .8rem;
        align-items: center;
    }

    .h-title .icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 6px 18px rgba(102, 126, 234, 0.18);
    }

    /* ------------------------------------------
       GRID SECTION (Backup + Restore)
       Tidak sempit di semua device 💯
       ------------------------------------------ */
    .section-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 1rem;
        margin-top: .8rem;
        width: 100%;
    }

    @media (max-width: 480px) {
        .section-row {
            grid-template-columns: 1fr !important;
        }
    }

    .card-inner {
        padding: 14px;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 10px;
        border: 1px solid rgba(118, 75, 162, 0.03);
        width: 100%;
    }

    .btn-primary-gradient {
        background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        color: #04203a;
        font-weight: 700;
    }

    .kv-note {
        font-size: .90rem;
        color: #6c7177;
    }

    .table-files td,
    .table-files th {
        vertical-align: middle;
    }

    .input-file {
        display: flex;
        gap: .6rem;
        align-items: center;
    }

    .badge-mode {
        background: linear-gradient(90deg, #667eea, #764ba2);
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 700;
        font-size: .88rem;
    }

    .alert-light-custom {
        background: rgba(46, 213, 115, 0.07);
        border: 1px solid rgba(46, 213, 115, 0.12);
        color: rgba(10, 80, 40, 0.9);
    }

    .form-small {
        max-width: 420px;
    }

    /* ------------------------------------------
       FIX KHUSUS HALAMAN BACKUP — ANTI SEMPIT
       ------------------------------------------ */

    /* Hilangkan batas container global */
    .main-content,
    .container,
    .wrapper,
    .page-content {
        max-width: 100% !important;
        width: 100% !important;
    }

    /* Responsive Cards */
    @media (max-width: 768px) {

        .card,
        .card-gradient,
        .card-inner {
            width: 100% !important;
            margin: 0 !important;
            padding: 14px !important;
        }
    }

    /* Table responsive */
    .table-responsive {
        width: 100% !important;
        overflow-x: auto;
    }
</style>


<div class="db-tools">
    <div class="card-gradient">
        <div class="tools-header">
            <div class="h-title">
                <div class="icon"><i class="fas fa-database"></i></div>
                <div>
                    <h2 style="margin:0">Database Tools</h2>
                    <div style="color: rgba(0,0,0,0.56)">Backup & Restore — kelola cadangan database dengan aman</div>
                </div>
            </div>

            <div style="text-align:right">
                <div class="kv-note">Mode: <span class="badge-mode"><?= esc($mode ?? 'local') ?></span></div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="section-row">
            <!-- BACKUP CARD -->
            <div class="card-inner card">
                <h5><i class="fa fa-save me-2"></i> Backup Database</h5>
                <p class="kv-note">Buat salinan database saat ini. File akan disimpan ke <code>writable/backups/<?= esc($mode ?? 'local') ?></code>.</p>

                <form action="<?= smart_url('admin/database-tools/backup') ?>" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button class="btn btn-primary btn-primary-gradient" id="btnBackup">
                        <i class="fa fa-download me-2"></i> Backup Sekarang
                    </button>
                </form>

                <hr>

                <h6>Riwayat Backup</h6>
                <?php if (empty($files)): ?>
                    <div class="alert alert-secondary">Belum ada backup.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-files table-striped">
                            <thead>
                                <tr>
                                    <th>Nama File</th>
                                    <th>Ukuran</th>
                                    <th>Waktu</th>
                                    <th style="width:220px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($files as $f): ?>
                                    <tr>
                                        <td><?= esc($f['name']) ?></td>
                                        <td><?= esc($f['size']) ?></td>
                                        <td><?= esc($f['mtime']) ?></td>
                                        <td style="white-space:nowrap">
                                            <a href="<?= smart_url('admin/database-tools/download/' . urlencode($f['name'])) ?>" class="btn btn-sm btn-success">
                                                <i class="fa fa-cloud-download-alt"></i> Download
                                            </a>

                                            <form action="<?= smart_url('admin/database-tools/delete') ?>" method="post" style="display:inline;" onsubmit="return confirm('Hapus file <?= esc($f['name']) ?> ?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="file" value="<?= esc($f['name']) ?>">
                                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RESTORE CARD -->
            <div class="card-inner card">
                <h5><i class="fa fa-undo me-2"></i> Restore Database</h5>
                <p class="kv-note">Upload file backup (.sql atau .zip) lalu jalankan restore. Sebelum proses restore, sistem akan otomatis membuat <strong>auto-backup safety</strong>.</p>

                <div class="mb-2">
                    <form action="<?= smart_url('admin/database-tools/restore-upload') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="input-file">
                            <input type="file" name="restore_file" accept=".sql,.zip" class="form-control form-control-sm" required id="restoreInput">
                            <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                        <div class="form-text mt-1">Ukuran maksimal sesuai konfigurasi hosting. Jika file zip, pastikan berisi file .sql.</div>
                    </form>
                </div>

                <?php if (session()->get('restore_file')): ?>
                    <div class="alert alert-warning">
                        <strong>File siap restore:</strong>
                        <div style="margin-top:.4rem; font-family:monospace;"><?= esc(session()->get('restore_file')) ?></div>
                    </div>

                    <div class="form-small">
                        <form action="<?= smart_url('admin/database-tools/restore-run') ?>" method="post" onsubmit="return confirmRestore();">
                            <?= csrf_field() ?>

                            <div class="mb-2">
                                <label for="adminPassword" class="form-label"><strong>Konfirmasi Password Admin</strong></label>
                                <input type="password" name="password" id="adminPassword" class="form-control" required placeholder="Masukkan password akun Anda untuk konfirmasi">
                                <div class="form-text">Password akan diverifikasi sebelum proses restore. Hanya Superadmin yang bisa melakukan restore.</div>
                            </div>

                            <div class="d-flex gap-2">
                                <button id="btnRestore" class="btn btn-danger"><i class="fa fa-exclamation-triangle me-1"></i> Restore Sekarang</button>
                                <a href="<?= current_url() ?>" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="alert alert-secondary">Belum ada file restore diunggah.</div>
                <?php endif; ?>

                <hr>

                <div class="alert alert-light-custom small">
                    <strong>Catatan Keamanan:</strong>
                    <ul style="margin:0; padding-left:1.05rem;">
                        <li>Sebelum restore, sistem akan otomatis membuat backup safety</li>
                        <li>Restore akan menimpa seluruh data saat ini — pastikan file backup benar</li>
                        <li>Hanya akun dengan hak admin dapat melakukan restore</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmRestore() {
        const pw = document.getElementById('adminPassword').value || '';
        if (pw.trim().length < 4) {
            alert('Masukkan password yang valid untuk konfirmasi.');
            return false;
        }

        if (!confirm('Proses restore akan menimpa seluruh data saat ini. Sistem juga akan membuat backup safety. Lanjutkan?')) {
            return false;
        }

        // disable button to avoid double submit
        const btn = document.getElementById('btnRestore');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
        }
        return true;
    }

    // small UX: show filename when chosen
    const input = document.getElementById('restoreInput');
    if (input) {
        input.addEventListener('change', function() {
            const f = this.files && this.files[0];
            if (f) {
                const text = document.createElement('div');
                text.className = 'form-text mt-2';
                text.innerText = 'File terpilih: ' + f.name;
                if (this.parentNode && !this.parentNode.querySelector('.form-text.file-name')) {
                    text.classList.add('file-name');
                    this.parentNode.appendChild(text);
                } else {
                    const existing = this.parentNode.querySelector('.form-text.file-name');
                    if (existing) existing.innerText = 'File terpilih: ' + f.name;
                }
            }
        });
    }
</script>

<?= $this->endSection() ?>