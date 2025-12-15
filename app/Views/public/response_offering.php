<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Penawaran Kerja - Cartenz Technology</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card-response {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            background: white;
            
            /* --- PENGATURAN UKURAN (MELEBAR) --- */
            max-width: 900px;  /* Lebar maksimal */
            width: 100%;
            min-height: 400px; /* Tinggi minimal agar proporsional */
            /* ----------------------------------- */
            
            display: flex;
            flex-direction: row; /* PENTING: Menata ke samping */
        }

        /* --- SISI KIRI (WARNA) --- */
        .side-panel {
            flex: 0 0 38%; /* Lebar 38% */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            color: white;
            text-align: center;
            position: relative;
        }

        /* --- SISI KANAN (KONTEN) --- */
        .main-content {
            flex: 1; /* Sisa lebar (62%) */
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header-icon {
            font-size: 80px;
            margin-bottom: 10px;
            line-height: 1;
        }

        .side-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .side-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .company-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .company-logo {
            font-weight: 700;
            color: #333;
            margin: 0;
            font-size: 1.1rem;
        }

        /* WARNA TEMA */
        .theme-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .theme-danger  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .theme-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

        /* Responsif HP: Kembali menumpuk ke bawah */
        @media (max-width: 768px) {
            .card-response {
                flex-direction: column;
                max-width: 500px;
            }
            .side-panel {
                padding: 40px 20px;
            }
            .main-content {
                padding: 30px;
            }
        }
    </style>
</head>
<body>

    <div class="card card-response">
        
        <?php if ($status == 'terima'): ?>
            
            <div class="side-panel theme-success">
                <i class="mdi mdi-check-decagram header-icon"></i>
                <div class="side-title">Selamat!</div>
                <div class="side-subtitle">Konfirmasi Berhasil</div>
            </div>

            <div class="main-content">
                <div class="company-header">
                    <h5 class="company-logo"><i class="mdi mdi-cube-outline text-success"></i> Cartenz Technology</h5>
                    <small class="text-muted fw-bold">HRD DEPT</small>
                </div>

                <div class="flex-grow-1">
                    <h4 class="fw-bold text-dark mb-2">Halo, <?= esc($nama) ?></h4>
                    <p class="text-muted mb-3">
                        Terima kasih telah menerima tawaran kami. Anda resmi terdaftar untuk posisi:
                    </p>
                    <div class="badge bg-success-subtle text-success fs-6 px-3 py-2 rounded-pill mb-4 border border-success-subtle">
                        <?= esc($posisi) ?>
                    </div>

                    <div class="alert alert-light border shadow-sm p-3 small text-secondary d-flex">
                        <i class="mdi mdi-information-outline fs-4 me-2 text-success"></i>
                        <div>
                            <strong>Langkah Selanjutnya:</strong><br>
                            Tim HRD kami akan menghubungi Anda via WhatsApp/Email untuk jadwal <strong>Onboarding</strong>.
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="https://inispk.free.nf" class="btn btn-outline-dark rounded-pill px-4 btn-sm fw-bold">
                        Website Perusahaan <i class="mdi mdi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

        <?php elseif ($status == 'tolak'): ?>

            <div class="side-panel theme-danger">
                <i class="mdi mdi-close-circle-outline header-icon"></i>
                <div class="side-title">Terima Kasih</div>
                <div class="side-subtitle">Respon Tersimpan</div>
            </div>

            <div class="main-content">
                <div class="company-header">
                    <h5 class="company-logo"><i class="mdi mdi-cube-outline text-danger"></i> Cartenz Technology</h5>
                    <small class="text-muted fw-bold">HRD DEPT</small>
                </div>

                <div class="flex-grow-1">
                    <h4 class="fw-bold text-dark mb-2">Halo, <?= esc($nama) ?></h4>
                    <p class="text-muted mb-3">
                        Kami menghargai konfirmasi Anda untuk menolak posisi <strong><?= esc($posisi) ?></strong>.
                    </p>
                    
                    <p class="text-secondary small">
                        Pintu kami selalu terbuka untuk talenta berbakat seperti Anda. Semoga sukses dalam perjalanan karir Anda selanjutnya.
                    </p>
                </div>

                <div class="mt-4 text-end">
                    <a href="https://inispk.free.nf" class="btn btn-outline-secondary rounded-pill px-4 btn-sm fw-bold">
                        Website Perusahaan
                    </a>
                </div>
            </div>

        <?php else: ?>

            <div class="side-panel theme-warning">
                <i class="mdi mdi-alert-circle-outline header-icon"></i>
                <div class="side-title">Oops!</div>
                <div class="side-subtitle">Data Tidak Valid</div>
            </div>
            <div class="main-content text-center">
                <h4 class="fw-bold text-dark mt-4">Link Kadaluarsa</h4>
                <p class="text-muted">Maaf, link konfirmasi ini mungkin sudah tidak berlaku.</p>
            </div>

        <?php endif; ?>

    </div>

</body>
</html>