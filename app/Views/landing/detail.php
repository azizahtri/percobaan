<!DOCTYPE html>
<html lang="id">

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
  
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

  <style>
      body { 
        background-color: #f6f9ff; 
        font-family: 'Nunito', sans-serif; 
    }
      .page-content { 
        padding-top: 130px; 
        min-height: 85vh; 
    }

      #header {
          background:  rgba(255, 255, 255, 0.95);
          padding: 20px 0;
          box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1);
          border-radius: 50px;  
          padding: 12px 30px;                    
          max-width: 90%;              
          margin: 0 auto;
          margin-top: 5px;
      }

      #header.header-scrolled {
          background:  rgba(255, 255, 255, 0.95);
          padding: 20px 0;
          box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1);
          border-radius: 50px;  
          padding: 12px 30px; 
          max-width: 90%; 
          margin: 0 auto;
          margin-top: 5px;
      }

      .navmenu a, .navmenu a:focus {
          color: #012970;
          font-weight: 600;
      }

      .logo img {
          max-height: 50px;
          
      }

      .logo img { 
        max-height: 50px; 
    }
      
      .navmenu ul { 
        margin: 0; 
        padding: 0; 
        display: flex; 
        list-style: none; 
        align-items: center; 
    }
      
      @media (max-width: 991px) {
          .mobile-nav-toggle { 
            display: block; 
        }
          #header .container-fluid { 
            max-width: 95% !important; 
            padding: 10px 20px; 
        }
      }

      .card-job { 
        border: none; 
        border-radius: 15px; 
        box-shadow: 0 10px 30px rgba(1, 41, 112, 0.08); 
        background: #fff; 
    }
      .modal-content { 
        border: none; 
        border-radius: 15px; 
        overflow: hidden; 
    }
      .modal-header { 
        border-bottom: 1px solid #eee; 
        background-color: #f9fafb; 
    }
      .form-control:focus, .form-select:focus { 
        box-shadow: none; 
        border-color: #4154f1; 
    }
      .hover-scale:hover { 
        transform: scale(1.02); 
        transition: 0.3s; 
    }
  </style>
</head>

<body>

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="<?= base_url('/') ?>" class="logo d-flex align-items-center me-auto">
        <img src="<?= base_url('FlexStart/assets/img/cartenz-logo.png') ?>" alt="logo">
        <span class="d-none d-lg-block ms-2 text-dark fw-bold">Cartenz Technology</span>
        </a>

      <nav id="navmenu" class="navmenu">
        <ul>
            <li><a href="<?= base_url('/') ?>" class="text-dark fw-bold">Beranda</a></li>
            <li><a href="<?= base_url('/#lowongan') ?>" class="text-primary fw-bold">Lowongan</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="page-content">
    <section class="container pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-job p-4 p-md-5">
              
              <div class="text-center mb-4">
                  <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill fw-bold">
                      <?= esc(strtoupper($lowongan['jenis'])) ?>
                  </span>
                  <h1 class="fw-bold text-dark mb-3 display-6"><?= esc($lowongan['judul_lowongan']) ?></h1>
                  
                  <div class="d-inline-block bg-light px-4 py-2 rounded-pill border">
                    <small class="text-muted fw-bold text-uppercase me-2" style="font-size: 0.7rem;">Periode</small>
                    <span class="text-dark fw-bold">
                        <i class="bi bi-calendar-event me-1"></i> 
                        <?= date('d M Y', strtotime($lowongan['tanggal_mulai'])) ?> 
                        <span class="mx-1 text-muted">-</span> 
                        <span class="text-danger"><?= date('d M Y', strtotime($lowongan['tanggal_selesai'])) ?></span>
                    </span>
                  </div>
              </div>

              <?php 
                $today = date('Y-m-d');
                $isClosed = ($lowongan['status'] == 'closed' || $today > $lowongan['tanggal_selesai']);
              ?>

              <?php if ($isClosed): ?>
                <div class="alert alert-warning border-0 shadow-sm text-center mb-4">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <strong>Mohon Maaf, lowongan ini sudah ditutup.</strong>
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
                <?php if ($isClosed): ?>
                    <button type="button" class="btn btn-secondary btn-lg px-5 py-3 rounded-pill shadow-none fw-bold" disabled>
                      <i class="bi bi-lock-fill me-2"></i> Pendaftaran Ditutup
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow fw-bold hover-scale" data-bs-toggle="modal" data-bs-target="#modalLamaran">
                      <i class="bi bi-send-fill me-2"></i> Lamar Sekarang
                    </button>
                <?php endif; ?>
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
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-0 shadow-lg">
        
        <div class="modal-header py-3 px-4 bg-primary text-white">
          <div>
              <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-person me-2"></i>Formulir Lamaran Kerja</h5>
              <p class="mb-0 small opacity-75">Posisi: <strong><?= esc($lowongan['judul_lowongan']) ?></strong></p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4 bg-light">
            <form action="<?= base_url('lamaran/submit') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id_lowongan" value="<?= $lowongan['id'] ?>">
                
                <?php if (!empty($lowongan['link_google_form'])): ?>
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4">
                        <i class="bi bi-google fs-3 me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Wajib Mengisi Google Form</h6>
                            <p class="mb-2 small">Lowongan ini mewajibkan pelamar untuk mengisi formulir eksternal berikut:</p>
                            <a href="<?= esc($lowongan['link_google_form']) ?>" target="_blank" class="btn btn-sm btn-light text-primary fw-bold border">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Google Form
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <div class="col-lg-6">
                         <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3 fw-bold text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i>Data Identitas
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nomor KTP (NIK) <span class="text-danger">*</span></label>
                                    <input type="text" name="no_ktp" class="form-control" required placeholder="16 Digit NIK" pattern="\d{16}" minlength="16" maxlength="16">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" class="form-control" required>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" name="tempat_lahir" class="form-control" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_lahir" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" class="form-select" required>
                                            <option value="">- Pilih -</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Status <span class="text-danger">*</span></label>
                                        <select name="status_pernikahan" class="form-select" required>
                                            <option value="">- Pilih -</option>
                                            <option value="Belum Menikah">Belum Menikah</option>
                                            <option value="Menikah">Menikah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea name="alamat" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>
                         </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-white py-3 fw-bold text-primary">
                                <i class="bi bi-envelope-paper me-2"></i>Kontak
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Email Aktif <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required placeholder="contoh@email.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">No HP (WhatsApp) <span class="text-danger">*</span></label>
                                    <input type="number" name="no_hp" class="form-control" required placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                             <div class="card-header bg-white py-3 fw-bold text-primary">
                                <i class="bi bi-file-earmark-arrow-up me-2"></i>Berkas Lamaran
                            </div>
                             <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">CV (PDF Max 2MB) <span class="text-danger">*</span></label>
                                    <input type="file" name="file_cv" class="form-control" accept=".pdf" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Foto Profil (JPG/PNG) <span class="text-danger">*</span></label>
                                    <input type="file" name="foto_profil" class="form-control" accept=".jpg,.jpeg,.png" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Link Google Drive (Portofolio) <span class="text-danger">*</span></label>
                                    <input type="url" name="link_drive" class="form-control" required placeholder="https://drive.google.com/...">
                                </div>
                             </div>
                        </div>
                    </div>
                </div>

                <?php 
                    $formConfig = json_decode($lowongan['form_config'] ?? '[]', true);
                ?>
                
                <?php if (!empty($formConfig)): ?>
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white py-3 fw-bold text-dark border-bottom">
                            <i class="bi bi-list-check me-2 text-primary"></i>Pertanyaan Kualifikasi
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php foreach($formConfig as $q): ?>
                                    <div class="col-12">
                                        <?php 
                                            // Normalisasi tipe data (lowercase) agar aman
                                            $type = strtolower($q['type']);
                                            
                                            // Tentukan Wajib/Tidak (Textarea = Opsional)
                                            $isWajib = ($type != 'textarea'); 
                                        ?>
                                        
                                        <label class="form-label small fw-bold">
                                            <?= esc($q['label']) ?> 
                                            <?php if($isWajib): ?>
                                                <span class="text-danger">*</span>
                                            <?php else: ?>
                                                <span class="text-muted fw-normal small">(Opsional)</span>
                                            <?php endif; ?>
                                        </label>

                                        <?php if($type == 'text'): ?>
                                            <input type="text" name="jawaban[<?= esc($q['label']) ?>]" class="form-control" required>

                                        <?php elseif($type == 'number'): ?>
                                            <input type="number" name="jawaban[<?= esc($q['label']) ?>]" class="form-control" required>

                                        <?php elseif($type == 'textarea'): ?>
                                            <textarea name="jawaban[<?= esc($q['label']) ?>]" class="form-control" rows="3" placeholder="Tulis jawaban Anda di sini..."></textarea>

                                        <?php elseif($type == 'dropdown'): ?>
                                            <select name="jawaban[<?= esc($q['label']) ?>]" class="form-control" required>
                                                <option value="">-- Pilih Jawaban --</option>
                                                <?php foreach(explode(',', $q['options']) as $opt): ?>
                                                    <option value="<?= esc(trim($opt)) ?>"><?= esc(trim($opt)) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        <?php elseif($type == 'radio'): ?>
                                            <div class="mt-1">
                                                <?php foreach(explode(',', $q['options']) as $opt): 
                                                    $val = trim($opt);
                                                    $radId = 'rad_' . md5($q['label'] . $val); 
                                                ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" 
                                                               name="jawaban[<?= esc($q['label']) ?>]" 
                                                               value="<?= esc($val) ?>" 
                                                               id="<?= $radId ?>" required>
                                                        <label class="form-check-label" for="<?= $radId ?>" style="cursor:pointer;">
                                                            <?= esc($val) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                        <?php elseif($type == 'checkbox'): ?>
                                            <div class="mt-1 border rounded p-3 bg-white">
                                                <?php foreach(explode(',', $q['options']) as $opt): 
                                                    $val = trim($opt);
                                                    $chkId = 'chk_' . md5($q['label'] . $val); 
                                                ?>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="jawaban[<?= esc($q['label']) ?>][]" 
                                                               value="<?= esc($val) ?>" 
                                                               id="<?= $chkId ?>">
                                                        <label class="form-check-label" for="<?= $chkId ?>" style="cursor:pointer;">
                                                            <?= esc($val) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="form-text small text-muted">Anda dapat memilih lebih dari satu.</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-grid mt-4 pt-2">
                    <button type="submit" class="btn btn-primary py-3 fw-bold rounded-pill shadow-lg hover-scale">
                        <i class="bi bi-send-fill me-2"></i> KIRIM LAMARAN SEKARANG
                    </button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= base_url('FlexStart/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  
  <script src="<?= base_url('FlexStart/assets/js/main.js') ?>"></script> 
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
      // ALERT SUKSES
      <?php if (session()->getFlashdata('success')) : ?>
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success') ?>',
            confirmButtonColor: '#4154f1',
            confirmButtonText: 'Mantap!',
            timer: 5000
          });
      <?php endif; ?>

      // ALERT ERROR
      <?php if (session()->getFlashdata('error')) : ?>
          Swal.fire({
            icon: 'error',
            title: 'Gagal Mengirim Lamaran',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Tutup'
          });
          // Buka modal lagi jika error validasi (kecuali blacklist/spam)
          <?php if(!str_contains(session()->getFlashdata('error'), 'Blacklist') && !str_contains(session()->getFlashdata('error'), 'sudah melamar')): ?>
              var myModal = new bootstrap.Modal(document.getElementById('modalLamaran'));
              myModal.show();
          <?php endif; ?>
      <?php endif; ?>

      // NAVBAR SCROLL
      const selectHeader = document.querySelector('#header');
      if (selectHeader) {
        const headerScrolled = () => {
          if (window.scrollY > 50) { selectHeader.classList.add('header-scrolled'); } 
          else { selectHeader.classList.remove('header-scrolled'); }
        }
        window.addEventListener('load', headerScrolled);
        document.addEventListener('scroll', headerScrolled);
      }
  </script>


</body>
</html>