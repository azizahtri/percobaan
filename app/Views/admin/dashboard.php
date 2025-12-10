<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #1F3BB3 0%, #464ebd 100%); color: white; border-radius: 15px;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center position-relative z-index-1">
                        <div class="me-4 bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                            <span class="fs-1">👋</span>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Halo, <?= esc($user_name) ?>!</h3>
                            <p class="mb-0 opacity-75">
                                Selamat datang kembali di Panel Admin Cartenz Tech. 
                                <span class="d-none d-md-inline">Pantau aktivitas rekrutmen hari ini.</span>
                            </p>
                        </div>
                    </div>
                    <i class="mdi mdi-cube-outline position-absolute text-white opacity-10" style="font-size: 10rem; right: -20px; top: -40px; transform: rotate(15deg);"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm card-hover-lift">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold ls-1 mb-1">Total Lowongan</h6>
                        <h2 class="mb-0 fw-bold text-dark display-6"><?= $stats['lowongan'] ?></h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background-color: rgba(31, 59, 179, 0.1); color: #1F3BB3;">
                        <i class="mdi mdi-briefcase" style="font-size: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm card-hover-lift">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold ls-1 mb-1">Pelamar Masuk</h6>
                        <h2 class="mb-0 fw-bold text-info display-6"><?= $stats['pelamar'] ?></h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0;">
                        <i class="mdi mdi-account-group" style="font-size: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm card-hover-lift">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold ls-1 mb-1">Karyawan Aktif</h6>
                        <h2 class="mb-0 fw-bold text-success display-6"><?= $stats['karyawan'] ?></h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                        <i class="mdi mdi-account-check" style="font-size: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm card-hover-lift">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold ls-1 mb-1">Perlu Review</h6>
                        <h2 class="mb-0 fw-bold text-warning display-6"><?= $stats['pending'] ?></h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">
                        <i class="mdi mdi-alert-circle-outline" style="font-size: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark"><i class="mdi mdi-account-clock me-2 text-primary"></i>Pelamar Terbaru</h6>
                    </div>
                    <a href="<?= base_url('admin/data') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold">
                        Lihat Semua
                    </a>
                </div>
                
                <div class="table-responsive p-2">
                    <table class="table align-middle mb-0 table-hover datatable" style="width:100%">
                        <thead class="bg-light text-secondary text-uppercase small">
                            <tr>
                                <th class="ps-4 py-3" style="width: 40%;">Nama Pelamar</th>
                                <th class="py-3" style="width: 25%;">Posisi</th>
                                <th class="py-3" style="width: 20%;">Status</th>
                                <th class="py-3 text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($recent_pelamar)): ?>
                                <?php foreach($recent_pelamar as $p): ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold text-primary" 
                                                 style="width: 40px; height: 40px; background-color: #eef2ff; border: 1px solid #dae2fc;">
                                                <?= strtoupper(substr($p['nama'], 0, 1)) ?>
                                            </div>
                                            <div class="flex-grow-1 min-width-0">
                                                <span class="fw-bold text-dark d-block text-truncate" style="max-width: 180px;"><?= esc($p['nama']) ?></span>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    <?= date('d M, H:i', strtotime($p['created_at'] ?? 'now')) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-normal">
                                            <?= esc($p['judul_lowongan']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <?php 
                                            $st = $p['status'] ?: 'proses';
                                            $cls = match($st) {
                                                'memenuhi' => 'bg-success-subtle text-success',
                                                'tidak memenuhi' => 'bg-danger-subtle text-danger',
                                                default => 'bg-warning-subtle text-warning-emphasis'
                                            };
                                        ?>
                                        <span class="badge <?= $cls ?> border-0 px-3 py-2 rounded-pill">
                                            <?= ucfirst($st) ?>
                                        </span>
                                    </td>
                                    <td class="text-center py-3">
                                        <a href="<?= base_url('admin/lowongan/pelamar/'.$p['id']) ?>" 
                                           class="btn btn-action btn-action-edit"
                                           title="Proses">
                                            <i class="mdi mdi-pencil-box-outline"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada pelamar baru.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="mdi mdi-briefcase-check me-2 text-success"></i>Lowongan Terkini</h6>
                </div>
                <div class="card-body p-0">
                    <?php if(!empty($recent_jobs)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($recent_jobs as $j): ?>
                                <div class="list-group-item border-0 px-4 py-3 d-flex align-items-center list-group-item-action">
                                    <div class="bg-primary text-white p-2 rounded-circle me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                        <i class="mdi mdi-briefcase-outline fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <h6 class="mb-1 fw-bold text-dark text-truncate"><?= esc($j['judul_lowongan']) ?></h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.7rem;">
                                                <?= strtoupper($j['jenis']) ?>
                                            </span>
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                <?= date('d M Y', strtotime($j['tanggal_posting'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="p-3 text-center border-top">
                            <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-link text-decoration-none btn-sm fw-bold">
                                Kelola Semua Lowongan <i class="mdi mdi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 px-4 text-muted">
                            <p class="mb-0 small">Belum ada lowongan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>


<?= $this->endSection() ?>