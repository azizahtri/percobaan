<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="bg-white p-3 rounded-circle shadow-sm me-3 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
            <i class="mdi mdi-domain fs-4"></i>
        </div>
        <div>
            <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.75rem;">Divisi</small>
            <h3 class="mb-0 fw-bold text-dark"><?= esc($namaDivisi) ?></h3>
        </div>
    </div>
    <a href="<?= base_url('admin/pekerjaan') ?>" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-bold">
        <i class="mdi mdi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
          <h6 class="mb-0 fw-bold text-dark">
              <i class="mdi mdi-format-list-bulleted me-2 text-primary"></i>Daftar Posisi Pekerjaan
          </h6>
          
          <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddposisi">
              <i class="mdi mdi-plus-circle me-1"></i> Tambah Posisi
          </button>
      </div>
    
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
          <i class="mdi mdi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle datatable" style="width:100%">
          <thead class="bg-light text-secondary text-uppercase small">
            <tr>
              <th width="5%" class="text-center">No</th>
              <th>Nama Posisi</th>
              <th class="text-center">Standar Kelulusan (WP)</th>
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
                          <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                            Target: <?= number_format($j['standar_spk'], 4) ?>
                          </span>
                      </td>
                      <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            
                            <button type="button" class="btn btn-action btn-action-edit" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditPosisi"
                                    onclick="editPosisi('<?= $j['id'] ?>', '<?= esc($j['posisi']) ?>')"
                                    title="Edit Posisi"
                                    data-bs-toggle="tooltip" data-bs-placement="top">
                                <i class="mdi mdi-pencil"></i>
                            </button>

                            <a href="<?= base_url('admin/pekerjaan/delete/' . $j['id']) ?>" 
                               onclick="return confirm('PERINGATAN: Menghapus posisi ini akan menghapus data terkait. Lanjutkan?')" 
                               class="btn btn-action btn-action-delete"
                               title="Hapus Posisi"
                               data-bs-toggle="tooltip" data-bs-placement="top">
                                <i class="mdi mdi-delete"></i>
                            </a>

                        </div>
                      </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalAddposisi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="mdi mdi-plus-circle me-2"></i> Tambah Posisi Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <form action="<?= base_url('admin/pekerjaan/store') ?>" method="post">
          <div class="modal-body p-4">
            
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Divisi</label>
                <input type="text" name="divisi" class="form-control bg-light fw-bold text-dark" value="<?= esc($namaDivisi) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Nama Posisi</label>
                <input type="text" name="posisi" class="form-control" placeholder="Contoh: Staff, Supervisor, Manager" required>
            </div>

          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Data</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditPosisi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-warning">
        <h5 class="modal-title fw-bold text-white"><i class="mdi mdi-pencil me-2"></i> Edit Data Posisi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <form id="formEditPosisi" method="post">
          <div class="modal-body p-4">
            
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Nama Posisi</label>
                <input type="text" name="posisi" id="input_posisi" class="form-control" required>
            </div>

          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning text-white px-4 fw-bold">Update Data</button>
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