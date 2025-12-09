<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="alert alert-primary border-0 shadow-sm d-flex align-items-start mb-4">
    <div class="fs-4 me-3 mt-1"><i class="mdi mdi-account-group"></i></div>
    <div>
        <h5 class="alert-heading fw-bold mb-1">Data Alternatif (Evaluasi Kinerja)</h5>
        <p class="mb-0 small">Kelola data karyawan aktif dan lakukan penilaian kinerja berkala.</p>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h4 class="card-title mb-4 fw-bold text-dark">Daftar Karyawan</h4>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center datatable" style="width:100%">
          <thead class="bg-light text-secondary small text-uppercase">
            <tr>
              <th class="text-center">No</th>
              <th class="text-start">Nama Karyawan</th>
              <th class="text-center">Posisi</th>
              <th class="text-center">Skor Kinerja</th> 
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($alternatives)): ?>
              <?php foreach ($alternatives as $key => $alt): ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td class="text-start">
                    <span class="d-block fw-bold text-dark"><?= esc($alt['nama']) ?></span> 
                    <small class="text-muted"><?= esc($alt['kode']) ?></small>
                  </td>
                  <td>
                    <span class="badge bg-info text-dark border border-info-subtle">
                        <?= esc($alt['divisi'] ?? '-') ?> - <?= esc($alt['posisi'] ?? '-') ?>
                    </span>
                  </td>
                  
                  <td>
                    <?php if(!empty($alt['skor_akhir']) && $alt['skor_akhir'] > 0): ?>
                        <span class="fw-bold text-primary fs-6"><?= number_format($alt['skor_akhir'], 4) ?></span>
                    <?php else: ?>
                        <span class="badge bg-light text-muted border">Belum Dinilai</span>
                    <?php endif; ?>
                  </td>
                  
                  <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        
                        <a href="<?= base_url('admin/alternatives/penilaian/' . $alt['id']) ?>" 
                           class="btn btn-action btn-action-edit" 
                           title="Evaluasi Kinerja"
                           data-bs-toggle="tooltip" data-bs-placement="top">
                          <i class="mdi mdi-star"></i> </a>

                        <a href="<?= base_url('admin/alternatives/detail/' . $alt['id']) ?>" 
                           class="btn btn-action btn-action-detail" 
                           title="Detail Profil"
                           data-bs-toggle="tooltip" data-bs-placement="top">
                          <i class="mdi mdi-eye"></i>
                        </a>
                        
                        <a href="<?= base_url('admin/alternatives/delete/' . $alt['id']) ?>" 
                           onclick="return confirm('Hapus data karyawan ini? Data penilaian juga akan hilang.')" 
                           class="btn btn-action btn-action-delete" 
                           title="Hapus Data"
                           data-bs-toggle="tooltip" data-bs-placement="top">
                          <i class="mdi mdi-delete"></i>
                        </a>

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

<?= $this->endSection() ?>