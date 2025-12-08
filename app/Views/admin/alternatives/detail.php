<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Detail Karyawan</h5>
                    <a href="<?= base_url('admin/alternatives') ?>" class="btn btn-sm btn-light text-primary fw-bold">Kembali</a>
                </div>
                
                <div class="card-body text-center pt-5 pb-4">
                    <div class="avatar-circle bg-light text-primary mx-auto mb-3 border d-flex align-items-center justify-content-center rounded-circle" style="width: 100px; height: 100px; font-size: 40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    
                    <h3 class="fw-bold mb-1"><?= esc($karyawan['nama']) ?></h3>
                    <span class="badge bg-info text-dark fs-6 mb-4"><?= esc($karyawan['posisi']) ?></span>

                    <div class="row text-start mt-4 px-md-5">
                        <div class="col-12 mb-3">
                            <h6 class="text-uppercase text-muted small fw-bold border-bottom pb-2">Info Kepegawaian</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Kode Karyawan</small>
                            <span class="fw-bold"><?= esc($karyawan['kode']) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success">AKTIF</span>
                        </div>

                        <div class="col-12 mb-3 mt-2">
                            <h6 class="text-uppercase text-muted small fw-bold border-bottom pb-2">Biodata & Kontak</h6>
                        </div>

                        <?php if($biodata): ?>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">Email</small>
                                <strong><?= esc($biodata['email']) ?></strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">No HP / WhatsApp</small>
                                <strong>
                                <?php 
                                    // Format Nomor HP untuk Link WA (Ganti 0 di depan jadi 62)
                                    $hpRaw = $biodata['no_hp'];
                                    // Hapus karakter selain angka (spasi, strip, dll)
                                    $hpClean = preg_replace('/[^0-9]/', '', $hpRaw);
                                    
                                    // Jika diawali angka 0, ganti dengan 62
                                    if(substr($hpClean, 0, 1) == '0'){
                                        $hpClean = '62' . substr($hpClean, 1);
                                    }
                                ?>
                                
                                <a href="https://wa.me/<?= $hpClean ?>" target="_blank" class="text-decoration-none text-success fw-bold" title="Chat via WhatsApp">
                                    <?= esc($biodata['no_hp']) ?> <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 10px;"></i>
                                </a>
                                </strong>
                            </div>
                            <div class="col-12 mb-3">
                                <small class="text-muted d-block">Pesan / Cover Letter Awal</small>
                                <div class="bg-light p-3 rounded mt-1 small text-secondary">
                                    <?= nl2br(esc($biodata['pesan'] ?? '-')) ?>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <?php if (filter_var($biodata['link'], FILTER_VALIDATE_URL)): ?>
                                    <a href="<?= $biodata['link'] ?>" target="_blank" class="btn btn-outline-primary">
                                        <i class="bi bi-file-earmark-person me-2"></i>Lihat Folder Berkas
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-warning small">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    Data biodata pelamar asli tidak ditemukan atau sudah dihapus dari arsip.
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>