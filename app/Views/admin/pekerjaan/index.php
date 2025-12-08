<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4">
    <div class="fs-4 me-3 mt-1"><i class="mdi mdi-domain"></i></div>
      <div>
        <h5 class="alert-heading fw-bold mb-1">Data Pekerjaan</h5>
          <p class="mb-0 small">Berikut adalah daftar Divisi yang tersedia. Klik tombol <b>"Lihat Posisi"</b> untuk mengelola posisi pekerjaan di dalam divisi tersebut.</p>
        <ul class="small mb-0 ps-3">
          <li>Silahkan Klik tombol <b>"Tambah Divisi"</b> untuk menambah Divisi pada perusahaan. Dan setidaknya ada 1 <b> Posisi </b> yang terisi.</li>
          <li>Klik tombol <b>"Tambah Posisi"</b> pada halaman detail setiap Divisi, untuk menambahkan posisi apa saja yang akan dibutuhkan.</li>
        </ul>
      </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="card-title mb-0 fw-bold">Daftar Divisi</h4>
        
        <button type="button" class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalAddDivisi">
            <i class="mdi mdi-plus-circle me-1"></i> Tambah Divisi
        </button>
      </div>

      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="mdi mdi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-light">
            <tr>
              <th width="10%">No</th>
              <th class="text-start">Nama Divisi</th>
              <th>Jumlah Posisi</th>
              <th width="20%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($divisi)): ?>
              <?php foreach ($divisi as $key => $d): ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td class="text-start fw-bold text-primary fs-5">
                      <i class="mdi mdi-folder-outline me-2"></i><?= esc($d['divisi']) ?>
                      
                      <button type="button" class="btn btn-sm btn-link text-warning p-0 ms-2" 
                              data-bs-toggle="modal" 
                              data-bs-target="#modalEditDivisi"
                              onclick="editDivisi('<?= esc($d['divisi']) ?>')"
                              title="Ganti Nama Divisi">
                          <i class="mdi mdi-pencil"></i>
                      </button>
                  </td>
                  <td>
                      <span class="badge bg-secondary rounded-pill px-3">
                        <?= $d['total_posisi'] ?> Posisi
                      </span>
                  </td>
                  <td>
                    <a href="<?= base_url('admin/pekerjaan/detail/' . urlencode($d['divisi'])) ?>" 
                       class="btn btn-info btn-sm text-white px-3">
                        <i class="mdi mdi-format-list-bulleted me-1"></i> Lihat Posisi
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center text-muted py-5">
                    <i class="mdi mdi-folder-remove" style="font-size: 3rem;"></i>
                    <p class="mt-2">Belum ada data divisi.</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<!-- Tambah Divisi Modal -->
<div class="modal fade" id="modalAddDivisi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title fw-bold"><i class="mdi mdi-folder-plus me-1"></i> Buat Divisi Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <form action="<?= base_url('admin/pekerjaan/store') ?>" method="post">
          <div class="modal-body">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Divisi</label>
                <input type="text" name="divisi" class="form-control" placeholder="Contoh: Marketing, Produksi" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Posisi Pertama</label>
                <input type="text" name="posisi" class="form-control" placeholder="Contoh: Manager, Staff" required>
                <div class="form-text text-muted small">
                    Sebuah divisi harus memiliki minimal satu posisi pekerjaan.
                </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success px-4">Simpan</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Divisi Modal -->
<div class="modal fade" id="modalEditDivisi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title text-white fw-bold"><i class="mdi mdi-pencil me-1"></i> Edit Nama Divisi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('admin/pekerjaan/updateDivisi') ?>" method="post">
          <div class="modal-body">
            <input type="hidden" name="old_divisi" id="old_divisi">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Divisi Baru</label>
                <input type="text" name="divisi" id="new_divisi" class="form-control" required>
            </div>
            
            <div class="alert alert-info small d-flex align-items-center">
                <i class="mdi mdi-information fs-4 me-2"></i>
                <div>
                    Mengubah nama ini akan otomatis memperbarui nama divisi pada semua posisi yang ada di dalamnya.
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning text-white px-4">Simpan Perubahan</button>
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