<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4">
    <div class="fs-4 me-3 mt-1"><i class="mdi mdi-domain"></i></div>
      <div>
        <h5 class="alert-heading fw-bold mb-1">Data Pekerjaan & Divisi</h5>
          <p class="mb-2 small">Berikut adalah daftar Divisi yang tersedia di perusahaan Anda.</p>
        <ul class="small mb-0 ps-3">
          <li>Klik <strong>"Tambah Divisi"</strong> untuk membuat divisi baru (Wajib input minimal 1 posisi).</li>
          <li>Klik tombol <strong>"Lihat Posisi"</strong> (ikon mata) untuk mengelola daftar jabatan di dalam divisi tersebut.</li>
        </ul>
      </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
          <h6 class="mb-0 fw-bold text-dark">Daftar Divisi</h6>
          
          <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddDivisi">
              <i class="mdi mdi-plus-circle me-1"></i> Tambah Divisi
          </button>
      </div>
        
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="mdi mdi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <div class="table-responsive">
          <table class="table table-hover align-middle text-center datatable" style="width:100%">
            <thead class="bg-light text-secondary text-uppercase small">
              <tr>
                <th class="text-center">No</th>
                <th class="text-start">Nama Divisi</th>
                <th class="text-center">Jumlah Posisi</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($divisi)): ?>
                <?php foreach ($divisi as $key => $d): ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td class="text-start">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center text-primary">
                                <i class="mdi mdi-folder-outline fs-5"></i>
                            </div>
                            <span class="fw-bold text-dark fs-6"><?= esc($d['divisi']) ?></span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary rounded-pill px-3 py-2 border">
                          <?= $d['total_posisi'] ?> Posisi Terdaftar
                        </span>
                    </td>
                    <td class="text-center">
                      <div class="d-flex justify-content-center gap-2">
                          
                          <button type="button" class="btn btn-action btn-action-edit" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#modalEditDivisi"
                                  onclick="editDivisi('<?= esc($d['divisi']) ?>')"
                                  title="Edit Nama Divisi">
                              <i class="mdi mdi-pencil"></i>
                          </button>

                          <a href="<?= base_url('admin/pekerjaan/detail/' . urlencode($d['divisi'])) ?>" 
                            class="btn btn-action btn-action-detail"
                            title="Lihat Daftar Posisi">
                              <i class="mdi mdi-eye"></i>
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

<div class="modal fade" id="modalAddDivisi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="mdi mdi-folder-plus me-2"></i> Buat Divisi Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <form action="<?= base_url('admin/pekerjaan/store') ?>" method="post">
          <div class="modal-body p-4">
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Nama Divisi</label>
                <input type="text" name="divisi" class="form-control" placeholder="Contoh: Marketing, IT, Finance" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Posisi Awal (Wajib)</label>
                <input type="text" name="posisi" class="form-control" placeholder="Contoh: Manager, Staff Admin" required>
                <div class="form-text text-muted small mt-2">
                    <i class="mdi mdi-information-outline"></i> Sebuah divisi baru harus memiliki minimal satu posisi pekerjaan awal.
                </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Divisi</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditDivisi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-warning">
        <h5 class="modal-title text-white fw-bold"><i class="mdi mdi-pencil me-2"></i> Edit Nama Divisi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('admin/pekerjaan/updateDivisi') ?>" method="post">
          <div class="modal-body p-4">
            <input type="hidden" name="old_divisi" id="old_divisi">
            
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Nama Divisi Baru</label>
                <input type="text" name="divisi" id="new_divisi" class="form-control" required>
            </div>
            
            <div class="alert alert-info small d-flex align-items-center mb-0 border-0 bg-info-subtle text-info-emphasis">
                <i class="mdi mdi-alert-circle-outline fs-4 me-3"></i>
                <div>
                    <strong>Perhatian:</strong><br>
                    Mengubah nama ini akan otomatis memperbarui nama divisi pada <u>semua posisi pekerjaan</u> yang ada di dalamnya.
                </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning text-white px-4 fw-bold">Simpan Perubahan</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
    function editDivisi(nama) {
        document.getElementById('old_divisi').value = nama;
        document.getElementById('new_divisi').value = nama;
    }
</script>

<?= $this->endSection() ?>