<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="mdi mdi-account-plus me-2"></i>Tambah Akun Baru</h6>
                    <a href="<?= base_url('admin/akun') ?>" class="btn btn-sm btn-light border rounded-pill px-3">Kembali</a>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger small mb-4">
                             <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif ?>

                    <form action="<?= base_url('admin/akun/store') ?>" method="post">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NAMA LENGKAP</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">USERNAME</label>
                            <input type="text" name="username" class="form-control" placeholder="username_login" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-secondary">PASSWORD</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-secondary">ROLE (HAK AKSES)</label>
                                <select name="role" class="form-select border-primary-subtle">
                                    <option value="HRD">HRD (Recruiter)</option>
                                    <option value="Super Admin">Super Admin</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill">Simpan Akun</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>