<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Kategori</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/categories/add') ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Deskripsi</th>
                <th scope="col">Gambar (Path)</th>
                <th scope="col" class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $index => $cat): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $cat->name ?></td>
                        <td><?= character_limiter($cat->description, 50) ?></td>
                        <td><small class="text-muted"><?= $cat->image ?></small></td>
                        <td class="text-end">
                            <a href="<?= base_url('admin/categories/edit/' . $cat->id) ?>"
                                class="btn btn-sm btn-outline-secondary me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url('admin/categories/delete/' . $cat->id) ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>