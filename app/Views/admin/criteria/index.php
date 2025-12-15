<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
    <div class="fs-4 me-3 mt-1"><i class="mdi mdi-information-outline"></i></div>
    <div>
        <h5 class="alert-heading fw-bold mb-1">Panduan Kelola Kriteria</h5>
        <p class="mb-2 small">Gunakan filter di bawah untuk melihat kriteria per Divisi.</p>
        <ul class="small mb-0 ps-3">
            <li><strong>Filter Divisi:</strong> Pilih divisi untuk melihat kriteria yang berlaku.</li>
            <li><strong>Tambah Kriteria:</strong> Kriteria ditambahkan per Divisi (berlaku untuk semua jabatan di dalamnya).</li>
            <li><strong>Atur Standar:</strong> Klik tombol "Atur Standar" untuk menentukan nilai kelulusan per jabatan spesifik.</li>
        </ul>
    </div>
  </div>

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="row align-items-center g-3">
            <div class="col-md-6 d-flex align-items-center gap-3">
                <label class="fw-bold text-nowrap text-muted small text-uppercase">Filter Divisi:</label>
                <select id="selectDivisi" class="form-select border-primary-subtle fw-bold text-dark" 
                        onchange="window.location.href='?field='+this.value">
                    <option value="all">-- Tampilkan Semua --</option>
                    <?php foreach ($divisiList as $d): ?>
                        <option value="<?= esc($d['divisi']) ?>" <?= ($selectedField == $d['divisi']) ? 'selected' : '' ?>>
                            <?= esc($d['divisi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 text-md-end">
                <?php if ($selectedField != 'all'): ?>
                    <a href="<?= base_url('admin/criteria/standar?divisi=' . urlencode($selectedField)) ?>" 
                       class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold">
                        <i class="mdi mdi-calculator me-1"></i> Atur Standar Nilai
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
          <h6 class="mb-0 fw-bold text-dark">
              <i class="mdi mdi-format-list-checks me-2 text-primary"></i>Daftar Kriteria
          </h6>

          <div>
              <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold me-2" data-bs-toggle="modal" data-bs-target="#modalDuplicate">
                  <i class="mdi mdi-content-copy me-1"></i> Duplicate Kriteria
              </button>

              <?php if($selectedField != 'all'): ?>
                  <a href="<?= base_url('admin/criteria/create?field=' . urlencode($selectedField)) ?>" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                      <i class="mdi mdi-plus-circle me-1"></i> Tambah Kriteria
                  </a>
              <?php else: ?>
                  <button class="btn btn-secondary btn-sm rounded-pill px-3 fw-bold" disabled title="Pilih divisi terlebih dahulu">
                      <i class="mdi mdi-plus-circle me-1"></i> Tambah Kriteria
                  </button>
              <?php endif; ?>
          </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center datatable" style="width:100%">
          <thead class="bg-light text-secondary small text-uppercase">
            <tr>
              <th class="text-center">No</th>
              <th class="text-center">Kode</th>
              <th class="text-start">Nama Kriteria</th>
              <th class="text-center">Bobot (Maks)</th>
              <th class="text-center">Tipe</th>
              <th class="text-center">Divisi</th> 
              <th class="text-center">Subkriteria</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($criteria)): ?>
                <?php foreach ($criteria as $key => $c): ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><span class="badge bg-light text-dark border"><?= esc($c['kode']) ?></span></td>
                  <td class="text-start fw-bold text-dark"><?= esc($c['nama']) ?></td>
                  <td><span class="fw-bold"><?= esc($c['bobot']) ?></span></td>
                  <td>
                    <span class="badge rounded-pill <?= $c['tipe']=='Benefit'?'bg-success-subtle text-success':'bg-danger-subtle text-danger' ?>">
                        <?= ucfirst($c['tipe']) ?>
                    </span>
                  </td>
                  
                  <td>
                    <?php 
                        $divisiName = '-';
                        foreach($pekerjaanFull as $p) {
                            if($p['id'] == $c['pekerjaan_id']) { 
                                $divisiName = $p['divisi']; 
                                break; 
                            }
                        }
                    ?>
                    <span class="badge bg-info text-dark border border-info-subtle">
                        <?= esc($divisiName) ?>
                    </span>
                  </td>

                  <td>
                    <a href="<?= base_url('admin/subcriteria/' . $c['id']) ?>" class="btn btn-outline-info btn-sm py-1 rounded-pill" style="font-size: 0.75rem;">
                        <i class="mdi mdi-format-list-bulleted me-1"></i> Detail Sub
                    </a>
                  </td>
                  
                  <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="<?= base_url('admin/criteria/edit/' . $c['id']) ?>" 
                           class="btn btn-action btn-action-edit" 
                           title="Edit Data"
                           data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        
                        <button type="button"
                            class="btn btn-action btn-action-delete"
                            data-bs-toggle="modal"
                            data-bs-target="#modalHapus<?= $c['id'] ?>"
                            title="Hapus Kriteria">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
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

<div class="modal fade" id="modalDuplicate" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="mdi mdi-content-copy me-2"></i>Duplicate Kriteria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="<?= base_url('admin/criteria/duplicate') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="alert alert-warning small border-0 bg-warning-subtle text-warning-emphasis mb-4">
                        <i class="mdi mdi-alert-circle me-1"></i> 
                        Fitur ini akan menyalin seluruh Kriteria & Subkriteria dari Divisi Asal ke Divisi Tujuan.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Dari Divisi (Sumber)</label>
                        <select name="source_divisi" class="form-select border-primary" required>
                            <option value="">-- Pilih Divisi Asal --</option>
                            <?php foreach ($divisiList as $d): ?>
                                <option value="<?= esc($d['divisi']) ?>"><?= esc($d['divisi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="text-center my-2 text-muted">
                        <i class="mdi mdi-arrow-down-bold fs-4"></i>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Ke Divisi (Tujuan)</label>
                        <select name="target_divisi" class="form-select border-success" required>
                            <option value="">-- Pilih Divisi Tujuan --</option>
                            <?php foreach ($divisiList as $d): ?>
                                <option value="<?= esc($d['divisi']) ?>"><?= esc($d['divisi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="mdi mdi-check-all me-1"></i> Proses Copy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($criteria as $c): ?>
<div class="modal fade" id="modalHapus<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title fw-bold">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> Hapus Kriteria?
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-4 text-danger opacity-25">
                    <i class="mdi mdi-trash-can-outline" style="font-size: 60px;"></i>
                </div>
                <p class="mb-2 text-muted">Apakah Anda yakin ingin menghapus kriteria:</p>
                <h5 class="fw-bold text-dark mb-4 px-3 mx-auto"
                    style="max-width: 100%; word-wrap: break-word; overflow-wrap: break-word;">
                    <?= esc($c['nama']) ?> (<?= esc($c['kode']) ?>)
                </h5>
                <div class="alert alert-warning small text-start">
                    <i class="mdi mdi-alert me-2"></i>
                    <strong>Perhatian:</strong> Semua subkriteria terkait juga akan dihapus secara permanen.
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <a href="<?= base_url('admin/criteria/delete/' . $c['id']) ?>"
                    class="btn btn-danger rounded-pill px-4 shadow-sm">
                    Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?= $this->endSection() ?>