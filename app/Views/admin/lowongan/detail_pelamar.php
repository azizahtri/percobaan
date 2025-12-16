<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<?php 
    $isHistory  = (isset($data['is_history']) && $data['is_history'] == 1);
    
    // DATA 1: Jawaban Pelamar (Murni dari user)
    $jawabanPelamar = json_decode($data['form_data'] ?? '[]', true);
    $hasJawaban     = !empty($jawabanPelamar);

    // DATA 2: Rincian Penilaian Admin (Hasil hitung SPK)
    $riwayatSPK     = json_decode($data['spk_log'] ?? '[]', true);
    $hasRiwayat     = !empty($riwayatSPK);
?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 text-dark fw-bold">
            <i class="mdi mdi-clipboard-check-outline me-2 text-primary"></i>
            <?= $isHistory ? 'Detail Hasil Penilaian' : 'Proses Penilaian Pelamar' ?>
        </h4>
        <div class="text-muted small">
            Lowongan: <span class="fw-bold text-dark"><?= esc($data['judul_lowongan']) ?></span> | 
            Posisi: <span class="badge bg-info text-dark"><?= esc($data['nama_pekerjaan']) ?></span>
        </div>
    </div>
    <a href="<?= base_url('admin/lowongan/detail/' . $data['id_lowongan']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
        <i class="mdi mdi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php if($isHistory): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center bg-success-subtle">
        <i class="mdi mdi-check-circle fs-4 me-3"></i>
        <div><strong>Proses Selesai.</strong> Data ini sudah masuk Arsip History.</div>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white border-bottom pt-4 pb-3 text-center">
            <?php if (!empty($data['foto_profil'])): ?>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalFoto">
                    <img src="<?= base_url('uploads/berkas/' . $data['foto_profil']) ?>" 
                         class="avatar-lg rounded-circle mx-auto mb-3 shadow-sm border p-1 hover-scale" 
                         style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;"
                         title="Klik untuk memperbesar">
                </a>
            <?php else: ?>
                <div class="avatar-lg bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold fs-3 mb-3" style="width: 80px; height: 80px;">
                    <?= strtoupper(substr($data['nama_lengkap'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <h5 class="card-title fw-bold mb-1 text-dark"><?= esc($data['nama_lengkap']) ?></h5>
            <div class="text-muted small mb-1"><i class="mdi mdi-card-account-details-outline me-1"></i><?= esc($data['no_ktp']) ?></div>
            <small class="text-muted"><i class="mdi mdi-email-outline me-1"></i><?= esc($data['email']) ?></small>
            
            <?php if($data['is_blacklisted'] == 1): ?>
                    <div class="alert alert-danger border-0 shadow-sm p-2 mb-3 text-center">
                        <i class="mdi mdi-account-cancel fs-4 d-block mb-1"></i>
                        <strong>PELAMAR DIBLACKLIST</strong>
                        <div class="small mt-1 fst-italic">"<?= esc($data['alasan_blacklist']) ?>"</div>
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                    <?php if($data['is_blacklisted'] == 0): ?>
                        <hr class="my-2">
                        <button type="button" class="btn btn-outline-danger btn-sm fw-bold" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalBlacklist">
                            <i class="mdi mdi-account-cancel me-1"></i> Blacklist Pelamar
                        </button>
                    <?php endif; ?>
                </div>
        </div>
        
        <div class="card-body">
            
            <ul class="list-group list-group-flush small mb-4">
                <li class="list-group-item px-0 d-flex justify-content-between py-2 border-0">
                    <span class="text-muted">No. HP</span>
                    <span class="fw-bold text-dark">
                        <a href="https://wa.me/<?= preg_replace('/^0/', '62', $data['no_hp']) ?>" target="_blank" class="text-decoration-none text-dark">
                            <?= esc($data['no_hp']) ?> <i class="mdi mdi-whatsapp text-success"></i>
                        </a>
                    </span>
                </li>
            </ul>
            <div class="d-grid gap-2">
                <?php if (!empty($data['file_cv'])): ?>
                    <a href="<?= base_url('uploads/berkas/' . $data['file_cv']) ?>" target="_blank" class="btn btn-outline-primary btn-sm fw-bold">
                        <i class="mdi mdi-file-document-outline me-1"></i> Lihat File CV
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>CV Tidak Ada</button>
                <?php endif; ?>

                <?php if ($hasJawaban): ?>
                    <button type="button" class="btn btn-primary btn-sm text-white fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalJawaban">
                        <i class="mdi mdi-chat-question-outline me-1"></i> Lihat Jawaban Kualifikasi
                    </button>
                <?php endif; ?>
            </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      
      <?php if($data['spk_score'] != null && $data['spk_score'] > 0): ?>
        <div class="card shadow-sm border-0 mb-4 bg-primary text-white overflow-hidden position-relative">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-md-6 border-end border-white-50">
                        <small class="text-white-50 text-uppercase ls-1 fw-bold">Skor Akhir (SPK)</small>
                        <h1 class="display-4 fw-bold mb-0"><?= number_format($data['spk_score'], 4) ?></h1>
                        <div class="badge bg-white bg-opacity-25 text-white mt-1">Standar Lulus: <?= $data['standar_spk'] ?></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <h6 class="mb-2 text-white-50 small fw-bold">Rekomendasi Sistem</h6>
                        <?php if($data['spk_score'] >= $data['standar_spk']): ?>
                            <div class="bg-white text-success fs-5 px-4 py-2 rounded-pill fw-bold shadow-sm d-inline-block">MEMENUHI</div>
                        <?php else: ?>
                            <div class="bg-white text-danger fs-5 px-4 py-2 rounded-pill fw-bold shadow-sm d-inline-block">TIDAK MEMENUHI</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($hasRiwayat): ?>
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body py-3 px-4">
                <h6 class="fw-bold text-dark mb-2 small text-uppercase"><i class="mdi mdi-history me-1"></i>Rincian Penilaian Terakhir</h6>
                <div class="row">
                    <?php foreach($riwayatSPK as $kriteria => $nilai): ?>
                        <div class="col-md-6 mb-1 small">
                            <span class="text-muted"><?= esc($kriteria) ?>:</span> 
                            <span class="fw-bold text-dark"><?= esc($nilai) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

      <?php endif; ?>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="mb-0 fw-bold text-dark">Input Penilaian (Divisi: <?= esc($data['divisi']) ?>)</h6>
            <?php if (!empty($criteria) && !$isHistory): ?>
                <button type="button" class="btn btn-sm btn-light text-primary border fw-bold" id="btnAddRow">
                    <i class="mdi mdi-plus me-1"></i>Tambah Kriteria
                </button>
            <?php endif; ?>
        </div>
        
        <div class="card-body p-0">
            <?php if (empty($criteria)): ?>
                <div class="text-center py-5">
                    <h5 class="fw-bold text-dark">Kriteria Kosong</h5>
                    <p class="text-muted small">Belum ada kriteria untuk Divisi ini.</p>
                </div>
            <?php else: ?>
                <form action="<?= base_url('admin/lowongan/hitung/' . $data['id']) ?>" method="post">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light text-center small fw-bold text-uppercase">
                                <tr>
                                    <th style="width: 40%;">Kriteria</th>
                                    <th style="width: 15%;">Bobot</th>
                                    <th style="width: 35%;">Nilai (Subkriteria)</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="criteriaBody"></tbody>
                        </table>
                    </div>
                    <?php if(!$isHistory): ?>
                    <div class="card-footer bg-white p-3 text-end border-top">
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Perhitungan</button>
                    </div>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- modal jawaban -->
<?php if ($hasJawaban): ?>
<div class="modal fade" id="modalJawaban" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">Jawaban Kualifikasi Pelamar</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light p-4">
        <?php foreach($jawabanPelamar as $tanya => $jawab): ?>
            <div class="p-3 bg-white rounded border shadow-sm mb-3">
                <small class="d-block text-muted fw-bold mb-1"><?= esc($tanya) ?></small>
                <div class="text-dark fw-bold"><?= is_array($jawab) ? implode(', ', $jawab) : esc($jawab) ?></div>
            </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- js tabel kriteria -->
<?php if (!empty($criteria)): ?>
<script>
    const masterCriteria = <?= json_encode($criteria) ?>;
    const masterSubcriteria = <?= json_encode($subcriteria) ?>;
    const tbody = document.getElementById('criteriaBody');
    const btnAdd = document.getElementById('btnAddRow');
    const isHistory = <?= $isHistory ? 'true' : 'false' ?>;

    function addRow() {
        const tr = document.createElement('tr');
        let criteriaOptions = '<option value="">-- Pilih Kriteria --</option>';
        masterCriteria.forEach(c => {
            criteriaOptions += `<option value="${c.id}" data-bobot="${c.bobot}">${c.nama} (${c.tipe})</option>`;
        });
        const disabledAttr = isHistory ? 'disabled' : '';

        tr.innerHTML = `
            <td class="p-2">
                <select name="criteria_id[]" class="form-select select-criteria" required ${disabledAttr}>${criteriaOptions}</select>
            </td>
            <td class="text-center p-2">
                <input type="text" class="form-control text-center input-bobot bg-light fw-bold" readonly value="-" style="border:none;">
            </td>
            <td class="p-2">
                <select name="value[]" class="form-select select-sub" required ${disabledAttr}><option value="">(Pilih Kriteria Dahulu)</option></select>
            </td>
            <td class="text-center p-2">
                <button type="button" class="btn btn-danger btn-sm btn-remove" title="Hapus Baris" ${isHistory ? 'disabled' : ''}>
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);

        const selectCriteria = tr.querySelector('.select-criteria');
        const inputBobot = tr.querySelector('.input-bobot');
        const selectSub = tr.querySelector('.select-sub');
        const btnRemove = tr.querySelector('.btn-remove');

        selectCriteria.addEventListener('change', function() {
            const criteriaId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const bobot = selectedOption.getAttribute('data-bobot');
            inputBobot.value = bobot ? bobot : '-';
            selectSub.innerHTML = '<option value="">-- Pilih Nilai --</option>';
            if(criteriaId && masterSubcriteria[criteriaId]) {
                masterSubcriteria[criteriaId].forEach(sub => {
                    selectSub.innerHTML += `<option value="${sub.bobot_sub}">${sub.keterangan} (Nilai: ${sub.bobot_sub})</option>`;
                });
            }
        });
        if(!isHistory) btnRemove.addEventListener('click', function() { tr.remove(); });
    }

    if(masterCriteria.length > 0 && !isHistory) { addRow(); }
    if(btnAdd) { btnAdd.addEventListener('click', addRow); }
</script>
<?php endif; ?>

<!-- modal blacklist -->
<div class="modal fade" id="modalBlacklist" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/lowongan/blacklist') ?>" method="post">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="mdi mdi-account-cancel me-2"></i>Blacklist Pelamar
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <input type="hidden" name="id_pelamar" value="<?= $data['pelamar_id'] ?>">
                    
                    <div class="alert alert-warning d-flex align-items-center small p-2 mb-3">
                        <i class="mdi mdi-alert fs-4 me-2"></i>
                        <div>Yakin ingin memblokir: <strong><?= esc($data['nama_lengkap']) ?></strong>?</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Sanksi <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check border p-2 px-3 rounded w-100 bg-light">
                                <input class="form-check-input" type="radio" name="tipe_blacklist" id="typeTempDetail" value="temporary" checked>
                                <label class="form-check-label w-100 fw-bold text-warning" for="typeTempDetail" style="cursor:pointer;">
                                    <i class="mdi mdi-timer-sand"></i> Sementara
                                    <div class="text-muted fw-normal small" style="font-size: 0.75rem;">Masih bisa dipulihkan.</div>
                                </label>
                            </div>
                            
                            <div class="form-check border p-2 px-3 rounded w-100 bg-danger-subtle border-danger">
                                <input class="form-check-input" type="radio" name="tipe_blacklist" id="typePermDetail" value="permanent">
                                <label class="form-check-label w-100 fw-bold text-danger" for="typePermDetail" style="cursor:pointer;">
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

<!-- modal foto -->
<?php if (!empty($data['foto_profil'])): ?>
<div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-black position-absolute top-0 end-0 m-3 p-2 bg-white rounded-circle opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <img src="<?= base_url('uploads/berkas/' . $data['foto_profil']) ?>" class="img-fluid rounded shadow-lg" style="max-height: 85vh;">
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>