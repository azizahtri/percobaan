<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="alert alert-primary border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
    <div class="fs-4 me-3 mt-1"><i class="mdi mdi-briefcase-search"></i></div>
    <div>
        <h5 class="alert-heading fw-bold mb-1">Panduan Kelola Lowongan</h5>
        <p class="mb-2 small">Halaman ini digunakan untuk memposting lowongan pekerjaan yang akan tampil di halaman depan.</p>
        <ul class="small mb-0 ps-3">
            <li><strong>Filter:</strong> Gunakan dropdown untuk menyaring lowongan berdasarkan Divisi.</li>
            <li><strong>Tambah:</strong> Klik tombol biru untuk membuat lowongan baru.</li>
            <li><strong>Seleksi:</strong> Klik tombol pada kolom pelamar untuk melihat kandidat.</li>
        </ul>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-dark fw-bold">Daftar Lowongan</h4>
        <a href="<?= base_url('admin/lowongan/create') ?>" class="btn btn-primary btn-sm px-3">
          <i class="mdi mdi-plus-circle me-1"></i> Tambah Lowongan
        </a>
      </div>

      <form method="get" class="mb-4 row g-2 align-items-center">
        <div class="col-auto">
            <label class="col-form-label fw-bold text-muted small text-uppercase ls-1">Filter Divisi:</label>
        </div>
        <div class="col-md-4">
            <select name="field" class="form-select border-primary-subtle fw-bold text-dark" onchange="this.form.submit()">
              <option value="all">-- Semua Divisi --</option>
              
              <?php foreach ($divisiList as $d): ?>
                <option value="<?= esc($d['divisi']) ?>" <?= ($selectedField == $d['divisi']) ? 'selected' : '' ?>>
                  <?= esc($d['divisi']) ?>
                </option>
              <?php endforeach; ?>

            </select>
        </div>
        
        <?php if ($selectedField != 'all'): ?>
            <div class="col-auto">
                <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-refresh"></i> Reset
                </a>
            </div>
        <?php endif; ?>
      </form>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-light text-secondary small text-uppercase">
            <tr>
              <th>No</th>
              <th class="text-start">Posisi / Jabatan</th>
              <th width="25%">Judul Postingan</th>
              <th>Pelamar</th> 
              <th>Tipe</th>
              <th>Tanggal</th>
              <th width="15%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($lowongan)): ?>
              <?php foreach ($lowongan as $key => $l): ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  
                  <td class="text-start">
                      <span class="badge bg-info text-dark mb-1 border border-info-subtle">
                        <?= esc($l['divisi']) ?>
                      </span><br>
                      <span class="fw-bold text-dark small">
                        <?= esc($l['jabatan'] ?? '') ?> </span>
                  </td>

                  <td class="fw-bold text-start text-primary small"><?= esc($l['judul_lowongan']) ?></td>
                  
                  <td>
                    <?php if ($l['jumlah_pelamar'] > 0): ?>
                        <a href="<?= base_url('admin/lowongan/detail/' . $l['id']) ?>" 
                           class="btn btn-sm btn-outline-primary fw-bold position-relative px-3"
                           title="Lihat pelamar">
                           <?= $l['jumlah_pelamar'] ?> Orang
                        </a>
                    <?php else: ?>
                        <span class="badge bg-secondary text-white-50">0</span>
                    <?php endif; ?>
                  </td>
                  
                  <td><small class="text-uppercase"><?= esc($l['jenis']) ?></small></td>
                  <td><?= date('d M Y', strtotime($l['tanggal_posting'])) ?></td>
                  
                  <td>
                    <div class="d-flex justify-content-center gap-1">
                        <a href="<?= base_url('admin/lowongan/detail/' . $l['id']) ?>" class="btn btn-info btn-sm text-white" title="Detail">
                          <i class="mdi mdi-eye"></i>
                        </a>
                        <a href="<?= base_url('admin/lowongan/edit/' . $l['id']) ?>" class="btn btn-warning btn-sm text-white" title="Edit">
                          <i class="mdi mdi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $l['id'] ?>">
                          <i class="mdi mdi-close-circle"></i>
                        </button>
                    </div>

                    <div class="modal fade" id="modalHapus<?= $l['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Tutup Lamaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-start">
                                    Yakin ingin menutup: <b><?= esc($l['judul_lowongan']) ?></b>?
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <a href="<?= base_url('admin/lowongan/delete/'.$l['id']) ?>" class="btn btn-danger">Ya, Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>

                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <i class="mdi mdi-file-document-outline" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p class="mt-2">Belum ada data lowongan.</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?= $this->endSection() ?>