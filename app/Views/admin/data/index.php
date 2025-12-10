<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  
  <h4 class="mb-4 text-dark fw-bold">Manajemen Data Pelamar</h4>

  <!-- wadah tabel -->
  <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active fw-bold" id="baru-tab" data-bs-toggle="tab" data-bs-target="#baru" type="button">
        Pelamar Baru <span class="badge bg-danger rounded-pill ms-2"><?= count($belumDinilai) ?></span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold" id="nilai-tab" data-bs-toggle="tab" data-bs-target="#nilai" type="button">
        Hasil Penilaian (SPK) <span class="badge bg-warning text-dark rounded-pill ms-2"><?= count($sudahDinilai) ?></span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold text-secondary" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
        History / Arsip
      </button>
    </li>
  </ul>

  <!-- bagian tabel pelamar baru -->
  <div class="tab-content" id="myTabContent">
    
    <div class="tab-pane fade show active" id="baru" role="tabpanel">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center datatable" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Lowongan</th>
                                <th class="text-center">Tanggal Masuk</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($belumDinilai as $d): ?>
                            <tr>
                                <td class="text-start fw-bold"><?= esc($d['nama']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= esc($d['judul_lowongan']) ?></span></td>
                                <td><?= date('d M Y', strtotime($d['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/lowongan/pelamar/' . $d['id']) ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                        <i class="mdi mdi-calculator me-1"></i> Hitung SPK
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- bagian tabel penilaian -->
    <div class="tab-pane fade" id="nilai" role="tabpanel">
        <div class="card shadow-sm border-0 border-top border-4 border-warning">
            <div class="card-body">
                <div class="alert alert-warning small border-0"><i class="mdi mdi-information me-2"></i>Pelamar di tabel ini sudah dihitung skornya. Silakan tentukan status (Memenuhi/Tidak) di menu Detail, lalu Arsipkan.</div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center datatable" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Lowongan</th>
                                <th class="text-center">Skor SPK</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sudahDinilai as $d): ?>
                            <tr>
                                <td class="text-start fw-bold"><?= esc($d['nama']) ?></td>
                                <td><?= esc($d['judul_lowongan']) ?></td>
                                <td class="fw-bold text-primary"><?= number_format($d['spk_score'], 4) ?></td>
                                <td>
                                    <?php 
                                        $cls = match($d['status']) {
                                            'memenuhi' => 'bg-success',
                                            'tidak memenuhi' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    ?>
                                    <span class="badge <?= $cls ?>"><?= ucfirst($d['status'] ?: 'Belum Putus') ?></span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= base_url('admin/data/detail/' . $d['id']) ?>" 
                                            class="btn btn-action btn-action-detail" 
                                            title="Lihat Detail"
                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        
                                        <?php if($d['status'] == 'memenuhi' || $d['status'] == 'tidak memenuhi'): ?>
                                            <a href="<?= base_url('admin/data/arsipkan/' . $d['id']) ?>" class="btn btn-success btn-sm" onclick="return confirm('Selesaikan proses dan pindah ke history?')" title="Selesai">
                                                <i class="mdi mdi-check"></i> Selesai
                                            </a>
                                        <?php endif; ?>
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

<!-- bagian tabel selesai/history -->
    <div class="tab-pane fade" id="history" role="tabpanel">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center datatable" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Lowongan</th>
                                <th class="text-center">Hasil Akhir</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $h): ?>
                            <tr>
                                <td class="text-start"><?= esc($h['nama']) ?></td>
                                <td><?= esc($h['judul_lowongan']) ?></td>
                                <td>
                                    <span class="badge <?= $h['status'] == 'memenuhi' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= strtoupper($h['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/data/detail/' . $h['id']) ?>" 
                                    class="btn btn-action btn-action-detail">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

  </div>
</div>
<?= $this->endSection() ?>