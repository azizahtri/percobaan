<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <?php
        // Logika cari nama pekerjaan berdasarkan ID yang dipilih
        $namaPekerjaan = '';
        if (!empty($selectedField)) {
            foreach ($pekerjaan as $p) {
                if ($p['id'] == $selectedField) {
                    $namaPekerjaan = $p['divisi'];
                    break;
                }
            }
        }
    ?>

    <h4 class="card-title mb-4 fw-bold">
      Tambah Kriteria
      <?= $namaPekerjaan ? ' – ' . esc($namaPekerjaan) : '' ?>
    </h4>

    <form action="<?= base_url('admin/criteria/store') ?>" method="post">
      <?php
              // 1. CARI TAHU DIVISI TARGET BERDASARKAN ID YANG DIPILIH
              $targetDivisi = null;
              if (!empty($selectedField)) {
                  foreach ($pekerjaan as $p) {
                      if ($p['id'] == $selectedField) {
                          $targetDivisi = $p['divisi'];
                          break;
                      }
                  }
              }
        ?>

      <div class="row mb-3">
          <label class="col-sm-3 col-form-label fw-bold">Pilih Divisi</label>
          <div class="col-sm-9">
              
              <select name="nama_divisi" class="form-select" required>
                  <option value="">-- Pilih Divisi --</option>

                  <?php 
                      // Ambil divisi unik dari data pekerjaan
                      $divisiUnik = [];
                      foreach ($pekerjaan as $p) {
                          $divisiUnik[$p['divisi']] = $p['divisi'];
                      }
                      
                      foreach ($divisiUnik as $divisi): 
                  ?>
                      <option value="<?= esc($divisi) ?>" 
                          <?= ($selectedField == $divisi) ? 'selected' : '' ?>>
                          <?= esc($divisi) ?>
                      </option>
                  <?php endforeach; ?>

              </select>
              <div class="form-text text-muted">Kriteria ini akan berlaku untuk semua jabatan di divisi ini.</div>
          
          </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label fw-bold">Kode Kriteria</label>
        <div class="col-sm-9">
          <input type="text" name="kode" class="form-control" placeholder="Contoh: C1, C2" required>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label fw-bold">Nama Kriteria</label>
        <div class="col-sm-9">
          <input type="text" name="nama" class="form-control" placeholder="Contoh: Pendidikan, Pengalaman" required>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label fw-bold">Bobot (Maksimal)</label>
        <div class="col-sm-9">
          <input type="number" step="0.01" name="bobot" class="form-control" placeholder="Nilai bobot (misal: 4 atau 5)" required>
        </div>
      </div>

      <div class="row mb-4">
        <label class="col-sm-3 col-form-label fw-bold">Tipe Atribut</label>
        <div class="col-sm-9">
          <select name="tipe" class="form-select" required>
            <option value="Benefit">Benefit</option>
            <option value="Cost">Cost</option>
          </select>
        </div>
      </div>

      <div class="text-end">
        <a href="<?= base_url('admin/criteria') ?>" class="btn btn-light me-2">Batal</a>
        <button type="submit" class="btn btn-success px-4">
            <i class="mdi mdi-content-save"></i> Simpan
        </button>
      </div>

    </form>
  </div>
</div>

<?= $this->endSection() ?>