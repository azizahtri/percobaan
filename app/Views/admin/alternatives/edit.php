<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-4">Edit Alternatif</h4>

    <form action="<?= base_url('admin/alternatives/update/' . $alt['id']) ?>" method="post">
      
      <div class="mb-3">
        <label for="kode" class="form-label">Kode</label>
        <input type="text" name="kode" id="kode" value="<?= esc($alt['kode']) ?>" class="form-control" required>
      </div>
    
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Alternatif</label>
        <input type="text" name="nama" id="nama" value="<?= esc($alt['nama']) ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Ubah Status</label>
        <select name="status" class="form-select">
            <option value="aktif" <?= $alt['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="tidak aktif" <?= $alt['status'] == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
          </select>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><i class="mdi mdi-check"></i> Update</button>
        <a href="<?= base_url('admin/alternatives') ?>" class="btn btn-secondary"><i class="mdi mdi-arrow-left"></i> Kembali</a>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
