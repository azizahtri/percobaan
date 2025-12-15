<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<?php
    // LOGIKA PHP: Cari Divisi dari Posisi yang sedang terpilih
    $currentDivisi = '';
    foreach($pekerjaan as $p) {
        if ($p['id'] == $lowongan['pekerjaan_id']) {
            $currentDivisi = $p['divisi'];
            break;
        }
    }
?>

<div class="container-fluid py-4">
  <div class="card shadow-sm border-0" style="max-width: 800px; margin:0 auto;">
    <div class="card-header bg-warning text-white">
      <h5 class="mb-0 fw-bold"><i class="mdi mdi-pencil me-2"></i>Edit Lowongan</h5>
    </div>

    <div class="card-body">
      <form action="<?= base_url('admin/lowongan/update/' . $lowongan['id']) ?>" method="post">

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">Pilih Divisi</label>
                <select id="filterDivisi" class="form-select border-warning">
                    <option value="">-- Pilih Divisi Dahulu --</option>
                    <?php 
                        // Ambil daftar divisi unik
                        $divisiUnik = [];
                        foreach($pekerjaan as $p) {
                            $divisiUnik[$p['divisi']] = $p['divisi'];
                        }
                        foreach($divisiUnik as $d): 
                    ?>
                        <option value="<?= esc($d) ?>" <?= ($d == $currentDivisi) ? 'selected' : '' ?>>
                            <?= esc($d) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Pilih Posisi / Jabatan</label>
                <select name="pekerjaan_id" id="selectPosisi" class="form-select border-warning" required>
                    <option value="">-- Pilih Divisi Dahulu --</option>
                    
                    <?php foreach ($pekerjaan as $p): ?>
                        <option value="<?= $p['id'] ?>" 
                                data-divisi="<?= esc($p['divisi']) ?>" 
                                class="posisi-option"
                                <?= ($lowongan['pekerjaan_id'] == $p['id']) ? 'selected' : '' ?>>
                            <?= esc($p['posisi']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>
            <div class="col-12 mt-2">
                <div class="form-text text-muted small">
                    <i class="mdi mdi-information-outline"></i> Ubah Divisi untuk melihat daftar posisi lainnya.
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-bold">Status Lowongan</label>
              <select name="status" class="form-select border-warning fw-bold">
                <option value="open" <?= $lowongan['status']=='open'?'selected':'' ?>>OPEN (Dibuka)</option>
                <option value="closed" <?= $lowongan['status']=='closed'?'selected':'' ?>>CLOSED (Ditutup)</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" class="form-control" value="<?= $lowongan['tanggal_mulai'] ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">Tanggal Selesai</label>
              <input type="date" name="tanggal_selesai" class="form-control" value="<?= $lowongan['tanggal_selesai'] ?>" required>
            </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Judul Lowongan</label>
          <input type="text" name="judul_lowongan" class="form-control"
                 value="<?= esc($lowongan['judul_lowongan']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Tipe Pekerjaan</label>
          <select name="jenis" class="form-select" required>
            <option value="Full Time" <?= $lowongan['jenis']=='Full Time'?'selected':'' ?>>Full Time</option>
            <option value="Magang" <?= $lowongan['jenis']=='Magang'?'selected':'' ?>>Magang</option>
            <option value="Part Time" <?= $lowongan['jenis']=='Part Time'?'selected':'' ?>>Part Time</option>
          </select>
        </div>

        <div class="mb-4">
          <label class="form-label fw-bold">Deskripsi Pekerjaan</label>
          <div id="editor-container" style="height: 200px; background: #fff;">
            <?= $lowongan['deskripsi'] ?>
          </div>
          <input type="hidden" name="deskripsi" id="deskripsi_input">
        </div>

        <hr class="my-4">

        <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Template Formulir Lamaran (Internal)</label>
              <select name="formulir_id" class="form-select border-warning">
                  <option value="">-- Tidak Pakai Pertanyaan --</option>
                  <?php foreach($templateInternal as $f): ?>
                      <option value="<?= $f['id'] ?>" <?= ($lowongan['formulir_id'] == $f['id']) ? 'selected' : '' ?>>
                          <?= esc($f['nama_template']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Template Google Form (Eksternal)</label>
              <select name="link_google_form" class="form-select border-warning">
                  <option value="">-- Tidak Pakai Google Form --</option>
                  <?php foreach($templateEksternal as $f): ?>
                      <option value="<?= esc($f['link_google_form']) ?>" 
                          <?= ($lowongan['link_google_form'] == $f['link_google_form']) ? 'selected' : '' ?>>
                          <?= esc($f['nama_template']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
            </div>
        </div>

        <input type="hidden" name="tanggal_posting" value="<?= $lowongan['tanggal_posting'] ?>">

        <div class="text-end mt-4">
          <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-light me-2">Kembali</a>
          <button type="submit" class="btn btn-warning text-white px-4 fw-bold">Update Perubahan</button>
        </div>

      </form>
    </div>
  </div>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. SETUP QUILL EDITOR ---
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tulis deskripsi pekerjaan...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'header': [1, 2, 3, false] }],
                    ['clean']
                ]
            }
        });

        // Simpan ke input hidden saat submit
        var form = document.querySelector('form');
        form.onsubmit = function() {
            var deskripsiInput = document.querySelector('input[name=deskripsi]');
            if (quill.getText().trim().length === 0) {
                 deskripsiInput.value = ""; 
            } else {
                 deskripsiInput.value = quill.root.innerHTML;
            }
        };

        // --- 2. LOGIKA FILTER DIVISI & POSISI ---
        const filterDivisi = document.getElementById('filterDivisi');
        const selectPosisi = document.getElementById('selectPosisi');
        const allOptions   = document.querySelectorAll('.posisi-option');

        // Fungsi untuk memfilter posisi berdasarkan divisi yang dipilih
        function filterOptions(selectedDivisi) {
            // Reset isi dropdown posisi
            // Kita simpan value yang sedang terpilih (jika ada) agar tidak hilang saat refresh list
            const currentVal = selectPosisi.value;
            
            // Sembunyikan semua opsi
            allOptions.forEach(option => {
                if (option.getAttribute('data-divisi') === selectedDivisi) {
                    option.style.display = 'block'; // Tampilkan yang cocok
                } else {
                    option.style.display = 'none';  // Sembunyikan yang tidak cocok
                }
            });

            // Jika posisi saat ini tidak sesuai dengan divisi baru, reset ke opsi pertama yang valid
            const selectedOption = selectPosisi.options[selectPosisi.selectedIndex];
            if (selectedOption && selectedOption.getAttribute('data-divisi') !== selectedDivisi) {
                selectPosisi.value = ""; // Reset jika tidak match
            }
        }

        // Jalankan filter saat halaman pertama kali dimuat (Initial Load)
        // Agar posisi yang tersimpan tetap muncul, tapi opsi lain difilter
        if (filterDivisi.value) {
            filterOptions(filterDivisi.value);
        }

        // Event saat Divisi diubah user
        filterDivisi.addEventListener('change', function() {
            const selectedDivisi = this.value;
            selectPosisi.value = ""; // Reset pilihan posisi
            
            if (selectedDivisi === "") {
                // Tampilkan pesan kosong
                allOptions.forEach(opt => opt.style.display = 'none');
            } else {
                filterOptions(selectedDivisi);
            }
        });
    });
</script>
<?= $this->endSection() ?>