<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
          <h4 class="mb-0 fw-bold text-dark">Manajemen Akun Admin</h4>
          <p class="text-muted small mb-0">Kelola pengguna yang dapat login ke dashboard ini.</p>
      </div>
      <a href="<?= base_url('admin/akun/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
          <i class="mdi mdi-account-plus me-1"></i> Tambah Akun
      </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
          <i class="mdi mdi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center datatable" style="width:100%">
          <thead class="bg-light text-secondary text-uppercase small">
            <tr>
              <th width="5%" class="text-center">No</th>
              <th class="text-start">Nama Pengguna</th>
              <th>Username</th>
              <th>Role</th>
              <th>Dibuat</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $key => $u): ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td class="text-start">
                      <div class="d-flex align-items-center">
                          <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold border border-primary-subtle">
                              <?= strtoupper(substr($u['name'], 0, 1)) ?>
                          </div>
                          <span class="fw-bold text-dark"><?= esc($u['name']) ?></span>
                      </div>
                  </td>
                  <td><span class="text-muted">@<?= esc($u['username']) ?></span></td>
                  <td>
                      <?php 
                        $badgeClass = match($u['role']) {
                            'Super Admin' => 'bg-danger-subtle text-danger border-danger-subtle',
                            'HRD'         => 'bg-success-subtle text-success border-success-subtle',
                            default       => 'bg-secondary-subtle text-secondary border-secondary-subtle'
                        };
                      ?>
                      <span class="badge <?= $badgeClass ?> border px-3 py-2 rounded-pill">
                          <?= esc($u['role']) ?>
                      </span>
                  </td>
                  <td><small class="text-muted"><?= date('d M Y', strtotime($u['created_at'] ?? 'now')) ?></small></td>
                  <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="<?= base_url('admin/akun/edit/' . $u['id']) ?>" 
                           class="btn btn-action btn-action-edit" 
                           title="Edit Akun"
                           data-bs-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        
                        <button type="button"
                          class="btn btn-action btn-action-delete"
                          data-bs-toggle="modal"
                          data-bs-target="#modalHapus<?= $u['id'] ?>"
                          title="Hapus Akun">
                          <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                  </td>
                </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus Akun -->
<?php foreach ($users as $u): ?>
<div class="modal fade" id="modalHapus<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title fw-bold">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> Hapus Akun Admin?
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-4 text-danger opacity-25">
                    <i class="mdi mdi-trash-can-outline" style="font-size: 60px;"></i>
                </div>
                <p class="mb-2 text-muted">Apakah Anda yakin ingin menghapus akun:</p>
                <h5 class="fw-bold text-dark mb-4 px-3 mx-auto"
                    style="max-width: 100%; word-wrap: break-word; overflow-wrap: break-word;">
                    <?= esc($u['name']) ?> (@<?= esc($u['username']) ?>)
                </h5>
                <div class="alert alert-warning small text-start">
                    <i class="mdi mdi-alert me-2"></i>
                    <strong>Perhatian:</strong> Akun ini akan dihapus secara permanen dan tidak dapat login lagi.
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <a href="<?= base_url('admin/akun/delete/' . $u['id']) ?>"
                    class="btn btn-danger rounded-pill px-4 shadow-sm">
                    Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>