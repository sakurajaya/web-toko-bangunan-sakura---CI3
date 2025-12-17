<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pesan Masuk</h1>
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
                <th scope="col">Tanggal</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Pesan</th>
                <th scope="col" class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $index => $msg): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d M Y H:i', strtotime($msg->created_at)) ?></td>
                        <td><?= $msg->name ?></td>
                        <td>
                            <?php if(!empty($msg->email)): ?>
                                <a href="mailto:<?= $msg->email ?>"><?= $msg->email ?></a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= nl2br($msg->message) ?></td>
                        <td class="text-end">
                            <a href="<?= base_url('admin/messages/delete/' . $msg->id) ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada pesan masuk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
