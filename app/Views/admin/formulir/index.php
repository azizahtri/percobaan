<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0 fw-bold text-dark">Template Formulir Lamaran</h4>
      <a href="<?= base_url('admin/formulir/create') ?>" class="btn btn-primary btn-sm px-3">
          <i class="mdi mdi-plus-circle me-1"></i> Buat Template Baru
      </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th class="text-start">Nama Template</th>
                        <th>Jumlah Pertanyaan</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($formulir)): ?>
                        <?php foreach($formulir as $key => $f): ?>
                        <?php 
                            $cfg = json_decode($f['config'], true); 
                            $count = count($cfg ?? []);
                            // Encode data row ke JSON agar bisa dikirim ke JS
                            $jsonData = htmlspecialchars(json_encode($f), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td class="text-start fw-bold"><?= esc($f['nama_template']) ?></td>
                            <td>
                                <span class="badge bg-info text-dark fs-6">
                                    <?= $count ?> Pertanyaan
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-info btn-sm text-white" 
                                            onclick="showDetail(<?= $jsonData ?>)" title="Lihat Detail">
                                        <i class="mdi mdi-eye"></i> Detail
                                    </button>

                                    <a href="<?= base_url('admin/formulir/edit/'.$f['id']) ?>" class="btn btn-warning btn-sm text-white" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    
                                    <a href="<?= base_url('admin/formulir/delete/'.$f['id']) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Hapus template ini?')" title="Hapus">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="mdi mdi-file-document-outline" style="font-size: 3rem;"></i>
                                <p class="mt-2">Belum ada template formulir.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDetailForm" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title fw-bold" id="modalTitle">
            <i class="mdi mdi-file-document-box me-2"></i>Detail Template
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light" id="modalContent">
          </div>
      <div class="modal-footer bg-white">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
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
                <div class="text-center text-muted py-4">
                    <i class="mdi mdi-emoticon-sad-outline fs-1"></i>
                    <p class="mt-2">Template ini belum memiliki pertanyaan.</p>
                </div>`;
        } else {
            config.forEach((item, index) => {
                // Deteksi format data (String lama vs Object baru)
                let label = (typeof item === 'string') ? item : item.label;
                let type  = (typeof item === 'object' && item.type) ? item.type : 'text';
                let opts  = (typeof item === 'object' && item.options) ? item.options : '';

                // Tentukan Badge Tipe
                let badgeClass = 'bg-secondary';
                let typeName   = 'Teks Singkat';

                if(type === 'textarea') { badgeClass = 'bg-warning text-dark'; typeName = 'Paragraf'; }
                else if(type === 'radio') { badgeClass = 'bg-success'; typeName = 'Pilihan Ganda'; }
                else if(type === 'checkbox') { badgeClass = 'bg-primary'; typeName = 'Checkbox'; }
                else if(type === 'file') { badgeClass = 'bg-dark'; typeName = 'Upload File'; }

                // Template HTML per Item
                let html = `
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-light text-dark border">No. ${index + 1}</span>
                                <span class="badge ${badgeClass}">${typeName}</span>
                            </div>
                            
                            <h6 class="fw-bold text-dark mb-1">${label}</h6>
                `;

                // Jika ada opsi (Radio/Checkbox), tampilkan
                if ((type === 'radio' || type === 'checkbox') && opts) {
                    html += `
                        <div class="mt-2 p-2 bg-light rounded border border-light-subtle small text-muted">
                            <i class="mdi mdi-format-list-bulleted me-1"></i> <strong>Opsi:</strong> ${opts}
                        </div>`;
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