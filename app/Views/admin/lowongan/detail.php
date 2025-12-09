<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
          <h4 class="mb-0 fw-bold text-dark">Detail Lowongan</h4>
          <p class="text-muted small mb-0">Informasi lengkap dan daftar pelamar yang masuk.</p>
      </div>
      <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
        <i class="mdi mdi-arrow-left me-1"></i> Kembali
      </a>
  </div>

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 fw-bold text-primary">
            <i class="mdi mdi-briefcase-outline me-2"></i><?= esc($lowongan['judul_lowongan']) ?>
        </h5>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
            <?= esc(strtoupper($lowongan['jenis'])) ?>
        </span>
      </div>

      <div class="row mb-4">
          <div class="col-md-6 py-3 border-end">
              <div class="d-flex align-items-center mb-2">
                  <div class="bg-light p-2 rounded-circle me-3 text-secondary">
                      <i class="mdi mdi-account-tie fs-4"></i>
                  </div>
                  <div>
                      <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Posisi / Jabatan</small>
                      <h6 class="fw-bold mb-0 text-dark"><?= esc($lowongan['nama_pekerjaan']) ?></h6>
                  </div>
              </div>
          </div>
          <div class="col-md-6 py-3 ps-md-4">
              <div class="d-flex align-items-center mb-2">
                  <div class="bg-light p-2 rounded-circle me-3 text-secondary">
                      <i class="mdi mdi-calendar-clock fs-4"></i>
                  </div>
                  <div>
                      <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Tanggal Posting</small>
                      <h6 class="fw-bold mb-0 text-dark"><?= date('d F Y', strtotime($lowongan['tanggal_posting'])) ?></h6>
                  </div>
              </div>
          </div>
      </div>

      <div class="mb-3">
          <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Deskripsi Pekerjaan</small>
          <div class="border rounded p-3 bg-light position-relative mt-1">
              <div id="descContainer" style="max-height: 120px; overflow: hidden; transition: max-height 0.5s ease;">
                <?= $lowongan['deskripsi'] ?>
              </div>
              
              <div class="text-center mt-2 pt-2 border-top" id="toggleDescBtnContainer">
                  <button class="btn btn-sm btn-link text-decoration-none fw-bold" onclick="toggleDescription()">
                      <i class="mdi mdi-chevron-down"></i> Baca Selengkapnya
                  </button>
              </div>
          </div>
      </div>

      <?php if(!empty($lowongan['link_google_form'])): ?>
        <div class="mt-3">
            <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Link Google Form</small>
            <div class="d-flex align-items-center mt-1">
                <i class="mdi mdi-google-forms text-success fs-4 me-2"></i>
                <a href="<?= $lowongan['link_google_form'] ?>" target="_blank" class="text-primary text-decoration-none fw-bold text-break">
                    <?= esc($lowongan['link_google_form']) ?> <i class="mdi mdi-open-in-new ms-1"></i>
                </a>
            </div>
        </div>
      <?php endif; ?>

    </div>
  </div>


  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="mdi mdi-account-group-outline me-2 text-primary"></i>Daftar Pelamar Masuk
        </h5>
        <span class="badge bg-secondary text-white rounded-pill px-3">
            <?= count($pelamar) ?> Kandidat
        </span>
      </div>

      <?php if (!empty($pelamar)): ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle text-center datatable" style="width:100%">
            <thead class="bg-light text-secondary text-uppercase small">
              <tr>
                <th width="5%" class="text-center">No</th>
                <th class="text-start">Nama Pelamar</th>
                <th class="text-start">Kontak</th>
                <th class="text-center">Skor SPK</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($pelamar as $i => $p): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  
                  <td class="text-start">
                      <span class="d-block fw-bold text-dark"><?= esc($p['nama']) ?></span>
                      <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                          <?= esc($p['email']) ?>
                      </small>
                  </td>
                  
                  <td class="text-start">
                      <?php 
                        // Format WA
                        $hp = preg_replace('/[^0-9]/', '', $p['no_hp']);
                        if(substr($hp, 0, 1) == '0') $hp = '62' . substr($hp, 1);
                      ?>
                      <a href="https://wa.me/<?= $hp ?>" target="_blank" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none">
                          <i class="mdi mdi-whatsapp me-1"></i> <?= esc($p['no_hp']) ?>
                      </a>
                  </td>
                  
                  <td>
                    <?php if($p['spk_score'] > 0): ?>
                        <span class="fw-bold text-primary fs-6"><?= number_format($p['spk_score'], 4) ?></span>
                    <?php else: ?>
                        <span class="badge bg-light text-muted border">Belum Dinilai</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <?php 
                        $badgeClass = match($p['status']) {
                            'memenuhi' => 'bg-success text-white',
                            'tidak memenuhi' => 'bg-danger text-white',
                            default => 'bg-warning text-dark'
                        };
                    ?>
                    <span class="badge rounded-pill <?= $badgeClass ?> px-3">
                      <?= ucfirst($p['status'] ?: 'Proses') ?>
                    </span>
                  </td>

                  <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <?php if($p['is_history'] == 0): ?>
                            <a href="<?= base_url('admin/lowongan/pelamar/' . $p['id']) ?>" 
                               class="btn btn-action btn-action-edit" 
                               title="Proses Penilaian"
                               data-bs-toggle="tooltip" data-bs-placement="top">
                              <i class="mdi mdi-pencil-box-outline"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('admin/data/detail/' . $p['id']) ?>" 
                               class="btn btn-action btn-action-detail" 
                               title="Lihat Hasil Akhir"
                               data-bs-toggle="tooltip" data-bs-placement="top">
                              <i class="mdi mdi-eye"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php else: ?>
        <div class="text-center py-5 text-muted">
           <div class="opacity-50 mb-3"><i class="mdi mdi-account-off" style="font-size: 4rem;"></i></div>
           <h6 class="fw-bold">Belum Ada Pelamar</h6>
           <p class="small">Belum ada kandidat yang mendaftar untuk posisi ini.</p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script>
    function toggleDescription() {
        var container = document.getElementById("descContainer");
        var btn = document.querySelector("#toggleDescBtnContainer button");
        
        if (container.style.maxHeight === "120px") {
            container.style.maxHeight = "none"; // Buka penuh
            btn.innerHTML = '<i class="mdi mdi-chevron-up"></i> Tutup Deskripsi';
        } else {
            container.style.maxHeight = "120px"; // Tutup
            btn.innerHTML = '<i class="mdi mdi-chevron-down"></i> Baca Selengkapnya';
        }
    }

    // Cek jika konten pendek, sembunyikan tombol
    document.addEventListener("DOMContentLoaded", function() {
        var container = document.getElementById("descContainer");
        // Jika tinggi asli kurang dari batas, sembunyikan tombol
        if (container.scrollHeight <= 120) {
            document.getElementById("toggleDescBtnContainer").style.display = 'none';
            container.style.maxHeight = "none"; // Biarkan tampil penuh
            container.classList.remove('border', 'bg-light', 'p-3'); // Hapus border agar clean
        }
    });
</script>

<?= $this->endSection() ?>