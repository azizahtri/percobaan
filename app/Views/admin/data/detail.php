<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  <div class="card shadow-sm" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Arsip Hasil Seleksi</h5>
        <a href="<?= base_url('admin/data') ?>" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        
        <div class="text-center mb-4">
            <h3 class="fw-bold"><?= esc($data['nama']) ?></h3>
            <span class="badge bg-light text-dark border"><?= esc($data['judul_lowongan']) ?></span>
        </div>

        <div class="row mb-3">
            <div class="col-6 border-end text-center">
                <small class="text-muted">Skor Weighted Product</small>
                <h2 class="text-primary fw-bold"><?= number_format($data['spk_score'] ?? 0, 4) ?></h2>
            </div>
            <div class="col-6 text-center">
                <small class="text-muted">Status Akhir</small><br>
                <span class="badge fs-6 mt-1 <?= $data['status']=='memenuhi' ? 'bg-success' : 'bg-danger' ?>">
                    <?= strtoupper($data['status']) ?>
                </span>
            </div>
        </div>

        <div class="border rounded p-3 bg-light mb-4">
            <div class="row">
                <div class="col-6"><strong>Email:</strong><br><?= esc($data['email']) ?></div>
                <div class="col-6"><strong>No HP:</strong><br><?= esc($data['no_hp']) ?></div>
            </div>
            <hr>
            <strong>Link CV:</strong><br>
            <?php if (filter_var($data['link'], FILTER_VALIDATE_URL)): ?>
                <a href="<?= $data['link'] ?>" target="_blank" class="text-decoration-none">
                    <i class="bi bi-box-arrow-up-right"></i> Buka Berkas (Google Drive)
                </a>
            <?php else: ?>
                <span class="text-danger">- Link tidak valid / Kosong -</span>
            <?php endif; ?>
        </div>

        <?php if($data['status'] == 'memenuhi'): ?>
            <div class="card border-primary mb-3">
                <div class="card-body text-center">
                    <h6 class="fw-bold text-primary">Tindak Lanjut Rekrutmen</h6>
                    
                    <?php if($isRekrut): ?>
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle-fill"></i> Kandidat ini sudah resmi menjadi Karyawan (Alternatif).
                        </div>
                    <?php else: ?>
                        <p class="small text-muted mb-3">
                            Jika proses offering & pemberkasan selesai, klik tombol di bawah untuk memasukkan data ke Master Karyawan.
                        </p>
                        <a href="<?= base_url('admin/data/onboard/' . $data['id']) ?>" 
                           class="btn btn-primary w-100 py-2"
                           onclick="return confirm('Apakah pelamar ini sudah menyetujui offering dan siap menjadi karyawan aktif?')">
                           <i class="bi bi-person-plus-fill"></i> REKRUT / JADIKAN KARYAWAN
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
  </div>
</div>

<?= $this->endSection() ?>