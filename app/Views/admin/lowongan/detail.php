<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0 fw-bold"><?= esc($lowongan['judul_lowongan']) ?></h5>
      <span class="badge bg-light text-primary"><?= esc($lowongan['jenis']) ?></span>
    </div>
    <div class="card-body">
      
      <div class="row mb-3">
          <div class="col-md-6">
              <p class="mb-1 text-muted small">POSISI / JABATAN</p>
              <h6 class="fw-bold"><?= esc($lowongan['nama_pekerjaan']) ?></h6>
          </div>
          <div class="col-md-6">
              <p class="mb-1 text-muted small">TANGGAL POSTING</p>
              <h6 class="fw-bold"><?= date('d M Y', strtotime($lowongan['tanggal_posting'])) ?></h6>
          </div>
      </div>

      <p class="mb-1 text-muted small">DESKRIPSI PEKERJAAN</p>
      <div class="border rounded p-3 bg-light position-relative">
          
          <div id="descContainer" style="max-height: 100px; overflow: hidden; transition: max-height 0.5s ease;">
            <?= $lowongan['deskripsi'] ?>
          </div>
          
          <div class="text-center mt-2" id="toggleDescBtnContainer">
              <button class="btn btn-sm btn-link text-decoration-none" onclick="toggleDescription()">
                  <i class="mdi mdi-chevron-down"></i> Baca Selengkapnya
              </button>
          </div>

      </div>

      <?php if(!empty($lowongan['link_google_form'])): ?>
        <div class="mt-3">
            <p class="mb-1 text-muted small">LINK GOOGLE FORM</p>
            <a href="<?= $lowongan['link_google_form'] ?>" target="_blank" class="text-primary text-break">
                <i class="mdi mdi-link-variant me-1"></i> <?= esc($lowongan['link_google_form']) ?>
            </a>
        </div>
      <?php endif; ?>

    </div>
  </div>


  <div class="card shadow-sm border-0">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Daftar Pelamar Masuk</h5>
      <span class="badge bg-white text-secondary border"><?= count($pelamar) ?> Orang</span>
    </div>
    <div class="card-body">

      <?php if (!empty($pelamar)): ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Nama Pelamar</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Skor SPK</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($pelamar as $i => $p): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td class="fw-bold text-start"><?= esc($p['nama']) ?></td>
                  <td><?= esc($p['email']) ?></td>
                  <td><?= esc($p['no_hp']) ?></td>
                  
                  <td>
                    <?php if($p['spk_score'] > 0): ?>
                        <span class="fw-bold text-primary"><?= number_format($p['spk_score'], 4) ?></span>
                    <?php else: ?>
                        <span class="text-muted small">-</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <?php 
                        $badgeClass = match($p['status']) {
                            'memenuhi' => 'bg-success',
                            'tidak memenuhi' => 'bg-danger',
                            default => 'bg-warning text-dark'
                        };
                    ?>
                    <span class="badge rounded-pill <?= $badgeClass ?>">
                      <?= ucfirst($p['status'] ?: 'proses') ?>
                    </span>
                  </td>

                  <td>
                    <?php if($p['is_history'] == 0): ?>
                        <a href="<?= base_url('admin/lowongan/pelamar/' . $p['id']) ?>" class="btn btn-warning btn-sm text-white">
                          <i class="mdi mdi-pencil-box-outline"></i> Proses Nilai
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('admin/data/detail/' . $p['id']) ?>" class="btn btn-secondary btn-sm">
                          <i class="mdi mdi-eye"></i> Lihat Hasil
                        </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php else: ?>
        <div class="text-center py-5 text-muted">
           <i class="mdi mdi-account-off" style="font-size: 3rem; opacity: 0.5;"></i>
           <p class="mt-2">Belum ada pelamar untuk lowongan ini.</p>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <div class="mt-4 pb-5">
    <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-outline-secondary">
      <i class="mdi mdi-arrow-left"></i> Kembali ke Daftar Lowongan
    </a>
  </div>
</div>

<script>
    function toggleDescription() {
        var container = document.getElementById("descContainer");
        var btn = document.querySelector("#toggleDescBtnContainer button");
        
        if (container.style.maxHeight === "100px") {
            container.style.maxHeight = "none"; // Buka penuh
            btn.innerHTML = '<i class="mdi mdi-chevron-up"></i> Tutup Deskripsi';
        } else {
            container.style.maxHeight = "100px"; // Tutup
            btn.innerHTML = '<i class="mdi mdi-chevron-down"></i> Baca Selengkapnya';
        }
    }

    // Cek jika konten pendek, sembunyikan tombol
    document.addEventListener("DOMContentLoaded", function() {
        var container = document.getElementById("descContainer");
        if (container.scrollHeight <= 100) {
            document.getElementById("toggleDescBtnContainer").style.display = 'none';
        }
    });
</script>

<?= $this->endSection() ?>