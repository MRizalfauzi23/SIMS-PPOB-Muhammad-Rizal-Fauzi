<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIMS PPOB</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

<div class="container">
    <div class="row g-0 shadow-lg bg-white rounded overflow-hidden">
        
        <!-- Form Login -->
        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center text-center p-5">
            <!-- Logo dan Judul -->
            <div class="d-flex align-items-center mb-4">
                <img src="<?= base_url('img/logo.png') ?>" alt="Logo" class="me-2" style="max-width: 40px;">
                <h2 class="fw-bold mb-0">SIMS PPOB</h2>
            </div>

            <h3 class="fw-semibold mb-4">Masuk atau buat akun untuk memulai</h3>

            <form action="<?= base_url('auth/login') ?>" method="post">
                <?= csrf_field() ?> <!-- Tambahkan CSRF protection -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Masukkan email anda" value="<?= old('email') ?>" required autofocus>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan password anda" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger w-100">Masuk</button>
            </form>

            <p class="mt-3">Belum punya akun? <a href="<?= base_url('register') ?>" class="text-danger">Registrasi di sini</a></p>
        </div>

        <!-- Gambar -->
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="background-color: #FCE8E6;">
            <img src="<?= base_url('img/Login.png') ?>" alt="Illustrasi Login" class="img-fluid w-75">
        </div>
    </div>
</div>

<!-- Menampilkan SweetAlert jika ada flashdata -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    let successMessage = "<?= session()->getFlashdata('success') ?>";
    let errorMessage = "<?= session()->getFlashdata('error') ?>";

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    } else if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: errorMessage,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Coba Lagi'
        });
    }
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>