<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-4">Bangun Rumah Impian Anda Bersama Kami</h1>
        <p class="lead mb-4 mx-auto" style="max-width: 700px;">Menyediakan bahan bangunan lengkap, alat listrik, dan
            kebutuhan pertukangan dengan kualitas terbaik dan harga bersaing.</p>
        <a href="<?= base_url('products') ?>" class="btn btn-primary-custom btn-lg rounded-pill px-5">Lihat Katalog</a>
    </div>
</section>

</section>

<!-- New Arrivals Section -->
<?php if (!empty($new_arrivals)): ?>
    <section id="new-arrivals" class="section-padding bg-light">
        <div class="container">
            <div class="section-title">
                <h2>Our New Arrival</h2>
                <div class="section-title-line"></div>
                <p class="text-muted mt-2">Produk terbaru pilihan kami untuk Anda</p>
            </div>

            <!-- Swiper Container -->
            <div class="swiper newArrivalsSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($new_arrivals as $item): ?>
                        <?php
                        $imgUrl = !empty($item->image) ? base_url($item->image) : 'https://placehold.co/300x200?text=No+Image';
                        $price = number_format($item->price, 0, ',', '.');
                        $desc = strlen($item->description) > 60 ? substr($item->description, 0, 60) . '...' : $item->description;
                        ?>
                        <div class="swiper-slide h-auto">
                            <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                                <div class="position-relative" style="height: 250px; overflow: hidden;">
                                    <img src="<?= $imgUrl ?>" class="card-img-top w-100 h-100 object-fit-cover"
                                        alt="<?= $item->name ?>">
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-warning text-dark">New</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-dark mb-2"><?= $item->name ?></h5>
                                    <h6 class="text-warning fw-bold mb-3">Rp <?= $price ?></h6>
                                    <div class="d-grid">
                                        <a href="https://wa.me/6285608679124?text=Halo,%20saya%20tertarik%20dengan%20produk%20baru%20ini:%20<?= urlencode($item->name) ?>"
                                            target="_blank" class="btn btn-outline-warning btn-sm">
                                            <i class="fab fa-whatsapp me-2"></i>Pesan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination mt-4"></div>
            </div>

            <!-- Swiper Init -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const swiper = new Swiper(".newArrivalsSwiper", {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: true,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 30,
                            },
                            1024: {
                                slidesPerView: 4,
                                spaceBetween: 30,
                            },
                        },
                    });
                });
            </script>
        </div>
    </section>
<?php endif; ?>

<!-- About Section -->
<section id="about" class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4 position-relative pb-2">Mitra Bangunan Terpercaya Sejak 2010 <div
                        class="position-absolute bottom-0 start-0 bg-warning" style="width: 80px; height: 4px;"></div>
                </h2>
                <p class="text-secondary mb-4">TB. Sakura Jaya telah melayani ribuan pelanggan untuk kebutuhan renovasi
                    dan konstruksi. Kami mengutamakan kualitas barang dan pelayanan yang ramah.</p>
                <p class="text-secondary mb-4">Kami bekerja sama dengan berbagai supplier terkemuka untuk memastikan
                    Anda mendapatkan produk asli dengan ketahanan yang teruji.</p>

                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check-circle text-warning fs-4 me-3"></i>
                    <span class="fw-bold">Produk Lengkap & Berkualitas</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-warning fs-4 me-3"></i>
                    <span class="fw-bold">Pengiriman Cepat & Tepat</span>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('assets/hero1.jpeg') ?>" alt="Tentang Kami"
                    class="img-fluid rounded-3 shadow-lg border border-5 border-white">
            </div>
        </div>
    </div>
</section>

<!-- Products Categories Section -->
<section id="products" class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Kategori Produk</h2>
            <div class="section-title-line"></div>
        </div>

        <div class="row g-4">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <div class="col-lg-3 col-md-6">
                        <a href="<?= base_url('products?category=' . $cat->id) ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 hover-shadow transition">
                                <div class="position-relative" style="height: 200px; overflow: hidden;">
                                    <img src="<?= base_url($cat->image) ?>" class="card-img-top w-100 h-100 object-fit-cover"
                                        alt="<?= $cat->name ?>">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold"><?= $cat->name ?></h5>
                                    <p class="card-text text-muted small"><?= $cat->description ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback if database is empty -->
                <div class="col-12 text-center text-muted">
                    <p>Silakan import database.sql untuk melihat kategori.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h2>Keunggulan Kami</h2>
            <div class="section-title-line"></div>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="p-4 border rounded-3 h-100 bg-white shadow-sm hover-shadow">
                    <i class="fas fa-tags text-warning display-4 mb-3"></i>
                    <h5 class="fw-bold">Harga Bersaing</h5>
                    <p class="text-muted small">Harga terbaik untuk pelanggan setia kami.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4 border rounded-3 h-100 bg-white shadow-sm">
                    <i class="fas fa-truck text-warning display-4 mb-3"></i>
                    <h5 class="fw-bold">Siap Antar</h5>
                    <p class="text-muted small">Layanan pesan antar ke lokasi proyek Anda.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4 border rounded-3 h-100 bg-white shadow-sm">
                    <i class="fas fa-thumbs-up text-warning display-4 mb-3"></i>
                    <h5 class="fw-bold">Produk Asli</h5>
                    <p class="text-muted small">Garansi keaslian untuk setiap barang.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4 border rounded-3 h-100 bg-white shadow-sm">
                    <i class="fas fa-headset text-warning display-4 mb-3"></i>
                    <h5 class="fw-bold">Konsultasi Gratis</h5>
                    <p class="text-muted small">Bingung? Kami siap membantu menghitung kebutuhan Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Galeri Toko</h2>
            <div class="section-title-line"></div>
        </div>

        <div class="row g-3">
            <?php if (!empty($gallery)): ?>
                <?php foreach ($gallery as $item): ?>
                    <div class="col-md-3 col-6">
                        <div class="position-relative overflow-hidden rounded shadow-sm group-hover-zoom"
                            style="height: 250px;">
                            <img src="<?= base_url($item->image) ?>" alt="<?= $item->title ?>"
                                class="w-100 h-100 object-fit-cover">
                            <div
                                class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-warning bg-opacity-75 opacity-0 hover-opacity-100 transition-opacity">
                                <span class="fw-bold text-dark"><?= $item->title ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">
                    <p>Belum ada foto galeri.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Interactive styling for gallery hover -->
        <style>
            .hover-opacity-100:hover {
                opacity: 1 !important;
            }

            .transition-opacity {
                transition: opacity 0.3s ease;
            }
        </style>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="section-padding bg-dark text-white">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <h2 class="text-warning mb-4">Hubungi Kami</h2>

                <div class="d-flex mb-4">
                    <div class="bg-warning bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-map-marker-alt text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Alamat</h5>
                        <p class="text-secondary mb-0">Jl. Raya Talun depan Kantor POS Talun, Talun - Blitar</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-warning bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-phone-alt text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Telepon / WhatsApp</h5>
                        <p class="text-secondary mb-0">0856-0867-9124</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-warning bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-envelope text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Email</h5>
                        <p class="text-secondary mb-0">info@tbSakurajaya.com</p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="bg-warning bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Jam Operasional</h5>
                        <p class="text-secondary mb-0">Senin - Sabtu: 08.00 - 17.00 WIB</p>
                        <p class="text-secondary mb-0">Minggu: Libur</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="bg-secondary bg-opacity-25 p-5 rounded-3">
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control bg-dark border-secondary text-white p-3"
                                placeholder="Nama Anda" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control bg-dark border-secondary text-white p-3"
                                placeholder="Email Anda">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control bg-dark border-secondary text-white p-3" rows="5"
                                placeholder="Pesan Anda" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100 py-3">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>