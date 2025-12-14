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
                        <!-- Search Form: Handled by JS -->
                        <form id="searchForm" class="mb-4">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari..."
                                    value="<?= isset($initial_search) ? htmlspecialchars($initial_search) : '' ?>">
                                <button class="btn btn-warning text-white" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <h5 class="fw-bold mb-3">Kategori</h5>
                        <div class="list-group list-group-flush" id="categoryList">
                            <!-- Helper for cleaner JS logic: store ID in data attribute -->
                            <a href="#"
                                class="category-link list-group-item list-group-item-action <?= empty($initial_category) ? 'active bg-warning border-warning text-dark' : '' ?>"
                                data-id="">
                                Semua Kategori
                            </a>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <a href="#"
                                        class="category-link list-group-item list-group-item-action <?= (isset($initial_category) && $initial_category == $cat->id) ? 'active bg-warning border-warning text-dark' : '' ?>"
                                        data-id="<?= $cat->id ?>">
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
                <!-- Search Result Info -->
                <div id="searchInfo" class="mb-4 d-none">
                    <p>Menampilkan hasil pencarian untuk: <strong id="searchKeyword"></strong></p>
                </div>

                <!-- Product Container -->
                <div id="productGrid" class="row g-4">
                    <!-- Products will be injected here via JS -->
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- No Results State -->
                <div id="noResults" class="col-12 text-center py-5 d-none">
                    <div class="mb-3">
                        <i class="fas fa-box-open text-muted display-1"></i>
                    </div>
                    <h4 class="text-muted">Produk tidak ditemukan</h4>
                    <p class="text-secondary">Coba kata kunci lain atau pilih kategori berbeda.</p>
                    <button class="btn btn-warning mt-3" onclick="resetFilters()">Lihat Semua Produk</button>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentCategory = '<?= isset($initial_category) ? $initial_category : "" ?>';
        let currentSearch = '<?= isset($initial_search) ? $initial_search : "" ?>';

        // Initial Load
        fetchProducts(currentCategory, currentSearch);

        // Search Handler
        document.getElementById('searchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            currentSearch = document.getElementById('searchInput').value;
            currentCategory = ''; // Reset category on new search? Or keep it? Usually reset or keep. Let's keep strict search.

            // Update UI for Categories (Reset active)
            document.querySelectorAll('.category-link').forEach(l => {
                l.classList.remove('active', 'bg-warning', 'border-warning', 'text-dark');
                if (l.dataset.id === '') l.classList.add('active', 'bg-warning', 'border-warning', 'text-dark');
            });

            fetchProducts('', currentSearch);
        });

        // Category Click Handler
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                // UI Update
                document.querySelectorAll('.category-link').forEach(l => l.classList.remove('active', 'bg-warning', 'border-warning', 'text-dark'));
                this.classList.add('active', 'bg-warning', 'border-warning', 'text-dark');

                // Logic
                currentCategory = this.dataset.id;
                currentSearch = ''; // Clear search when clicking category
                document.getElementById('searchInput').value = '';

                fetchProducts(currentCategory, '');
            });
        });
    });

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        // Click "All Categories"
        document.querySelector('.category-link[data-id=""]').click();
    }

    function fetchProducts(category, search) {
        const grid = document.getElementById('productGrid');
        const spinner = document.getElementById('loadingSpinner');
        const noResults = document.getElementById('noResults');
        const searchInfo = document.getElementById('searchInfo');
        const keywordSpan = document.getElementById('searchKeyword');

        // Show Loader
        grid.innerHTML = '';
        spinner.classList.remove('d-none');
        noResults.classList.add('d-none');

        // Update Search Info Text
        if (search) {
            searchInfo.classList.remove('d-none');
            keywordSpan.textContent = `"${search}"`;
        } else {
            searchInfo.classList.add('d-none');
        }

        // Update URL (PushState)
        const url = new URL(window.location);
        if (category) url.searchParams.set('category', category); else url.searchParams.delete('category');
        if (search) url.searchParams.set('q', search); else url.searchParams.delete('q');
        window.history.pushState({}, '', url);

        // API Call
        const apiUrl = `<?= base_url('products/get_json') ?>?category=${category}&q=${search}`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                spinner.classList.add('d-none');

                if (data.data.length > 0) {
                    renderProducts(data.data);
                } else {
                    noResults.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                spinner.classList.add('d-none');
            });
    }

    function renderProducts(products) {
        const grid = document.getElementById('productGrid');
        let html = '';

        products.forEach(product => {
            // Safe check for image
            let imgUrl = product.image ? product.image : 'https://placehold.co/300x200?text=No+Image';
            // Check if full url or needs base_url (simple check)
            if (!imgUrl.startsWith('http')) {
                imgUrl = '<?= base_url() ?>' + imgUrl;
            }

            // Format Price
            const price = new Intl.NumberFormat('id-ID').format(product.price);

            // Truncate Description (Safe check)
            const description = product.description || '';
            const desc = description.length > 60 ? description.substring(0, 60) + '...' : description;

            html += `
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                <div class="position-relative" style="height: 200px; overflow: hidden;">
                    <img src="${imgUrl}" 
                         class="card-img-top w-100 h-100 object-fit-cover" 
                         alt="${product.name}">
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold text-dark mb-2">${product.name}</h5>
                    <h8 class="fw-bold text-dark mb-2">${product.kode_product}</h5>
                    <h6 class="text-warning fw-bold mb-3">Rp ${price}</h6>
                    <p class="card-text text-muted small mb-3">
                        ${desc}
                    </p>
                    <div class="d-grid">
                        <a href="https://wa.me/6285608679124?text=Halo,%20saya%20tertarik%20dengan%20produk%20${encodeURIComponent(product.name)}%20kode:%20${encodeURIComponent(product.kode_product)}" 
                           target="_blank" 
                           class="btn btn-outline-warning btn-sm">
                           <i class="fab fa-whatsapp me-2"></i>Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
        `;
        });

        grid.innerHTML = html;
    }
</script>