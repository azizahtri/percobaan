<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= esc($lowongan['judul_lowongan']) ?> - Detail Karir</title>
  <link href="<?= base_url('FlexStart/assets/img/logo-me.jpeg') ?>" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= base_url('FlexStart/assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('FlexStart/assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?= base_url('FlexStart/assets/css/main.css') ?>" rel="stylesheet">
  
  <style>
      /* --- 1. GLOBAL STYLE --- */
      body { background-color: #f6f9ff; font-family: 'Nunito', sans-serif; }
      .page-content { padding-top: 130px; min-height: 85vh; }

      /* --- 2. NAVBAR KAPSUL (MODERN GLASS - SAMA SEPERTI INDEX) --- */
      #header {
          position: fixed;
          top: 20px; 
          left: 0; right: 0;
          z-index: 997;
          transition: all 0.5s ease-in-out;
          padding: 0;
          background: transparent !important; /* Paksa Transparan */
      }

      /* Container Navbar */
      #header .container-fluid {
          background: rgba(255, 255, 255, 0.9);
          backdrop-filter: blur(15px);
          -webkit-backdrop-filter: blur(15px);
          border-radius: 50px;
          padding: 12px 30px;
          max-width: 90%;
          margin: 0 auto;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
          border: 1px solid rgba(255, 255, 255, 0.5);
          transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      }

      /* Efek Scroll */
      #header.header-scrolled { top: 10px; }
      #header.header-scrolled .container-fluid {
          max-width: 85%;
          padding: 8px 25px;
          background: rgba(255, 255, 255, 0.95);
          box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      }

      /* Logo */
      .logo img { max-height: 50px; }
      

      /* Menu Link */
      .navmenu ul { margin: 0; padding: 0; display: flex; list-style: none; align-items: center; }
      
      .navmenu a {
          color: #012970;
          font-weight: 600;
          font-size: 15px;
          padding: 8px 20px;
          border-radius: 30px;
          transition: 0.3s;
          text-decoration: none;
      }
      
      .navmenu a:hover, .navmenu .active {
          color: #4154f1;
          background: rgba(65, 84, 241, 0.08);
      }

      /* Mobile Nav */
      .mobile-nav-toggle { color: #012970; font-size: 28px; cursor: pointer; display: none; }
      @media (max-width: 991px) {
          .mobile-nav-toggle { display: block; }
          #header .container-fluid { max-width: 95% !important; padding: 10px 20px; }
      }

      /* --- 3. CARD & MODAL STYLE --- */
      .card-job { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(1, 41, 112, 0.08); background: #fff; }
      .modal-content { border: none; border-radius: 15px; overflow: hidden; }
      .modal-header { border-bottom: 1px solid #eee; background-color: #f9fafb; }
      .form-control:focus { box-shadow: none; border-color: #4154f1; }
      .bg-light-input { background-color: #f8f9fa; border: 1px solid #e9ecef; }
  </style>
</head>

<body>

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?= base_url('/') ?>" class="logo d-flex align-items-center me-auto text-decoration-none">
        <img src="<?= base_url('FlexStart/assets/img/cartenz-logo.png') ?>" alt="logo">
        <span class="d-none d-lg-block ms-2 text-dark fw-bold">Cartenz Technology</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul class="d-flex list-unstyled m-0 gap-4">
          <li><a href="<?= base_url('/') ?>" class="text-dark fw-bold">Beranda</a></li>
          <li><a href="<?= base_url('/#lowongan') ?>" class="text-primary fw-bold">Lowongan</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="page-content">
    <div class="container mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-muted text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/#lowongan') ?>" class="text-muted text-decoration-none">Lowongan</a></li>
            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page"><?= esc($lowongan['judul_lowongan']) ?></li>
          </ol>
        </nav>
    </div>

    <section class="container pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-job p-4 p-md-5">
                  
                  <div class="text-center mb-5">
                      <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill fw-bold">
                          <?= esc(strtoupper($lowongan['jenis'])) ?>
                      </span>
                      <h1 class="fw-bold text-dark mb-3 display-6"><?= esc($lowongan['judul_lowongan']) ?></h1>
                      <div class="text-muted small">
                        <i class="bi bi-calendar-event me-1"></i> Diposting: <?= date('d M Y', strtotime($lowongan['tanggal_posting'])) ?>
                      </div>
                  </div>

                  <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                  <?php endif; ?>

                  <hr class="text-muted opacity-25 mb-4">

                  <div class="content mb-5">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Deskripsi Pekerjaan</h5>
                    <div class="text-secondary lh-lg" style="text-align: justify;">
                        <?= $lowongan['deskripsi'] ?> 
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="button" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow fw-bold" data-bs-toggle="modal" data-bs-target="#modalLamaran">
                      <i class="bi bi-send-fill me-2"></i> Lamar Sekarang
                    </button>
                    
                    <div class="mt-3">
                        <a href="<?= base_url('/#lowongan') ?>" class="text-decoration-none text-muted small">
                          <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Lowongan
                        </a>
                    </div>
                  </div>

            </div>
        </div>
      </div>
    </section>

  </main>

  <footer class="bg-white py-4 mt-auto border-top text-center">
    <div class="container">
      <p class="mb-0 text-muted small">© <?= date('Y') ?> <strong>Cartenz Technology</strong>. All Rights Reserved</p>
    </div>
  </footer>

  <div class="modal fade" id="modalLamaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        
        <div class="modal-header py-3 px-4">
          <div>
              <h5 class="modal-title fw-bold text-dark" id="modalLabel">Formulir Lamaran</h5>
              <p class="mb-0 small text-muted">Posisi: <strong class="text-primary"><?= esc($lowongan['judul_lowongan']) ?></strong></p>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body p-4">
            
            <?php if(!empty($lowongan['link_google_form'])): ?>
                <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center">
                    <i class="bi bi-google fs-4 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Formulir Eksternal Tersedia</h6>
                        <p class="small mb-1">Silahkan Mengisi untuk kebutuhan administrasi tambahan</p>
                        <a href="<?= $lowongan['link_google_form'] ?>" target="_blank" class="fw-bold text-decoration-underline">Buka Google Form</a>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('lamaran/submit') ?>" method="post">
                <input type="hidden" name="id_lowongan" value="<?= $lowongan['id'] ?>">

                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Data Diri</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control bg-light-input" placeholder="Sesuai KTP" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Nomor HP / WhatsApp</label>
                        <input type="number" name="no_hp" class="form-control bg-light-input" placeholder="08..." required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Email Aktif</label>
                        <input type="email" name="email" class="form-control bg-light-input" placeholder="contoh@email.com" required>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Berkas Lamaran</h6>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Link CV / Portfolio (Google Drive)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-success border-end-0"><i class="bi bi-google-drive"></i></span>
                        <input type="url" name="link" class="form-control bg-light-input border-start-0" placeholder="https://drive.google.com/..." required>
                    </div>
                    <div class="form-text text-danger small mt-1">
                        <i class="bi bi-info-circle me-1"></i> Pastikan akses link disetel ke <strong>"Anyone with the link"</strong>.
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Pesan Singkat</h6>
                <div class="mb-3">
                    <textarea name="pesan" class="form-control bg-light-input" rows="3" placeholder="Promosikan diri Anda secara singkat..."></textarea>
                </div>

                <?php 
                    // Fallback Logic
                    $jsonString = $lowongan['form_config'] ?? null;
                    $customQuestions = json_decode($jsonString, true);
                    if (!is_array($customQuestions)) $customQuestions = [];
                ?>

                <?php if (!empty($customQuestions)): ?>
                    <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Silahkan jawab pertanyaan di bawah ini terlebih dahulu</h6>
                    <div class="mb-4">
                        <?php foreach ($customQuestions as $item): ?>
                            <?php 
                               if (is_string($item)) { $label = $item; $type = 'text'; $opts = ''; } 
                               else { $label = $item['label']; $type = $item['type'] ?? 'text'; $opts = $item['options'] ?? ''; }
                            ?>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary"><?= esc($label) ?></label>
                                <?php if ($type == 'text'): ?>
                                    <input type="text" name="custom_answers[<?= esc($label) ?>]" class="form-control bg-light-input" required>
                                <?php elseif ($type == 'textarea'): ?>
                                    <textarea name="custom_answers[<?= esc($label) ?>]" class="form-control bg-light-input" rows="2" required></textarea>
                                <?php elseif ($type == 'radio'): ?>
                                    <div>
                                        <?php $options = explode(',', $opts); foreach($options as $opt): $opt = trim($opt); ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="custom_answers[<?= esc($label) ?>]" value="<?= esc($opt) ?>" required>
                                                <label class="form-check-label small"><?= esc($opt) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php elseif ($type == 'checkbox'): ?>
                                    <div>
                                        <?php $options = explode(',', $opts); foreach($options as $opt): $opt = trim($opt); ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="custom_answers[<?= esc($label) ?>][]" value="<?= esc($opt) ?>">
                                                <label class="form-check-label small"><?= esc($opt) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="d-grid mt-4 pt-2">
                    <button type="submit" class="btn btn-success py-3 fw-bold rounded-3 shadow-sm">
                        <i class="bi bi-paperplane-fill me-2"></i> Kirim Lamaran
                    </button>
                </div>

            </form>
        </div>

      </div>
    </div>
  </div>

  <script src="<?= base_url('FlexStart/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  
  <script>
      // Script Navbar Scroll (Sama seperti Index)
      const selectHeader = document.querySelector('#header');
      if (selectHeader) {
        const headerScrolled = () => {
          if (window.scrollY > 50) {
            selectHeader.classList.add('header-scrolled');
          } else {
            selectHeader.classList.remove('header-scrolled');
          }
        }
        window.addEventListener('load', headerScrolled);
        document.addEventListener('scroll', headerScrolled);
      }
  </script>

</body>
</html>