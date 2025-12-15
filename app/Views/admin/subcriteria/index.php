<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <i class="bi bi-diagram-3"></i>
        Subkriteria / <?= esc($pekerjaan['divisi'] ?? 'Tanpa Pekerjaan') ?>
        – <?= esc($criteria['nama']) ?>
      </h5>
      <a href="<?= base_url('admin/subcriteria/create/' . $criteria['id']) ?>" class="btn btn-light btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Subkriteria
      </a>
    </div>

    <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center datatable" style="width:100%">
          <thead class="bg-light text-secondary small text-uppercase">
            <tr>
              <th class="text-center">No</th>
              <th class="text-center">Deskripsi</th>
              <th class="text-center">Skor</th>
              <th class="text-center">Tipe</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($subcriteria)): ?>
              <?php foreach ($subcriteria as $key => $sub): ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= esc($sub['keterangan']) ?></td>
                  <td><?= esc($sub['bobot_sub']) ?></td>
                  <td>
                    <span class="badge <?= $sub['tipe'] == 'benefit' ? 'bg-success' : 'bg-danger' ?>">
                      <?= ucfirst($sub['tipe']) ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="<?= base_url('admin/subcriteria/edit/' . $sub['id']) ?>" 
                           class="btn btn-action btn-action-edit">
                           <i class="mdi mdi-pencil"></i>
                        </a>
                        <button type="button"
                          class="btn btn-action btn-action-delete"
                          data-bs-toggle="modal"
                          data-bs-target="#modalHapus<?= $sub['id'] ?>"
                          title="Hapus Subkriteria">
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

      <a href="<?= base_url('admin/criteria') ?>" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kriteria
      </a>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus Subkriteria -->
<?php foreach ($subcriteria as $sub): ?>
<div class="modal fade" id="modalHapus<?= $sub['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title fw-bold">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> Hapus Subkriteria?
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-4 text-danger opacity-25">
                    <i class="mdi mdi-trash-can-outline" style="font-size: 60px;"></i>
                </div>
                <p class="mb-2 text-muted">Apakah Anda yakin ingin menghapus subkriteria:</p>
                <h5 class="fw-bold text-dark mb-4 px-3 mx-auto"
                    style="max-width: 100%; word-wrap: break-word; overflow-wrap: break-word;">
                    "<?= esc($sub['keterangan']) ?>"
                </h5>
                <div class="alert alert-warning small text-start">
                    <i class="mdi mdi-alert me-2"></i>
                    <strong>Perhatian:</strong> Data ini akan dihapus secara permanen dan tidak dapat dikembalikan.
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <a href="<?= base_url('admin/subcriteria/delete/' . $sub['id']) ?>"
                    class="btn btn-danger rounded-pill px-4 shadow-sm">
                    Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?= $this->endSection() ?>