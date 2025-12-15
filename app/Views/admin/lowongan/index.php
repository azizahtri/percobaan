<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <!-- Alert Info -->
    <div class="alert alert-primary border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
        <div class="fs-4 me-3 mt-1"><i class="mdi mdi-briefcase-search"></i></div>
        <div>
            <h5 class="alert-heading fw-bold mb-1">Kelola Lowongan Pekerjaan</h5>
            <p class="mb-0 small">Halaman ini digunakan untuk memposting, mengedit, atau menutup lowongan.</p>
            <ul class="small mb-0 ps-3 mt-2">
                <li><strong>Status:</strong> Klik tombol OPEN/CLOSED untuk mengubah status lowongan.</li>
                <li><strong>Periode:</strong> Pastikan tanggal lowongan masih berlaku agar tampil di halaman depan.</li>
            </ul>
        </div>
    </div>

    <!-- Card Daftar Lowongan -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <h4 class="mb-0 text-dark fw-bold">Daftar Lowongan</h4>

                <div class="d-flex align-items-center gap-2">
                    <!-- Filter Divisi -->
                    <form method="get" class="d-flex align-items-center">
                        <select name="field" class="form-select form-select-sm border-secondary-subtle fw-bold text-dark me-2"
                                style="min-width: 150px;" onchange="this.form.submit()">
                            <option value="all">- Semua Divisi -</option>
                            <?php foreach ($divisiList as $d): ?>
                                <option value="<?= esc($d['divisi']) ?>" <?= ($selectedField == $d['divisi']) ? 'selected' : '' ?>>
                                    <?= esc($d['divisi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <?php if ($selectedField != 'all'): ?>
                            <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-outline-secondary btn-sm me-2" title="Reset Filter">
                                <i class="mdi mdi-refresh"></i>
                            </a>
                        <?php endif; ?>
                    </form>

                    <!-- Tombol Tambah -->
                    <a href="<?= base_url('admin/lowongan/create') ?>" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="mdi mdi-plus-circle me-1"></i> Tambah Lowongan
                    </a>
                </div>
            </div>

            <!-- Tabel Lowongan -->
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center datatable" style="width:100%">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th width="5%">No</th>
                            <th class="text-start">Posisi / Divisi</th>
                            <th class="text-start">Judul Lowongan</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Pelamar</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lowongan)): ?>
                            <?php foreach ($lowongan as $key => $l): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>

                                    <td class="text-start">
                                        <span class="badge bg-info text-dark mb-1 border border-info-subtle">
                                            <?= esc($l['divisi']) ?>
                                        </span>
                                        <div class="small fw-bold text-secondary mt-1">
                                            <i class="mdi mdi-account-tie me-1"></i>
                                            <?= esc($l['posisi'] ?? $l['jabatan'] ?? '-') ?>
                                        </div>
                                    </td>

                                    <td class="text-start">
                                        <div class="fw-bold text-dark" style="line-height: 1.4;">
                                            <?= esc($l['judul_lowongan']) ?>
                                        </div>
                                        <span class="badge bg-light text-secondary border mt-1 fw-normal small">
                                            <?= esc($l['jenis']) ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <?php if (!empty($l['tanggal_mulai']) && !empty($l['tanggal_selesai'])): ?>
                                            <div class="small fw-bold text-dark">
                                                <?= date('d M Y', strtotime($l['tanggal_mulai'])) ?>
                                            </div>
                                            <div class="small text-muted">s/d</div>
                                            <div class="small fw-bold text-danger">
                                                <?= date('d M Y', strtotime($l['tanggal_selesai'])) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php
                                            $isOpen = ($l['status'] == 'open');
                                            $btnClass = $isOpen ? 'btn-success' : 'btn-secondary';
                                            $statusLabel = $isOpen ? 'OPEN' : 'CLOSED';
                                            $statusIcon = $isOpen ? 'mdi-check-circle-outline' : 'mdi-close-circle-outline';
                                        ?>
                                        <a href="<?= base_url('admin/lowongan/toggleStatus/' . $l['id']) ?>"
                                           class="btn btn-sm rounded-pill px-3 fw-bold <?= $btnClass ?> shadow-sm"
                                           title="Klik untuk mengubah status">
                                            <i class="mdi <?= $statusIcon ?> me-1"></i><?= $statusLabel ?>
                                        </a>
                                    </td>

                                    <td>
                                        <?php if ($l['jumlah_pelamar'] > 0): ?>
                                            <a href="<?= base_url('admin/lowongan/detail/' . $l['id']) ?>"
                                               class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">
                                                <?= $l['jumlah_pelamar'] ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted opacity-50">0</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?= base_url('admin/lowongan/detail/' . $l['id']) ?>"
                                               class="btn btn-action btn-action-detail" title="Lihat Detail">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admin/lowongan/edit/' . $l['id']) ?>"
                                               class="btn btn-action btn-action-edit" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-action btn-action-delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalHapus<?= $l['id'] ?>"
                                                    title="Hapus Lowongan">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Hapus Lowongan (di dalam loop agar unik per baris) -->
                                <div class="modal fade" id="modalHapus<?= $l['id'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel<?= $l['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-danger text-white">
                                                <h6 class="modal-title fw-bold" id="modalHapusLabel<?= $l['id'] ?>">
                                                    <i class="mdi mdi-alert-circle-outline me-2"></i> Hapus Lowongan?
                                                </h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center p-4">
                                                <div class="mb-4 text-danger opacity-25">
                                                    <i class="mdi mdi-trash-can-outline" style="font-size: 60px;"></i>
                                                </div>
                                                <p class="mb-2 text-muted">Apakah Anda yakin ingin menghapus lowongan:</p>
                                                <h5 class="fw-bold text-dark mb-4 px-3 mx-auto"
                                                    style="max-width: 100%; word-wrap: break-word; overflow-wrap: break-word;">
                                                    "<?= esc($l['judul_lowongan']) ?>"
                                                </h5>
                                                <div class="alert alert-warning small text-start">
                                                    <i class="mdi mdi-alert me-2"></i>
                                                    <strong>Perhatian:</strong> Semua data pelamar yang terkait dengan lowongan ini juga akan dihapus secara permanen.
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center border-0 pb-4">
                                                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                                                    Batal
                                                </button>
                                                <a href="<?= base_url('admin/lowongan/delete/' . $l['id']) ?>"
                                                   class="btn btn-danger rounded-pill px-4 shadow-sm">
                                                    Ya, Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="mdi mdi-briefcase-off-outline fs-1 opacity-25 mb-3 d-block"></i>
                                    <p class="mb-0">Belum ada lowongan pekerjaan.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>