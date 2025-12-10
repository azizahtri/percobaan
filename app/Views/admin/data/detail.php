<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">Detail Pelamar</h4>
            <p class="text-muted small mb-0">Review hasil penilaian dan tentukan keputusan akhir.</p>
        </div>
        <a href="<?= base_url('admin/data') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
            <i class="mdi mdi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover-lift">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <div class="avatar-xl bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold display-4 shadow-sm" style="width: 100px; height: 100px;">
                            <?= strtoupper(substr($data['nama'], 0, 1)) ?>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-1"><?= esc($data['nama']) ?></h4>
                    <p class="text-muted mb-3"><?= esc($data['email']) ?></p>
                    
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                        <i class="mdi mdi-briefcase-outline me-1"></i> <?= esc($data['judul_lowongan']) ?>
                    </span>

                    <hr class="my-4 opacity-10">

                    <div class="text-start">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Kontak</small>
                        <div class="d-flex align-items-center mt-2 mb-3">
                            <div class="bg-light p-2 rounded-circle me-3"><i class="mdi mdi-phone text-success"></i></div>
                            <span class="fw-bold"><?= esc($data['no_hp']) ?></span>
                        </div>
                        
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Tanggal Lamar</small>
                        <div class="d-flex align-items-center mt-2">
                            <div class="bg-light p-2 rounded-circle me-3"><i class="mdi mdi-calendar text-primary"></i></div>
                            <span class="fw-bold"><?= date('d F Y', strtotime($data['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h6 class="mb-0 fw-bold"><i class="mdi mdi-chart-bar me-2 text-info"></i>Hasil Perhitungan Sistem (SPK)</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="row align-items-center">
                        <div class="col-md-6 border-end">
                            <h6 class="text-muted small">SKOR AKHIR</h6>
                            <h1 class="display-4 fw-bold text-primary mb-0"><?= number_format($data['spk_score'], 4) ?></h1>
                            <small class="text-muted">Metode SAW / SMART</small>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">STATUS SAAT INI</h6>
                            <?php 
                                $badgeColor = match($data['status']) {
                                    'memenuhi'       => 'bg-success',
                                    'tidak memenuhi' => 'bg-danger',
                                    default          => 'bg-warning text-dark'
                                };
                            ?>
                            <span class="badge <?= $badgeColor ?> fs-6 px-4 py-2 rounded-pill text-uppercase">
                                <?= $data['status'] ?: 'BELUM DIPUTUSKAN' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">Keputusan Akhir (Final Decision)</h5>
                    
                    <div class="mb-4">
                        <p class="small text-muted mb-2">Apakah kandidat ini memenuhi kualifikasi perusahaan?</p>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/data/status/' . $data['id'] . '/memenuhi') ?>" 
                               class="btn <?= ($data['status'] == 'memenuhi') ? 'btn-success' : 'btn-outline-success bg-white' ?> flex-grow-1 py-2 fw-bold"
                               onclick="return confirm('Nyatakan pelamar ini MEMENUHI kualifikasi?')">
                                <i class="mdi mdi-check-circle-outline me-1"></i> Ya, Lolos Kualifikasi
                            </a>

                            <a href="<?= base_url('admin/data/status/' . $data['id'] . '/tidak memenuhi') ?>" 
                               class="btn <?= ($data['status'] == 'tidak memenuhi') ? 'btn-danger' : 'btn-outline-danger bg-white' ?> flex-grow-1 py-2 fw-bold"
                               onclick="return confirm('Nyatakan pelamar ini TIDAK MEMENUHI kualifikasi?')">
                                <i class="mdi mdi-close-circle-outline me-1"></i> Tidak Lolos
                            </a>
                        </div>
                    </div>

                    <?php if($data['status'] == 'memenuhi'): ?>
                        <div class="alert alert-success border-0 fade show mt-3" role="alert">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success text-white p-2 rounded-circle me-3">
                                    <i class="mdi mdi-account-check fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-success">Kandidat Lolos Kualifikasi</h6>
                                    <p class="mb-0 small text-muted">Pelamar ini siap untuk diproses menjadi karyawan.</p>
                                </div>
                            </div>
                            
                            <?php if ($isRekrut): ?>
                                <button class="btn btn-secondary w-100 disabled fw-bold py-2 rounded-pill border-0" style="opacity: 0.7; cursor: not-allowed;">
                                    <i class="mdi mdi-check-all me-1"></i> Sudah Menjadi Karyawan
                                </button>
                            <?php else: ?>
                                <a href="<?= base_url('admin/data/onboard/' . $data['id']) ?>" 
                                   class="btn btn-success w-100 fw-bold py-2 rounded-pill shadow-sm"
                                   onclick="return confirm('Yakin ingin mengangkat pelamar ini menjadi Karyawan Tetap? Data akan masuk ke menu Alternatif.')">
                                    <i class="mdi mdi-briefcase-plus me-1"></i> Rekrut Sekarang
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>



<?= $this->endSection() ?>