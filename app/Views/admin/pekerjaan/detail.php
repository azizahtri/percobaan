<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="bg-white p-2 rounded shadow-sm me-3 text-primary">
            <i class="mdi mdi-domain fs-3"></i>
        </div>
        <div>
            <small class="text-muted text-uppercase fw-bold ls-1">Divisi</small>
            <h3 class="mb-0 fw-bold text-dark"><?= esc($namaDivisi) ?></h3>
        </div>
    </div>
    <a href="<?= base_url('admin/pekerjaan') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="mdi mdi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      
      <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h5 class="card-title mb-0 text-primary">
            <i class="mdi mdi-format-list-bulleted me-2"></i>Daftar Posisi
        </h5>
        
        <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalAddposisi">
            <i class="mdi mdi-plus-circle me-1"></i> Tambah Posisi Baru
        </button>
      </div>

      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
          <i class="mdi mdi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="bg-light text-secondary small text-uppercase">
            <tr>
              <th width="5%" class="text-center">No</th>
              <th width="40%">Nama Posisi</th>
              <th width="20%" class="text-center">Standar Kelulusan (WP)</th>
              <th width="15%" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($posisi)): ?>
                <?php foreach ($posisi as $key => $j): ?>
                    <tr>
                      <td class="text-center"><?= $key + 1 ?></td>
                      <td class="fw-bold text-dark fs-6">
                          <?= esc($j['posisi']) ?>
                      </td>
                      <td class="text-center">
                          <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">
                            Target: <?= number_format($j['standar_spk'], 4) ?>
                          </span>
                      </td>
                      <td class="text-center">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning btn-sm text-white" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditPosisi"
                                    onclick="editPosisi('<?= $j['id'] ?>', '<?= esc($j['posisi']) ?>')">
                                <i class="mdi mdi-pencil"></i>
                            </button>

                            <a href="<?= base_url('admin/pekerjaan/delete/' . $j['id']) ?>" 
                               onclick="return confirm('PERINGATAN: Menghapus posisi ini akan menghapus data terkait. Lanjutkan?')" 
                               class="btn btn-danger btn-sm">
                                <i class="mdi mdi-trash-can"></i>
                            </a>
                        </div>
                      </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <p class="mt-2 mb-0">Belum ada posisi di divisi ini.</p>
                    </td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalAddposisi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="mdi mdi-plus-circle me-1"></i> Tambah Posisi Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <form action="<?= base_url('admin/pekerjaan/store') ?>" method="post">
          <div class="modal-body">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Divisi</label>
                <input type="text" name="divisi" class="form-control bg-light fw-bold" value="<?= esc($namaDivisi) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Posisi</label>
                <input type="text" name="posisi" class="form-control" placeholder="Contoh: Staff, Supervisor" required>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditPosisi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title fw-bold text-white"><i class="mdi mdi-pencil me-1"></i> Edit Data Posisi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <form id="formEditPosisi" method="post">
          <div class="modal-body">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Posisi</label>
                <input type="text" name="posisi" id="input_posisi" class="form-control" required>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning text-white px-4">Update Data</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
    function editPosisi(id, nama) {
        // Set Action Form URL secara dinamis
        const baseUrl = "<?= base_url('admin/pekerjaan/updatePosisi') ?>"; 
        document.getElementById('formEditPosisi').action = baseUrl + '/' + id;

        // Isi Value Input
        document.getElementById('input_posisi').value = nama;
    }
</script>

<?= $this->endSection() ?>