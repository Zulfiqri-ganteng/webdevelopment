<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* ===================================
       STYLING PAGE OPTIMIZE STORAGE
    ====================================== */

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
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: .6rem;
        border-radius: 10px;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(118, 75, 162, 0.3);
    }

    .opt-card {
        background: white;
        border-radius: 16px;
        padding: 1.3rem;
        margin-top: 1rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .btn-preview,
    .btn-run {
        border: none;
        font-weight: bold;
        padding: .8rem 1rem;
        border-radius: 12px;
        width: 100%;
        font-size: 1rem;
    }

    .btn-preview {
        background: linear-gradient(90deg, #4facfe, #00f2fe);
        color: #00314d;
    }

    .btn-run {
        background: linear-gradient(90deg, #ff5f6d, #ffc371);
        color: white;
    }

    /* LIST FILE ORPHAN */
    .orphan-list {
        margin-top: 1rem;
        display: grid;
        gap: .8rem;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    }

    .orphan-item {
        padding: .9rem;
        border-radius: 12px;
        background: #f9f9fb;
        border: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: .9rem;
    }

    .orphan-item img {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .text-small {
        font-size: .83rem;
        color: #555;
        line-height: 1.3;
    }

    /* MOBILE SPACING FIX */
    @media (max-width: 768px) {
        .opt-container {
            padding: .7rem;
        }

        .opt-card {
            padding: 1rem;
        }
    }
</style>

<div class="opt-container">

    <div class="opt-title">
        <i class="fas fa-broom"></i>
        <span>Optimasi Storage</span>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success mt-3">
            <?= session('success') ?>
        </div>
    <?php endif ?>

    <div class="opt-card">

        <p class="mb-3 text-muted">
            Sistem akan mencari file yang tidak memiliki referensi di database (orphan files).
            <br>Silakan lakukan <strong>Preview</strong> dulu sebelum melakukan penghapusan.
        </p>

        <!-- BUTTONS -->
        <div class="row g-2">
            <div class="col-md-6">
                <button id="btnPreview" class="btn-preview">
                    <i class="fas fa-search"></i> Preview File Orphan
                </button>
            </div>
            <div class="col-md-6">
                <form id="runForm" action="<?= smart_url('admin/optimize-storage/run') ?>" method="post">
                    <input type="hidden" name="files" id="filesInput">
                    <button class="btn-run">
                        <i class="fas fa-trash"></i> Hapus Semua Orphan
                    </button>
                </form>
            </div>
        </div>

        <!-- HASIL PREVIEW -->
        <div id="previewBox" class="mt-4" style="display:none;">
            <h5 class="fw-bold">Daftar File Orphan (<span id="orphanCount">0</span>)</h5>
            <div class="orphan-list" id="orphanList"></div>
        </div>

    </div>
</div>

<script>
    document.getElementById('btnPreview').addEventListener('click', function() {

        let box = document.getElementById('previewBox');
        let list = document.getElementById('orphanList');
        let counter = document.getElementById('orphanCount');

        list.innerHTML = "<p class='text-muted'>Sedang memuat...</p>";
        box.style.display = "block";
        fetch("<?= smart_url('admin/optimize-storage/json-preview') ?>")
            .then(res => res.json())
            .then(data => {

                list.innerHTML = "";
                counter.innerHTML = data.count;

                if (data.count === 0) {
                    list.innerHTML = "<p class='text-muted'>Tidak ada file orphan ditemukan 🎉</p>";
                    return;
                }

                data.orphans.forEach(f => {

                    list.innerHTML += `
                    <div class="orphan-item">
                        <img src="${f.url}" onerror="this.src='<?= base_url('logo_sekolah.png') ?>'" />
                        <div>
                            <div><strong>${f.name}</strong></div>
                            <div class="text-small">${(f.size / 1024).toFixed(1)} KB</div>
                            <div class="text-small text-muted">${f.folder}</div>
                        </div>
                    </div>
                `;
                });

                // simpan file json ke form delete
                document.getElementById('filesInput').value = JSON.stringify(data.orphans);
            });
    });
</script>

<?= $this->endSection() ?>