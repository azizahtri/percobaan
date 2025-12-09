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
                    <a href="<?= base_url('admin/subcriteria/delete/' . $sub['id']) ?>" 
                      class="btn btn-action btn-action-delete" onclick="return confirm('Yakin ingin menghapus subkriteria ini?')">
                      <i class="mdi mdi-delete"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-3">
                  Belum ada subkriteria untuk kriteria ini.
                </td>
              </tr>
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

<?= $this->endSection() ?>
