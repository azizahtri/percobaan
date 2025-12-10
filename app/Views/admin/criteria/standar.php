<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold"><i class="bi bi-sliders me-2"></i>Atur Standar Kelulusan</h4>
                <p class="mb-0 text-white-50">Tentukan nilai minimal (Passing Grade) untuk posisi ini.</p>
            </div>
            <a href="<?= base_url('admin/criteria') ?>" class="btn btn-light text-primary fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3 h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-person-workspace me-1"></i> Target Posisi
                </div>
                <div class="card-body">
                    
                    <form method="get" action="">
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Posisi:</label>
                            
                            <select name="pekerjaan" class="form-select border-primary text-dark fw-bold" onchange="this.form.submit()">
                                <?php if (!$selected): ?>
                                    <option value="">-- Pilih Posisi --</option>
                                <?php endif; ?>
                                
                                <?php if(!empty($pekerjaanList)): ?>
                                    <optgroup label="<?= esc($pekerjaanList[0]['divisi']) ?>">
                                        <?php foreach($pekerjaanList as $p): ?>
                                            <option value="<?= $p['id'] ?>" <?= ($selected && $selected['id'] == $p['id']) ? 'selected' : '' ?>>
                                                <?= esc($p['posisi']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>

                            </select>
                            
                            <div class="mt-2 text-end">
                                <a href="<?= base_url('admin/criteria/standar') ?>" class="text-decoration-none small text-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Lihat Semua Divisi
                                </a>
                            </div>
                        </div>
                    </form>

                    <?php if($selected): ?>
                        <div class="alert alert-light border text-center">
                            <h6 class="text-muted small text-uppercase mb-2">Nilai Standar Saat Ini</h6>
                            <h1 class="text-success fw-bold display-4 mb-0">
                                <?= number_format($selected['standar_spk'], 4) ?>
                            </h1>
                            <small class="text-muted d-block mt-2">
                                Posisi: <strong><?= esc($selected['posisi']) ?></strong>
                            </small>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalListKriteria">
                                <i class="bi bi-list-check me-1"></i> Cek Kriteria (<?= esc($selected['divisi']) ?>)
                            </button>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-calculator me-1"></i> Simulasi Perhitungan</span>
                    
                    <?php if (!empty($criteria)): ?>
                        <button type="button" class="btn btn-sm btn-primary" id="btnAddRow">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Kriteria
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    
                    <?php if (empty($criteria)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Kriteria Kosong</h5>
                            <p class="text-muted small">Belum ada kriteria untuk posisi ini.</p>
                        </div>
                    <?php else: ?>

                        <form action="<?= base_url('admin/criteria/savestandar') ?>" method="post">
                            <input type="hidden" name="pekerjaan_id" value="<?= $selected['id'] ?>">

                            <div class="table-responsive mb-3">
                                <table class="table table-bordered align-middle">
                                    <thead class="bg-light text-center small fw-bold text-uppercase">
                                        <tr>
                                            <th style="width: 40%;">Kriteria</th>
                                            <th style="width: 15%;">Bobot</th>
                                            <th style="width: 35%;">Standar Minimal</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="criteriaBody">
                                        </tbody>
                                </table>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-save me-1"></i> Hitung & Simpan
                                </button>
                            </div>
                        </form>

                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalListKriteria" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold">Daftar Kriteria: <?= esc($selected['posisi']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <?php if(empty($criteria)): ?>
            <div class="p-4 text-center text-muted">Belum ada kriteria.</div>
        <?php else: ?>
            <ul class="list-group list-group-flush">
                <?php foreach($criteria as $c): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= esc($c['nama']) ?></strong>
                            <br><small class="text-muted"><?= $c['tipe'] ?></small>
                        </div>
                        <span class="badge bg-primary rounded-pill">Maksimal Bobot: <?= $c['bobot'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($criteria)): ?>
<script>
    // Data dari PHP
    const masterCriteria = <?= json_encode($criteria) ?>;
    const masterSubcriteria = <?= json_encode($sub) ?>; 

    const tbody = document.getElementById('criteriaBody');
    const btnAdd = document.getElementById('btnAddRow');

    function addRow() {
        const tr = document.createElement('tr');
        
        // Opsi Kriteria (Sekarang menampilkan kriteria milik Divisi)
        let criteriaOptions = '<option value="">-- Pilih Kriteria --</option>';
        masterCriteria.forEach(c => {
            criteriaOptions += `<option value="${c.id}" data-bobot="${c.bobot}">${c.nama}</option>`;
        });

        tr.innerHTML = `
            <td class="p-2">
                <select class="form-select select-criteria" required>
                    ${criteriaOptions}
                </select>
            </td>
            <td class="text-center p-2">
                <input type="text" class="form-control text-center input-bobot bg-light fw-bold" readonly value="-" style="border:none;">
            </td>
            <td class="p-2">
                <select class="form-select select-sub" required>
                    <option value="">(Pilih Kriteria Dahulu)</option>
                </select>
            </td>
            <td class="text-center p-2">
                <button type="button" class="btn btn-danger btn-sm btn-icon-text btn-remove" title="Hapus Baris">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);

        // Elements
        const selectCriteria = tr.querySelector('.select-criteria');
        const inputBobot     = tr.querySelector('.input-bobot');
        const selectSub      = tr.querySelector('.select-sub');
        const btnRemove      = tr.querySelector('.btn-remove');

        selectCriteria.addEventListener('change', function() {
            const criteriaId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const bobot = selectedOption.getAttribute('data-bobot');
            
            inputBobot.value = bobot ? bobot : '-';
            selectSub.innerHTML = '<option value="">-- Pilih Nilai Minimal --</option>';
            
            // PERBAIKAN UTAMA: gunakan .name bukan .nama
            if(criteriaId) {
                selectSub.name = "sub_" + criteriaId; 
            }

            if(criteriaId && masterSubcriteria[criteriaId]) {
                masterSubcriteria[criteriaId].forEach(sub => {
                    selectSub.innerHTML += `<option value="${sub.id}">${sub.keterangan} (Nilai: ${sub.bobot_sub})</option>`;
                });
            } else {
                 selectSub.innerHTML = '<option value="">Tidak ada subkriteria</option>';
            }
        });

        btnRemove.addEventListener('click', function() { tr.remove(); });
    }

    // Auto tambah 1 baris
    if(masterCriteria.length > 0) { addRow(); }
    
    // Listener
    if(btnAdd) { btnAdd.addEventListener('click', addRow); }
</script>
<?php endif; ?>

<?= $this->endSection() ?>