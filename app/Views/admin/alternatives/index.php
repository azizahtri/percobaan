<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="alert alert-primary border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
    <div class="fs-4 me-3 mt-1"><i class="mdi mdi-account-group"></i></div>
    <div>
        <h5 class="alert-heading fw-bold mb-1">Data Alternatif (Karyawan Aktif)</h5>
        <p class="mb-2 small">Halaman ini berisi daftar kandidat yang telah resmi direkrut melalui proses seleksi.</p>
        <ul class="small mb-0 ps-3">
            <li><strong>Detail:</strong> Klik tombol biru untuk melihat biodata lengkap.</li>
            <li><strong>Hapus:</strong> Gunakan tombol merah jika karyawan resign atau data ganda.</li>
            <li>Data di sini digunakan sebagai <strong>Alternatif</strong> untuk penilaian kinerja.</li>
        </ul>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="card-title mb-0 fw-bold">Daftar Karyawan</h4>
        
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-light text-secondary text-uppercase small">
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th class="text-start">Nama Karyawan</th>
              <th>Posisi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($alternatives)): ?>
              <?php foreach ($alternatives as $key => $alt): ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td>
                    <span class="badge bg-light text-dark border"><?= esc($alt['kode']) ?></span>
                  </td>
                  
                  <td>
                    <span class="badge bg-light text-dark border"><?= esc($alt['nama']) ?></span> 
                  </td>
                  
                  <td>
                    <span class="badge bg-info text-dark">
                        <?= esc($alt['divisi'] ?? '-') ?> - <?= esc($alt['posisi'] ?? '-') ?>
                    </span>
                  </td>

                  <td>
                    <span class="badge bg-success bg-opacity-75">
                        <?= esc($alt['status']) ?>
                    </span>
                  </td>
                  
                  <td>
                    <div class="d-flex justify-content-center gap-1">
                        <a href="<?= base_url('admin/alternatives/detail/' . $alt['id']) ?>" 
                           class="btn btn-info btn-sm text-white" 
                           title="Lihat Biodata">
                          <i class="mdi mdi-account-card-details"></i> Detail
                        </a>

                        <a href="<?= base_url('admin/alternatives/delete/' . $alt['id']) ?>" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data karyawan ini?')" 
                           class="btn btn-danger btn-sm" 
                           title="Hapus">
                          <i class="mdi mdi-delete"></i>
                        </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-5">
                    <i class="mdi mdi-account-off" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p class="mt-2">Belum ada data karyawan aktif.</p>
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