<?= $this->extend('layout/main') ?>

<?= $this->section('head') ?>
<!-- Tambahkan CSS SweetAlert2 & DataTables jika belum ada di layout main -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- Tambahkan CSS untuk tooltips dan animasi premium -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<!-- Bootstrap Tooltips memerlukan Popper.js jika belum ada -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ===========================
   EKSUL UI PREMIUM-PROMAX ULTRA MAX RESPONSIVE
   CLEAN – MODERN – INTERACTIVE – PROFESSIONAL – BUG-FREE – RESPONSIVE
=========================== */

    :root {
        --primary: #2d5be3;
        --primary-hover: #1a47d1;
        --primary-soft: #e7edff;
        --danger: #dc3545;
        --danger-soft: #ffe7e7;
        --warning: #ffc107;
        --warning-soft: #fff4db;
        --success: #28a745;
        --success-soft: #e8f7e1;
        --dark: #2a2d3e;
        --text-muted: #6c757d;
        --background: #f4f6f9;
        --white: #ffffff;
        --shadow-light: rgba(0, 0, 0, 0.05);
        --shadow-medium: rgba(0, 0, 0, 0.1);
        --border-radius: 16px;
        --transition: all 0.3s ease;
    }

    body {
        background: var(--background);
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* ---------- HEADER CARDS ---------- */
    .info-box-modern {
        border-radius: var(--border-radius);
        padding: 20px;
        display: flex;
        align-items: center;
        background: var(--white);
        box-shadow: 0 8px 24px var(--shadow-light);
        margin-bottom: 24px;
        transition: var(--transition);
        cursor: pointer;
    }

    .info-box-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px var(--shadow-medium);
    }

    .info-box-modern .icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2rem;
        margin-right: 16px;
        background: linear-gradient(135deg, var(--primary-soft) 0%, var(--primary) 100%);
        color: var(--white);
        flex-shrink: 0;
    }

    .info-box-modern h3 {
        margin: 0;
        font-size: 1.6rem;
        font-weight: 700;
    }

    /* ---------- SCHEDULE CHIP ---------- */
    .schedule-chip {
        padding: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--primary-soft);
        border-left: 4px solid var(--primary);
        border-radius: 12px;
        margin-bottom: 12px;
        transition: var(--transition);
    }

    .schedule-chip:hover {
        transform: translateX(8px);
        background: #d8e2ff;
        box-shadow: 0 4px 12px var(--shadow-light);
    }

    .schedule-chip span {
        font-weight: 600;
        color: var(--dark);
        flex: 1;
    }

    /* Action buttons inside chip */
    .schedule-actions .btn-icon {
        border: none;
        background: none;
        font-size: 1.2rem;
        transition: var(--transition);
        cursor: pointer;
        padding: 4px;
    }

    .schedule-actions .btn-icon:hover {
        transform: scale(1.1);
    }

    /* ---------- TABLE DESIGN ---------- */
    .table-modern thead {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
        color: var(--white);
    }

    .table-modern thead th {
        padding: 16px;
        border: none;
        font-size: 1rem;
        font-weight: 600;
    }

    .table-modern tbody tr {
        transition: var(--transition);
    }

    .table-modern tbody tr:hover {
        background: #f0f4ff;
        box-shadow: 0 2px 8px var(--shadow-light);
    }

    .table-modern td {
        padding: 16px !important;
        vertical-align: middle;
    }

    /* ---------- ACTION BUTTONS ---------- */
    .action-group {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .action-group .btn {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px var(--shadow-light);
        transition: var(--transition);
        cursor: pointer;
    }

    .action-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px var(--shadow-medium);
    }

    /* ---------- CARD WRAPPER ---------- */
    .card-modern {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 28px var(--shadow-light);
        overflow: hidden;
    }

    /* ---------- MODAL PREMIUM ---------- */
    .modal-content {
        border-radius: var(--border-radius);
        border: none;
        box-shadow: 0 12px 32px var(--shadow-medium);
    }

    .modal-header {
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        padding: 20px;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-body input,
    .modal-body select,
    .modal-body textarea {
        border-radius: 12px !important;
        border: 1px solid #dee2e6;
        padding: 12px;
        transition: var(--transition);
    }

    .modal-body input:focus,
    .modal-body select:focus,
    .modal-body textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(45, 91, 227, 0.25);
    }

    /* ---------- INPUT GROUP PREMIUM ---------- */
    .input-group-prepend .input-group-text {
        border-radius: 12px 0 0 12px;
        background: var(--white);
    }

    /* ---------- ALERT IN MODAL ---------- */
    .alert-light {
        background: var(--primary-soft);
        border-left: 4px solid var(--primary);
        border-radius: 8px;
    }

    /* ---------- LOADING SPINNER ---------- */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* ---------- RESPONSIVE IMPROVEMENTS ---------- */
    @media (max-width: 1200px) {
        .table-modern td {
            padding: 12px !important;
        }
    }

    @media (max-width: 992px) {
        .info-box-modern {
            margin-bottom: 16px;
        }

        .card-body {
            padding: 16px;
        }
    }

    @media (max-width: 768px) {
        .info-box-modern {
            padding: 16px;
            flex-direction: column;
            text-align: center;
        }

        .info-box-modern .icon-wrapper {
            margin-right: 0;
            margin-bottom: 12px;
        }

        .schedule-chip {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            padding: 12px;
        }

        .schedule-actions {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .action-group {
            justify-content: center;
        }

        .action-group .btn {
            width: 36px;
            height: 36px;
        }

        .container-fluid {
            padding-left: 12px;
            padding-right: 12px;
        }

        .modal-dialog {
            margin: 1rem;
            max-width: 95%;
        }

        .modal-body {
            padding: 16px;
        }

        .table-modern thead th {
            padding: 12px;
            font-size: 0.9rem;
        }

        .table-modern td {
            padding: 12px !important;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        h1 {
            font-size: 1.5rem;
        }

        .btn-tambah-ekskul {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .action-group {
            gap: 4px;
        }

        .action-group .btn {
            width: 32px;
            height: 32px;
        }

        .schedule-chip span {
            font-size: 0.9rem;
        }
    }

    /* ---------- ANIMATIONS ---------- */
    .animate__animated {
        animation-duration: 0.5s;
    }

    /* ---------- TOOLTIP STYLES ---------- */
    .tooltip-inner {
        background-color: var(--dark);
        color: var(--white);
        border-radius: 8px;
        padding: 8px 12px;
    }

    .bs-tooltip-top .arrow::before {
        border-top-color: var(--dark);
    }

    /* ---------- SELECT2 RESPONSIVE ---------- */
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        border-radius: 12px;
        height: 46px;
        padding: 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
    }
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="content-wrapper" style="background-color: var(--background);">
    <!-- Header with White Background -->
    <div class="content-header bg-white shadow-sm mb-4" style="border-bottom: 1px solid #dee2e6;">
        <div class="container-fluid">
            <div class="row py-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark" style="font-weight: 700; font-size: 1.9rem;">
                        <i class="fas fa-running text-primary mr-2"></i>Manajemen Ekskul
                    </h1>
                    <p class="text-muted mb-0 mt-1" style="font-size: 1rem;">Platform kontrol kegiatan ekstrakurikuler sekolah yang premium, interaktif, dan profesional.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- STATS WIDGETS -->
            <?php
            $totalEkskul = count($ekskulList ?? []);
            $totalPembimbing = count($pembimbingList ?? []);
            $totalJadwal = 0;
            foreach (($ekskulList ?? []) as $e) {
                $totalJadwal += count($e['jadwal'] ?? []);
            }
            ?>
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box-modern animate__animated animate__fadeIn" data-toggle="tooltip" title="Jumlah total cabang ekstrakurikuler yang terdaftar.">
                        <div class="icon-wrapper">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="content-wrapper">
                            <span class="text-muted font-weight-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Total Ekskul</span>
                            <h3 class="font-weight-bold mb-0 text-dark"><?= $totalEkskul ?> <small class="text-muted" style="font-size: 1rem;">Cabang</small></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box-modern animate__animated animate__fadeIn" style="animation-delay: 0.2s;" data-toggle="tooltip" title="Jumlah guru pembimbing yang tersedia.">
                        <div class="icon-wrapper" style="background: linear-gradient(135deg, var(--success-soft) 0%, var(--success) 100%);">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="content-wrapper">
                            <span class="text-muted font-weight-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Pembimbing</span>
                            <h3 class="font-weight-bold mb-0 text-dark"><?= $totalPembimbing ?> <small class="text-muted" style="font-size: 1rem;">Guru</small></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="info-box-modern animate__animated animate__fadeIn" style="animation-delay: 0.4s;" data-toggle="tooltip" title="Total sesi jadwal aktif per minggu.">
                        <div class="icon-wrapper" style="background: linear-gradient(135deg, var(--warning-soft) 0%, var(--warning) 100%);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="content-wrapper">
                            <span class="text-muted font-weight-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Jadwal Aktif</span>
                            <h3 class="font-weight-bold mb-0 text-dark"><?= $totalJadwal ?> <small class="text-muted" style="font-size: 1rem;">Sesi/Minggu</small></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flashdata SweetAlert Handling -->
            <div id="flash-success" data-msg="<?= session()->getFlashdata('success') ?>"></div>
            <div id="flash-error" data-msg="<?= session()->getFlashdata('error') ?>"></div>

            <div class="card card-modern animate__animated animate__fadeInUp">
                <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                    <div class="mb-2 mb-md-0">
                        <h3 class="card-title font-weight-bold text-dark" style="font-size: 1.3rem;">
                            <i class="fas fa-list-alt text-primary mr-2"></i>Daftar Ekstrakurikuler
                        </h3>
                        <p class="text-muted small mb-0 ml-4 pl-1">Kelola data dan jadwal latihan dengan mudah, aman, dan profesional.</p>
                    </div>
                    <button type="button" class="btn btn-primary shadow-sm btn-tambah-ekskul px-4 rounded-pill" data-toggle="tooltip" title="Tambahkan ekstrakurikuler baru ke sistem.">
                        <i class="fas fa-plus mr-1"></i> Tambah Baru
                    </button>
                </div>

                <div class="card-body p-4">
                    <!-- Deteksi Error Validasi untuk Membuka Modal -->
                    <?php
                    $validation_errors = session()->getFlashdata('validation_errors');
                    $has_errors = $validation_errors || (isset($validation) && $validation->getErrors());
                    ?>
                    <div id="validation-check"
                        data-has-error="<?= $has_errors ? 'true' : 'false' ?>"
                        data-modal-target="<?= session()->getFlashdata('show_modal') === 'jadwal' ? '#modalTambahJadwal' : '#modalTambahEkskul' ?>">
                    </div>

                    <div class="table-responsive">
                        <table id="ekskulTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Pembimbing</th>
                                    <th>Jadwal Aktif</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($ekskulList ?? [] as $ekskul) : ?>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                        <td>
                                            <span class="font-weight-bold text-dark" style="font-size: 1.1rem;"><?= esc($ekskul['nama_ekskul']) ?></span>
                                            <?php if (!empty($ekskul['keterangan'])): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= esc($ekskul['keterangan']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center p-2 rounded bg-light" style="width: fit-content; box-shadow: 0 2px 4px var(--shadow-light);">
                                                <div class="mr-2 text-primary">
                                                    <i class="fas fa-user-circle fa-lg"></i>
                                                </div>
                                                <span class="font-weight-500"><?= esc($ekskul['pembimbing_nama'] ?? 'Belum ditentukan') ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($ekskul['jadwal'])) : ?>
                                                <?php foreach ($ekskul['jadwal'] as $jadwal) :
                                                    $jadwal_id = $jadwal['jadwal_id'] ?? $jadwal['id'] ?? null;
                                                ?>
                                                    <div class="schedule-chip animate__animated animate__fadeIn">
                                                        <span>
                                                            <i class="far fa-calendar-alt mr-1"></i>
                                                            <strong><?= esc($jadwal['hari']) ?></strong>
                                                            <small class="text-muted ml-1" style="font-size: 0.85em;">(<?= date('H:i', strtotime($jadwal['jam_mulai'])) ?> - <?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>)</small>
                                                        </span>
                                                        <div class="schedule-actions">
                                                            <button type="button" class="btn-icon text-warning btn-edit-jadwal"
                                                                data-ekskul-id="<?= $ekskul['id'] ?>"
                                                                data-ekskul-nama="<?= esc($ekskul['nama_ekskul']) ?>"
                                                                data-jadwal-id="<?= $jadwal_id ?>"
                                                                data-hari-index="<?= $jadwal['hari_index'] ?? '' ?>"
                                                                data-jam-mulai="<?= date('H:i', strtotime($jadwal['jam_mulai'])) ?>"
                                                                data-jam-selesai="<?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>"
                                                                data-toggle="tooltip" title="Edit jadwal ini.">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <button type="button" class="btn-icon text-danger btn-delete-jadwal"
                                                                data-url="<?= base_url('ekskul/deleteJadwal/' . $jadwal_id) ?>"
                                                                data-toggle="tooltip" title="Hapus jadwal ini.">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <small class="text-muted font-italic">Belum ada jadwal</small>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-xs btn-outline-primary mt-2 btn-tambah-jadwal-trigger rounded-pill px-3 py-1"
                                                style="font-size: 0.8rem; transition: var(--transition);"
                                                data-ekskul-id="<?= $ekskul['id'] ?>"
                                                data-ekskul-nama="<?= esc($ekskul['nama_ekskul']) ?>"
                                                data-toggle="tooltip" title="Tambahkan jadwal baru untuk ekskul ini.">
                                                <i class="fas fa-plus mr-1"></i> Jadwal
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-group">

                                                <!-- Tombol Anggota Ekskul -->
                                                <a href="<?= smart_url('ekskul/anggota/' . $ekskul['id']) ?>"
                                                    class="btn btn-sm btn-warning shadow-sm rounded-circle"
                                                    data-toggle="tooltip" title="Kelola anggota ekskul ini.">
                                                    <i class="fas fa-users"></i>
                                                </a>

                                                <!-- Tombol Edit -->
                                                <button type="button"
                                                    class="btn btn-sm btn-info btn-edit shadow-sm rounded-circle"
                                                    data-id="<?= $ekskul['id'] ?>"
                                                    data-nama="<?= esc($ekskul['nama_ekskul']) ?>"
                                                    data-pembimbing="<?= esc($ekskul['pembimbing_id']) ?>"
                                                    data-keterangan="<?= esc($ekskul['keterangan']) ?>"
                                                    data-toggle="tooltip" title="Edit data ekskul ini.">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Tombol Delete -->
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-delete-ekskul shadow-sm rounded-circle"
                                                    data-url="<?= base_url('ekskul/delete/' . $ekskul['id']) ?>"
                                                    data-nama="<?= esc($ekskul['nama_ekskul']) ?>"
                                                    data-toggle="tooltip" title="Hapus data ekskul ini (konfirmasi diperlukan).">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah/Edit Ekskul (Desain Modern) -->
<div class="modal fade" id="modalTambahEkskul" tabindex="-1" role="dialog" aria-labelledby="modalTambahEkskulLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content animate__animated animate__zoomIn">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalTambahEkskulLabel">Form Ekstrakurikuler</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('ekskul/save') ?>" method="post" id="formEkskul" novalidate>
                <div class="modal-body p-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="ekskulId">

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-graduation-cap text-primary"></i></span>
                            </div>
                            <input type="text" class="form-control border-left-0" id="nama_ekskul" name="nama_ekskul"
                                placeholder="Misal: Basket, Tari, Coding" value="<?= old('nama_ekskul') ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Pembimbing <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="pembimbing_id" name="pembimbing_id" required style="width: 100%;">
                            <option value="">-- Pilih Guru Pembimbing --</option>
                            <?php foreach ($pembimbingList ?? [] as $pembimbing) : ?>
                                <option value="<?= $pembimbing['id'] ?>" <?= old('pembimbing_id') == $pembimbing['id'] ? 'selected' : '' ?>>
                                    <?= esc($pembimbing['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Deskripsi singkat..."><?= old('keterangan') ?></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal" id="btnBatalEkskul">Batal</button>
                    <button type="submit" class="btn btn-primary shadow-sm" id="btnSaveEkskul"><i class="fas fa-save mr-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Jadwal (Desain Modern) -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" role="dialog" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content animate__animated animate__zoomIn">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold" id="modalTambahJadwalLabel">Kelola Jadwal</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('ekskul/saveJadwal') ?>" method="post" id="formJadwal" novalidate>
                <div class="modal-body p-4">
                    <div class="alert alert-light border-left-success border-0 shadow-sm mb-3">
                        <small class="text-muted d-block">Menambahkan jadwal untuk:</small>
                        <strong id="jadwalEkskulNama" class="text-success h6"></strong>
                    </div>

                    <?= csrf_field() ?>
                    <input type="hidden" name="ekskul_id" id="jadwalEkskulId">
                    <input type="hidden" name="id" id="jadwalId">

                    <div class="form-group">
                        <label class="font-weight-bold">Hari <span class="text-danger">*</span></label>
                        <select class="form-control" id="hari" name="hari_index" required>
                            <option value="">Pilih Hari...</option>
                            <?php $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                            foreach ($days as $index => $day) : ?>
                                <option value="<?= $index ?>"><?= $day ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm shadow-sm" data-dismiss="modal" id="btnBatalJadwal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm shadow-sm" id="btnSaveJadwal"><i class="fas fa-check mr-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Script SweetAlert2 & DataTables -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // --- 0. INISIALISASI TOOLTIPS --- //
        $('[data-toggle="tooltip"]').tooltip({
            placement: 'top',
            animation: true,
            delay: {
                show: 100,
                hide: 100
            }
        });

        // --- 1. INISIALISASI & KONFIGURASI --- //

        // Select2 dengan tema premium
        $('.select2').select2({
            dropdownParent: $('#modalTambahEkskul'),
            theme: 'bootstrap4',
            placeholder: 'Pilih opsi',
            dropdownCssClass: 'select2-dropdown-premium',
            width: '100%'
        });

        // DataTables Responsive dengan language Indonesian manual (hindari CORS)
        const table = $('#ekskulTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "emptyTable": "Tidak ada data tersedia dalam tabel",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ total entri)",
                "infoThousands": ".",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Memuat...",
                "processing": "Memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": aktifkan untuk mengurutkan kolom naik",
                    "sortDescending": ": aktifkan untuk mengurutkan kolom turun"
                }
            },
            "columnDefs": [{
                "orderable": false,
                "targets": [3, 4]
            }],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "pagingType": "simple_numbers",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            "pageLength": 10,
            "order": [
                [1, 'asc']
            ]
        });

        // --- 2. NOTIFIKASI OTOMATIS (SWEETALERT) --- //

        const flashSuccess = $('#flash-success').data('msg');
        const flashError = $('#flash-error').data('msg');

        if (flashSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: flashSuccess,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#e8f7e1',
                iconColor: '#28a745'
            });
        }

        if (flashError) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: flashError,
                background: '#ffe7e7',
                iconColor: '#dc3545'
            });
        }

        // --- VALIDASI & MODAL AUTO-OPEN --- //
        const validationEl = $('#validation-check');
        if (validationEl.data('has-error')) {
            const targetModal = validationEl.data('modal-target');
            setTimeout(() => {
                $(targetModal).modal('show');
            }, 500);

            // Tampilkan error field
            <?php if (isset($validation)) : ?>
                const errors = <?= json_encode($validation->getErrors()) ?>;
                $.each(errors, function(field, message) {
                    const $input = $(`[name="${field}"]`);
                    $input.addClass('is-invalid');
                    const $feedback = $input.next('.invalid-feedback');
                    if ($feedback.length) {
                        $feedback.text(message);
                    } else {
                        $input.after(`<div class="invalid-feedback d-block">${message}</div>`);
                    }
                });
            <?php endif; ?>

            // Isi ulang data untuk Jadwal
            const oldEkskulId = '<?= old('ekskul_id') ?>';
            if (targetModal === '#modalTambahJadwal' && oldEkskulId) {
                $('#jadwalEkskulId').val(oldEkskulId);
                const oldJadwalId = '<?= old('id') ?>';
                if (oldJadwalId) {
                    $('#jadwalId').val(oldJadwalId);
                    $('#modalTambahJadwalLabel').text('Edit Jadwal (Koreksi)');
                }

                $('#hari').val('<?= old('hari_index') ?>');
                $('#jam_mulai').val('<?= old('jam_mulai') ?>');
                $('#jam_selesai').val('<?= old('jam_selesai') ?>');
            }
        }

        // --- FORM VALIDATION CLIENT-SIDE --- //
        $('#formEkskul, #formJadwal').on('submit', function(e) {
            const $form = $(this);
            if (!$form[0].checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                $form.addClass('was-validated');
                Swal.fire({
                    icon: 'warning',
                    title: 'Lengkapi Form!',
                    text: 'Mohon isi semua field yang diwajibkan.',
                    toast: true,
                    position: 'top-end'
                });
                return false;
            }
            showLoading();
        });

        // Validasi waktu real-time
        $('#jam_mulai, #jam_selesai').on('change', function() {
            const mulai = $('#jam_mulai').val();
            const selesai = $('#jam_selesai').val();
            const $selesai = $('#jam_selesai');
            const $feedback = $selesai.next('.invalid-feedback');
            if (mulai && selesai && mulai >= selesai) {
                $selesai.addClass('is-invalid');
                if ($feedback.length) {
                    $feedback.text('Waktu selesai harus setelah mulai.');
                } else {
                    $selesai.after('<div class="invalid-feedback d-block">Waktu selesai harus setelah mulai.</div>');
                }
            } else {
                $selesai.removeClass('is-invalid');
                $selesai.next('.invalid-feedback').remove();
            }
        });

        // --- 3. LOGIKA EKSKUL --- //
        $('.btn-tambah-ekskul').on('click', function() {
            $('#modalTambahEkskulLabel').text('Tambah Ekstrakurikuler Baru');
            $('#formEkskul')[0].reset();
            $('#ekskulId').val('');
            $('#pembimbing_id').val('').trigger('change');
            clearValidation();
            $('#modalTambahEkskul').modal('show');
        });

        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const pembimbingId = $(this).data('pembimbing');
            const keterangan = $(this).data('keterangan');

            $('#modalTambahEkskulLabel').text('Edit Ekskul: ' + nama);
            $('#ekskulId').val(id);
            $('#nama_ekskul').val(nama);
            $('#keterangan').val(keterangan);
            $('#pembimbing_id').val(pembimbingId).trigger('change');
            clearValidation();
            $('#modalTambahEkskul').modal('show');
        });

        $(document).on('click', '.btn-delete-ekskul', function() {
            const url = $(this).data('url');
            const nama = $(this).data('nama');

            Swal.fire({
                title: 'Hapus ' + nama + '?',
                text: "Semua jadwal terkait juga akan dihapus. Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#ffe7e7',
                iconColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    window.location.href = url;
                }
            });
        });

        // --- 4. LOGIKA JADWAL --- //
        $(document).on('click', '.btn-tambah-jadwal-trigger', function() {
            const ekskulId = $(this).data('ekskul-id');
            const ekskulNama = $(this).data('ekskul-nama');

            $('#modalTambahJadwalLabel').text('Tambah Jadwal');
            $('#formJadwal')[0].reset();
            $('#jadwalEkskulId').val(ekskulId);
            $('#jadwalId').val('');
            $('#jadwalEkskulNama').text(ekskulNama);
            clearValidation();
            $('#modalTambahJadwal').modal('show');
        });

        $(document).on('click', '.btn-edit-jadwal', function() {
            const ekskulId = $(this).data('ekskul-id');
            const jadwalId = $(this).data('jadwal-id');
            const ekskulNama = $(this).data('ekskul-nama');

            $('#modalTambahJadwalLabel').text('Perbarui Jadwal');
            $('#jadwalEkskulId').val(ekskulId);
            $('#jadwalId').val(jadwalId);
            $('#jadwalEkskulNama').text(ekskulNama);

            $('#hari').val($(this).data('hari-index'));
            $('#jam_mulai').val($(this).data('jam-mulai'));
            $('#jam_selesai').val($(this).data('jam-selesai'));

            clearValidation();
            $('#modalTambahJadwal').modal('show');
        });

        $(document).on('click', '.btn-delete-jadwal', function() {
            const url = $(this).data('url');

            Swal.fire({
                title: 'Hapus Jadwal?',
                text: "Jadwal akan dihapus dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                background: '#ffe7e7',
                iconColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    window.location.href = url;
                }
            });
        });

        // Perbaikan Tombol Batal
        $('#btnBatalEkskul, #btnBatalJadwal').on('click', function() {
            clearValidation();
            $(this).closest('.modal').modal('hide');
        });

        // Modal hidden event: reset form
        $('#modalTambahEkskul, #modalTambahJadwal').on('hidden.bs.modal', function() {
            clearValidation();
            $(this).find('form')[0].reset();
        });

        // Fungsi clear validation
        function clearValidation() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('form').removeClass('was-validated');
        }

        // Fungsi Show Loading
        function showLoading() {
            $('#loadingOverlay').fadeIn(200);
        }

        // Hide loading on page unload (optional)
        $(window).on('beforeunload', function() {
            $('#loadingOverlay').fadeOut(200);
        });
    });
</script>
<?= $this->endSection() ?>