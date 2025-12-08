<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="card shadow-sm border-0">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 pt-1">
            <i class="bi bi-plus-circle"></i> Tambah Subkriteria – <?= esc($criteria['nama']) ?>
        </h5>
    </div>

    <div class="card-body">
      <form action="<?= base_url('admin/subcriteria/store') ?>" method="post">
        <input type="hidden" name="criteria_id" value="<?= $criteria['id'] ?>">

        <div class="mb-3">
          <label for="keterangan" class="form-label">Deskripsi Subkriteria</label>
          <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Contoh: SMA / S1 / S2 (untuk Jenjang Pendidikan)" required>
        </div>

        <div class="mb-3">
          <label for="bobot_sub" class="form-label">Skor</label>
          <input type="number" step="0.01" name="bobot_sub" id="bobot_sub" class="form-control" placeholder="Contoh: 3" required>
        </div>

        <div class="mb-3">
          <label for="tipe" class="form-label">Tipe</label>
          <select name="tipe" id="tipe" class="form-select" required>
            <option value="benefit">Benefit</option>
            <option value="cost">Cost</option>
          </select>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="<?= base_url('admin/subcriteria/' . $criteria['id']) ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Batal
          </a>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
