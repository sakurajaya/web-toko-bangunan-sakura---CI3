<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TB. Sakura Jaya | Pusat Bahan Bangunan & Alat Listrik</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #FFC300;
            --primary-dark: #cc9c00;
            --contrast: #222222;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            padding-top: 76px;
            /* Offset for fixed navbar */
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }

        .text-primary-custom {
            color: var(--primary) !important;
        }

        .bg-primary-custom {
            background-color: var(--primary) !important;
        }

        .btn-primary-custom {
            background-color: var(--primary);
            border-color: var(--primary);
            color: var(--contrast);
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            background-color: transparent;
            color: var(--contrast);
            border-color: var(--contrast);
        }

        /* Navbar */
        .navbar-brand span {
            color: var(--primary-dark);
        }

        .nav-link {
            font-weight: 500;
            color: var(--contrast) !important;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-dark) !important;
        }

        /* Hero */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?= base_url("assets/hero.jpg") ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            min-height: 500px;
            display: flex;
            align-items: center;
            color: white;
            margin-top: -76px;
            /* Counteract body padding for full screen hero */
            padding-top: 76px;
        }

        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            margin-bottom: 50px;
            text-align: center;
        }

        .section-title h2 {
            margin-bottom: 10px;
        }

        .section-title-line {
            width: 60px;
            height: 4px;
            background-color: var(--primary);
            margin: 0 auto;
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="<?= base_url() ?>">TB. <span>Sakura Jaya</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="<?= base_url('#home') ?>">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('#about') ?>">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('products') ?>">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('#gallery') ?>">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('#contact') ?>">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>