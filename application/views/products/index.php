<section class="section-padding bg-light">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2>Katalog Produk</h2>
            <div class="section-title-line"></div>
        </div>

        <div class="row">
            <!-- Sidebar / Filter -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Cari Produk</h5>
                        <form action="<?= base_url('products') ?>" method="get" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Cari..."
                                    value="<?= isset($search_query) ? htmlspecialchars($search_query) : '' ?>">
                                <button class="btn btn-warning text-white" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <h5 class="fw-bold mb-3">Kategori</h5>
                        <div class="list-group list-group-flush">
                            <a href="<?= base_url('products') ?>"
                                class="list-group-item list-group-item-action <?= empty($selected_category) ? 'active bg-warning border-warning text-dark' : '' ?>">
                                Semua Kategori
                            </a>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <a href="<?= base_url('products?category=' . $cat->id) ?>"
                                        class="list-group-item list-group-item-action <?= (isset($selected_category) && $selected_category == $cat->id) ? 'active bg-warning border-warning text-dark' : '' ?>">
                                        <?= $cat->name ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9">
                <?php if (!empty($search_query)): ?>
                    <p class="mb-4">Menampilkan hasil pencarian untuk:
                        <strong>"<?= htmlspecialchars($search_query) ?>"</strong></p>
                <?php endif; ?>

                <div class="row g-4">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                                        <!-- Fallback image if no image provided -->
                                        <img src="<?= !empty($product->image) ? base_url($product->image) : 'https://placehold.co/300x200?text=No+Image' ?>"
                                            class="card-img-top w-100 h-100 object-fit-cover" alt="<?= $product->name ?>">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-dark mb-2"><?= $product->name ?></h5>
                                        <h6 class="text-warning fw-bold mb-3">Rp
                                            <?= number_format($product->price, 0, ',', '.') ?></h6>
                                        <p class="card-text text-muted small mb-3">
                                            <?= character_limiter($product->description, 60) ?>
                                        </p>
                                        <div class="d-grid">
                                            <a href="https://wa.me/6285608679124?text=Halo,%20saya%20tertarik%20dengan%20produk%20<?= urlencode($product->name) ?>"
                                                target="_blank" class="btn btn-outline-warning btn-sm">
                                                <i class="fab fa-whatsapp me-2"></i>Pesan Sekarang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-box-open text-muted display-1"></i>
                            </div>
                            <h4 class="text-muted">Produk tidak ditemukan</h4>
                            <p class="text-secondary">Coba kata kunci lain atau pilih kategori berbeda.</p>
                            <a href="<?= base_url('products') ?>" class="btn btn-warning mt-3">Lihat Semua Produk</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    .transition {
        transition: all 0.3s ease;
    }
</style>