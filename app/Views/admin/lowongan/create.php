<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm border-0" style="max-width: 800px; margin:0 auto;">
  <div class="card-body">
    <h4 class="card-title mb-4 fw-bold">Buat Lowongan Baru</h4>

    <form action="<?= base_url('admin/lowongan/store') ?>" method="post">

      <div class="row mb-4">
        
        <div class="col-md-6">
            <label class="form-label fw-bold">Pilih Divisi</label>
            <select id="filterDivisi" class="form-select border-primary">
                <option value="">-- Pilih Divisi Dahulu --</option>
                <?php 
                    // Ambil daftar divisi unik
                    $divisiUnik = [];
                    foreach($pekerjaan as $p) {
                        $divisiUnik[$p['divisi']] = $p['divisi'];
                    }
                    foreach($divisiUnik as $d): 
                ?>
                    <option value="<?= esc($d) ?>"><?= esc($d) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Pilih Posisi / Jabatan</label>
            <select name="pekerjaan_id" id="selectPosisi" class="form-select border-primary" required disabled>
                <option value="">-- Pilih Divisi Dahulu --</option>
                
                <?php foreach ($pekerjaan as $p): ?>
                    <option value="<?= $p['id'] ?>" data-divisi="<?= esc($p['divisi']) ?>" class="posisi-option d-none">
                        <?= esc($p['posisi']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>
        
        <div class="col-12 mt-2">
            <div class="form-text text-muted small">
                <i class="mdi mdi-information-outline"></i> Pilih Divisi terlebih dahulu, maka daftar Posisi akan muncul otomatis.
            </div>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Judul Postingan</label>
        <input type="text" name="judul_lowongan" class="form-control" placeholder="Contoh: Dicari Senior Programmer (Urgent)" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Tipe Pekerjaan</label>
        <select name="jenis" class="form-select" required>
            <option value="Full Time">Full Time</option>
            <option value="Part Time">Part Time</option>
            <option value="Magang">Magang</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Deskripsi Pekerjaan</label>
        
        <div id="editor-container" style="height: 200px; background: #fff;"></div>
        
        <input type="hidden" name="deskripsi" id="deskripsi_input">
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Template Formulir Lamaran</label>
        <select name="formulir_id" class="form-select">
            <option value="">-- Pilih Template --</option>
            <?php foreach($formulir as $f): ?>
                <option value="<?= $f['id'] ?>">
                    <?= esc($f['nama_template']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="form-text">Pilih pertanyaan tambahan yang akan muncul untuk pelamar.</div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Link Google Form (Opsional)</label>
        <input type="text" name="link_google_form" class="form-control" placeholder="Isi jika pelamar diarahkan ke GForm eksternal">
      </div>

      <div class="mb-4">
        <label class="form-label fw-bold">Tanggal Posting</label>
        <input type="date" name="tanggal_posting" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>

      <div class="text-end">
        <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-light me-2">Batal</a>
        <button type="submit" class="btn btn-primary px-4">Simpan Lowongan</button>
      </div>

    </form>
  </div>
</div>

<!-- js untuk Filter pekerjaan -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterDivisi = document.getElementById('filterDivisi');
        const selectPosisi = document.getElementById('selectPosisi');
        const allOptions   = document.querySelectorAll('.posisi-option');

        filterDivisi.addEventListener('change', function() {
            const selectedDivisi = this.value;

            // Reset Posisi
            selectPosisi.value = "";
            
            if (selectedDivisi === "") {
                // Jika tidak pilih divisi, disable posisi
                selectPosisi.disabled = true;
                selectPosisi.innerHTML = '<option value="">-- Pilih Divisi Dahulu --</option>';
            } else {
                // Aktifkan dropdown
                selectPosisi.disabled = false;
                
                // Sembunyikan semua opsi dulu
                // Lalu tampilkan hanya yang divisinya cocok
                let hasOption = false;
                
                // Reset options visibility
                // Cara paling aman cross-browser adalah me-rebuild option atau hidden class
                // Disini kita pakai style display
                
                selectPosisi.innerHTML = '<option value="">-- Pilih Posisi --</option>';

                allOptions.forEach(option => {
                    if (option.getAttribute('data-divisi') === selectedDivisi) {
                        // Clone option agar tidak merusak data asli di memory
                        let newOption = option.cloneNode(true);
                        newOption.classList.remove('d-none');
                        selectPosisi.appendChild(newOption);
                        hasOption = true;
                    }
                });

                if (!hasOption) {
                    selectPosisi.innerHTML = '<option value="">Tidak ada posisi di divisi ini</option>';
                }
            }
        });
    });
</script>


<!-- js untuk API Quill Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Inisialisasi Quill
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Jelaskan tanggung jawab, kualifikasi, dan benefit...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],        // toggled buttons
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'header': [1, 2, 3, false] }],
                    ['clean']                               // remove formatting button
                ]
            }
        });

        // 2. Saat form akan disubmit, pindahkan isi Quill ke Input Hidden
        var form = document.querySelector('form');
        form.onsubmit = function() {
            // Ambil HTML dari editor
            var deskripsi = document.querySelector('input[name=deskripsi]');
            
            // Jika editor kosong, set value kosong (untuk validasi required controller)
            if (quill.getText().trim().length === 0) {
                deskripsi.value = "";
            } else {
                deskripsi.value = quill.root.innerHTML;
            }
        };
    });
</script>


<?= $this->endSection() ?>