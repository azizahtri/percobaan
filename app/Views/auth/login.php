<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Cartenz Technology</title>

  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/css/style.css') ?>">
  <link rel="shortcut icon" href="<?= base_url('skydash/dist/assets/images/favicon.png') ?>" />

  <style>
    /* Custom Styles untuk Halaman Login */
    body {
        font-family: 'Nunito', sans-serif;
    }
    
    .content-wrapper {
        /* Gradient Background Biru Profesional */
        background: linear-gradient(135deg, #1F3BB3 0%, #4b6cb7 100%);
        min-height: 100vh;
    }

    .auth-form-light {
        border-radius: 20px; /* Sudut lebih bulat */
        box-shadow: 0 15px 35px rgba(0,0,0,0.2); /* Bayangan lembut */
        background: #ffffff;
        padding: 40px 50px !important; /* Padding lebih lega */
    }

    .brand-logo img {
        width: 180px; /* Logo lebih besar */
        margin-bottom: 20px;
    }

    h4 {
        color: #1F3BB3;
        font-weight: 800;
        margin-bottom: 5px;
    }

    h6.font-weight-light {
        color: #6c757d;
        margin-bottom: 30px;
        font-size: 0.95rem;
    }

    .form-control {
        border-radius: 10px;
        padding: 25px 20px; /* Input lebih tinggi */
        border: 1px solid #e0e0e0;
        background-color: #fcfcfc;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #1F3BB3;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(31, 59, 179, 0.1);
    }

    .auth-form-btn {
        border-radius: 10px;
        padding: 12px;
        font-size: 1rem;
        font-weight: 700;
        background: #1F3BB3;
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .auth-form-btn:hover {
        background: #162d8a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(31, 59, 179, 0.3);
    }

    /* Animasi Fade In */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-entry {
        animation: fadeInUp 0.8s ease-out;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            
            <div class="auth-form-light text-center py-5 px-4 px-sm-5 animate-entry">
              
              <div class="brand-logo mb-4">
                <img src="<?= base_url('FlexStart/assets/img/cartenz-logo.png') ?>" alt="logo">
              </div>
              
              <h4>Selamat Datang!</h4>
              <h6 class="font-weight-light">Masuk ke Dashboard Admin Cartenz Tech.</h6>
              
              <form class="pt-3" action="<?= base_url('login') ?>" method="post">
                
                <div class="form-group mb-3 position-relative">
                  <input type="text" name="username" class="form-control form-control-lg" placeholder="Masukkan Username Anda" required>
                  <i class="mdi mdi-account-outline" style="position: absolute; top: 50%; right: 20px; transform: translateY(-50%); color: #ccc; font-size: 1.2rem;"></i>
                </div>
                
                <div class="form-group mb-4 position-relative">
                  <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan Password Anda" required>
                  <i class="mdi mdi-lock-outline" style="position: absolute; top: 50%; right: 20px; transform: translateY(-50%); color: #ccc; font-size: 1.2rem;"></i>
                </div>
                
                <div class="mt-3 d-grid gap-2">
                  <button type="submit" class="btn btn-primary btn-lg font-weight-medium auth-form-btn">
                    MASUK SEKARANG
                  </button>
                </div>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger mt-4 d-flex align-items-center justify-content-center border-0 shadow-sm" style="border-radius: 10px; font-size: 0.9rem;">
                        <i class="mdi mdi-alert-circle me-2 fs-5"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

              </form>
            </div>

          </div>
        </div>
      </div>
      </div>
    </div>

  <script src="<?= base_url('skydash/dist/assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/template.js') ?>"></script>

</body>
</html>