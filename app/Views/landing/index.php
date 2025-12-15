<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= $title ?? 'Karir & Lowongan' ?></title>
  <meta name="description" content="Portal Lowongan Kerja dan Rekrutmen">
  <meta name="keywords" content="loker, karir, rekrutmen">

  <link href="<?= base_url('FlexStart/assets/img/logo-me.jpeg') ?>" rel="icon">
  <link href="<?= base_url('FlexStart/assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link href="<?= base_url('FlexStart/assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('FlexStart/assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?= base_url('FlexStart/assets/vendor/aos/aos.css') ?>" rel="stylesheet">
  <link href="<?= base_url('FlexStart/assets/vendor/glightbox/css/glightbox.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('FlexStart/assets/vendor/swiper/swiper-bundle.min.css') ?>" rel="stylesheet">

  <link href="<?= base_url('FlexStart/assets/css/main.css') ?>" rel="stylesheet">

  <style>
      /* --- 1. KONFIGURASI NAVBAR TRANSPARAN KE BIRU --- */
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

      /* Class ini akan ditambahkan via JS saat scroll */
      #header.header-scrolled {
          background:  rgba(255, 255, 255, 0.95);
          padding: 20px 0;
          box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1);
          border-radius: 50px;  
          padding: 12px 30px;                    /* Padding Besar (Awal) */
          max-width: 90%;                        /* Lebar Awal */
          margin: 0 auto;
          margin-top: 5px;
      }

      /* Warna Teks Menu */
      .navmenu a, .navmenu a:focus {
          color: #012970; /* Biru Tua agar kontras */
          font-weight: 600;
      }

      /* --- 2. KONFIGURASI LOGO BESAR --- */
      .logo img {
          max-height: 50px;
          
      }
      

      /* --- 3. KONFIGURASI BACKGROUND HERO --- */
      #hero {
          width: 100%;
          height: 100vh; /* Full layar */
          background: url('FlexStart/assets/img/hero-pic.jpg') top center;
          background-size: cover;
          position: relative;
          padding: 0;
          margin-bottom: 0; /* Hapus margin bawah default */
      }

      /* Overlay Putih Transparan agar teks terbaca */
      #hero:before {
          content: "";
          background: rgba(255, 255, 255, 0.6); /* Putih transparansi 60% */
          position: absolute;
          bottom: 0;
          top: 0;
          left: 0;
          right: 0;
      }

      /* Agar konten berada di atas overlay */
      #hero .container {
          position: relative;
          z-index: 2;
          padding-top: 80px; /* Jarak dari atas karena navbar fixed */
      }
      
      /* Jarak section lowongan */
      #lowongan {
          padding-top: 80px;
      }

      .dev-alert-container {
        position: fixed;
        bottom: 20px;          /* Jarak dari bawah */
        left: 50%;
        transform: translateX(-50%); /* Posisi tengah */
        z-index: 99999;        /* Pastikan di atas semua elemen */
        width: 90%;
        max-width: 600px;
        animation: slideUp 0.5s ease-out;
    }

    .dev-alert-content {
        background-color: #fff3cd; /* Warna Kuning Soft */
        color: #856404;            /* Warna Teks Coklat Gelap */
        border: 1px solid #ffeeba;
        border-left: 5px solid #ffc107; /* Aksen Kuning Tebal */
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 14px;
    }

    .dev-icon {
        font-size: 20px;
        margin-right: 15px;
    }

    .dev-close-btn {
        background: transparent;
        border: none;
        font-size: 24px;
        line-height: 1;
        color: #856404;
        cursor: pointer;
        margin-left: 15px;
        opacity: 0.6;
        transition: 0.2s;
    }

    .dev-close-btn:hover {
        opacity: 1;
    }

    /* Animasi Muncul */
    @keyframes slideUp {
        from { bottom: -100px; opacity: 0; }
        to { bottom: 20px; opacity: 1; }
    }

    /* Responsif untuk HP */
    @media (max-width: 600px) {
        .dev-alert-container {
            width: 95%;
            bottom: 10px;
        }
    }
  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="<?= base_url('/') ?>" class="logo d-flex align-items-center me-auto">
        <img src="<?= base_url('FlexStart/assets/img/cartenz-logo.png') ?>" alt="logo">
        <span class="d-none d-lg-block ms-2 text-dark fw-bold">Cartenz Technology</span>
        </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Beranda</a></li>
          <li><a href="#lowongan">Lowongan</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
    <div id="devAlert" class="dev-alert-container">
      <div class="dev-alert-content">
          <span class="dev-icon">🚧</span>
          <span>
              <strong>Development Mode:</strong> Website ini sedang dalam tahap pengembangan & pengujian data.
          </span>
          <button onclick="closeDevAlert()" class="dev-close-btn" title="Tutup">&times;</button>
      </div>
    </div>
  </header>

  <main class="main">

    <section id="hero" class="hero section d-flex align-items-center">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h1 data-aos="fade-up" style="color: #0d2c63ff; font-weight: 800;">Temukan Karir Impianmu Bersama Kami</h1>
            <p data-aos="fade-up" data-aos-delay="100" style="color: #333333ff; font-size: 18px;">
              Bergabunglah dengan tim profesional Cartenz Technology. Kami mencari talenta terbaik untuk berkembang dan sukses bersama.
            </p>
            <!-- <div class="d-flex flex-column flex-md-row" data-aos="fade-up" data-aos-delay="200">
              <a href="#lowongan" class="btn-get-started">Lihat Lowongan <i class="bi bi-arrow-down"></i></a>
            </div> -->
          </div>
          
          <!-- <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out">
            <img src="<?= base_url('FlexStart/assets/img/hero-img.png') ?>" class="img-fluid animated" alt="">
          </div> -->
        </div>
      </div>
    </section>

    <section id="lowongan" class="recent-posts section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Karir</h2>
        <p>Posisi Terbaru yang Tersedia</p>
      </div>

      <div class="container">

        <?php if(empty($lowongan)): ?>
            <?php else: ?>

            <div id="lowongan-container" class="row gy-5">
              <?php 
                $countOpen = 0;
                foreach ($lowongan as $i => $l): 
                    // FILTER: Hanya tampilkan yang OPEN dan Belum Lewat Tanggal Selesai
                    $today = date('Y-m-d');
                    if ($l['status'] !== 'open' || $l['tanggal_selesai'] < $today) {
                        continue; // Skip loop ini (jangan tampilkan)
                    }
                    $countOpen++;

                    $jenis = strtolower($l['jenis'] ?? 'full time');
                    $badgeClass = match ($jenis) {
                        'magang' => 'bg-warning text-dark',
                        'part time' => 'bg-info text-dark',
                        default => 'bg-success text-white',
                    };
              ?>
                
                <div class="col-xl-4 col-md-6 lowongan-item" data-aos="fade-up">
                  <div class="post-item position-relative h-100 shadow-sm rounded-3 overflow-hidden border">
                    <div class="post-content d-flex flex-column p-4 h-100">
                      
                      <div class="d-flex justify-content-between align-items-start mb-3">
                          <span class="badge <?= $badgeClass ?> rounded-pill text-uppercase" style="font-size: 0.75rem;">
                            <?= esc($jenis) ?>
                          </span>
                          <small class="text-danger fw-bold" style="font-size: 0.8rem;">
                            <i class="bi bi-calendar-x me-1"></i> Sampai <?= date('d M Y', strtotime($l['tanggal_selesai'])) ?>
                          </small>
                      </div>

                      <h3 class="post-title mb-3">
                          <a href="<?= site_url('lowongan/detail/' . $l['id']) ?>" class="text-decoration-none text-dark stretched-link">
                              <?= esc($l['judul_lowongan']) ?>
                          </a>
                      </h3>

                      <p class="text-muted flex-grow-1" style="font-size: 0.95rem; line-height: 1.6;">
                        <?= character_limiter(strip_tags($l['deskripsi']), 100) ?>
                      </p>

                      <hr class="my-4 text-muted opacity-25">
                      
                      <div class="d-flex align-items-center justify-content-between">
                        <span class="text-primary fw-bold" style="font-size: 0.9rem;">Lihat Detail</span>
                        <i class="bi bi-arrow-right text-primary"></i>
                      </div>

                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <?php if($countOpen == 0): ?>
                <div class="text-center py-5">
                    <i class="bi bi-emoji-frown text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-muted">Mohon maaf, saat ini belum ada posisi yang dibuka.</h4>
                </div>
            <?php endif; ?>

            <?php endif; ?>

      </div>
    </section>

  </main>

  <footer id="footer" class="footer">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-6 footer-about">
          <a href="<?= base_url('/') ?>" class="d-flex align-items-center">
            <span class="sitename">Cartenz Technology</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Jalan Teknologi No. 123</p>
            <p>Jakarta Selatan, Indonesia</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+62 812 3456 7890</span></p>
            <p><strong>Email:</strong> <span>hrd@company.com</span></p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <h4>Menu</h4>
            <ul class="list-unstyled">
                <li><a href="#hero">Beranda</a></li>
                <li><a href="#lowongan">Lowongan</a></li>
            </ul>
        </div>

        <div class="col-lg-4 col-md-12">
          <h4>Ikuti Kami</h4>
          <p>Dapatkan informasi terbaru mengenai lowongan kerja melalui sosial media kami.</p>
          <div class="social-links d-flex">
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
          </div>
        </div>

      </div>
    </div>
    <div class="container copyright text-center mt-4">
      <p>© <?= date('Y') ?> <strong>Cartenz Technology</strong>. All Rights Reserved</p>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="<?= base_url('FlexStart/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('FlexStart/assets/vendor/aos/aos.js') ?>"></script>
  <script src="<?= base_url('FlexStart/assets/vendor/glightbox/js/glightbox.min.js') ?>"></script>
  <script src="<?= base_url('FlexStart/assets/vendor/purecounter/purecounter_vanilla.js') ?>"></script>
  <script src="<?= base_url('FlexStart/assets/vendor/swiper/swiper-bundle.min.js') ?>"></script>
  <script src="<?= base_url('FlexStart/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') ?>"></script>
  <script src="<?= base_url('FlexStart/assets/vendor/isotope-layout/isotope.pkgd.min.js') ?>"></script>

  <script src="<?= base_url('FlexStart/assets/js/main.js') ?>"></script>

  <!-- JS untuk mengubah warna navbar saat di-scroll -->
  <script>
      
      const selectHeader = document.querySelector('#header');
      if (selectHeader) {
        const headerScrolled = () => {
          if (window.scrollY > 80) { // Jika scroll lebih dari 80px
            selectHeader.classList.add('header-scrolled');
          } else {
            selectHeader.classList.remove('header-scrolled');
          }
        }
        window.addEventListener('load', headerScrolled);
        document.addEventListener('scroll', headerScrolled);
      }
      
      // Pagination Logic (Sama seperti sebelumnya)
      document.addEventListener("DOMContentLoaded", function() {
          const items = document.querySelectorAll(".lowongan-item");
          if (items.length === 0) return;
          const perPage = 6; 
          let currentPage = 0;
          const prevBtn = document.getElementById("prevBtn");
          const nextBtn = document.getElementById("nextBtn");
          
          if(!prevBtn || !nextBtn) return;

          function showPage(page) {
            items.forEach((item, index) => {
              const shouldShow = index >= page * perPage && index < (page + 1) * perPage;
              if (shouldShow) { item.classList.remove("d-none"); item.classList.add("aos-animate"); } 
              else { item.classList.add("d-none"); }
            });
            prevBtn.disabled = page === 0;
            nextBtn.disabled = (page + 1) * perPage >= items.length;
          }
          prevBtn.addEventListener("click", () => { if (currentPage > 0) { currentPage--; showPage(currentPage); document.getElementById('lowongan').scrollIntoView({ behavior: 'smooth' }); } });
          nextBtn.addEventListener("click", () => { if ((currentPage + 1) * perPage < items.length) { currentPage++; showPage(currentPage); document.getElementById('lowongan').scrollIntoView({ behavior: 'smooth' }); } });
          showPage(0);
      });
  </script>

<!-- Fungsi untuk menutup alert -->
<script>
    
    function closeDevAlert() {
        const alertBox = document.getElementById('devAlert');
        alertBox.style.display = 'none';
        
        // (Opsional) Simpan status di browser agar tidak muncul lagi setelah ditutup
        // localStorage.setItem('devAlertClosed', 'true'); 
    }

    // (Opsional) Cek apakah user pernah menutup alert
    // if(localStorage.getItem('devAlertClosed') === 'true') {
    //     document.getElementById('devAlert').style.display = 'none';
    // }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
      <?php if (session()->getFlashdata('success')) : ?>
          Swal.fire({
            icon: 'success',
            title: 'Lamaran Terkirim!',
            text: '<?= session()->getFlashdata('success') ?>',
            confirmButtonColor: '#4154f1',
            confirmButtonText: 'OK'
          });
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')) : ?>
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#d33'
          });
      <?php endif; ?>
  </script>
</body>
</html>