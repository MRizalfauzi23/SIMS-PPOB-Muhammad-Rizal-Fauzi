<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - SIMS PPOB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #FFF5F5; }
        .card { border: none; }
        .form-control { height: 45px; border-radius: 10px; }
        .btn-danger { border-radius: 10px; height: 45px; }
        .input-group-text { background: transparent; border-right: none; }
        .form-control:focus { box-shadow: none; border-color: #ff4d4d; }
        .alert { display: none; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg rounded-4">
                    <div class="row g-0">
                        <!-- Form Registrasi -->
                        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center text-center p-5">
                            <div class="d-flex align-items-center mb-4">
                                <img src="<?= base_url('img/logo.png') ?>" alt="Logo" class="me-2" style="max-width: 40px;">
                                <h2 class="fw-bold mb-0">SIMS PPOB</h2>
                            </div>
                            <h2 class="h5 mb-4 fw-semibold">Lengkapi data untuk membuat akun</h2>

                            <!-- ALERT MESSAGE -->
                            <div id="response-message" class="alert"></div>

                            <form id="registerForm" action="<?= base_url('auth/register') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Nama depan" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Nama belakang" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Buat password" required minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$">
                                </div>
                                <div class="mb-3">
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Konfirmasi password" required minlength="8">
                                    <small id="error-message" class="text-danger d-none">Password tidak cocok!</small>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Registrasi</button>
                            </form>
                            <p class="mt-3 text-center">Sudah punya akun? <a href="<?= base_url('/') ?>" class="text-danger fw-bold">Login di sini</a></p>
                        </div>

                        <!-- Gambar -->
                        <div class="col-md-6 d-flex align-items-center justify-content-center" style="background-color: #FFF5F5;">
                            <img src="<?= base_url('img/Login.png') ?>" class="img-fluid" alt="Illustrasi Login" style="max-width: 80%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT VALIDASI & REQUEST -->
    <script>
       document.getElementById('registerForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirm_password').value;
    let errorMessage = document.getElementById('error-message');
    let responseMessage = document.getElementById('response-message');

    if (password !== confirmPassword) {
        errorMessage.classList.remove('d-none');
        return;
    } else {
        errorMessage.classList.add('d-none');
    }

    let formData = {
        email: document.getElementById('email').value,
        first_name: document.getElementById('first_name').value,
        last_name: document.getElementById('last_name').value,
        password: password
    };

    try {
        let response = await fetch('<?= base_url('auth/register') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        let text = await response.text(); // Debugging
        console.log("Raw response:", text);

        let result = JSON.parse(text); // Parsing manual
        
        if (response.ok && result.status === 1) {
            Swal.fire({
                title: "Registrasi Berhasil!",
                text: result.message,
                icon: "success",
                confirmButtonColor: "#ff4d4d"
            }).then(() => {
                window.location.href = "<?= base_url('/') ?>";
            });
        } else {
            Swal.fire({
                title: "Registrasi Gagal!",
                text: result.message,
                icon: "error",
                confirmButtonColor: "#ff4d4d"
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan. Coba lagi nanti.",
            icon: "error",
            confirmButtonColor: "#ff4d4d"
        });
        console.error("Error:", error);
    }
});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
