<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $title ?? 'Admin Dashboard' ?></title>

  <!-- Skydash CSS -->
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/feather/feather.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/ti-icons/css/themify-icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/ti-icons/css/themify-icons.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('skydash/dist/assets/js/select.dataTables.min.css') ?>">

  
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/css/style.css') ?>">

  <link rel="shortcut icon" href="<?= base_url('skydash/dist/assets/images/favicon.png') ?>" />

</head>

 <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo align-items-center">
                  <img src="skydash/dist/assets/images/logo-hi.jpeg" alt="logo">
                </div>
                <h4>Hai!</h4>
                <h6 class="font-weight-light">Masuk dulu ya</h6>
                <form class="pt-3" method="post" action="<?= base_url('login') ?>">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control form-control-lg"
                              placeholder="Masukkan Username" required>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-lg"
                              placeholder="Masukkan Password" required>
                    </div>

                    <div class="mt-3 d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg font-weight-medium auth-form-btn">
                            Masuk
                        </button>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger mt-3">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>

 <!-- Skydash JS -->
  <script src="<?= base_url('skydash/dist/assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/misc.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/dashboard.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/vendors/chart.js/chart.umd.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/vendors/datatables.net/jquery.dataTables.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/dataTables.select.min.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/template.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/settings.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/todolist.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/jquery.cookie.js')?>" type="text/javascript"></script>


  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

</body>
</html>