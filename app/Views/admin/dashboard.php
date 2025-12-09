<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">Halo, <?= esc($user_name) ?>! 👋</h3>
                        <p class="mb-0 opacity-75">Selamat datang kembali di Panel Admin Cartenz Tech.</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="mdi mdi-view-dashboard-outline" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase fw-bold">Total Lowongan</h6>
                    <h2 class="mb-0 fw-bold text-dark"><?= $stats['lowongan'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase fw-bold">Pelamar Masuk</h6>
                    <h2 class="mb-0 fw-bold text-info"><?= $stats['pelamar'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase fw-bold">Karyawan Aktif</h6>
                    <h2 class="mb-0 fw-bold text-success"><?= $stats['karyawan'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase fw-bold">Perlu Review</h6>
                    <h2 class="mb-0 fw-bold text-warning"><?= $stats['pending'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Pelamar Terbaru</h6>
                    <a href="<?= base_url('admin/data') ?>" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nama</th>
                                <th>Posisi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($recent_pelamar)): ?>
                                <?php foreach($recent_pelamar as $p): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= esc($p['nama']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= esc($p['judul_lowongan']) ?></span></td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
                                            <?= ucfirst($p['status'] ?: 'Proses') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/lowongan/pelamar/'.$p['id']) ?>" class="btn btn-sm btn-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="mdi mdi-pencil" style="font-size: 14px;"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Lowongan Terkini</h6>
                </div>
                <div class="card-body">
                    <?php if(!empty($recent_jobs)): ?>
                        <?php foreach($recent_jobs as $j): ?>
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="bg-light p-2 rounded me-3 text-primary">
                                    <i class="mdi mdi-briefcase fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= esc($j['judul_lowongan']) ?></h6>
                                    <small class="text-muted"><?= date('d M Y', strtotime($j['tanggal_posting'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Belum ada lowongan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>