<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">Detail Keputusan Pelamar</h4>
            <p class="text-muted small mb-0">Review hasil penilaian dan tentukan nasib pelamar.</p>
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
                        <?php if (!empty($data['foto_profil'])): ?>
                            <img src="<?= base_url('uploads/berkas/' . $data['foto_profil']) ?>" 
                                 class="avatar-xl rounded-circle mx-auto shadow-sm border p-1" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        <?php else: ?>
                            <div class="avatar-xl bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold display-4 shadow-sm" style="width: 100px; height: 100px;">
                                <?= strtoupper(substr($data['nama_lengkap'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h4 class="fw-bold text-dark mb-1"><?= esc($data['nama_lengkap']) ?></h4>
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
                        <div class="d-flex align-items-center mt-2 mb-4">
                            <div class="bg-light p-2 rounded-circle me-3"><i class="mdi mdi-calendar text-primary"></i></div>
                            <span class="fw-bold"><?= date('d F Y', strtotime($data['tanggal_daftar'] ?? $data['created_at'])) ?></span>
                        </div>

                        <div class="d-grid">
                            <?php if (!empty($data['file_cv'])): ?>
                                <a href="<?= base_url('uploads/berkas/' . $data['file_cv']) ?>" target="_blank" class="btn btn-outline-primary fw-bold">
                                    <i class="mdi mdi-file-document me-1"></i> Lihat File CV
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>CV Tidak Tersedia</button>
                            <?php endif; ?>
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
                            <small class="text-muted">Metode Weighted Product</small>
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

            <!-- Keputusan & Proses -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">Keputusan & Proses Rekrutmen</h5>

                    <!-- Belum Diputuskan / Proses -->
                    <?php if(in_array($data['status'] ?? '', [null, '', 'proses', 'seleksi', 'tidak memenuhi'])): ?>
                        <p class="small text-muted mb-3">Tentukan hasil seleksi. Notifikasi WhatsApp akan dikirim otomatis.</p>
                        <div class="d-grid d-md-flex gap-3">
                            <button type="button" class="btn btn-success rounded-pill px-4 py-3 fw-bold flex-grow-1 shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#modalLolos">
                                <i class="mdi mdi-check-circle-outline me-2"></i> Nyatakan Lolos Seleksi
                            </button>
                            <button type="button" class="btn btn-danger rounded-pill px-4 py-3 fw-bold flex-grow-1 shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#modalTidakLolos">
                                <i class="mdi mdi-close-circle-outline me-2"></i> Nyatakan Tidak Lolos
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Sudah Lolos -->
                    <?php if($data['status'] == 'memenuhi'): ?>
                        <div class="alert alert-success border-0 shadow-sm">
                            <h6 class="fw-bold mb-2"><i class="mdi mdi-check-circle me-2"></i> Lolos Seleksi</h6>
                            <p class="small text-muted mb-3">Kandidat memenuhi kualifikasi. Lanjutkan ke tahap penawaran?</p>
                            <button type="button" class="btn btn-success rounded-pill w-100 py-3 fw-bold shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#modalOffering">
                                <i class="mdi mdi-email-fast-outline me-2"></i> Kirim Offering Letter via WhatsApp
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Offering Dikirim -->
                    <?php if($data['status'] == 'offering'): ?>
                        <div class="alert alert-warning border-0 shadow-sm">
                            <h6 class="fw-bold mb-2"><i class="mdi mdi-clock-outline me-2"></i> Menunggu Konfirmasi</h6>
                            <p class="small text-muted mb-3">Offering sudah dikirim. Masukkan jawaban pelamar:</p>
                            <div class="d-grid d-md-flex gap-3">
                                <button type="button" class="btn btn-primary rounded-pill px-4 py-3 fw-bold flex-grow-1 shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalTerima">
                                    <i class="mdi mdi-handshake me-2"></i> Pelamar Menerima
                                </button>
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-3 fw-bold flex-grow-1"
                                        data-bs-toggle="modal" data-bs-target="#modalTolakOffering">
                                    <i class="mdi mdi-close me-2"></i> Pelamar Menolak
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Hired -->
                    <?php if($data['status'] == 'hired'): ?>
                        <div class="alert alert-primary border-0 text-center py-5 shadow-sm">
                            <i class="mdi mdi-party-popper fs-1 mb-3 d-block"></i>
                            <h5 class="fw-bold">SELAMAT! SUDAH DIREKRUT</h5>
                            <p class="small text-muted">Kandidat resmi menjadi karyawan perusahaan.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Rejected Offer -->
                    <?php if($data['status'] == 'rejected_offer'): ?>
                        <div class="alert alert-secondary border-0 text-center py-5 shadow-sm">
                            <i class="mdi mdi-close-circle fs-1 mb-3 d-block"></i>
                            <h5 class="fw-bold">MENOLAK TAWARAN</h5>
                            <p class="small text-muted">Pelamar menolak offering letter.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Lolos Seleksi -->
<div class="modal fade" id="modalLolos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title fw-bold"><i class="mdi mdi-check-circle-outline me-2"></i> Nyatakan Lolos?</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="mdi mdi-account-check-outline text-success opacity-25" style="font-size: 80px;"></i>
                <p class="mt-3 mb-2">Pelamar akan dinyatakan <strong>LOLOS SELEKSI</strong>.</p>
                <p class="text-muted small">Notifikasi WhatsApp akan dikirim otomatis ke kandidat.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('admin/data/status/' . $data['id'] . '/memenuhi') ?>"
                   class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">Ya, Loloskan</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Tidak Lolos -->
<div class="modal fade" id="modalTidakLolos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title fw-bold"><i class="mdi mdi-close-circle-outline me-2"></i> Nyatakan Tidak Lolos?</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="mdi mdi-account-remove-outline text-danger opacity-25" style="font-size: 80px;"></i>
                <p class="mt-3 mb-2">Pelamar akan dinyatakan <strong>TIDAK LOLOS</strong>.</p>
                <p class="text-muted small">Pesan penolakan WhatsApp akan dikirim otomatis.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('admin/data/status/' . $data['id'] . '/tidak memenuhi') ?>"
                   class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">Ya, Tolak</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kirim Offering -->
<div class="modal fade" id="modalOffering" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title fw-bold"><i class="mdi mdi-email-fast-outline me-2"></i> Kirim Offering Letter?</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="mdi mdi-file-send-outline text-primary opacity-25" style="font-size: 80px;"></i>
                <p class="mt-3 mb-2">Offering letter akan dikirim via WhatsApp ke pelamar.</p>
                <p class="text-muted small">Status akan berubah menjadi "Menunggu Konfirmasi".</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('admin/data/offering/' . $data['id']) ?>"
                   class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Ya, Kirim Offering</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pelamar Menerima -->
<div class="modal fade" id="modalTerima" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title fw-bold"><i class="mdi mdi-handshake me-2"></i> Pelamar Menerima Tawaran?</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="mdi mdi-account-heart-outline text-success opacity-25" style="font-size: 80px;"></i>
                <p class="mt-3 mb-2">Pelamar <strong>MENERIMA</strong> tawaran kerja.</p>
                <p class="text-muted small">Data akan dipindahkan ke Daftar Karyawan.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('admin/data/confirm/' . $data['id'] . '/terima') ?>"
                   class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">Ya, Terima</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pelamar Menolak Offering -->
<div class="modal fade" id="modalTolakOffering" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-secondary text-white">
                <h6 class="modal-title fw-bold"><i class="mdi mdi-close me-2"></i> Pelamar Menolak Tawaran?</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="mdi mdi-account-off-outline text-secondary opacity-25" style="font-size: 80px;"></i>
                <p class="mt-3 mb-2">Pelamar <strong>MENOLAK</strong> offering letter.</p>
                <p class="text-muted small">Data akan diarsipkan sebagai ditolak.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('admin/data/confirm/' . $data['id'] . '/tolak') ?>"
                   class="btn btn-secondary rounded-pill px-5 fw-bold shadow-sm">Ya, Tolak</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>