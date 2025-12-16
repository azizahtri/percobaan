<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
      <h4 class="mb-0 text-dark fw-bold">Manajemen Data Pelamar</h4>
      
      <form method="get" class="d-flex align-items-center bg-white p-2 rounded shadow-sm border">
          <label class="small fw-bold text-muted me-2 text-nowrap mb-0"><i class="mdi mdi-filter-variant"></i> Filter Divisi:</label>
          <select name="divisi" class="form-select form-select-sm border-0 fw-bold text-primary bg-light" 
                  style="min-width: 150px; cursor: pointer;" onchange="this.form.submit()">
              <option value="all">- Semua Divisi -</option>
              <?php foreach ($divisiList as $div): ?>
                  <option value="<?= esc($div['divisi']) ?>" <?= ($selectedDivisi == $div['divisi']) ? 'selected' : '' ?>>
                      <?= esc($div['divisi']) ?>
                  </option>
              <?php endforeach; ?>
          </select>
          
          <?php if($selectedDivisi != 'all'): ?>
              <a href="<?= base_url('admin/data') ?>" class="btn btn-sm btn-link text-danger text-decoration-none ms-2" title="Hapus Filter">
                  <i class="mdi mdi-close-circle"></i>
              </a>
          <?php endif; ?>
      </form>
  </div>

  <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active fw-bold" id="baru-tab" data-bs-toggle="tab" data-bs-target="#baru" type="button">
        Pelamar Baru <span class="badge bg-primary rounded-pill ms-2"><?= count($belumDinilai) ?></span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold" id="nilai-tab" data-bs-toggle="tab" data-bs-target="#nilai" type="button">
        Hasil Penilaian <span class="badge bg-warning text-dark rounded-pill ms-2"><?= count($sudahDinilai) ?></span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold text-secondary" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
        History / Arsip
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold text-danger" id="blacklist-tab" data-bs-toggle="tab" data-bs-target="#blacklist" type="button">
        Blacklist <span class="badge bg-danger rounded-pill ms-2"><?= count($blacklist ?? []) ?></span>
      </button>
    </li>
  </ul>

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
                                <td class="text-start fw-bold"><?= esc($d['nama_lengkap']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= esc($d['judul_lowongan']) ?></span></td>
                                <td><?= date('d M Y', strtotime($d['tanggal_daftar'])) ?></td>
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
                                <td class="text-start fw-bold"><?= esc($d['nama_lengkap']) ?></td>
                                <td><?= esc($d['judul_lowongan']) ?></td>
                                <td class="fw-bold text-primary"><?= number_format($d['spk_score'], 4) ?></td>
                                <td>
                                    <?php 
                                        $cls = match($d['status']) {
                                            'memenuhi' => 'bg-success',
                                            'tidak memenuhi' => 'bg-danger',
                                            'blacklist'      => 'bg-dark text-white',
                                            default => 'bg-secondary'
                                        };
                                    ?>
                                    <span class="badge <?= $cls ?>"><?= ucfirst($d['status'] ?: 'Belum Putus') ?></span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= base_url('admin/data/detail/' . $d['id']) ?>" class="btn btn-action btn-action-detail" title="Lihat Detail"><i class="mdi mdi-eye"></i></a>
                                        <?php if (in_array($d['status'], ['memenuhi', 'tidak memenuhi', 'blacklist'])): ?>
                                            <button type="button"
                                                    class="btn btn-success rounded-pill px-4 fw-bold shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalSelesai<?= $d['id'] ?>"
                                                    title="Selesaikan Proses">
                                                <i class="mdi mdi-check me-1"></i> Selesai
                                            </button>
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
                                <td class="text-start"><?= esc($h['nama_lengkap']) ?></td>
                                <td><?= esc($h['judul_lowongan']) ?></td>
                                <td>
                                    <?php 
                                        $badge = match($h['status']) {
                                            'memenuhi' => 'bg-success',
                                            'tidak memenuhi' => 'bg-danger',
                                            'blacklist' => 'bg-dark',
                                            default => 'bg-secondary'
                                        };
                                    ?>
                                    <span class="badge <?= $badge ?>">
                                        <?= strtoupper($h['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/data/detail/' . $h['id']) ?>" class="btn btn-action btn-action-detail"><i class="mdi mdi-eye"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="blacklist" role="tabpanel">
        <div class="card shadow-sm border-0 border-top border-4 border-danger">
            <div class="card-body">
                <div class="alert alert-danger small border-0 bg-danger-subtle text-danger">
                    <i class="mdi mdi-alert-circle me-2"></i>Daftar pelamar yang diblokir oleh sistem. Mereka tidak dapat melamar lowongan baru.
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center datatable" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Nama Pelamar</th>
                                <th class="text-center">No KTP / NIK</th>
                                <th class="text-center">Alasan Blacklist</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($blacklist ?? [] as $b): ?>
                            <tr>
                                <td class="text-start">
                                    <span class="fw-bold text-danger"><?= esc($b['nama_lengkap']) ?></span>
                                    <?php if($b['blacklist_type'] == 'permanent'): ?>
                                        <span class="badge bg-danger ms-2">PERMANEN</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark ms-2">SEMENTARA</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= esc($b['no_ktp']) ?></td>
                                <td class="text-center text-muted small fst-italic">"<?= esc($b['alasan_blacklist']) ?>"</td>
                                <td class="text-center">
                                    
                                    <?php if($b['blacklist_type'] == 'permanent'): ?>
                                        <button class="btn btn-secondary btn-sm rounded-pill px-3" disabled title="Sanksi Permanen Tidak Bisa Dipulihkan">
                                            <i class="mdi mdi-lock me-1"></i> Terkunci
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin/data/pulihkan-blacklist/' . $b['id']) ?>" 
                                           class="btn btn-outline-success btn-sm rounded-pill px-3"
                                           onclick="return confirm('Yakin ingin memulihkan status pelamar ini?')">
                                            <i class="mdi mdi-refresh me-1"></i> Pulihkan
                                        </a>
                                    <?php endif; ?>

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

<?php foreach ($sudahDinilai as $d): ?>
    <?php if (in_array($d['status'], ['memenuhi', 'tidak memenuhi', 'blacklist'])): ?>
    <div class="modal fade" id="modalSelesai<?= $d['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title fw-bold">
                        <i class="mdi mdi-check-all me-2"></i> Selesaikan Proses Rekrutmen?
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="mdi mdi-archive-arrow-down-outline text-success opacity-25" style="font-size: 80px;"></i>
                    <p class="mt-3 mb-2 fw-bold">Proses rekrutmen untuk pelamar ini akan diselesaikan.</p>
                    <p class="text-muted small">Data akan dipindahkan ke riwayat (history) dan tidak muncul lagi di daftar aktif.</p>
                    
                    <div class="alert alert-light border mt-3 mb-0 py-2">
                        <small class="fw-bold text-dark"><?= esc($d['nama_lengkap']) ?></small><br>
                        <span class="badge bg-secondary"><?= ucfirst($d['status']) ?></span>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <a href="<?= base_url('admin/data/arsipkan/' . $d['id']) ?>"
                       class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                        Ya, Selesaikan
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; ?>

<?= $this->endSection() ?>