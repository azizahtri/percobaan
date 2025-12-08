<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  
  <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
    <div class="fs-4 me-3 mt-1"><i class="bi bi-info-circle-fill"></i></div>
    <div>
        <h5 class="alert-heading fw-bold mb-1">Panduan Data Pelamar (Arsip)</h5>
        <p class="mb-2 small">Halaman ini mengelola data pelamar yang sedang diproses maupun yang sudah selesai dinilai (History).</p>
        <ul class="small mb-0 ps-3">
            <li><strong>Tabel Atas (Sedang Diproses):</strong> Berisi pelamar baru yang belum dinilai. Klik tombol <b>"Proses"</b> untuk memberikan nilai SPK.</li>
            <li>Jika sudah melakukan penilaian pada pelamar tetapi belum tekan tombol <b>"Selesai"</b>, penilaian masih bisa dilakukan. </li>
            <li><strong>Tabel Bawah (History):</strong> Berisi pelamar yang sudah selesai dinilai. Klik <b>"Detail"</b> untuk melihat hasil akhir.</li>
            <li><strong>Rekrutmen:</strong> Untuk mengangkat pelamar menjadi karyawan, buka detail pelamar yang berstatus <b>"MEMENUHI"</b> lalu klik tombol <b>"Rekrut"</b>.</li>
        </ul>
    </div>
  </div>

  <h4 class="mb-4 text-dark fw-bold">Data Pelamar</h4>

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0 text-white fw-bold"><i class="bi bi-hourglass-split me-2"></i>Pelamar Sedang Diproses</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Lowongan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($diproses)): ?>
                        <?php foreach ($diproses as $d): ?>
                        <tr>
                            <td class="fw-bold"><?= esc($d['nama']) ?></td>
                            <td><?= esc($d['email']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= esc($d['judul_lowongan']) ?></span></td>
                            <td>
                                <span class="badge bg-secondary"><?= ucfirst($d['status']) ?></span>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/lowongan/pelamar/' . $d['id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square me-1"></i> Proses Nilai
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Tidak ada pelamar yang sedang diproses.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-white fw-bold"><i class="bi bi-archive me-2"></i>History Pelamar (Selesai)</h6>
        
        <form action="" method="get" class="d-flex" style="max-width: 300px;">
            <input type="text" name="keyword" class="form-control form-control-sm me-2" placeholder="Cari pelamar..." value="<?= esc($keyword) ?>">
            <button type="submit" class="btn btn-sm btn-dark px-3">Cari</button>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle text-center">
                <thead class="table-light text-secondary text-uppercase small">
                    <tr>
                        <th class="text-start">Nama</th>
                        <th>Email</th>
                        <th>Lowongan</th>
                        <th>SPK Score</th>
                        <th>Hasil Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($history)): ?>
                        <?php foreach ($history as $h): ?>
                        <tr>
                            <td class="fw-bold text-start"><?= esc($h['nama']) ?></td>
                            <td><?= esc($h['email']) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= esc($h['judul_lowongan']) ?></span></td>
                            
                            <td>
                                <span class="fw-bold text-primary"><?= number_format($h['spk_score'] ?? 0, 4) ?></span>
                            </td>

                            <td>
                                <?php if($h['status'] == 'memenuhi'): ?>
                                    <span class="badge bg-success">MEMENUHI</span>
                                <?php elseif($h['status'] == 'tidak memenuhi'): ?>
                                    <span class="badge bg-danger">TIDAK MEMENUHI</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= strtoupper($h['status']) ?></span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="<?= base_url('admin/data/detail/' . $h['id']) ?>" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-folder2-open" style="font-size: 2rem; opacity: 0.5;"></i>
                                <p class="mt-2 mb-0">Belum ada history data pelamar.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>