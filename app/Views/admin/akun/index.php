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
                        
                        <a href="<?= base_url('admin/akun/delete/' . $u['id']) ?>" 
                           onclick="return confirm('Yakin hapus akun <?= esc($u['name']) ?>?')" 
                           class="btn btn-action btn-action-delete" 
                           title="Hapus Akun"
                           data-bs-toggle="tooltip">
                            <i class="mdi mdi-delete"></i>
                        </a>
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
<?= $this->endSection() ?>