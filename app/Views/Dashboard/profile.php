<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<?= $this->include('layout/navbar') ?> <!-- Memanggil navbar -->

<div class="container mt-4">
<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= esc(session()->getFlashdata('success')) ?>',
            showConfirmButton: false,
            timer: 2500
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= esc(session()->getFlashdata('error')) ?>',
            showConfirmButton: true
        });
    </script>
<?php endif; ?>


    <div class="text-center mb-4">
        <div class="position-relative d-inline-block">
            <img src="<?= esc($user['profile_image'] ?? 'https://via.placeholder.com/120') ?>" 
                 class="rounded-circle border shadow-sm" width="120" height="120" alt="Profile Picture">
            
            <!-- Tombol upload foto -->
            <button class="btn btn-light border rounded-circle position-absolute bottom-0 end-0 p-1"
                    onclick="document.getElementById('file-input').click();">
                <i class="fas fa-camera"></i>
            </button>
            
            <!-- Input file tersembunyi -->
            <form action="<?= base_url('profile/uploadImage') ?>" method="post" enctype="multipart/form-data" id="upload-form">
                <input type="file" id="file-input" name="file" accept="image/jpeg, image/png" class="d-none" 
                       onchange="document.getElementById('upload-form').submit();">
            </form>
        </div>
        <h1 class="mt-3 fw-semibold"><?= esc($user['first_name'] ?? 'Nama') ?> <?= esc($user['last_name'] ?? 'Pengguna') ?></h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="<?= base_url('profile/updateProfile') ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" 
                               value="<?= esc($user['email'] ?? '') ?>" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Depan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="first_name" 
                               value="<?= esc($user['first_name'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Belakang</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="last_name" 
                               value="<?= esc($user['last_name'] ?? '') ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-danger w-100">Simpan</button>
            </form>
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-outline-secondary w-100 mt-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
