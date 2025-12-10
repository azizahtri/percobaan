<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    
    <a class="navbar-brand brand-logo me-5" href="<?= site_url('admin/dashboard') ?>">
        <div class="d-flex align-items-center">
            <img src="<?= base_url('FlexStart/assets/img/cartenz-logo.png') ?>" class="me-2" alt="logo" style="height: 35px; width: auto;"/>
            <span class="fw-bold" style="color: #012970; font-size: 1.2rem; white-space: nowrap;">
                Cartenz Technology
            </span>
        </div>
    </a>

    <a class="navbar-brand brand-logo-mini" href="<?= site_url('admin/dashboard') ?>">
        <img src="<?= base_url('FlexStart/assets/img/cartenz-logo.png') ?>" alt="logo" style="height: 35px; width: auto; margin: 0 auto;"/>
    </a>

  </div>
  
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    
    <ul class="navbar-nav navbar-nav-right">
      
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
          <i class="icon-bell mx-0"></i>
          <span class="count" id="notif-badge" style="display:none;"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
          <p class="mb-0 font-weight-normal float-left dropdown-header">Notifikasi</p>
          
          <a class="dropdown-item preview-item" href="<?= base_url('admin/data') ?>">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-info">
                <i class="ti-user mx-0"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <h6 class="preview-subject font-weight-normal">Pelamar Baru</h6>
              <p class="font-weight-light small-text mb-0 text-muted">
                <span id="notif-count-text">0</span> orang menunggu review
              </p>
            </div>
          </a>
          
        </div>
      </li>
      
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown" id="profileDropdown">
          
          <span class="d-none d-lg-inline me-2 text-dark fw-bold" style="font-size: 0.9rem;">
            Hi, <?= esc(session()->get('name') ?? 'Admin') ?>
          </span>

          <img src="<?= base_url('skydash/dist/assets/images/faces/face1.jpg') ?>" alt="profile"/>
        </a>
        
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          
          <div class="dropdown-item disabled text-muted text-center border-bottom pb-2 mb-2 small">
            Role: <?= esc(session()->get('role')) ?>
          </div>

          <?php if (session()->get('role') === 'Super Admin'): ?>
            <a class="dropdown-item" href="<?= base_url('admin/akun') ?>">
                <i class="mdi mdi-account-cog text-primary"></i>
                Manajemen Akun
            </a>
            <div class="dropdown-divider"></div>
          <?php endif; ?>

          <a class="dropdown-item" href="<?= site_url('logout') ?>">
            <i class="ti-power-off text-primary"></i>
            Logout
          </a>
        </div>
      </li>
    </ul>
    
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>

<!-- js notifikasi -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    updateNotification(); 
    setInterval(updateNotification, 60000);
});

function updateNotification() {
    fetch('<?= base_url("admin/api/count-pelamar") ?>')
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        const badge = document.getElementById('notif-badge');
        const textCount = document.getElementById('notif-count-text');

        // Pastikan data.count ada dan valid
        const count = (data && data.count) ? parseInt(data.count) : 0;

        if(textCount) textCount.innerText = count;

        if (count > 0) {
            if(badge) {
                badge.style.display = 'block';
                badge.innerText = ""; 
            }
        } else {
            if(badge) badge.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Gagal memuat notifikasi:', error);
        // Default ke 0 jika error
        const textCount = document.getElementById('notif-count-text');
        if(textCount) textCount.innerText = "0";
    });
}
</script>