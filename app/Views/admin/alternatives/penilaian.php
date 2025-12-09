<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold text-dark"><i class="mdi mdi-star-circle me-2 text-warning"></i>Evaluasi Kinerja</h4>
    <a href="<?= base_url('admin/alternatives') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="mdi mdi-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center pt-4">
                <div class="avatar-circle bg-primary text-white mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 32px;">
                    <?= substr($karyawan['nama'], 0, 1) ?>
                </div>
                <h5 class="fw-bold mb-1"><?= esc($karyawan['nama']) ?></h5>
                <p class="text-muted small mb-2"><?= esc($karyawan['kode']) ?></p>
                <span class="badge bg-info text-dark"><?= esc($karyawan['posisi']) ?></span>
                
                <hr>
                <div class="text-start small">
                    <p class="mb-1 text-muted">Skor Saat Ini:</p>
                    <h3 class="fw-bold text-primary">
                        <?= !empty($karyawan['skor_akhir']) ? number_format($karyawan['skor_akhir'], 4) : '0.0000' ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h6 class="mb-0 fw-bold">Formulir Penilaian</h6>
                
                <button type="button" class="btn btn-sm btn-light text-primary border fw-bold" id="btnAddRow">
                    <i class="mdi mdi-plus-circle me-1"></i> Tambah Kriteria
                </button>
            </div>
            
            <div class="card-body p-0">
                <?php if (empty($criteria)): ?>
                    <div class="text-center py-5">
                        <div class="mb-3 text-warning opacity-75">
                            <i class="mdi mdi-alert-circle" style="font-size: 3rem;"></i>
                        </div>
                        <p class="px-4 text-muted">Kriteria penilaian belum diatur untuk posisi <strong><?= esc($karyawan['posisi']) ?></strong>.</p>
                    </div>
                <?php else: ?>

                    <form action="<?= base_url('admin/alternatives/hitung/' . $karyawan['id']) ?>" method="post">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="ps-4" width="40%">Kriteria</th>
                                        <th class="text-center" width="15%">Bobot</th>
                                        <th>Penilaian</th>
                                        <th class="text-center" width="10%">#</th>
                                    </tr>
                                </thead>
                                <tbody id="criteriaBody" class="border-top-0">
                                    </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-white p-3 text-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="mdi mdi-calculator me-2"></i> Simpan & Hitung Evaluasi
                            </button>
                        </div>
                    </form>

                <?php endif; ?>
            </div>
        </div>
    </div>
  </div>
</div>

<?php if (!empty($criteria)): ?>
<script>
    // Siapkan Data dari PHP ke JS
    const masterCriteria = <?= json_encode($criteria) ?>;
    const masterSubcriteria = <?= json_encode($subcriteria) ?>;
    const savedValues = <?= json_encode($savedValues ?? []) ?>; // Data nilai lama (JSON)

    const tbody = document.getElementById('criteriaBody');
    const btnAdd = document.getElementById('btnAddRow');

    // Fungsi Tambah Baris
    function addRow(selectedCriteriaId = null, selectedValue = null) {
        const tr = document.createElement('tr');
        
        // Buat Opsi Dropdown Kriteria
        let criteriaOptions = '<option value="">-- Pilih Kriteria --</option>';
        masterCriteria.forEach(c => {
            const isSelected = (c.id == selectedCriteriaId) ? 'selected' : '';
            criteriaOptions += `<option value="${c.id}" data-bobot="${c.bobot}" ${isSelected}>${c.nama} (${c.tipe})</option>`;
        });

        tr.innerHTML = `
            <td class="ps-4">
                <select name="criteria_id[]" class="form-select form-select-sm select-criteria border-secondary-subtle" required>
                    ${criteriaOptions}
                </select>
            </td>
            <td class="text-center">
                <input type="text" class="form-control form-control-sm text-center input-bobot bg-light fw-bold" readonly value="-" style="border:none;">
            </td>
            <td>
                <select name="value[]" class="form-select form-select-sm select-sub" required>
                    <option value="">(Pilih Kriteria Dahulu)</option>
                </select>
            </td>
            <td class="text-center pe-3">
                <button type="button" class="btn btn-danger btn-sm btn-remove" title="Hapus Baris">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);

        // Definisi Elemen
        const selectCriteria = tr.querySelector('.select-criteria');
        const inputBobot     = tr.querySelector('.input-bobot');
        const selectSub      = tr.querySelector('.select-sub');
        const btnRemove      = tr.querySelector('.btn-remove');

        // Logic saat Kriteria Berubah
        function updateSubcriteria() {
            const criteriaId = selectCriteria.value;
            const selectedOption = selectCriteria.options[selectCriteria.selectedIndex];
            const bobot = selectedOption.getAttribute('data-bobot');
            
            inputBobot.value = bobot ? bobot : '-';
            selectSub.innerHTML = '<option value="">-- Pilih Nilai --</option>';
            
            if(criteriaId && masterSubcriteria[criteriaId]) {
                masterSubcriteria[criteriaId].forEach(sub => {
                    // Cek jika ini mode edit (nilai lama dipilih otomatis)
                    const isSubSelected = (selectedValue && sub.bobot_sub == selectedValue) ? 'selected' : '';
                    selectSub.innerHTML += `<option value="${sub.bobot_sub}" ${isSubSelected}>${sub.keterangan} (Nilai: ${sub.bobot_sub})</option>`;
                });
            } else {
                 selectSub.innerHTML = '<option value="">Tidak ada subkriteria</option>';
            }
        }

        // Event Listener
        selectCriteria.addEventListener('change', function() {
            selectedValue = null; // Reset value jika user ganti kriteria manual
            updateSubcriteria();
        });

        btnRemove.addEventListener('click', function() { tr.remove(); });

        // Trigger update pertama kali (jika mode edit/load data)
        if(selectedCriteriaId) {
            updateSubcriteria();
        }
    }

    // --- INISIALISASI ---
    // 1. Jika ada data tersimpan (Mode Edit), load barisnya
    if (Object.keys(savedValues).length > 0) {
        for (const [cID, val] of Object.entries(savedValues)) {
            addRow(cID, val);
        }
    } 
    // 2. Jika data kosong (Baru), tampilkan semua kriteria master otomatis (biar user ga capek klik tambah)
    else {
        masterCriteria.forEach(c => {
            addRow(c.id, null);
        });
    }

    // Event Tombol Tambah Manual
    if(btnAdd) { 
        btnAdd.addEventListener('click', () => addRow()); 
    }

</script>
<?php endif; ?>

<?= $this->endSection() ?>