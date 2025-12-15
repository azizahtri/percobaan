<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="card-title mb-0 fw-bold">Edit Template Formulir</h4>
            <a href="<?= base_url('admin/formulir') ?>" class="btn btn-light btn-sm border">Kembali</a>
        </div>
        
        <form action="<?= base_url('admin/formulir/update/' . $formulir['id']) ?>" method="post">
            <div class="mb-3">
                <label class="fw-bold">Nama Template</label>
                <input type="text" name="nama_template" class="form-control" value="<?= esc($formulir['nama_template']) ?>" required>
            </div>

            <div class="mb-4">
                <label class="fw-bold text-primary"><i class="mdi mdi-google-forms me-1"></i>Link Google Form (Opsional)</label>
                <input type="url" name="link_google_form" class="form-control bg-light" value="<?= esc($formulir['link_google_form']) ?>" placeholder="https://forms.google.com/...">
                <small class="text-muted">Link form eksternal yang akan digunakan.</small>
            </div>

            <hr>

            <h6 class="fw-bold mb-3">Daftar Pertanyaan</h6>
            <div id="formContainer">
                
                <?php 
                    $questions = json_decode($formulir['config'] ?? '[]', true);
                ?>

                <?php if(!empty($questions)): ?>
                    <?php foreach($questions as $q): ?>
                        <?php 
                            $label = is_array($q) ? $q['label'] : $q;
                            $type  = is_array($q) ? ($q['type'] ?? 'text') : 'text';
                            $opts  = is_array($q) ? ($q['options'] ?? '') : '';
                            
                            $showOpts = ($type == 'radio' || $type == 'checkbox') ? '' : 'd-none';
                            $reqOpts  = ($type == 'radio' || $type == 'checkbox') ? 'required' : '';
                        ?>

                        <div class="card mb-2 border shadow-sm bg-light text-dark item-pertanyaan">
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="q_text[]" class="form-control form-control-sm" value="<?= esc($label) ?>" placeholder="Pertanyaan..." required>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="q_type[]" class="form-select form-select-sm q-type-select">
                                            <option value="text" <?= $type=='text'?'selected':'' ?>>Teks Singkat</option>
                                            <option value="textarea" <?= $type=='textarea'?'selected':'' ?>>Paragraf</option>
                                            <option value="radio" <?= $type=='radio'?'selected':'' ?>>Pilihan Ganda</option>
                                            <option value="checkbox" <?= $type=='checkbox'?'selected':'' ?>>Checkbox</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm p-1 remove-q"><i class="mdi mdi-close"></i></button>
                                    </div>
                                    <div class="col-12 mt-2 option-container <?= $showOpts ?>">
                                        <input type="text" name="q_options[]" class="form-control form-control-sm" value="<?= esc($opts) ?>" placeholder="Opsi dipisah koma (Ya, Tidak)" <?= $reqOpts ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
            
            <button type="button" class="btn btn-sm btn-info text-white mb-4" id="btnAddQuestion">
                <i class="mdi mdi-plus"></i> Tambah Pertanyaan
            </button>

            <div class="text-end border-top pt-3">
                <button type="submit" class="btn btn-primary fw-bold px-4">Update Template</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script>
    const container = document.getElementById('formContainer');

    document.getElementById('btnAddQuestion').addEventListener('click', function() {
        const card = document.createElement('div');
        // TAMBAHAN: text-dark di sini juga
        card.className = 'card mb-2 border shadow-sm bg-light text-dark item-pertanyaan';
        
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
        attachEvents(card);
    });

    function attachEvents(card) {
        const select = card.querySelector('.q-type-select');
        const opts = card.querySelector('.option-container');
        const btnRemove = card.querySelector('.remove-q');

        select.addEventListener('change', function() {
            if(this.value === 'radio' || this.value === 'checkbox') {
                opts.classList.remove('d-none');
                opts.querySelector('input').required = true;
            } else {
                opts.classList.add('d-none');
                opts.querySelector('input').required = false;
            }
        });

        btnRemove.addEventListener('click', function() { 
            card.remove(); 
        });
    }

    document.querySelectorAll('.item-pertanyaan').forEach(card => {
        attachEvents(card);
    });
</script>

<?= $this->endSection() ?>