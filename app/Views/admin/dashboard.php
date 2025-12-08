<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-wrapper">
  
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Halo, <?= esc($user_name) ?>! 👋</h3>
          <h6 class="font-weight-normal mb-0">
            Sistem Pendukung Keputusan Rekrutmen berjalan lancar. 
            <?php if($stats['pending'] > 0): ?>
                Ada <span class="text-primary font-weight-bold"><?= $stats['pending'] ?> pelamar baru</span> menunggu review Anda!
            <?php else: ?>
                Semua data pelamar sudah diproses.
            <?php endif; ?>
          </h6>
        </div>
        <div class="col-12 col-xl-4">
           <div class="justify-content-end d-flex">
              <div class="bg-white p-2 rounded shadow-sm text-muted small">
                  <i class="mdi mdi-calendar me-1"></i> <?= date('d F Y') ?>
              </div>
           </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    
    <div class="col-md-3 mb-4 stretch-card transparent">
      <div class="card card-tale bg-primary text-white shadow-sm">
        <div class="card-body">
          <p class="mb-4">Total Lowongan</p>
          <p class="fs-30 mb-2 font-weight-bold"><?= $stats['lowongan'] ?></p>
          <p>Posisi Dibuka</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-4 stretch-card transparent">
      <div class="card card-dark-blue bg-info text-white shadow-sm">
        <div class="card-body">
          <p class="mb-4">Total Pelamar Masuk</p>
          <p class="fs-30 mb-2 font-weight-bold"><?= $stats['pelamar'] ?></p>
          <p>Orang Mendaftar</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-4 stretch-card transparent">
      <div class="card card-light-blue bg-success text-white shadow-sm">
        <div class="card-body">
          <p class="mb-4">Karyawan Direkrut</p>
          <p class="fs-30 mb-2 font-weight-bold"><?= $stats['karyawan'] ?></p>
          <p>Orang (Data Alternatif)</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-4 stretch-card transparent">
      <div class="card card-light-danger bg-warning text-white shadow-sm">
        <div class="card-body">
          <p class="mb-4">Perlu Review</p>
          <p class="fs-30 mb-2 font-weight-bold"><?= $stats['pending'] ?></p>
          <p>Pelamar Status 'Proses'</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    
    <div class="col-md-7 grid-margin stretch-card">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
             <p class="card-title mb-0">Pelamar Terbaru</p>
             <a href="<?= base_url('admin/data') ?>" class="text-primary small">Lihat Semua</a>
          </div>
          
          <div class="table-responsive">
            <table class="table table-hover table-striped table-borderless">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Posisi</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($recent_pelamar)): ?>
                    <?php foreach($recent_pelamar as $p): ?>
                        <tr>
                          <td class="font-weight-bold"><?= esc($p['nama']) ?></td>
                          <td class="text-muted small"><?= esc($p['judul_lowongan']) ?></td>
                          <td>
                            <?php if($p['status'] == 'proses'): ?>
                                <span class="badge badge-warning">Proses</span>
                            <?php elseif($p['status'] == 'memenuhi'): ?>
                                <span class="badge badge-success">Lolos</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Gagal</span>
                            <?php endif; ?>
                          </td>
                          <td>
                              <a href="<?= base_url('admin/lowongan/pelamar/'.$p['id']) ?>" class="btn btn-sm btn-outline-primary py-1 px-2">
                                  <i class="mdi mdi-eye"></i>
                              </a>
                          </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted">Belum ada pelamar.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-5 grid-margin stretch-card">
      <div class="card shadow-sm">
        <div class="card-body">
          <p class="card-title mb-3">Lowongan Terkini</p>
          <ul class="list-unstyled">
            <?php if(!empty($recent_jobs)): ?>
                <?php foreach($recent_jobs as $j): ?>
                <li class="d-flex align-items-center mb-4 pb-2 border-bottom">
                    
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-3 flex-shrink-0" 
                         style="width: 45px; height: 45px; font-size: 20px;">
                        <i class="mdi mdi-briefcase"></i>
                    </div>
                    
                    <div class="w-100 overflow-hidden"> <h6 class="mb-1 font-weight-bold text-truncate"><?= esc($j['judul_lowongan']) ?></h6>
                        <small class="text-muted d-block"><?= esc($j['jenis']) ?></small>
                        <small class="text-primary">Posted: <?= date('d M Y', strtotime($j['tanggal_posting'])) ?></small>
                    </div>
                    
                    <div class="ms-2">
                        <a href="<?= base_url('admin/lowongan/detail/'.$j['id']) ?>" class="btn btn-light btn-sm rounded-circle p-2">
                            <i class="mdi mdi-arrow-right"></i>
                        </a>
                    </div>
                </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-muted text-center py-4">Belum ada lowongan dibuka.</li>
            <?php endif; ?>
          </ul>
          
          <div class="mt-4">
              <a href="<?= base_url('admin/lowongan/create') ?>" class="btn btn-primary w-100">
                  <i class="mdi mdi-plus-circle me-1"></i> Buat Lowongan Baru
              </a>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>