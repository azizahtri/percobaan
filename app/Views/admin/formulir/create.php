<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <h4 class="card-title">Buat Template Formulir</h4>
        
        <form action="<?= base_url('admin/formulir/store') ?>" method="post">
            <div class="mb-4">
                <label class="fw-bold">Nama Template</label>
                <input type="text" name="nama_template" class="form-control" placeholder="Contoh: Form IT Staff, Form Magang" required>
            </div>

            <h6 class="fw-bold mb-3">Daftar Pertanyaan</h6>
            <div id="formContainer"></div>
            
            <button type="button" class="btn btn-sm btn-info text-white mb-4" id="btnAddQuestion">
                <i class="mdi mdi-plus"></i> Tambah Pertanyaan
            </button>

            <div class="text-end border-top pt-3">
                <a href="<?= base_url('admin/formulir') ?>" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-success">Simpan Template</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script>
    document.getElementById('btnAddQuestion').addEventListener('click', function() {
        const container = document.getElementById('formContainer');
        const card = document.createElement('div');
        card.className = 'card mb-2 border shadow-sm bg-light';
        
        card.innerHTML = `
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="q_text[]" class="form-control form-control-sm" placeholder="Pertanyaan..." required>
                    </div>
                    <div class="col-md-3">
                        <select name="q_type[]" class="form-select form-select-sm q-type-select">
                            <option value="text">Teks Singkat</option>
                            <option value="textarea">Paragraf</option>
                            <option value="radio">Pilihan Ganda</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm p-1 remove-q"><i class="mdi mdi-close"></i></button>
                    </div>
                    <div class="col-12 mt-2 option-container d-none">
                        <input type="text" name="q_options[]" class="form-control form-control-sm" placeholder="Opsi dipisah koma (Ya, Tidak)">
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);

        const select = card.querySelector('.q-type-select');
        const opts = card.querySelector('.option-container');
        
        select.addEventListener('change', function() {
            if(this.value === 'radio' || this.value === 'checkbox') {
                opts.classList.remove('d-none');
                opts.querySelector('input').required = true;
            } else {
                opts.classList.add('d-none');
                opts.querySelector('input').required = false;
            }
        });

        card.querySelector('.remove-q').addEventListener('click', function() { card.remove(); });
    });
</script>

<?= $this->endSection() ?>