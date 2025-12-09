<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="bg-primary text-white rounded p-3 me-3">
            <i class="mdi mdi-trophy-variant-outline fs-2"></i>
        </div>
        <div>
            <h4 class="mb-0 fw-bold text-primary">Hasil Perangkingan (SPK)</h4>
            <p class="text-muted mb-0 small">Daftar peringkat pelamar berdasarkan skor Weighted Product tertinggi.</p>
        </div>
    </div>
  </div>

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        
        <form method="get" class="d-flex align-items-center gap-2 w-100" style="max-width: 500px;">
            <label class="fw-bold text-nowrap">Filter Kategori:</label>
            <select name="field" class="form-select" onchange="this.form.submit()">
                <option value="all">-- Semua Kategori --</option>
                
                <?php if(!empty($divisiList)): ?>
                    <?php foreach ($divisiList as $d): ?>
                        <option value="<?= esc($d['divisi']) ?>" <?= ($selectedField == $d['divisi']) ? 'selected' : '' ?>>
                            <?= esc($d['divisi']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>

            </select>
            
            <?php if ($selectedField != 'all'): ?>
                <a href="<?= base_url('admin/spk') ?>" class="btn btn-secondary btn-sm">Reset</a>
            <?php endif; ?>
        </form>

        <?php if ($selectedField == 'all' && !empty($rankingData)): ?>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleAll('show')">
                    <i class="mdi mdi-arrow-expand-vertical"></i> Buka Semua
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleAll('hide')">
                    <i class="mdi mdi-arrow-collapse-vertical"></i> Tutup Semua
                </button>
            </div>
        <?php endif; ?>

    </div>
  </div>

  <?php if (empty($rankingData)): ?>
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="mdi mdi-alert-circle-outline fs-4 me-2"></i>
        <div>
             Belum ada data penilaian. Silakan lakukan penilaian pelamar di menu <strong>Kelola Lowongan</strong> terlebih dahulu.
        </div>
    </div>
  <?php else: ?>

    <div class="accordion" id="accordionRanking">
    <?php foreach ($rankingData as $group): ?>
        <?php 
            $job        = $group['job'];
            $candidates = $group['candidates'];
            
            // Pakai operator ?? agar jika null defaultnya 0
            $standar    = $job['standar_spk'] ?? 0; 
            
            $collapseId = 'collapse_' . $job['id'];
            $showClass  = ($selectedField != 'all') ? 'show' : '';
        ?>

        <div class="card shadow-sm border-0 mb-3 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 cursor-pointer" 
                 data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                 
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="btn btn-light btn-sm text-primary rounded-circle border">
                            <i class="mdi mdi-chevron-down"></i>
                        </span>
                        <div>
                            <h5 class="card-title mb-1 fw-bold text-dark">
                                <i class="mdi mdi-briefcase-outline me-1 text-info"></i> <?= esc($job['judul_lowongan']) ?>
                            </h5>
                            <small class="text-muted">
                                Standar Lulus: <span class="badge bg-secondary"><?= $standar ?></span> 
                                &bull; Pelamar Dinilai: <strong><?= count($candidates) ?></strong>
                            </small>
                        </div>
                    </div>
                    
                    <?php if(!empty($candidates)): ?>
                        <div class="text-end d-none d-md-block">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">TOP CANDIDATE</small>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                <i class="mdi mdi-crown"></i> <?= esc($candidates[0]['nama']) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="<?= $collapseId ?>" class="collapse <?= $showClass ?>" data-bs-parent="#accordionRanking">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center mb-0 datatable">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th width="5%">Rank</th>
                                    <th class="text-start">Nama Kandidat</th>
                                    <th>Nilai (Vector S)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidates as $rank => $c): ?>
                                    <?php 
                                        $isLolos = $c['spk_score'] >= $standar;
                                        $rankBadge = $rank + 1;
                                        if($rank == 0) $rankBadge = '<i class="mdi mdi-crown text-warning fs-4"></i>'; 
                                        else if($rank == 1) $rankBadge = '<span class="badge bg-secondary rounded-circle" style="width:25px;height:25px;">2</span>';
                                        else if($rank == 2) $rankBadge = '<span class="badge bg-brown rounded-circle" style="width:25px;height:25px;background:#cd7f32;">3</span>';
                                    ?>
                                    
                                    <tr class="<?= ($rank == 0) ? 'bg-primary-subtle' : '' ?>">
                                        <td class="fw-bold"><?= $rankBadge ?></td>
                                        <td class="text-start">
                                            <span class="fw-bold d-block"><?= esc($c['nama']) ?></span>
                                            <small class="text-muted"><?= esc($c['email']) ?></small>
                                        </td>
                                        <td>
                                            <span class="fs-5 fw-bold <?= $isLolos ? 'text-primary' : 'text-danger' ?>">
                                                <?= number_format($c['spk_score'], 4) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($isLolos): ?>
                                                <span class="badge bg-success"><i class="mdi mdi-check-circle"></i> Memenuhi</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><i class="mdi mdi-close-circle"></i> Tidak Memenuhi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-dark border-0" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDetail<?= $c['id'] ?>">
                                                <i class="mdi mdi-eye me-1"></i> Detail
                                            </button>

                                            <div class="modal fade" id="modalDetail<?= $c['id'] ?>" tabindex="-1">
                                              <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content text-start"> <div class="modal-header <?= $isLolos ? 'bg-success' : 'bg-danger' ?> text-white">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="mdi mdi-account-details me-2"></i>Detail Hasil Seleksi
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                  </div>

                                                  <div class="modal-body text-center pb-4">
                                                    
                                                    <h3 class="fw-bold mt-3"><?= esc($c['nama']) ?></h3>
                                                    <p class="text-muted mb-4"><?= esc($job['judul_lowongan']) ?></p>

                                                    <div class="row justify-content-center mb-4">
                                                        <div class="col-5 border-end">
                                                            <small class="text-muted text-uppercase d-block mb-1">Skor Akhir</small>
                                                            <h2 class="fw-bold text-primary mb-0"><?= number_format($c['spk_score'], 4) ?></h2>
                                                        </div>
                                                        <div class="col-5">
                                                            <small class="text-muted text-uppercase d-block mb-1">Status</small>
                                                            <?php if($isLolos): ?>
                                                                <span class="badge bg-success fs-6 mt-1">MEMENUHI</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger fs-6 mt-1">TIDAK MEMENUHI</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="bg-light p-3 rounded text-start mx-3">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span class="text-muted"><i class="mdi mdi-email me-1"></i> Email:</span>
                                                            <span class="fw-bold"><?= esc($c['email']) ?></span>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span class="text-muted"><i class="mdi mdi-phone me-1"></i> No HP:</span>
                                                            <span class="fw-bold"><?= esc($c['no_hp']) ?></span>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted"><i class="mdi mdi-target me-1"></i> Standar Lulus:</span>
                                                            <span class="fw-bold"><?= $standar ?></span>
                                                        </div>
                                                    </div>

                                                    <?php if (filter_var($c['link'], FILTER_VALIDATE_URL)): ?>
                                                        <div class="mt-4">
                                                            <a href="<?= $c['link'] ?>" target="_blank" class="btn btn-outline-primary btn-sm w-75 rounded-pill">
                                                                <i class="mdi mdi-file-document me-1"></i> Lihat Berkas Pelamar
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>

                                                  </div>
                                                  <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                                  </div>
                                                </div>
                                              </div>
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

    <?php endforeach; ?>
    </div>

  <?php endif; ?>

</div>

<script>
    function toggleAll(action) {
        var collapses = document.querySelectorAll('.collapse');
        collapses.forEach(function(el) {
            var bsCollapse = new bootstrap.Collapse(el, {toggle: false});
            if (action === 'show') {
                bsCollapse.show();
            } else {
                bsCollapse.hide();
            }
        });
    }
</script>

<?= $this->endSection() ?>