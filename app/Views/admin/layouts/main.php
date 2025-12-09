<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Admin Dashboard' ?></title>

  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/feather/feather.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/ti-icons/css/themify-icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('skydash/dist/assets/js/select.dataTables.min.css') ?>">

  <link rel="stylesheet" href="<?= base_url('skydash/dist/assets/css/style.css') ?>">
  <link rel="shortcut icon" href="<?= base_url('skydash/dist/assets/images/favicon.png') ?>" />

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <link rel="stylesheet" href="<?= base_url('assets/custom-admin.css') ?>">

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

  <script src="<?= base_url('skydash/dist/assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('skydash/dist/assets/js/template.js') ?>"></script>
  
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  
  <button onclick="topFunction()" id="myBtn" title="Kembali ke atas">
    <i class="mdi mdi-arrow-up"></i>
  </button>
  
  <script>
    $(document).ready(function() {
        // Hancurkan datatable lama jika ada (mencegah duplikasi error)
        if ($.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable().destroy();
        }

        // Inisialisasi Baru
        $('.datatable').DataTable({
            responsive: true,
            language: {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": ">",
                    "previous": "<"
                },
                "zeroRecords": "Tidak ada data yang ditemukan",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(disaring dari _MAX_ total data)"
            }
        });
    });

    // Scroll to Top Logic
    var mybutton = document.getElementById("myBtn");
    window.onscroll = function() {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            if(mybutton) mybutton.style.display = "block";
        } else {
            if(mybutton) mybutton.style.display = "none";
        }
    };
    function topFunction() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
    
    // Aktifkan Tooltip Bootstrap 5
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        })
  </script>

</body>
</html>