<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
// CSRF token (CI4 helpers)
$csrfName = csrf_token();
$csrfHash = csrf_hash();
?>

<style>
    .log-box {
        background: #0d1117;
        color: #c9d1d9;
        padding: 1rem;
        border-radius: 10px;
        height: 520px;
        overflow-y: scroll;
        white-space: pre-wrap;
        font-family: Consolas, monospace;
        border: 1px solid #30363d;
    }

    .btn-refresh {
        background: #3498db;
        color: white;
        border: none;
        padding: .45rem .9rem;
        border-radius: 6px;
    }

    .btn-refresh:hover {
        background: #217dbb;
    }

    .btn-clear {
        background: #e74c3c;
        color: white;
        border: none;
        padding: .45rem .9rem;
        border-radius: 6px;
    }

    .alert-ajax {
        margin-top: .8rem;
        display: none;
    }
</style>

<!-- put CSRF token in meta so JS can read -->
<meta name="csrf-name" content="<?= esc($csrfName) ?>">
<meta name="csrf-hash" content="<?= esc($csrfHash) ?>">

<div class="container py-3">

    <h4 class="fw-bold">
        <i class="fa fa-bug text-danger"></i> Log Error Sistem
    </h4>

    <div class="d-flex justify-content-between align-items-center mt-2">
        <span class="fw-bold">
            File Log: <a id="logFilename" class="text-primary" href="javascript:;">Memuat...</a>
        </span>

        <div class="d-flex gap-2">

            <button id="manualRefresh" class="btn-refresh">
                <i class="fa fa-sync-alt"></i> Refresh
            </button>

            <button id="clearLogs" class="btn-clear" title="Kosongkan semua file log">
                <i class="fa fa-trash"></i> Hapus Semua Log
            </button>
        </div>
    </div>

    <div id="ajaxAlert" class="alert alert-success alert-ajax" role="alert"></div>

    <div id="logBox" class="log-box mt-3">Memuat data log...</div>

    <p class="text-muted mt-2" style="font-size:.85rem;">
        🔄 Auto-refresh setiap <b>20 detik</b>. Log terbaru berada di <b>paling atas</b>.
    </p>
</div>

<script>
    const fetchUrl = "<?= smart_url('admin/error-log/fetch') ?>";
    const clearUrl = "<?= smart_url('admin/error-log/clear') ?>";
    const csrfName = document.querySelector('meta[name="csrf-name"]').getAttribute('content');
    const csrfHashMeta = document.querySelector('meta[name="csrf-hash"]').getAttribute('content');

    // load and display log
    async function loadLog() {
        try {
            const res = await fetch(fetchUrl, {
                credentials: 'same-origin'
            });
            if (!res.ok) throw new Error('Fetch error');
            const data = await res.json();
            document.getElementById('logBox').textContent = data.content || '(kosong)';
            document.getElementById('logFilename').textContent = data.filename || 'Tidak ada file';
        } catch (err) {
            console.error(err);
            document.getElementById('logBox').textContent = 'Gagal memuat log.';
        }
    }

    // clear logs via POST AJAX and include CSRF
    async function clearLogs() {
        if (!confirm('Yakin ingin mengosongkan semua log?')) return;

        // prepare body with CSRF
        const body = new URLSearchParams();
        body.append(csrfName, csrfHashMeta);

        try {
            const res = await fetch(clearUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: body.toString(),
                credentials: 'same-origin'
            });

            const json = await res.json();

            // show message
            const alert = document.getElementById('ajaxAlert');
            alert.style.display = 'block';
            if (json.status === 'ok') {
                alert.className = 'alert alert-success alert-ajax';
                alert.textContent = json.message;
            } else {
                alert.className = 'alert alert-danger alert-ajax';
                alert.textContent = json.message || 'Gagal membersihkan log';
            }

            // reload view after small delay
            setTimeout(() => {
                loadLog();
                alert.style.display = 'none';
            }, 800);

        } catch (err) {
            console.error(err);
            const alert = document.getElementById('ajaxAlert');
            alert.style.display = 'block';
            alert.className = 'alert alert-danger alert-ajax';
            alert.textContent = 'Terjadi kesalahan saat menghapus log.';
            setTimeout(() => alert.style.display = 'none', 2500);
        }
    }

    // events
    document.getElementById('manualRefresh').addEventListener('click', loadLog);
    document.getElementById('clearLogs').addEventListener('click', clearLogs);

    // auto refresh every 20s
    setInterval(loadLog, 20000);

    // initial load
    loadLog();
</script>

<?= $this->endSection() ?>