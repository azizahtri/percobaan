<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  <div class="card shadow-sm border-0" style="max-width: 800px; margin:0 auto;">
    <div class="card-header bg-warning text-white">
      <h5 class="mb-0 fw-bold"><i class="mdi mdi-pencil me-2"></i>Edit Lowongan</h5>
    </div>

    <div class="card-body">
      <form action="<?= base_url('admin/lowongan/update/' . $lowongan['id']) ?>" method="post">

        <div class="mb-3">
          <label class="form-label fw-bold">Posisi Pekerjaan</label>
          <select name="pekerjaan_id" class="form-select border-warning" required>
            <option value="">-- Pilih Posisi --</option>
            
            <?php 
                $currDiv = '';
                foreach ($pekerjaan as $p): 
                    if($currDiv != $p['divisi']): 
                        if($currDiv != '') echo '</optgroup>';
                        echo '<optgroup label="'.esc($p['divisi']).'">';
                        $currDiv = $p['divisi'];
                    endif;
            ?>
              
              <option value="<?= $p['id'] ?>" 
                  <?= ($lowongan['pekerjaan_id'] == $p['id']) ? 'selected' : '' ?>>
                  <?= esc($p['posisi']) ?>
              </option>

            <?php endforeach; if($currDiv != '') echo '</optgroup>'; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Template Formulir Lamaran</label>
          <select name="formulir_id" class="form-select border-warning">
              <option value="">-- Pilih Template --</option>
              <?php foreach($formulir as $f): ?>
                  <option value="<?= $f['id'] ?>" 
                      <?= ($lowongan['formulir_id'] == $f['id']) ? 'selected' : '' ?>>
                      <?= esc($f['nama_template']) ?>
                  </option>
              <?php endforeach; ?>
          </select>
          <div class="form-text text-muted">Pilih pertanyaan tambahan yang akan muncul untuk pelamar.</div>
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

        <div class="mb-3">
          <label class="form-label fw-bold">Deskripsi</label>
          
          <div id="editor-container" style="height: 200px; background: #fff;">
            <?= $lowongan['deskripsi'] ?>
          </div>
          
          <input type="hidden" name="deskripsi" id="deskripsi_input">
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Link Google Form</label>
          <input type="url" name="link_google_form" class="form-control" 
                 placeholder="https://forms.google.com/..." 
                 value="<?= isset($lowongan['link_google_form']) ? esc($lowongan['link_google_form']) : '' ?>">
          <small class="text-muted">Opsional.</small>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Tanggal Posting</label>
            <input type="date" name="tanggal_posting" class="form-control"
                   value="<?= date('Y-m-d', strtotime($lowongan['tanggal_posting'])) ?>" required>
        </div>

        <div class="text-end">
          <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-light me-2">Kembali</a>
          <button type="submit" class="btn btn-warning text-white px-4">Update Perubahan</button>
        </div>

      </form>
    </div>
  </div>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Inisialisasi Quill
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

        // 2. Event saat form disubmit
        var form = document.querySelector('form');
        form.onsubmit = function() {
            var deskripsiInput = document.querySelector('input[name=deskripsi]');
            
            // Ambil HTML dari editor dan masukkan ke input hidden
            // Cek jika editor kosong (hanya berisi spasi/enter)
            if (quill.getText().trim().length === 0) {
                 // Opsional: Bisa dikosongkan atau biarkan error controller menangani
                 deskripsiInput.value = ""; 
            } else {
                 deskripsiInput.value = quill.root.innerHTML;
            }
        };
    });
</script>
<?= $this->endSection() ?>