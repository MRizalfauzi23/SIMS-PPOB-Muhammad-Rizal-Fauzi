<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    
<?= $this->include('layout/navbar') ?> <!-- Navbar -->
<?= $this->include('layout/balance') ?> <!-- Saldo -->

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form id="topupForm" action="<?= base_url('transaction/process') ?>" method="post">
                <?= csrf_field(); ?>

                <div class="input-group mt-3 mb-2">
                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                    <input type="text" class="form-control" id="nominal" name="nominal" placeholder="Masukkan nominal Top Up">
                </div>

                <button type="submit" id="topupBtn" class="btn btn-secondary w-100 py-2">Top Up</button>
            </form>
        </div>

       <!-- Pilihan Nominal -->
        <div class="col-md-4">
            <div class="row g-2">
                <?php 
                $nominals = [10000, 20000, 50000, 100000, 250000, 500000];
                foreach ($nominals as $value): ?>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-secondary w-100 nominal-btn" data-value="<?= $value; ?>">
                            Rp<?= number_format($value, 0, ',', '.'); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
       document.addEventListener("DOMContentLoaded", function () {
    let nominalInput = document.getElementById('nominal');
    let topupForm = document.getElementById('topupForm');
    let topupBtn = document.getElementById('topupBtn');

    // Event listener untuk memilih nominal dari tombol
    document.querySelectorAll('.nominal-btn').forEach(button => {
        button.addEventListener('click', function () {
            nominalInput.value = this.getAttribute('data-value');
        });
    });

    // Validasi sebelum submit form
    topupBtn.addEventListener('click', function (event) {
        event.preventDefault();

        let nominal = parseInt(nominalInput.value.replace(/\D/g, '')); // Hapus karakter non-numeric

        if (!nominal || isNaN(nominal) || nominal < 1000) {
            Swal.fire({
                icon: 'error',
                title: 'Nominal Tidak Valid',
                text: 'Masukkan nominal yang sesuai (minimal Rp 1.000).'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Top Up',
            text: "Anda akan menambahkan saldo sebesar Rp " + new Intl.NumberFormat('id-ID').format(nominal),
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                topupForm.submit();
            }
        });
    });

    // Tampilkan alert dari Flashdata jika ada (prioritas sukses)
    let successMessage = "<?= esc(session()->getFlashdata('success')) ?>";
    let errorMessage = "<?= esc(session()->getFlashdata('error')) ?>";

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage,
            confirmButtonColor: '#3085d6'
        });
    } else if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorMessage
        });
    }
});

    </script>

</body>
</html>