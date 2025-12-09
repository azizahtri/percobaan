<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('LandingController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// ==================================================================
// 1. PUBLIC ROUTES (BEBAS AKSES)
// ==================================================================
$routes->get('/', 'LandingController::index');
$routes->get('lowongan/detail/(:num)', 'LandingController::detail/$1');
$routes->post('lamaran/submit', 'LandingController::submit');

// ==================================================================
// 2. AUTH ROUTES (LOGIN/LOGOUT)
// ==================================================================
// Penting: Jangan masukkan ini ke dalam group filter 'auth'!
$routes->get('login', 'AuthController::login'); 
$routes->post('login', 'AuthController::doLogin');
$routes->get('logout', 'AuthController::logout');

// ==================================================================
// 3. ADMIN ROUTES (DILINDUNGI FILTER)
// ==================================================================
// Semua yang ada di sini WAJIB LOGIN dulu
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    
    // --- DASHBOARD ---
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('api/count-pelamar', 'AdminController::countPelamar');

    // --- MANAJEMEN AKUN (Create/Edit/Hapus digabung di AdminController) ---
    $routes->get('akun', 'AdminController::akunIndex');          // List Akun
    $routes->get('akun/create', 'AdminController::akunCreate');  // Form Tambah
    $routes->post('akun/store', 'AdminController::akunStore');   // Proses Simpan
    $routes->get('akun/edit/(:num)', 'AdminController::akunEdit/$1'); // Form Edit
    $routes->post('akun/update/(:num)', 'AdminController::akunUpdate/$1'); // Proses Update
    $routes->get('akun/delete/(:num)', 'AdminController::akunDelete/$1');
    
    // --------------------------------------------------------
    // MASTER DATA: PEKERJAAN (DIVISI & POSISI)
    // --------------------------------------------------------
    $routes->get('pekerjaan', 'PekerjaanController::index'); 
    $routes->get('pekerjaan/create', 'PekerjaanController::create'); // Create Divisi Baru
    $routes->get('pekerjaan/create/(:any)', 'PekerjaanController::create/$1'); // Create Posisi di Divisi tertentu
    $routes->post('pekerjaan/store', 'PekerjaanController::store');
    
    // Detail & Edit
    $routes->get('pekerjaan/detail/(:any)', 'PekerjaanController::detailDivisi/$1'); // Lihat isi Divisi
    $routes->get('pekerjaan/edit/(:num)', 'PekerjaanController::edit/$1'); // Halaman Edit (jika pakai view terpisah)
    
    // Aksi Update (Modal)
    $routes->post('pekerjaan/updateDivisi', 'PekerjaanController::updateDivisi');
    $routes->post('pekerjaan/updatePosisi/(:num)', 'PekerjaanController::updatePosisi/$1');
    
    // Delete
    $routes->get('pekerjaan/delete/(:num)', 'PekerjaanController::delete/$1');


    // --------------------------------------------------------
    // MASTER DATA: KRITERIA & SUBKRITERIA
    // --------------------------------------------------------
    // Criteria
    $routes->get('criteria', 'CriteriaController::index');
    $routes->get('criteria/create', 'CriteriaController::create');
    $routes->post('criteria/store', 'CriteriaController::store');
    $routes->get('criteria/edit/(:num)', 'CriteriaController::edit/$1');
    $routes->post('criteria/update/(:num)', 'CriteriaController::update/$1');
    $routes->get('criteria/delete/(:num)', 'CriteriaController::delete/$1');
    
    // Standar Nilai SPK
    $routes->get('criteria/standar', 'CriteriaController::standar');
    $routes->post('criteria/savestandar', 'CriteriaController::savestandar');

    // Subcriteria
    $routes->get('subcriteria/(:num)', 'SubcriteriaController::index/$1');
    $routes->get('subcriteria/create/(:num)', 'SubcriteriaController::create/$1');
    $routes->post('subcriteria/store', 'SubcriteriaController::store');
    $routes->get('subcriteria/edit/(:num)', 'SubcriteriaController::edit/$1');
    $routes->post('subcriteria/update/(:num)', 'SubcriteriaController::update/$1');
    $routes->get('subcriteria/delete/(:num)', 'SubcriteriaController::delete/$1');


    // --------------------------------------------------------
    // MANAJEMEN KARYAWAN (ALTERNATIF)
    // --------------------------------------------------------
    $routes->get('alternatives', 'AlternativesController::index');
    $routes->get('alternatives/create', 'AlternativesController::create'); // Jika ada fitur tambah manual
    $routes->post('alternatives/store', 'AlternativesController::store');
    $routes->get('alternatives/detail/(:num)', 'AlternativesController::detail/$1');
    $routes->get('alternatives/delete/(:num)', 'AlternativesController::delete/$1');
    $routes->get('alternatives/penilaian/(:num)', 'AlternativesController::penilaian/$1');
    $routes->post('alternatives/hitung/(:num)', 'AlternativesController::hitung/$1');


    // --------------------------------------------------------
    // TRANSAKSI: KELOLA LOWONGAN & SELEKSI (SPK)
    // --------------------------------------------------------
    $routes->get('lowongan', 'LowonganController::index');
    $routes->get('lowongan/create', 'LowonganController::create');
    $routes->post('lowongan/store', 'LowonganController::store');
    $routes->get('lowongan/edit/(:num)', 'LowonganController::edit/$1');
    $routes->post('lowongan/update/(:num)', 'LowonganController::update/$1');
    $routes->get('lowongan/delete/(:num)', 'LowonganController::delete/$1');
    
    // Detail & Proses Seleksi
    $routes->get('lowongan/detail/(:num)', 'LowonganController::detail/$1'); 
    $routes->get('lowongan/pelamar/(:num)', 'LowonganController::detailPelamar/$1'); // Halaman Hitung
    $routes->post('lowongan/hitung/(:num)', 'LowonganController::hitungSPK/$1');    // Proses Hitung
    $routes->get('lowongan/selesai/(:num)', 'LowonganController::finalisasi/$1');   // Finalisasi -> Pindah ke Arsip


    // --------------------------------------------------------
    // LAPORAN: DATA ARSIP & RANKING
    // --------------------------------------------------------
    // Data Pelamar (Arsip)
    $routes->get('data', 'DataController::index');
    $routes->get('data/detail/(:num)', 'DataController::detail/$1');
    $routes->get('data/onboard/(:num)', 'DataController::onboard/$1'); // Rekrut jadi karyawan

    // Ranking Global
    $routes->get('spk', 'SpkController::index');
    
    // MASTER DATA: FORMULIR
    $routes->get('formulir', 'FormulirController::index');
    $routes->get('formulir/create', 'FormulirController::create');
    $routes->post('formulir/store', 'FormulirController::store');
    $routes->get('formulir/delete/(:num)', 'FormulirController::delete/$1');
    $routes->get('formulir/edit/(:num)', 'FormulirController::edit/$1');
    $routes->post('formulir/update/(:num)', 'FormulirController::update/$1');
    
});



/*
 * --------------------------------------------------------------------
 * Additional Routing (Biarkan default)
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}