<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="alert alert-primary border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
    <div class="fs-4 me-3 mt-1"><i class="mdi mdi-briefcase-search"></i></div>
    <div>
        <h5 class="alert-heading fw-bold mb-1">Kelola Lowongan Pekerjaan</h5>
        <p class="mb-0 small">Halaman ini digunakan untuk memposting, mengedit, atau menutup lowongan yang tampil di website.</p>
        <ul class="small mb-0 ps-3 mt-1">
            <li><strong>Filter:</strong> Gunakan dropdown untuk menyaring lowongan berdasarkan Divisi.</li>
            <li><strong>Seleksi:</strong> Klik tombol pada kolom pelamar untuk melihat kandidat yang masuk.</li>
        </ul>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">

      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h4 class="mb-0 text-dark fw-bold">Daftar Lowongan</h4>
        
        <div class="d-flex align-items-center gap-2">
            <form method="get" class="d-flex align-items-center">
                <select name="field" class="form-select form-select-sm border-secondary-subtle fw-bold text-dark me-2" 
                        style="min-width: 150px;" onchange="this.form.submit()">
                  <option value="all">-- Semua Divisi --</option>
                  <?php foreach ($divisiList as $d): ?>
                    <option value="<?= esc($d['divisi']) ?>" <?= ($selectedField == $d['divisi']) ? 'selected' : '' ?>>
                      <?= esc($d['divisi']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                
                <?php if ($selectedField != 'all'): ?>
                    <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-outline-secondary btn-sm me-2" title="Reset Filter">
                        <i class="mdi mdi-refresh"></i>
                    </a>
                <?php endif; ?>
            </form>

            <a href="<?= base_url('admin/lowongan/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
              <i class="mdi mdi-plus-circle me-1"></i> Tambah Lowongan
            </a>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle text-center datatable" style="width:100%">
          <thead class="bg-light text-secondary small text-uppercase">
            <tr>
              <th class="text-center">No</th>
              <th class="text-start">Posisi / Jabatan</th>
              <th width="20%">Judul Postingan</th>
              <th class="text-center">Pelamar</th> 
              <th class="text-center">Tipe</th>
              <th class="text-center">Tanggal</th>
              <th class="text-center">Aksi</th>
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

                  <td class="text-start">
                      <span class="fw-bold text-primary small d-block text-truncate" style="max-width: 200px;">
                          <?= esc($l['judul_lowongan']) ?>
                      </span>
                  </td>
                  
                  <td>
                    <?php if ($l['jumlah_pelamar'] > 0): ?>
                        <a href="<?= base_url('admin/lowongan/detail/' . $l['id']) ?>" 
                           class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3 py-1"
                           title="Lihat pelamar">
                           <?= $l['jumlah_pelamar'] ?> Orang
                        </a>
                    <?php else: ?>
                        <span class="badge bg-light text-muted border rounded-pill px-3">0</span>
                    <?php endif; ?>
                  </td>
                  
                  <td>
                    <?php 
                        // 1. Standarisasi teks ke Huruf Besar
                        $jenisLabel = strtoupper($l['jenis']);

                        // 2. Tentukan Warna Berdasarkan Jenis
                        $badgeClass = match($jenisLabel) {
                            'FULL TIME'  => 'bg-primary-subtle text-primary border-primary-subtle',  // Biru
                            'PART TIME'  => 'bg-warning-subtle text-warning-emphasis border-warning-subtle', // Kuning/Oranye
                            'MAGANG'     => 'bg-success-subtle text-success border-success-subtle',  // Hijau
                            'INTERNSHIP' => 'bg-success-subtle text-success border-success-subtle',  // Hijau (Alternatif)
                            'FREELANCE'  => 'bg-info-subtle text-info border-info-subtle',           // Biru Muda
                            'CONTRACT'   => 'bg-dark-subtle text-dark border-dark-subtle',           // Abu Gelap
                            'KONTRAK'    => 'bg-dark-subtle text-dark border-dark-subtle',           // Abu Gelap
                            default      => 'bg-secondary-subtle text-secondary border-secondary-subtle' // Default Abu
                        };
                    ?>

                    <span class="badge <?= $badgeClass ?> border px-3 py-2 rounded-pill">
                        <?= esc($jenisLabel) ?>
                    </span>
                  </td>

                  <td>
                    <small class="text-muted">
                      <?= date('d M Y', strtotime($l['tanggal_posting'])) ?>
                    </small>
                  </td>
                  
                  <td>
                    <div class="d-flex justify-content-center gap-2">
                        
                        <a href="<?= base_url('admin/lowongan/detail/' . $l['id']) ?>" 
                           class="btn btn-action btn-action-detail" 
                           title="Lihat Detail"
                           data-bs-toggle="tooltip" data-bs-placement="top">
                          <i class="mdi mdi-eye"></i>
                        </a>

                        <a href="<?= base_url('admin/lowongan/edit/' . $l['id']) ?>" 
                           class="btn btn-action btn-action-edit" 
                           title="Edit Lowongan"
                           data-bs-toggle="tooltip" data-bs-placement="top">
                          <i class="mdi mdi-pencil"></i>
                        </a>

                        <button type="button" class="btn btn-action btn-action-delete" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalHapus<?= $l['id'] ?>"
                                title="Tutup / Hapus"
                                data-bs-toggle="tooltip" data-bs-placement="top">
                          <i class="mdi mdi-delete"></i>
                        </button>
                    </div>

                    <div class="modal fade" id="modalHapus<?= $l['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg overflow-hidden">
                                
                                <div class="modal-header bg-danger text-white px-4 py-3">
                                    <h5 class="modal-title fw-bold d-flex align-items-center">
                                        <i class="mdi mdi-alert-circle-outline me-2 fs-4"></i> Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                
                                <div class="modal-body p-4 text-center">
                                    
                                    <div class="mb-4">
                                        <div class="mx-auto bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                            <i class="mdi mdi-trash-can-outline" style="font-size: 36px;"></i>
                                        </div>
                                        
                                        <h6 class="mb-2 text-dark fw-bold">Hapus Lowongan Ini?</h6>
                                        
                                        <div class="bg-light p-3 rounded border text-break" style="white-space: normal;">
                                            <span class="fw-bold text-dark fs-6 d-block" style="line-height: 1.4;">
                                                "<?= esc($l['judul_lowongan']) ?>"
                                            </span>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning text-start border-0 bg-warning-subtle text-warning-emphasis p-3">
                                        <div class="d-flex align-items-start">
                                            <i class="mdi mdi-alert fs-4 me-3 flex-shrink-0 mt-1"></i>
                                            
                                            <div style="flex: 1; min-width: 0;">
                                                <strong class="d-block mb-1">Peringatan:</strong>
                                                <p class="mb-0 small text-wrap" style="line-height: 1.5;">
                                                    Data pelamar yang belum diproses pada lowongan ini akan ikut terhapus atau menjadi orphan data (tidak dapat diakses).
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="modal-footer bg-light border-top-0 d-flex justify-content-center gap-2 py-3">
                                  <button type="button" class="btn btn-light border border-2 px-4 rounded-pill fw-bold" data-bs-dismiss="modal">
                                      Batal
                                  </button>
                                  
                                  <a href="<?= base_url('admin/lowongan/delete/'.$l['id']) ?>" class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                      Ya, Hapus
                                  </a>
                              </div>
                            </div>
                        </div>
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

<?= $this->endSection() ?>