<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
    <div class="fs-4 me-3 mt-1"><i class="bi bi-info-circle-fill"></i></div>
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
                       class="btn btn-primary btn-sm px-3 shadow-sm">
                        <i class="bi bi-calculator me-1"></i> Atur Standar Nilai
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="bi bi-list-check me-2 text-primary"></i>Daftar Kriteria
        </h6>

        <?php if($selectedField != 'all'): ?>
            <a href="<?= base_url('admin/criteria/create?field=' . urlencode($selectedField)) ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kriteria
            </a>
        <?php else: ?>
            <button class="btn btn-secondary btn-sm" disabled title="Pilih divisi terlebih dahulu">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kriteria
            </button>
        <?php endif; ?>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center mb-0">
          <thead class="bg-light text-secondary small text-uppercase">
            <tr>
              <th width="5%">No</th>
              <th>Kode</th>
              <th class="text-start">Nama Kriteria</th>
              <th>Bobot</th>
              <th>Tipe</th>
              <th>Divisi</th> <th width="15%">Subkriteria</th>
              <th width="12%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($criteria)): ?>
                <?php foreach ($criteria as $key => $c): ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><span class="badge bg-light text-dark border"><?= esc($c['kode']) ?></span></td>
                  <td class="text-start fw-bold"><?= esc($c['nama']) ?></td>
                  <td><?= esc($c['bobot']) ?></td>
                  <td>
                    <span class="badge <?= $c['tipe']=='Benefit'?'bg-success-subtle text-success':'bg-danger-subtle text-danger' ?>">
                        <?= ucfirst($c['tipe']) ?>
                    </span>
                  </td>
                  
                  <td>
                    <?php 
                        $divisiName = '-';
                        // Cari nama divisi dari array pekerjaanFull berdasarkan ID yang tersimpan
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
                    <a href="<?= base_url('admin/subcriteria/' . $c['id']) ?>" class="btn btn-info btn-sm text-white py-0" style="font-size: 0.8rem;">
                        <i class="bi bi-list-nested me-1"></i> Detail Sub
                    </a>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('admin/criteria/edit/' . $c['id']) ?>" 
                           class="btn btn-warning btn-sm text-white" 
                           title="Edit">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        
                        <a href="<?= base_url('admin/criteria/delete/' . $c['id']) ?>" 
                           onclick="return confirm('Hapus kriteria ini? Subkriteria di dalamnya juga akan terhapus.')" 
                           class="btn btn-danger btn-sm" 
                           title="Hapus">
                            <i class="mdi mdi-delete"></i>
                        </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-folder2-open" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mt-2 mb-0">Belum ada data kriteria.</p>
                        <?php if($selectedField == 'all'): ?>
                            <small class="text-primary">Silakan pilih divisi di atas untuk memulai.</small>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>