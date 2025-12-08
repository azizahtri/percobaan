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
    
    <?= $this->include('admin/layouts/navbar') ?> 
    
    <div class="container-fluid page-body-wrapper">
      
      <?= $this->include('admin/layouts/sidebar') ?>
      
        <div class="main-panel">
          <div class="content-wrapper">
            <?= $this->renderSection('content') ?>
          </div>
          
          <?= $this->include('admin/layouts/footer') ?>

        </div>
         
    </div> 
  </div> 

  <!-- Skydash JS -->
  <script src="<?= base_url('skydash/dist/assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/misc.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/vendors/chart.js/chart.umd.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/vendors/datatables.net/jquery.dataTables.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/dataTables.select.min.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/template.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/settings.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/todolist.js')?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/jquery.cookie.js')?>" type="text/javascript"></script>
  <script src="<?= base_url('skydash/dist/assets/js/dashboard.js')?>"></script>

  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<button onclick="topFunction()" id="myBtn" title="Kembali ke atas">
      <i class="mdi mdi-arrow-up"></i>
  </button>

  <style>
      #myBtn {
          display: none; /* Sembunyikan default */
          position: fixed; /* Tetap di tempat saat scroll */
          bottom: 30px; /* Jarak dari bawah */
          right: 30px; /* Jarak dari kanan */
          z-index: 9999; /* Pastikan di atas elemen lain */
          border: none;
          outline: none;
          background-color: #4B49AC; /* Warna Utama Skydash */
          color: white;
          cursor: pointer;
          width: 50px;
          height: 50px;
          border-radius: 50%; /* Membuat bulat */
          font-size: 20px;
          box-shadow: 0 4px 8px rgba(0,0,0,0.3); /* Bayangan */
          transition: all 0.3s ease;
      }

      #myBtn:hover {
          background-color: #3f3e91; /* Warna saat hover */
          transform: translateY(-3px); /* Efek naik dikit */
      }
  </style>

  <script>
      // Ambil elemen tombol
      var mybutton = document.getElementById("myBtn");

      // Saat user scroll ke bawah 200px, munculkan tombol
      window.onscroll = function() {scrollFunction()};

      function scrollFunction() {
          if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
              mybutton.style.display = "block";
          } else {
              mybutton.style.display = "none";
          }
      }

      // Saat tombol diklik, scroll ke atas dengan mulus
      function topFunction() {
          window.scrollTo({top: 0, behavior: 'smooth'});
      }
  </script>

</body>
</html>
</body>
</html>
