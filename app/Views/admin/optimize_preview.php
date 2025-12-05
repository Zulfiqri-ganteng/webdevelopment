<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    .opt-container {
        max-width: 1100px;
        margin: auto;
        padding: 1rem;
    }

    .opt-title {
        display: flex;
        align-items: center;
        gap: .7rem;
        font-weight: 700;
        font-size: 1.4rem;
        color: #2d2d2d;
    }

    .opt-title i {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
        padding: .6rem;
        border-radius: 10px;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(0, 150, 200, 0.3);
    }

    .opt-card {
        background: white;
        border-radius: 16px;
        padding: 1.3rem;
        margin-top: 1rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* GRID LIST ORPHAN FILES */
    .orphan-grid {
        display: grid;
        gap: .9rem;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        margin-top: 1rem;
    }

    .orphan-card {
        background: #f9fafc;
        padding: .9rem;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, .05);
        display: flex;
        align-items: center;
        gap: .9rem;
    }

    .orphan-card img {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .file-icon {
        width: 58px;
        height: 58px;
        background: #e3e9ff;
        color: #445;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        border: 1px solid rgba(0, 0, 0, .05);
    }

    .btn-run {
        background: linear-gradient(90deg, #ff5f6d, #ffc371);
        border: none;
        padding: .9rem;
        font-weight: bold;
        color: white;
        font-size: 1.1rem;
        border-radius: 12px;
        width: 100%;
        margin-top: 1.5rem;
    }

    .text-small {
        font-size: .82rem;
        color: #555;
    }

    @media (max-width: 768px) {
        .opt-container {
            padding: .7rem;
        }
    }
</style>

<div class="opt-container">

    <div class="opt-title">
        <i class="fas fa-search"></i>
        Preview File Orphan
    </div>

    <div class="opt-card">

        <p class="text-muted">
            Berikut adalah daftar file yang <b>tidak ditemukan di database</b>.
        </p>

        <?php if (empty($orphans)) : ?>

            <div class="alert alert-success mt-3">
                Tidak ada file orphan ditemukan. Storage Anda bersih! 🎉
            </div>

        <?php else: ?>

            <div class="alert alert-warning mt-2">
                Ditemukan <b><?= count($orphans) ?></b> file orphan.
            </div>

            <div class="orphan-grid">

                <?php foreach ($orphans as $f): ?>

                    <?php
                    $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f['file']);
                    $previewURL = base_url(str_replace('writable/', '', $f['path']));
                    ?>

                    <div class="orphan-card">

                        <?php if ($isImage): ?>
                            <img src="<?= $previewURL ?>" alt="preview">
                        <?php else: ?>
                            <div class="file-icon">
                                <i class="fas fa-file"></i>
                            </div>
                        <?php endif; ?>

                        <div>
                            <div><strong><?= esc($f['file']) ?></strong></div>
                            <div class="text-small"><?= number_format($f['size'] / 1024, 1) ?> KB</div>
                            <div class="text-small text-muted"><?= esc($f['folder']) ?></div>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <form action="<?= smart_url('admin/optimize-storage/run') ?>" method="post">
                <button class="btn-run">
                    <i class="fas fa-trash"></i>
                    Hapus Semua File Orphan
                </button>
            </form>

        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>