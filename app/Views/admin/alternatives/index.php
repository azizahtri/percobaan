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
                        
                        <button type="button"
                          class="btn btn-action btn-action-delete"
                          data-bs-toggle="modal"
                          data-bs-target="#modalHapus<?= $alt['id'] ?>">
                            <span data-bs-toggle="tooltip"
                                  data-bs-placement="top"
                                  data-bs-original-title="Hapus Data">
                                <i class="mdi mdi-delete"></i>
                            </span>
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

<!-- Modal Konfirmasi Hapus - Taruh di sini (paling bawah sebelum endSection) -->
<?php foreach ($alternatives as $alt): ?>
<div class="modal fade" id="modalHapus<?= $alt['id'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel<?= $alt['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title fw-bold" id="modalHapusLabel<?= $alt['id'] ?>">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> Konfirmasi Hapus
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-4 text-danger opacity-25">
                    <i class="mdi mdi-trash-can-outline" style="font-size: 60px;"></i>
                </div>
                <p class="mb-2 text-muted">Apakah Anda yakin ingin menghapus karyawan:</p>
                <h5 class="fw-bold text-dark mb-4" style="max-width: 100%; word-wrap: break-word; overflow-wrap: break-word;">
                    <?= esc($alt['nama']) ?>
                </h5>
                <div class="alert alert-warning small text-start">
                    <i class="mdi mdi-alert me-2"></i>
                    <strong>Perhatian:</strong> Data penilaian kinerja terkait karyawan ini juga akan dihapus secara permanen.
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('admin/alternatives/delete/' . $alt['id']) ?>"
                   class="btn btn-danger rounded-pill px-4 shadow-sm"
                   onclick="event.stopPropagation();">
                    Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


<?= $this->endSection() ?>
