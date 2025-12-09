<?php 
  $uri = service('uri'); 
  $segment1 = $uri->getSegment(1); // admin
  $segment2 = $uri->getSegment(2); // dashboard, alternatives, akun, dll
  
  // Ambil role dari session untuk pengecekan akses
  $userRole = session()->get('role'); 
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <li class="nav-item <?= ($segment1 === 'admin' && ($segment2 === '' || $segment2 === 'dashboard')) ? 'active' : '' ?>">
      <a class="nav-link" href="<?= site_url('admin/dashboard') ?>">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item <?= $segment2 === 'alternatives' ? 'active' : '' ?>">
      <a class="nav-link" href="<?= site_url('admin/alternatives') ?>">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">Alternatif (Staff)</span>
      </a>
    </li>

    <li class="nav-item <?= $segment2 === 'pekerjaan' ? 'active' : '' ?>">
      <a class="nav-link" href="<?= site_url('admin/pekerjaan') ?>">
        <i class="mdi mdi-database menu-icon"></i>
        <span class="menu-title">Pekerjaan</span>
      </a>
    </li>

    <li class="nav-item <?= $segment2 === 'criteria' ? 'active' : '' ?>">
      <a class="nav-link" href="<?= site_url('admin/criteria') ?>">
        <i class="icon-layout menu-icon"></i>
        <span class="menu-title">Kriteria (Kualifikasi)</span>
      </a>
    </li>

    <li class="nav-item <?= $segment2 === 'formulir' ? 'active' : '' ?>">
      <a class="nav-link" href="<?= site_url('admin/formulir') ?>">
          <i class="mdi mdi-file-document-edit menu-icon"></i>
          <span class="menu-title">Template Formulir</span>
      </a>
    </li>
    
    <li class="nav-item <?= $segment2 === 'lowongan' ? 'active' : '' ?>">
      <a class="nav-link" href="<?= site_url('admin/lowongan') ?>">
        <i class="icon-briefcase menu-icon"></i>
        <span class="menu-title">Kelola Lowongan</span>
      </a>
    </li>

    <li class="nav-item <?= $segment2 === 'data' ? 'active' : '' ?>">
      <a class="nav-link" href="<?= site_url('admin/data') ?>">
          <i class="icon-paper menu-icon"></i>
          <span class="menu-title">Data Pelamar</span>
      </a>
    </li>

  </ul>
</nav>