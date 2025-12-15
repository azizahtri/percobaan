<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
          <h4 class="mb-0 fw-bold text-dark">Detail Lowongan</h4>
          <p class="text-muted small mb-0">Informasi lengkap dan daftar pelamar yang masuk.</p>
      </div>
      <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
        <i class="mdi mdi-arrow-left me-1"></i> Kembali
      </a>
  </div>

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 fw-bold text-primary">
            <i class="mdi mdi-briefcase-outline me-2"></i><?= esc($lowongan['judul_lowongan']) ?>
        </h5>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
            <?= esc(strtoupper($lowongan['jenis'])) ?>
        </span>
      </div>

      <div class="row mb-4">
        <div class="col-md-6 py-3 border-end">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-light p-2 rounded-circle me-3 text-secondary">
                    <i class="mdi mdi-account-tie fs-4"></i>
                </div>
                <div>
                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Posisi / Jabatan</small>
                    <h6 class="fw-bold mb-0 text-dark"><?= esc($lowongan['nama_pekerjaan']) ?></h6>
                </div>
            </div>
        </div>
        <div class="col-md-6 py-3 ps-md-4">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-light p-2 rounded-circle me-3 text-secondary">
                    <i class="mdi mdi-calendar-clock fs-4"></i>
                </div>
                <div>
                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Tanggal Posting</small>
                    <h6 class="fw-bold mb-0 text-dark"><?= date('d F Y', strtotime($lowongan['tanggal_posting'])) ?></h6>
                </div>
            </div>
        </div>
        <div class="col-md-6 py-3 ps-md-4">
              <div class="d-flex align-items-center mb-2">
                  <div class="bg-light p-2 rounded-circle me-3 text-secondary">
                      <i class="mdi mdi-calendar-range fs-4"></i>
                  </div>
                  <div>
                      <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Periode Pendaftaran</small>
                      <h6 class="fw-bold mb-0 text-dark">
                        <?= date('d M', strtotime($lowongan['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($lowongan['tanggal_selesai'])) ?>
                        
                        <?php if($lowongan['status'] == 'open'): ?>
                            <span class="badge bg-success ms-2 small">OPEN</span>
                        <?php else: ?>
                            <span class="badge bg-danger ms-2 small">CLOSED</span>
                        <?php endif; ?>
                      </h6>
                  </div>
              </div>
          </div>
      </div>

      <div class="mb-3">
          <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Deskripsi Pekerjaan</small>
          <div class="border rounded p-3 bg-light position-relative mt-1">
              <div id="descContainer" style="max-height: 120px; overflow: hidden; transition: max-height 0.5s ease;">
                <?= $lowongan['deskripsi'] ?>
              </div>
              
              <div class="text-center mt-2 pt-2 border-top" id="toggleDescBtnContainer">
                  <button class="btn btn-sm btn-link text-decoration-none fw-bold" onclick="toggleDescription()">
                      <i class="mdi mdi-chevron-down"></i> Baca Selengkapnya
                  </button>
              </div>
          </div>
      </div>

      <?php if(!empty($lowongan['link_google_form'])): ?>
        <div class="mt-3">
            <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Link Google Form</small>
            <div class="d-flex align-items-center mt-1">
                <i class="mdi mdi-google-forms text-success fs-4 me-2"></i>
                <a href="<?= $lowongan['link_google_form'] ?>" target="_blank" class="text-primary text-decoration-none fw-bold text-break">
                    <?= esc($lowongan['link_google_form']) ?> <i class="mdi mdi-open-in-new ms-1"></i>
                </a>
            </div>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="mdi mdi-account-group-outline me-2 text-primary"></i>Daftar Pelamar Masuk
        </h5>
        <span class="badge bg-secondary text-white rounded-pill px-3">
            <?= count($pelamar) ?> Kandidat
        </span>
      </div>

      <?php if (!empty($pelamar)): ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle text-center datatable" style="width:100%">
              <thead class="bg-light text-secondary small text-uppercase fw-bold">
                  <tr>
                      <th class="text-center" width="5%">No</th>
                      <th class="text-center" width="15%">No KTP</th> 
                      <th class="text-start" width="25%">Nama Pelamar</th> 
                      <th class="text-center" width="15%">Waktu Daftar</th> 
                      <th class="text-center" width="10%">Skor SPK</th> 
                      <th class="text-center" width="15%">Status</th> 
                      <th class="text-center" width="15%">Aksi</th> 
                  </tr>
              </thead>

              <tbody>
                  <?php foreach ($pelamar as $i => $p): ?>
                  <tr class="<?= ($p['is_blacklisted'] == 1) ? 'table-danger' : '' ?>">
                      
                      <td><?= $i + 1 ?></td>
                      
                      <td class="fw-bold"><?= esc($p['no_ktp']) ?></td>
                      
                      <td class="text-start">
                          <div class="d-flex align-items-center">
                              <div class="avatar-sm rounded-circle me-2 d-flex align-items-center justify-content-center bg-white text-primary border fw-bold" style="width: 35px; height: 35px;">
                                  <?= strtoupper(substr($p['nama'], 0, 1)) ?>
                              </div>
                              <div>
                                  <span class="d-block fw-bold text-dark"><?= esc($p['nama']) ?></span>
                                  
                                  <?php if($p['is_blacklisted'] == 1): ?>
                                      <span class="badge bg-danger" style="font-size: 0.65rem;">
                                          <i class="mdi mdi-cancel"></i> BLACKLISTED
                                      </span>
                                  <?php else: ?>
                                      <a href="https://wa.me/<?= preg_replace('/^0/', '62', $p['no_hp']) ?>" target="_blank" class="text-decoration-none small text-success">
                                          <i class="mdi mdi-whatsapp"></i> <?= esc($p['no_hp']) ?>
                                      </a>
                                  <?php endif; ?>
                              </div>
                          </div>
                      </td>
                      
                      <td class="small text-muted">
                          <?= date('d M Y, H:i', strtotime($p['created_at'])) ?>
                      </td>
                      
                      <td>
                          <?php if($p['spk_score'] > 0): ?>
                              <span class="fw-bold text-primary"><?= number_format($p['spk_score'], 4) ?></span>
                          <?php else: ?>
                              <span class="text-muted">-</span>
                          <?php endif; ?>
                      </td>

                      <td>
                          <?php 
                              $badge = match($p['status']) {
                                  'memenuhi'       => 'bg-success',
                                  'tidak memenuhi' => 'bg-danger',
                                  'blacklist'      => 'bg-dark text-white',
                                  default          => 'bg-warning text-dark'
                              };
                          ?>
                          <span class="badge rounded-pill <?= $badge ?> px-3">
                              <?= ucfirst($p['status'] ?: 'Proses') ?>
                          </span>
                      </td>

                      <td>
                          <div class="d-flex justify-content-center gap-1">
                              
                              <a href="<?= base_url('admin/lowongan/pelamar/' . $p['id']) ?>" 
                                class="btn btn-primary btn-sm p-1 px-2" 
                                title="Proses Penilaian">
                                  <i class="mdi mdi-pencil-box-outline fs-6"></i>
                              </a>

                              <?php if($p['is_blacklisted'] == 0): ?>
                                  <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 btn-blacklist" 
                                          data-bs-toggle="modal" 
                                          data-bs-target="#modalBlacklist"
                                          data-id="<?= $p['pelamar_id'] ?>"
                                          data-nama="<?= esc($p['nama']) ?>"
                                          title="Blacklist Pelamar">
                                      <i class="mdi mdi-account-cancel fs-6"></i>
                                  </button>
                              <?php endif; ?>

                          </div>
                      </td>

                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
        </div>

      <?php else: ?>
        <div class="text-center py-5 text-muted">
           <div class="opacity-50 mb-3"><i class="mdi mdi-account-off" style="font-size: 4rem;"></i></div>
           <h6 class="fw-bold">Belum Ada Pelamar</h6>
           <p class="small">Belum ada kandidat yang mendaftar untuk posisi ini.</p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<div class="modal fade" id="modalBlacklist" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/lowongan/blacklist') ?>" method="post">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="mdi mdi-alert-circle me-2"></i>Blacklist Pelamar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pelamar" id="blacklistId">
                    
                    <div class="alert alert-warning d-flex align-items-center small p-2">
                        <i class="mdi mdi-alert fs-4 me-2"></i>
                        <div>Anda akan memblokir: <strong id="blacklistNama"></strong></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Sanksi <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check border p-2 px-3 rounded w-100 bg-light">
                                <input class="form-check-input" type="radio" name="tipe_blacklist" id="typeTemp" value="temporary" checked>
                                <label class="form-check-label w-100 fw-bold text-warning" for="typeTemp" style="cursor:pointer;">
                                    <i class="mdi mdi-timer-sand"></i> Sementara
                                    <div class="text-muted fw-normal small" style="font-size: 0.75rem;">Masih bisa dipulihkan kembali.</div>
                                </label>
                            </div>
                            <div class="form-check border p-2 px-3 rounded w-100 bg-danger-subtle border-danger">
                                <input class="form-check-input" type="radio" name="tipe_blacklist" id="typePerm" value="permanent">
                                <label class="form-check-label w-100 fw-bold text-danger" for="typePerm" style="cursor:pointer;">
                                    <i class="mdi mdi-gavel"></i> Permanen
                                    <div class="text-dark fw-normal small" style="font-size: 0.75rem;">FATAL. Tidak bisa dipulihkan.</div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alasan <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="3" required placeholder="Jelaskan alasan pelanggaran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold">Ya, Blacklist</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var blacklistBtns = document.querySelectorAll('.btn-blacklist');
        blacklistBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var nama = this.getAttribute('data-nama');
                document.getElementById('blacklistId').value = id;
                document.getElementById('blacklistNama').innerText = nama;
            });
        });
    });

    function toggleDescription() {
        var container = document.getElementById("descContainer");
        var btn = document.querySelector("#toggleDescBtnContainer button");
        
        if (container.style.maxHeight === "120px") {
            container.style.maxHeight = "none"; 
            btn.innerHTML = '<i class="mdi mdi-chevron-up"></i> Tutup Deskripsi';
        } else {
            container.style.maxHeight = "120px"; 
            btn.innerHTML = '<i class="mdi mdi-chevron-down"></i> Baca Selengkapnya';
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        var container = document.getElementById("descContainer");
        if (container && container.scrollHeight <= 120) {
            document.getElementById("toggleDescBtnContainer").style.display = 'none';
            container.style.maxHeight = "none"; 
            container.classList.remove('border', 'bg-light', 'p-3'); 
        }
    });
</script>

<?= $this->endSection() ?>