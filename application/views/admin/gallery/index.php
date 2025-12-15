<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Galeri</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah Foto
        </button>
    </div>
</div>

<div id="alert-container"></div>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-striped table-hover mb-0" id="galleryTable">
        <thead>
            <tr>
                <th scope="col" width="5%">#</th>
                <th scope="col" width="20%">Gambar</th>
                <th scope="col" width="25%">Judul</th>
                <th scope="col" width="35%">Deskripsi</th>
                <th scope="col" width="15%" class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data loaded via AJAX -->
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="galleryForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel">Tambah Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="itemId" name="id">

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image_file" class="form-label">Gambar</label>
                        <div id="imagePreviewContainer" class="mb-2 d-none">
                            <img id="imagePreview" src="" alt="Preview" class="img-thumbnail"
                                style="max-height: 150px;">
                        </div>
                        <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar (saat edit).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const API_URL = '<?= base_url("admin/gallery/") ?>';
    let galleryModal;
    let table;

    $(document).ready(function () {
        galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));

        table = $('#galleryTable').DataTable({
            "ajax": {
                "url": API_URL + 'get_json',
                "dataSrc": ""
            },
            "columns": [
                { "data": null, "render": function (data, type, row, meta) { return meta.row + 1; } },
                {
                    "data": "image",
                    "render": function (data) {
                        return data ? `<img src="<?= base_url() ?>${data}" height="60" class="rounded">` : '-';
                    }
                },
                { "data": "title" },
                { "data": "description", "defaultContent": "-" },
                {
                    "data": "id",
                    "className": "text-end",
                    "render": function (data) {
                        return `
                            <button class="btn btn-sm btn-outline-secondary me-1" onclick="editItem(${data})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(${data})">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ],
            "language": {
                "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Sedang memuat...",
                "processing": "Sedang memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        $('#galleryForm').on('submit', function (e) {
            e.preventDefault();
            saveItem();
        });

        $('#image_file').change(function () {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('#imagePreview').attr('src', event.target.result);
                    $('#imagePreviewContainer').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    });

    function openAddModal() {
        $('#galleryForm')[0].reset();
        $('#itemId').val('');
        $('#galleryModalLabel').text('Tambah Foto');
        $('#saveBtn').text('Simpan').prop('disabled', false);
        $('#imagePreview').attr('src', '');
        $('#imagePreviewContainer').addClass('d-none');
        galleryModal.show();
    }

    function editItem(id) {
        $.ajax({
            url: API_URL + 'get_item/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    const data = response.data;
                    $('#itemId').val(data.id);
                    $('#title').val(data.title);
                    $('#description').val(data.description);

                    if (data.image) {
                        let imgUrl = data.image.startsWith('http') ? data.image : '<?= base_url() ?>' + data.image;
                        $('#imagePreview').attr('src', imgUrl);
                        $('#imagePreviewContainer').removeClass('d-none');
                    } else {
                        $('#imagePreviewContainer').addClass('d-none');
                    }

                    $('#galleryModalLabel').text('Edit Foto');
                    $('#saveBtn').text('Update').prop('disabled', false);
                    galleryModal.show();
                }
            }
        });
    }

    function saveItem() {
        let id = $('#itemId').val();
        let url = id ? API_URL + 'update/' + id : API_URL + 'store';
        let formData = new FormData(document.getElementById('galleryForm'));
        let btn = $('#saveBtn');

        btn.text('Menyimpan...').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    showAlert('success', response.message);
                    galleryModal.hide();
                    table.ajax.reload();
                } else {
                    showAlert('danger', response.message || response.errors);
                }
            },
            error: function () {
                showAlert('danger', 'Terjadi kesalahan sistem.');
            },
            complete: function () {
                btn.text('Simpan').prop('disabled', false);
            }
        });
    }

    function deleteItem(id) {
        if (!confirm('Hapus foto ini dari galeri?')) return;

        $.ajax({
            url: API_URL + 'delete/' + id,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    showAlert('success', response.message);
                    table.ajax.reload();
                } else {
                    showAlert('danger', response.message);
                }
            }
        });
    }

    function showAlert(type, message) {
        let html = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        $('#alert-container').html(html);
        setTimeout(() => $('.alert').alert('close'), 3000);
    }
</script>