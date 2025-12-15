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

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">Keputusan & Proses Rekrutmen</h5>
                    
                    <?php if($data['status'] == null || $data['status'] == 'proses' || $data['status'] == 'seleksi' || $data['status'] == 'tidak memenuhi'): ?>
                        <div class="mb-4">
                            <p class="small text-muted mb-2">Tentukan hasil seleksi (Email notifikasi otomatis dikirim):</p>
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('admin/data/status/' . $data['id'] . '/memenuhi') ?>" 
                                   class="btn btn-outline-success bg-white flex-grow-1 py-2 fw-bold"
                                   onclick="return confirm('Nyatakan Lolos? Email notifikasi akan dikirim ke pelamar.')">
                                    <i class="mdi mdi-check-circle-outline me-1"></i> Lolos Seleksi
                                </a>

                                <a href="<?= base_url('admin/data/status/' . $data['id'] . '/tidak memenuhi') ?>" 
                                   class="btn btn-outline-danger bg-white flex-grow-1 py-2 fw-bold"
                                   onclick="return confirm('Nyatakan Tidak Lolos? Email penolakan akan dikirim.')">
                                    <i class="mdi mdi-close-circle-outline me-1"></i> Tidak Lolos
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($data['status'] == 'memenuhi'): ?>
                        <div class="alert alert-success border-0 fade show shadow-sm" role="alert">
                            <h6 class="fw-bold mb-1 text-success"><i class="mdi mdi-check-circle me-1"></i> Lolos Seleksi</h6>
                            <p class="small text-muted mb-3">Kandidat memenuhi kualifikasi. Lanjutkan ke tahap penawaran kerja?</p>
                            
                            <a href="<?= base_url('admin/data/offering/' . $data['id']) ?>" 
                               class="btn btn-success w-100 fw-bold py-2 rounded-pill shadow-sm"
                               onclick="return confirm('Kirim email Offering Letter ke pelamar?')">
                                <i class="mdi mdi-email-fast-outline me-1"></i> Kirim Offering Letter
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if($data['status'] == 'offering'): ?>
                        <div class="alert alert-warning border-0 fade show shadow-sm" role="alert">
                            <h6 class="fw-bold mb-1 text-warning"><i class="mdi mdi-clock-outline me-1"></i> Menunggu Konfirmasi</h6>
                            <p class="small text-muted mb-3">Email Offering sudah dikirim. Masukkan jawaban pelamar secara manual di sini:</p>
                            
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('admin/data/confirm/' . $data['id'] . '/terima') ?>" 
                                   class="btn btn-primary flex-grow-1 fw-bold"
                                   onclick="return confirm('Pelamar MENERIMA tawaran? Data akan masuk ke Data Karyawan.')">
                                    <i class="mdi mdi-handshake me-1"></i> Pelamar Menerima
                                </a>
                                <a href="<?= base_url('admin/data/confirm/' . $data['id'] . '/tolak') ?>" 
                                   class="btn btn-outline-secondary flex-grow-1 fw-bold bg-white"
                                   onclick="return confirm('Pelamar MENOLAK tawaran? Data akan diarsipkan.')">
                                    <i class="mdi mdi-close me-1"></i> Pelamar Menolak
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($data['status'] == 'hired'): ?>
                        <div class="alert alert-primary border-0 text-center mb-0">
                            <i class="mdi mdi-party-popper fs-3"></i><br>
                            <strong>SUDAH DIREKRUT</strong><br>
                            <span class="small">Kandidat resmi menjadi karyawan.</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($data['status'] == 'rejected_offer'): ?>
                        <div class="alert alert-secondary border-0 text-center mb-0">
                            <i class="mdi mdi-close-circle fs-3"></i><br>
                            <strong>MENOLAK TAWARAN</strong><br>
                            <span class="small">Pelamar menolak offering letter.</span>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>