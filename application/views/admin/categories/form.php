<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= isset($category) ? 'Edit Kategori' : 'Tambah Kategori' ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/categories') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Use form_open_multipart for file uploads if using CI helper, or standard form with enctype -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= isset($category) ? $category->name : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="3"><?= isset($category) ? $category->description : '' ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Gambar Kategori</label>

                        <!-- Image Preview Area -->
                        <div class="card bg-light mb-3 text-center" style="min-height: 200px; border: 2px dashed #ccc;">
                            <div class="card-body d-flex align-items-center justify-content-center flex-column">
                                <?php
                                $current_image = isset($category) && !empty($category->image) ? $category->image : 'assets/categories/default.jpg';
                                // Handle full URL or relative path
                                $image_url = filter_var($current_image, FILTER_VALIDATE_URL) ? $current_image : base_url($current_image);
                                ?>
                                <img id="imagePreview" src="<?= $image_url ?>" alt="Preview"
                                    class="img-fluid rounded shadow-sm mb-3"
                                    style="max-height: 250px; max-width: 100%;">
                                <div class="small text-muted" id="previewText"><?= basename($current_image) ?></div>
                            </div>
                        </div>

                        <!-- File Input -->
                        <div class="input-group mb-2">
                            <input type="file" class="form-control" id="imageFile" name="image_file" accept="image/*">
                        </div>
                        <div class="form-text">Format: JPG, PNG, WEBP. Maks 2MB.</div>

                        <!-- Fallback / Manual Path Input (Hidden or Advanced) -->
                        <div class="collapse mt-2" id="manualPathCollapse">
                            <div class="card card-body p-2">
                                <label for="image" class="form-label small">Path Manual (Optional)</label>
                                <input type="text" class="form-control form-control-sm" id="image" name="image"
                                    value="<?= isset($category) ? $category->image : '' ?>"
                                    placeholder="assets/categories/...">
                            </div>
                        </div>
                        <a class="small text-decoration-none" data-bs-toggle="collapse" href="#manualPathCollapse"
                            role="button">
                            <i class="fas fa-cog"></i> Advanced: Edit Path Manual
                        </a>
                    </div>

                    <div class="mt-4 border-top pt-3 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i><?= isset($category) ? 'Update' : 'Simpan' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('imageFile').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const preview = document.getElementById('imagePreview');
                preview.src = e.target.result;
                document.getElementById('previewText').textContent = file.name;
            }

            reader.readAsDataURL(file);
        }
    });
</script>