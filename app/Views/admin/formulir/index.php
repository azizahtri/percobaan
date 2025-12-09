<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
          <h4 class="mb-0 fw-bold text-dark">Template Formulir Lamaran</h4>
          <p class="text-muted small mb-0">Kelola pertanyaan kustom yang akan muncul di formulir pelamar.</p>
      </div>
      <a href="<?= base_url('admin/formulir/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
          <i class="mdi mdi-plus-circle me-1"></i> Buat Template Baru
      </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center datatable" style="width:100%">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th class="text-start">Nama Template</th>
                        <th class="text-center">Jumlah Pertanyaan</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($formulir)): ?>
                        <?php foreach($formulir as $key => $f): ?>
                        <?php 
                            $cfg = json_decode($f['config'], true); 
                            $count = count($cfg ?? []);
                            // Encode data row ke JSON agar bisa dikirim ke JS (Aman dari kutip)
                            $jsonData = htmlspecialchars(json_encode($f), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td class="text-start">
                                <span class="fw-bold text-dark d-block"><?= esc($f['nama_template']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3">
                                    <?= $count ?> Pertanyaan
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    <button type="button" class="btn btn-action btn-action-detail" 
                                            onclick="showDetail(<?= $jsonData ?>)" 
                                            title="Lihat Detail Pertanyaan"
                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="mdi mdi-eye"></i>
                                    </button>

                                    <a href="<?= base_url('admin/formulir/edit/'.$f['id']) ?>" 
                                       class="btn btn-action btn-action-edit" 
                                       title="Edit Template"
                                       data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    
                                    <a href="<?= base_url('admin/formulir/delete/'.$f['id']) ?>" 
                                       class="btn btn-action btn-action-delete" 
                                       onclick="return confirm('Hapus template ini? Lowongan yang menggunakan template ini mungkin akan terdampak.')" 
                                       title="Hapus Template"
                                       data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="mdi mdi-delete"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDetailForm" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title fw-bold" id="modalTitle">
            <i class="mdi mdi-file-document-box me-2"></i>Detail Template
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light p-4" id="modalContent">
          </div>
      <div class="modal-footer bg-white">
        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
    function showDetail(data) {
        // 1. Set Judul Modal
        document.getElementById('modalTitle').innerHTML = '<i class="mdi mdi-file-document-box me-2"></i>' + data.nama_template;
        
        // 2. Parse Data JSON Config
        let config = [];
        try {
            config = JSON.parse(data.config);
        } catch(e) {
            console.error("JSON Error", e);
            config = [];
        }

        const container = document.getElementById('modalContent');
        container.innerHTML = ''; // Bersihkan isi lama

        // 3. Render Tampilan
        if (!config || config.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    <div class="mb-3 opacity-50"><i class="mdi mdi-playlist-remove" style="font-size: 4rem;"></i></div>
                    <h6 class="fw-bold">Template Kosong</h6>
                    <p class="small mb-0">Template ini belum memiliki pertanyaan.</p>
                </div>`;
        } else {
            config.forEach((item, index) => {
                // Deteksi format data (String lama vs Object baru)
                let label = (typeof item === 'string') ? item : item.label;
                let type  = (typeof item === 'object' && item.type) ? item.type : 'text';
                let opts  = (typeof item === 'object' && item.options) ? item.options : '';

                // Tentukan Badge Tipe & Ikon
                let badgeClass = 'bg-secondary';
                let typeName   = 'Teks Singkat';
                let typeIcon   = 'mdi-format-title';

                if(type === 'textarea') { badgeClass = 'bg-warning text-dark'; typeName = 'Paragraf'; typeIcon = 'mdi-format-align-left'; }
                else if(type === 'radio') { badgeClass = 'bg-success'; typeName = 'Pilihan Ganda'; typeIcon = 'mdi-radiobox-marked'; }
                else if(type === 'checkbox') { badgeClass = 'bg-primary'; typeName = 'Checkbox'; typeIcon = 'mdi-checkbox-marked-outline'; }
                else if(type === 'file') { badgeClass = 'bg-dark'; typeName = 'Upload File'; typeIcon = 'mdi-cloud-upload'; }

                // Template HTML per Item (Card Modern)
                let html = `
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-light text-dark border">Pertanyaan #${index + 1}</span>
                                <span class="badge ${badgeClass}"><i class="mdi ${typeIcon} me-1"></i>${typeName}</span>
                            </div>
                            
                            <h6 class="fw-bold text-dark mb-1" style="line-height: 1.5;">${label}</h6>
                `;

                // Jika ada opsi (Radio/Checkbox), tampilkan dengan rapi
                if ((type === 'radio' || type === 'checkbox') && opts) {
                    // Pecah opsi jadi array
                    let optList = opts.split(',').map(o => o.trim());
                    
                    html += `<div class="mt-2 pt-2 border-top">`;
                    optList.forEach(opt => {
                        html += `<span class="badge bg-light text-secondary border me-1 mb-1 font-weight-normal">${opt}</span>`;
                    });
                    html += `</div>`;
                }

                html += `</div></div>`;
                container.innerHTML += html;
            });
        }

        // 4. Tampilkan Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalDetailForm'));
        myModal.show();
    }
</script>

<?= $this->endSection() ?>