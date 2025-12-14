<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | TB. Sakura Jaya</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .btn-primary-custom {
            background-color: #FFC300;
            border-color: #FFC300;
            color: #222;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            background-color: #cc9c00;
            border-color: #cc9c00;
        }
    </style>
</head>

<body>
    <div class="card login-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h4 class="fw-bold">TB. Sakura Jaya</h4>
                <p class="text-muted">Login Administrator</p>
            </div>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger text-center">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/login') ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" 所需 required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary-custom btn-lg">Masuk</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="<?= base_url() ?>" class="text-decoration-none text-muted small">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>

</html>