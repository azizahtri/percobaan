<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold"><i class="mdi mdi-file-document-edit me-2"></i>Penyesuaian Kriteria</h4>
                <p class="mb-0 text-white-50">Divisi: <strong><?= esc($divisiName) ?></strong></p>
            </div>
            <div>
                <button type="button" class="btn btn-light text-primary fw-bold me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddSingle">
                    <i class="mdi mdi-plus-circle me-1"></i> Tambah Kriteria
                </button>
                
                <button type="submit" form="formUpdateDivisi" class="btn btn-outline-light fw-bold">
                    <i class="mdi mdi-check-all me-1"></i> Selesai & Simpan
                </button>
            </div>
        </div>
    </div>

    <form action="<?= base_url('admin/criteria/updateDivisi') ?>" method="post" id="formUpdateDivisi">
        <input type="hidden" name="divisi_name" value="<?= esc($divisiName) ?>">

        <div class="row">
            <div class="col-12">
                
                <?php if(empty($criteria)): ?>
                    <div class="alert alert-warning text-center py-5">
                        <i class="mdi mdi-alert-circle-outline fs-1 d-block mb-3"></i>
                        <h5>Data kriteria kosong</h5>
                        <p class="text-muted">Silakan tambah kriteria baru atau lakukan duplikasi ulang.</p>
                        <button type="button" class="btn btn-primary rounded-pill px-4 mt-2" data-bs-toggle="modal" data-bs-target="#modalAddSingle">
                            Tambah Kriteria Sekarang
                        </button>
                    </div>
                <?php else: ?>
                    
                    <div class="accordion shadow-sm" id="accordionCriteria">
                        <?php foreach($criteria as $index => $c): ?>
                            <div class="accordion-item border-0 mb-3 rounded overflow-hidden shadow-sm">
                                
                                <h2 class="accordion-header position-relative" id="heading<?= $c['id'] ?>">
                                    <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?> fw-bold pe-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $c['id'] ?>">
                                        <span class="badge bg-primary me-2"><?= esc($c['kode']) ?></span> <?= esc($c['nama']) ?>
                                    </button>
                                    <a href="<?= base_url('admin/criteria/deleteSingle/' . $c['id'] . '?divisi=' . urlencode($divisiName)) ?>" 
                                       class="btn btn-danger btn-sm position-absolute top-50 end-0 translate-middle-y me-5 rounded-circle shadow-sm"
                                       style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; z-index: 5;"
                                       onclick="return confirm('Hapus kriteria ini?')" title="Hapus Kriteria">
                                        <i class="mdi mdi-trash-can"></i>
                                    </a>
                                </h2>

                                <div id="collapse<?= $c['id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#accordionCriteria">
                                    <div class="accordion-body bg-light">
                                        
                                        <div class="row g-3 mb-4 border-bottom pb-4">
                                            <div class="col-md-2">
                                                <label class="small fw-bold text-muted">Kode</label>
                                                <input type="text" name="criteria[<?= $c['id'] ?>][kode]" class="form-control" value="<?= esc($c['kode']) ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small fw-bold text-muted">Nama Kriteria</label>
                                                <input type="text" name="criteria[<?= $c['id'] ?>][nama]" class="form-control" value="<?= esc($c['nama']) ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small fw-bold text-muted">Bobot Kriteria</label>
                                                <input type="number" step="0.01" name="criteria[<?= $c['id'] ?>][bobot]" class="form-control" value="<?= esc($c['bobot']) ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small fw-bold text-muted">Tipe</label>
                                                <select name="criteria[<?= $c['id'] ?>][tipe]" class="form-select">
                                                    <option value="Benefit" <?= $c['tipe'] == 'Benefit' ? 'selected' : '' ?>>Benefit</option>
                                                    <option value="Cost" <?= $c['tipe'] == 'Cost' ? 'selected' : '' ?>>Cost</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold text-dark mb-0">Subkriteria</h6>
                                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill" onclick="addSubRow(<?= $c['id'] ?>)">
                                                <i class="mdi mdi-plus me-1"></i> Tambah Sub
                                            </button>
                                        </div>

                                        <div class="table-responsive bg-white rounded border p-0 overflow-hidden">
                                            <table class="table table-borderless table-hover mb-0 align-middle">
                                                <thead class="bg-light text-secondary small text-uppercase">
                                                    <tr>
                                                        <th class="ps-4" width="60%">Keterangan Subkriteria</th>
                                                        <th class="text-center" width="25%">Nilai Skor (Bobot Sub)</th>
                                                        <th class="text-center" width="15%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbodySub<?= $c['id'] ?>">
                                                    
                                                    <?php if(!empty($subs[$c['id']])): ?>
                                                        <?php foreach($subs[$c['id']] as $s): ?>
                                                            <tr>
                                                                <td class="ps-4">
                                                                    <input type="text" name="subs[<?= $s['id'] ?>][keterangan]" class="form-control form-control-sm" value="<?= esc($s['keterangan']) ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="0.01" name="subs[<?= $s['id'] ?>][bobot_sub]" class="form-control form-control-sm text-center fw-bold" value="<?= esc($s['bobot_sub']) ?>">
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="<?= base_url('admin/criteria/deleteSub/' . $s['id']) ?>" 
                                                                       class="btn btn-sm text-danger"
                                                                       onclick="return confirm('Hapus subkriteria ini?')">
                                                                        <i class="mdi mdi-close"></i>
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
                        <?php endforeach; ?>
                    </div>

                    <div class="d-grid mt-4 mb-5">
                        <button type="submit" class="btn btn-success py-3 fw-bold shadow-sm rounded-pill">
                            <i class="mdi mdi-content-save-all me-2 fs-5"></i> Simpan Semua Perubahan
                        </button>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </form>

</div>

<div class="modal fade" id="modalAddSingle" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="mdi mdi-plus-circle me-2"></i>Tambah Kriteria Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/criteria/addSingle') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="divisi_name" value="<?= esc($divisiName) ?>">
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Kode Kriteria</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: C-05" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Kriteria</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Keahlian Framework" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Bobot</label>
                            <input type="number" step="0.01" name="bobot" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Tipe</label>
                            <select name="tipe" class="form-select" required>
                                <option value="Benefit">Benefit</option>
                                <option value="Cost">Cost</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addSubRow(criteriaId) {
        const tbody = document.getElementById('tbodySub' + criteriaId);
        const tr = document.createElement('tr');
        
        // Gunakan timestamp agar index array unik dan tidak bentrok
        const uniqueIndex = Date.now(); 

        tr.classList.add('bg-primary-subtle');

        tr.innerHTML = `
            <td class="ps-4">
                <input type="text" name="new_subs[${criteriaId}][${uniqueIndex}][keterangan]" class="form-control form-control-sm border-primary" placeholder="Subkriteria baru..." required>
            </td>
            <td>
                <input type="number" step="0.01" name="new_subs[${criteriaId}][${uniqueIndex}][bobot_sub]" class="form-control form-control-sm text-center fw-bold border-primary" placeholder="0" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm text-danger" onclick="this.closest('tr').remove()">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(tr);
    }
</script>

<?= $this->endSection() ?>