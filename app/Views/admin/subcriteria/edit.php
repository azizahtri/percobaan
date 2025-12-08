<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0 pt-1">
        <i class="bi bi-pencil-square me-2"></i> Edit Subkriteria
    </h5>
    </div>

    <div class="card-body">
      <form action="<?= base_url('admin/subcriteria/update/' . $sub['id']) ?>" method="post">
        <input type="hidden" name="criteria_id" value="<?= $sub['criteria_id'] ?>">

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <input type="text" name="keterangan" class="form-control" value="<?= esc($sub['keterangan']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Skor</label>
          <input type="number" step="0.01" name="bobot_sub" class="form-control" value="<?= esc($sub['bobot_sub']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tipe</label>
          <select name="tipe" class="form-select" required>
            <option value="benefit" <?= $sub['tipe'] == 'benefit' ? 'selected' : '' ?>>Benefit</option>
            <option value="cost" <?= $sub['tipe'] == 'cost' ? 'selected' : '' ?>>Cost</option>
          </select>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="<?= base_url('admin/subcriteria/' . $sub['criteria_id']) ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Batal
          </a>
          <button type="submit" class="btn btn-warning text-white">
            <i class="bi bi-save"></i> Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
