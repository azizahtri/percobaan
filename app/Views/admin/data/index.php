<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  
  <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
    <div class="fs-4 me-3 mt-1"><i class="mdi mdi-information-outline"></i></div>
    <div>
        <h5 class="alert-heading fw-bold mb-1">Panduan Data Pelamar (Arsip)</h5>
        <p class="mb-2 small">Halaman ini mengelola data pelamar yang sedang diproses maupun yang sudah selesai dinilai (History).</p>
        <ul class="small mb-0 ps-3">
            <li><strong>Tabel Atas (Sedang Diproses):</strong> Pelamar baru yang belum dinilai. Klik tombol <b>"Proses"</b> (ikon pensil) untuk menilai.</li>
            <li><strong>Tabel Bawah (History):</strong> Pelamar yang sudah selesai dinilai. Klik <b>"Detail"</b> (ikon mata) untuk melihat hasil akhir.</li>
        </ul>
    </div>
  </div>

  <h4 class="mb-4 text-dark fw-bold">Data Pelamar</h4>

  <div class="card shadow-sm border-0 mb-5">
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="mdi mdi-hourglass text-white me-2"></i>Pelamar Sedang Diproses</h6>
        <span class="badge bg-white text-primary rounded-pill"><?= count($diproses) ?> Orang</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center datatable" style="width:100%">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="text-start">Nama Pelamar</th>
                        <th>Email</th>
                        <th>Lowongan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($diproses)): ?>
                        <?php foreach ($diproses as $d): ?>
                        <tr>
                            <td class="text-start">
                                <span class="fw-bold text-dark d-block"><?= esc($d['nama']) ?></span>
                                <small class="text-muted"><?= date('d M Y', strtotime($d['created_at'] ?? 'now')) ?></small>
                            </td>
                            <td><?= esc($d['email']) ?></td>
                            <td>
                                <span class="badge bg-info text-dark border border-info-subtle">
                                    <?= esc($d['judul_lowongan']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 rounded-pill">
                                    <?= ucfirst($d['status'] ?: 'Proses') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/lowongan/pelamar/' . $d['id']) ?>" 
                                   class="btn btn-action btn-action-edit" 
                                   title="Proses Penilaian"
                                   data-bs-toggle="tooltip" data-bs-placement="top">
                                    <i class="mdi mdi-pencil-box-outline"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-header bg-secondary text-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="mdi mdi-history text-white me-2"></i>History Pelamar (Selesai)</h6>
    </div>

    <div class="card-body">
        
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center datatable" style="width:100%">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="text-start">Nama Pelamar</th>
                        <th>Email</th>
                        <th>Lowongan</th>
                        <th>SPK Score</th>
                        <th>Hasil Akhir</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($history)): ?>
                        <?php foreach ($history as $h): ?>
                        <tr>
                            <td class="text-start fw-bold text-dark"><?= esc($h['nama']) ?></td>
                            <td><?= esc($h['email']) ?></td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= esc($h['judul_lowongan']) ?>
                                </span>
                            </td>
                            
                            <td>
                                <?php if($h['spk_score'] > 0): ?>
                                    <span class="fw-bold text-primary"><?= number_format($h['spk_score'], 4) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php 
                                    $statusClass = match($h['status']) {
                                        'memenuhi'       => 'bg-success-subtle text-success border-success-subtle',
                                        'tidak memenuhi' => 'bg-danger-subtle text-danger border-danger-subtle',
                                        default          => 'bg-secondary-subtle text-secondary border-secondary-subtle'
                                    };
                                ?>
                                <span class="badge <?= $statusClass ?> px-3 py-2 rounded-pill border">
                                    <?= strtoupper($h['status']) ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="<?= base_url('admin/data/detail/' . $h['id']) ?>" 
                                   class="btn btn-action btn-action-detail" 
                                   title="Lihat Detail Hasil"
                                   data-bs-toggle="tooltip" data-bs-placement="top">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>