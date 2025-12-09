<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-white"><i class="mdi mdi-pencil me-2"></i>Edit Data Akun</h6>
                    <a href="<?= base_url('admin/akun') ?>" class="btn btn-sm btn-light border-0 opacity-75 rounded-pill px-3">Kembali</a>
                </div>
                <div class="card-body p-4">

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger small mb-4">
                             <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif ?>

                    <form action="<?= base_url('admin/akun/update/' . $user['id']) ?>" method="post">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NAMA LENGKAP</label>
                            <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">USERNAME</label>
                            <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-secondary">PASSWORD BARU</label>
                                <input type="password" name="password" class="form-control bg-light" placeholder="(Kosongkan jika tidak ubah)">
                                <small class="text-muted" style="font-size: 0.7rem;">Isi hanya jika ingin mengganti password.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-secondary">ROLE (HAK AKSES)</label>
                                <select name="role" class="form-select border-primary-subtle">
                                    <option value="HRD" <?= $user['role'] == 'HRD' ? 'selected' : '' ?>>HRD</option>
                                    <option value="Super Admin" <?= $user['role'] == 'Super Admin' ? 'selected' : '' ?>>Super Admin</option>
                                    <option value="Staff" <?= $user['role'] == 'Staff' ? 'selected' : '' ?>>Staff</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-warning text-white fw-bold rounded-pill">Update Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>