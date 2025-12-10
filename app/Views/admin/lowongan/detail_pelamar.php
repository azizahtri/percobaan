<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<?php 
    $isHistory = (isset($data['is_history']) && $data['is_history'] == 1);
    $jawaban   = json_decode($data['form_data'] ?? '[]', true);
    $hasJawaban = !empty($jawaban);
?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 text-dark fw-bold">
            <i class="mdi mdi-clipboard-check-outline me-2 text-primary"></i>
            <?= $isHistory ? 'Detail Hasil Penilaian' : 'Proses Penilaian Pelamar' ?>
        </h4>
        <div class="text-muted small">
            Lowongan: <span class="fw-bold text-dark"><?= esc($data['judul_lowongan']) ?></span> 
            <span class="mx-2">|</span> 
            Posisi: <span class="badge bg-info text-dark border border-info-subtle"><?= esc($data['nama_pekerjaan']) ?></span>
        </div>
    </div>
    <a href="<?= base_url('admin/lowongan/detail/' . $data['id_lowongan']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
        <i class="mdi mdi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php if($isHistory): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center bg-success-subtle text-success-emphasis">
        <i class="mdi mdi-check-circle fs-4 me-3"></i>
        <div>
            <strong>Proses Selesai.</strong> Data ini sudah masuk Arsip History.
            <a href="<?= base_url('admin/data/detail/' . $data['id']) ?>" class="fw-bold text-success text-decoration-underline ms-1">Lihat di Menu Data</a>
        </div>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white border-bottom pt-4 pb-3 text-center">
            <div class="avatar-lg bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold fs-3 mb-3" style="width: 70px; height: 70px;">
                <?= strtoupper(substr($data['nama'], 0, 1)) ?>
            </div>
            <h5 class="card-title fw-bold mb-1 text-dark"><?= esc($data['nama']) ?></h5>
            <small class="text-muted"><i class="mdi mdi-email-outline me-1"></i><?= esc($data['email']) ?></small>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush small mb-4">
                <li class="list-group-item px-0 d-flex justify-content-between py-2 border-0">
                    <span class="text-muted">No. HP</span>
                    <span class="fw-bold text-dark"><?= esc($data['no_hp']) ?></span>
                </li>
            </ul>
            <div class="d-grid gap-2">
                <?php if (filter_var($data['link'], FILTER_VALIDATE_URL)): ?>
                    <a href="<?= $data['link'] ?>" target="_blank" class="btn btn-outline-primary btn-sm fw-bold">Lihat CV</a>
                <?php endif; ?>
                <?php if ($hasJawaban): ?>
                    <button type="button" class="btn btn-primary btn-sm text-white fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalJawaban">
                        Lihat Jawaban
                    </button>
                <?php endif; ?>
            </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      
      <?php if($data['spk_score'] != null): ?>
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
                        <p class="text-white-50 mt-3 small mb-0">*Keputusan akhir ada di menu Data Pelamar.</p>
                    </div>
                </div>
            </div>
        </div>
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

<?php if ($hasJawaban): ?>
<div class="modal fade" id="modalJawaban" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">Jawaban Kualifikasi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light p-4">
        <?php foreach($jawaban as $tanya => $jawab): ?>
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

<?= $this->endSection() ?>