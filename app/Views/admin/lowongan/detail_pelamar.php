<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<?php 
    // Helper variables
    $isHistory = (isset($data['is_history']) && $data['is_history'] == 1);
    $jawaban   = json_decode($data['form_data'] ?? '[]', true);
    $hasJawaban = !empty($jawaban);
?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 text-primary fw-bold">
            <i class="bi bi-clipboard-check me-2"></i>
            <?= $isHistory ? 'Detail Hasil Penilaian' : 'Penilaian Pelamar' ?>
        </h4>
        <div class="text-muted small">
            Kategori Pekerjaan: <span class="badge bg-info text-dark ms-1"><?= esc($data['nama_pekerjaan']) ?></span>
        </div>
    </div>
    <a href="<?= base_url('admin/lowongan/detail/' . $data['id_lowongan']) ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php if($isHistory): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center">
        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
        <div>
            <strong>Proses Selesai.</strong> Data ini sudah masuk Arsip.
            <a href="<?= base_url('admin/data/detail/' . $data['id']) ?>" class="fw-bold text-success text-decoration-underline ms-1">Lihat di Menu Data</a>
        </div>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    
    <div class="col-lg-4">
      <div class="card shadow-sm border-0 mb-4 h-100">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <div class="text-center">
                <h5 class="card-title fw-bold mb-1"><?= esc($data['nama']) ?></h5>
                <span class="badge bg-light text-secondary border"><?= esc($data['judul_lowongan']) ?></span>
            </div>
        </div>
        <div class="card-body pt-4">
            <ul class="list-group list-group-flush small mb-4">
                <li class="list-group-item px-0 d-flex justify-content-between border-0 pb-1">
                    <span class="text-muted"><i class="bi bi-envelope me-2"></i>Email</span>
                    <span class="fw-medium text-truncate" style="max-width: 150px;"><?= esc($data['email']) ?></span>
                </li>
                <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 pt-1">
                    <span class="text-muted"><i class="bi bi-whatsapp me-2"></i>No HP / WhatsApp</span>
                    <span class="fw-medium">
                        <?php 
                            // Format Nomor HP untuk Link WA (Ganti 0 di depan jadi 62)
                            $hpRaw = $data['no_hp'];
                            // Hapus karakter selain angka (spasi, strip, dll)
                            $hpClean = preg_replace('/[^0-9]/', '', $hpRaw);
                            
                            // Jika diawali angka 0, ganti dengan 62
                            if(substr($hpClean, 0, 1) == '0'){
                                $hpClean = '62' . substr($hpClean, 1);
                            }
                        ?>
                        
                        <a href="https://wa.me/<?= $hpClean ?>" target="_blank" class="text-decoration-none text-success fw-bold" title="Chat via WhatsApp">
                            <?= esc($data['no_hp']) ?> <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 10px;"></i>
                        </a>
                    </span>
                </li>
            </ul>

            <div class="d-grid gap-2">
                <?php if (filter_var($data['link'], FILTER_VALIDATE_URL)): ?>
                    <a href="<?= $data['link'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                       <i class="bi bi-file-earmark-text me-2"></i>Lihat Berkas Lamaran
                    </a>
                <?php else: ?>
                    <button class="btn btn-light btn-sm text-muted" disabled>
                        <i class="bi bi-x-circle me-2"></i>Link Berkas Tidak Valid
                    </button>
                <?php endif; ?>

                <?php if ($hasJawaban): ?>
                    <button type="button" class="btn btn-primary btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalJawaban">
                        <i class="bi bi-chat-left-text-fill me-2"></i>Lihat Jawaban Kualifikasi
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
                    <div class="col-md-6 border-end border-white-50 text-center text-md-start">
                        <small class="text-white-50 text-uppercase ls-1">Skor Akhir (WP)</small>
                        <h1 class="display-4 fw-bold mb-0"><?= number_format($data['spk_score'], 4) ?></h1>
                        <small class="text-white-50">Standar Kelulusan: <?= $data['standar_spk'] ?></small>
                    </div>
                    <div class="col-md-6 text-center mt-3 mt-md-0">
                        <h5 class="mb-2">Rekomendasi Sistem:</h5>
                        <?php if($data['spk_score'] >= $data['standar_spk']): ?>
                            <span class="badge bg-white text-success fs-5 px-3 py-2 rounded-pill mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i>MEMENUHI
                            </span>
                        <?php else: ?>
                            <span class="badge bg-white text-danger fs-5 px-3 py-2 rounded-pill mb-3">
                                <i class="bi bi-x-circle-fill me-2"></i>TIDAK MEMENUHI
                            </span>
                        <?php endif; ?>

                        <div class="d-grid gap-2 px-4">
                            <?php if(!$isHistory): ?>
                                <?php if($data['spk_score'] >= $data['standar_spk']): ?>
                                    <a href="<?= base_url('admin/lowongan/selesai/'.$data['id'].'?status=memenuhi') ?>" 
                                       class="btn btn-success btn-sm border-white" 
                                       onclick="return confirm('Yakin nyatakan MEMENUHI?')">Terima & Selesai</a>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/lowongan/selesai/'.$data['id'].'?status=tidak memenuhi') ?>" 
                                       class="btn btn-danger btn-sm border-white" 
                                       onclick="return confirm('Yakin nyatakan GAGAL?')">Tolak & Selesai</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 10rem; transform: translate(20%, -20%);">
                <i class="bi bi-trophy"></i>
            </div>
        </div>
      <?php endif; ?>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Input Kriteria Penilaian</h6>
            <?php if (!empty($criteria) && !$isHistory): ?>
                <button type="button" class="btn btn-sm btn-light text-primary border" id="btnAddRow">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Kriteria
                </button>
            <?php endif; ?>
        </div>
        
        <div class="card-body p-0">
            <?php if (empty($criteria)): ?>
                <div class="text-center py-5">
                    <div class="mb-3 text-warning opacity-75"><i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i></div>
                    <h5 class="fw-bold">Kriteria Belum Diatur</h5>
                    <p class="text-muted small px-4">Silakan atur Master Data terlebih dahulu.</p>
                    <a href="<?= base_url('admin/criteria') ?>" class="btn btn-primary btn-sm mt-2">Atur Kriteria</a>
                </div>
            <?php else: ?>
                <form action="<?= base_url('admin/lowongan/hitung/' . $data['id']) ?>" method="post">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4" width="40%">Kriteria</th>
                                    <th class="text-center" width="15%">Bobot</th>
                                    <th>Penilaian (Subkriteria)</th>
                                    <th class="text-center" width="10%">#</th>
                                </tr>
                            </thead>
                            <tbody id="criteriaBody" class="border-top-0"></tbody>
                        </table>
                    </div>
                    <?php if(!$isHistory): ?>
                    <div class="card-footer bg-white p-3 text-end">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-calculator me-2"></i>Hitung Skor</button>
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
        <h5 class="modal-title fw-bold"><i class="bi bi-chat-left-text-fill me-2"></i>Jawaban Kualifikasi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light">
        <div class="d-flex flex-column gap-3">
            <?php foreach($jawaban as $tanya => $jawab): ?>
                <div class="p-3 bg-white rounded border shadow-sm">
                    <small class="d-block text-uppercase text-muted fw-bold mb-2" style="font-size: 0.75rem;">
                        <?= esc($tanya) ?>
                    </small>
                    <div class="text-dark fw-bold text-break" style="font-size: 0.95rem;">
                        <?php 
                            if(is_array($jawab)) {
                                echo implode(', ', $jawab);
                            } else {
                                echo esc($jawab);
                            }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer bg-white">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
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
        const hideRemoveBtn = isHistory ? 'd-none' : '';

        tr.innerHTML = `
            <td class="ps-4">
                <select name="criteria_id[]" class="form-select form-select-sm select-criteria border-secondary-subtle" required ${disabledAttr}>${criteriaOptions}</select>
            </td>
            <td class="text-center">
                <input type="text" class="form-control form-control-sm text-center input-bobot bg-light fw-bold" readonly value="-" style="border:none;">
            </td>
            <td>
                <select name="value[]" class="form-select form-select-sm select-sub" required ${disabledAttr}><option value="">(Pilih Kriteria Dahulu)</option></select>
            </td>
            <td class="text-center p-2">
                <button type="button" class="btn btn-danger btn-sm btn-remove" title="Hapus Baris">
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
    else if (isHistory) { tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4 small"><em>Penilaian dikunci.</em></td></tr>'; }
    if(btnAdd) { btnAdd.addEventListener('click', addRow); }
</script>
<?php endif; ?>

<?= $this->endSection() ?>