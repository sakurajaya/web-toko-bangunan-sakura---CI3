<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Produk</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#productModal"
            onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>
    </div>
</div>

<div id="alert-container"></div>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-striped table-hover mb-0" id="productsTable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Kode</th>
                <th scope="col">Status Barang</th>
                <th scope="col">Gambar</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Kategori</th>
                <th scope="col">Harga</th>
                <th scope="col" class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <!-- Data will be loaded here via AJAX -->
            <tr>
                <td colspan="6" class="text-center py-4">Memuat data...</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="productForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="productId" name="id">

                    <div class="mb-3">
                        <label for="kode_product" class="form-label">Kode Produk</label>
                        <input type="text" class="form-control" id="kode_product" name="kode_product" required>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (IDR)</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image_file" class="form-label">Gambar</label>
                        <div id="imagePreviewContainer" class="mb-2 d-none">
                            <img id="imagePreview" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                        <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar (saat edit).</small>
                    </div>
                    <div class="mb-3">
                        <label for="status_barang" class="form-label">Status</label>
                        <select class="form-select" id="status_barang" name="status_barang" required>
                            <option value="1">Active</option>
                            <option value="0">Not Active</option>
                            
                        </select>
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
    const API_URL = '<?= base_url("admin/products/") ?>';
    let productModal;
    let table;

    $(document).ready(function () {
        // Initialize Modal
        productModal = new bootstrap.Modal(document.getElementById('productModal'), {
            keyboard: false
        });

        // Initialize DataTable
        table = $('#productsTable').DataTable({
            "ajax": {
                "url": API_URL + 'get_json',
                "dataSrc": ""
            },
            "columns": [
                { "data": null, "render": function(data, type, row, meta) { return meta.row + 1; } },
                { "data": "kode_product", "defaultContent": "-" },
                { "data": "status_barang", "defaultContent": "-" },
                { 
                    "data": "image",
                    "render": function(data) {
                        return data ? `<img src="<?= base_url() ?>${data}" height="50" class="rounded">` : '-';
                    }
                },
                { "data": "name" },
                { "data": "category_name", "defaultContent": "-" },
                { 
                    "data": "price", 
                    "render": function(data) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                {
                    "data": "id",
                    "className": "text-end",
                    "render": function(data) {
                        return `
                            <button class="btn btn-sm btn-outline-secondary me-1" onclick="editProduct(${data})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${data})">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            }
        });

        $('#productForm').on('submit', function (e) {
            e.preventDefault();
            saveProduct();
        });

        // Image Preview Handler
        $('#image_file').change(function(){
            const file = this.files[0];
            if (file){
                let reader = new FileReader();
                reader.onload = function(event){
                    $('#imagePreview').attr('src', event.target.result);
                    $('#imagePreviewContainer').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    });

    function openAddModal() {
        $('#productForm')[0].reset();
        $('#productId').val('');
        $('#productModalLabel').text('Tambah Produk');
        $('#saveBtn').text('Simpan').prop('disabled', false);
        
        // Clear Preview
        $('#imagePreview').attr('src', '');
        $('#imagePreviewContainer').addClass('d-none');
        
        productModal.show();
    }

    function editProduct(id) {
        $.ajax({
            url: API_URL + 'get_item/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    const data = response.data;
                    $('#productId').val(data.id);
                    $('#kode_product').val(data.kode_product);
                    $('#name').val(data.name);
                    $('#category_id').val(data.category_id);
                    $('#price').val(data.price);
                    $('#description').val(data.description);
                    $('#status_id').val(data.status_id);

                    // Handle Image Preview
                    if(data.image) {
                        let imgUrl = data.image.startsWith('http') ? data.image : '<?= base_url() ?>' + data.image;
                        $('#imagePreview').attr('src', imgUrl);
                        $('#imagePreviewContainer').removeClass('d-none');
                    } else {
                        $('#imagePreviewContainer').addClass('d-none');
                    }

                    $('#productModalLabel').text('Edit Produk');
                    $('#saveBtn').text('Update').prop('disabled', false);

                    productModal.show();
                }
            }
        });
    }

    function saveProduct() {
        let id = $('#productId').val();
        let url = id ? API_URL + 'update/' + id : API_URL + 'store';
        let formData = new FormData(document.getElementById('productForm'));
        
        // Loading State
        let btn = $('#saveBtn');
        let originalText = btn.text();
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
                    productModal.hide();
                    table.ajax.reload(); // Reload DataTables
                } else {
                    showAlert('danger', response.message || response.errors);
                }
            },
            error: function () {
                showAlert('danger', 'Terjadi kesalahan sistem.');
            },
            complete: function() {
                btn.text(originalText).prop('disabled', false);
            }
        });
    }

    function deleteProduct(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) return;

        $.ajax({
            url: API_URL + 'delete/' + id,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    showAlert('success', response.message);
                    table.ajax.reload(); // Reload DataTables
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

        setTimeout(() => {
            $('.alert').alert('close');
        }, 3000);
    }
</script>