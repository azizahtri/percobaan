<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <h4 class="card-title mb-4 fw-bold">Edit Kriteria</h4>

    <?php
          $divisiLabel = '-';
          foreach($pekerjaan as $p) {
              if($p['id'] == $criteria['pekerjaan_id']) {
                  $divisiLabel = esc($p['divisi']); // Cukup tampilkan Divisi
                  break;
              }
          }
      ?>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label fw-bold">Divisi</label>
        <div class="col-sm-9">
          <input type="text" class="form-control bg-light fw-bold" value="<?= $divisiLabel ?>" readonly disabled>
          <input type="hidden" name="pekerjaan_id" value="<?= $criteria['pekerjaan_id'] ?>">
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label fw-bold">Kode Kriteria</label>
        <div class="col-sm-9">
          <input type="text" name="kode" value="<?= esc($criteria['kode']) ?>" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label fw-bold">Nama Kriteria</label>
        <div class="col-sm-9">
          <input type="text" name="nama" value="<?= esc($criteria['nama']) ?>" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label fw-bold">Bobot</label>
        <div class="col-sm-9">
          <input type="number" step="0.01" name="bobot" value="<?= esc($criteria['bobot']) ?>" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label fw-bold">Tipe Atribut</label>
        <div class="col-sm-9">
          <select name="tipe" class="form-select">
            <option value="Benefit" <?= $criteria['tipe'] == 'Benefit' ? 'selected' : '' ?>>Benefit</option>
            <option value="Cost" <?= $criteria['tipe'] == 'Cost' ? 'selected' : '' ?>>Cost</option>
          </select>
        </div>
      </div>

      <div class="text-end mt-4">
        <a href="<?= base_url('admin/criteria?field=' . $criteria['pekerjaan_id']) ?>" class="btn btn-light me-2">Kembali</a>
        <button type="submit" class="btn btn-primary px-4">Update Data</button>
      </div>

    </form>

  </div>
</div>

<?= $this->endSection() ?>