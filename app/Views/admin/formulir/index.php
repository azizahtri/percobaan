<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<?php
    // --- LOGIKA PEMISAHAN DATA ---
    $gformList = [];
    $internalList = [];

    if(!empty($formulir)) {
        foreach($formulir as $f) {
            // Jika punya Link G-Form, masuk kategori Eksternal/Hybrid
            if (!empty($f['link_google_form'])) {
                $gformList[] = $f;
            } else {
                // Sisanya masuk kategori Internal
                $internalList[] = $f;
            }
        }
    }
?>

<div class="container-fluid py-4">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
          <h4 class="mb-0 fw-bold text-dark">Template Formulir Lamaran</h4>
          <p class="text-muted small mb-0">Kelola dan pilih jenis formulir untuk rekrutmen.</p>
      </div>
      <a href="<?= base_url('admin/formulir/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
          <i class="mdi mdi-plus-circle me-1"></i> Buat Template Baru
      </a>
  </div>

  <h6 class="fw-bold text-primary mb-3"><i class="mdi mdi-google-forms me-2"></i>Template Google Form / Eksternal</h6>
  <div class="card shadow-sm border-0 mb-5">
    <div class="card-body">
        <?php if(empty($gformList)): ?>
            <div class="text-center py-4 text-muted small">Belum ada template Google Form.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" style="width:100%">
                    <thead class="bg-primary bg-opacity-10 text-primary small text-uppercase">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th class="text-start">Nama Template</th>
                            <th class="text-start">Link Tautan</th>
                            <th class="text-center">Info Tambahan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($gformList as $key => $f): ?>
                        <?php 
                            $cfg = json_decode($f['config'] ?? '[]', true); 
                            $count = count($cfg);
                            $jsonData = htmlspecialchars(json_encode($f), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td class="text-start fw-bold"><?= esc($f['nama_template']) ?></td>
                            <td class="text-start">
                                <a href="<?= esc($f['link_google_form']) ?>" target="_blank" class="text-decoration-none small text-truncate d-inline-block" style="max-width: 250px;">
                                    <i class="mdi mdi-open-in-new me-1"></i><?= esc($f['link_google_form']) ?>
                                </a>
                            </td>
                            <td>
                                <?php if($count > 0): ?>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-2">
                                        + <?= $count ?> Pertanyaan Tambahan
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2">
                                        Full Google Form
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-action btn-action-detail" onclick="showDetail(<?= $jsonData ?>)" title="Lihat Detail"><i class="mdi mdi-eye"></i></button>
                                    <a href="<?= base_url('admin/formulir/edit/'.$f['id']) ?>" class="btn btn-action btn-action-edit" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                    <button type="button"
                                            class="btn btn-action btn-action-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus<?= $f['id'] ?>"
                                            title="Hapus Template">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
  </div>

  <h6 class="fw-bold text-info mb-3"><i class="mdi mdi-file-document-edit-outline me-2"></i>Template Pertanyaan Internal (Sistem)</h6>
  <div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if(empty($internalList)): ?>
            <div class="text-center py-4 text-muted small">Belum ada template internal.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" style="width:100%">
                    <thead class="bg-info bg-opacity-10 text-info small text-uppercase">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th class="text-start">Nama Template</th>
                            <th class="text-center">Jumlah Pertanyaan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($internalList as $key => $f): ?>
                        <?php 
                            $cfg = json_decode($f['config'] ?? '[]', true); 
                            $count = count($cfg);
                            $jsonData = htmlspecialchars(json_encode($f), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td class="text-start fw-bold"><?= esc($f['nama_template']) ?></td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3">
                                    <?= $count ?> Pertanyaan
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-action btn-action-detail" onclick="showDetail(<?= $jsonData ?>)" title="Lihat Detail"><i class="mdi mdi-eye"></i></button>
                                    <a href="<?= base_url('admin/formulir/edit/'.$f['id']) ?>" class="btn btn-action btn-action-edit" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                    <button type="button"
                                        class="btn btn-action btn-action-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapus<?= $f['id'] ?>"
                                        title="Hapus Template">
                                    <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
  </div>

</div>

<div class="modal fade" id="modalDetailForm" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title fw-bold" id="modalTitle">Detail Template</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light p-4" id="modalContent"></div>
      <div class="modal-footer bg-white">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
    function showDetail(data) {
        document.getElementById('modalTitle').innerText = data.nama_template;
        let config = [];
        try { config = JSON.parse(data.config); } catch(e) { config = []; }
        const container = document.getElementById('modalContent');
        container.innerHTML = ''; 

        if (data.link_google_form) {
            container.innerHTML += `
                <div class="alert alert-primary d-flex align-items-center mb-3 border-0 shadow-sm">
                    <i class="mdi mdi-google-forms fs-1 me-3"></i>
                    <div class="overflow-hidden">
                        <small class="d-block text-uppercase fw-bold opacity-75">Link Google Form</small>
                        <a href="${data.link_google_form}" target="_blank" class="fw-bold text-primary text-truncate d-block">
                            ${data.link_google_form}
                        </a>
                    </div>
                </div>`;
        }

        if (config && config.length > 0) {
            if(data.link_google_form) container.innerHTML += `<h6 class="fw-bold mb-3 border-bottom pb-2">Pertanyaan Tambahan:</h6>`;
            
            config.forEach((item, index) => {
                let label = (typeof item === 'string') ? item : item.label;
                let type  = (typeof item === 'object' && item.type) ? item.type : 'text';
                let opts  = (typeof item === 'object' && item.options) ? item.options : '';
                
                let badge = 'bg-secondary';
                if(type=='textarea') badge='bg-warning text-dark';
                else if(type=='radio' || type=='checkbox') badge='bg-success';

                let html = `
                    <div class="card mb-2 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">#${index+1}</small>
                                <span class="badge ${badge}">${type}</span>
                            </div>
                            <div class="fw-bold text-dark">${label}</div>`;
                
                if((type=='radio'||type=='checkbox') && opts){
                    html += `<div class="mt-2 ps-2 border-start small text-muted">${opts.split(',').join('<br>')}</div>`;
                }
                html += `</div></div>`;
                container.innerHTML += html;
            });
        } else if(!data.link_google_form) {
            container.innerHTML = `<div class="text-center py-3 text-muted">Template kosong</div>`;
        }
        new bootstrap.Modal(document.getElementById('modalDetailForm')).show();
    }
</script>

<!-- Modal Hapus untuk Google Form -->
<?php foreach ($gformList as $f): ?>
    <div class="modal fade" id="modalHapus<?= $f['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title fw-bold">
                        <i class="mdi mdi-alert-circle-outline me-2"></i> Hapus Template?
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="mb-4 text-danger opacity-25">
                        <i class="mdi mdi-trash-can-outline" style="font-size: 60px;"></i>
                    </div>
                    <p class="mb-2 text-muted">Apakah Anda yakin ingin menghapus template:</p>
                    <h5 class="fw-bold text-dark mb-4 px-3 mx-auto"
                        style="max-width: 100%; word-wrap: break-word; overflow-wrap: break-word;">
                        "<?= esc($f['nama_template']) ?>"
                    </h5>
                    <div class="alert alert-warning small text-start">
                        <i class="mdi mdi-alert me-2"></i>
                        <strong>Perhatian:</strong> Template ini akan dihapus secara permanen dan tidak dapat dikembalikan.
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <a href="<?= base_url('admin/formulir/delete/' . $f['id']) ?>"
                        class="btn btn-danger rounded-pill px-4 shadow-sm">
                        Ya, Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<!-- Modal Hapus untuk Internal -->
<?php foreach ($internalList as $f): ?>
<div class="modal fade" id="modalHapus<?= $f['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title fw-bold">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> Hapus Template?
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-4 text-danger opacity-25">
                    <i class="mdi mdi-trash-can-outline" style="font-size: 60px;"></i>
                </div>
                <p class="mb-2 text-muted">Apakah Anda yakin ingin menghapus template:</p>
                <h5 class="fw-bold text-dark mb-4 px-3 mx-auto"
                    style="max-width: 100%; word-wrap: break-word; overflow-wrap: break-word;">
                    "<?= esc($f['nama_template']) ?>"
                </h5>
                <div class="alert alert-warning small text-start">
                    <i class="mdi mdi-alert me-2"></i>
                    <strong>Perhatian:</strong> Template ini akan dihapus secara permanen dan tidak dapat dikembalikan.
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <a href="<?= base_url('admin/formulir/delete/' . $f['id']) ?>"
                    class="btn btn-danger rounded-pill px-4 shadow-sm">
                    Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>