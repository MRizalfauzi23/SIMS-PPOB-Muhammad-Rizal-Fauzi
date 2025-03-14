<?php
$serviceCode = $_GET['service'] ?? 'PGN';
$serviceDetails = [
    "PBB" => ["PBB", "PBB.png", 100000],
    "Listrik" => ["Listrik Prabayar", "listrik.png", 50000],
    "Pulsa" => ["Pulsa", "pulsa.png", 20000],
    "PDAM" => ["PDAM", "PDAM.png", 75000],
    "PGN" => ["PGN Berlangganan", "PGN.png", 50000],
    "TV Langganan" => ["TV Langganan", "Televisi.png", 150000],
    "Musik" => ["Musik Streaming", "Musik.png", 25000],
    "Game" => ["Game Voucher", "Game.png", 50000],
    "Voucher Makanan" => ["Voucher Makanan", "Voucher Makanan.png", 100000],
    "Kurban" => ["Kurban", "Kurban.png", 500000],
    "Zakat" => ["Zakat", "Zakat.png", 100000],
    "Paket Data" => ["Paket Data", "Paket Data.png", 50000]
];

$selectedService = $serviceDetails[$serviceCode] ?? ["PGN Berlangganan", "PGN.png", 50000];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMS PPOB - Pembayaran</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
</head>
<body class="bg-light">

<?= $this->include('layout/navbar') ?> <!-- Navbar -->
<?= $this->include('layout/balance') ?> <!-- Balance Section -->

<div class="container mt-4">
    <h4 class="fw-semibold">Pembayaran</h4>
    
    <!-- Informasi Layanan -->
    <div class="d-flex align-items-center mt-3">
        <img src="<?= esc($service_icon) ?>" alt="Service Icon" width="40" class="me-3">
        <span class="fs-5 fw-semibold"><?= esc($service_name) ?></span>
        
    </div>

    <!-- Input Nominal -->
    <div class="mt-3">
        <label class="form-label">Jumlah Pembayaran</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-money-bill-wave text-success"></i></span>
            <input type="number" id="nominal" class="form-control" value="<?= esc($service_tariff) ?>" min="1000">
        </div>
    </div>

    <!-- Tombol Bayar -->
    <button class="btn btn-danger w-100 mt-3" onclick="processPayment()">
        <i class="fas fa-wallet"></i> Bayar Sekarang
    </button>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<Script>
    function processPayment() {
  let serviceCode = "<?= esc($serviceCode) ?>";
  let serviceName = "<?= esc($service_name) ?>";
  let serviceIcon = "<?= esc($service_icon) ?>";
  let serviceTariff = document.getElementById("nominal").value;

  // Validasi input minimal Rp 1.000
  if (serviceTariff < 1000) {
    Swal.fire({
      icon: "warning",
      title: "Oops!",
      text: "Nominal pembayaran minimal Rp 1.000",
    });
    return;
  }

  // Konfirmasi pembayaran
  Swal.fire({
    title: "Konfirmasi Pembayaran",
    text: `Anda yakin ingin membayar ${serviceName} sebesar Rp ${Number(
      serviceTariff
    ).toLocaleString()}?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, Bayar Sekarang!",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Memproses Pembayaran...",
        html: "Mohon tunggu sebentar",
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });

      // Proses pembayaran
      fetch("<?= base_url('transaction/payment') ?>", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        body: JSON.stringify({
          service_code: serviceCode,
          service_name: serviceName,
          service_icon: serviceIcon,
          service_tariff: serviceTariff,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          Swal.close();
          if (data.success) {
            Swal.fire({
              icon: "success",
              title: "Transaksi Berhasil!",
              text: "Pembayaran Anda telah berhasil diproses.",
              timer: 2000,
              showConfirmButton: false,
            }).then(() => {});
          } else {
            Swal.fire({
              icon: "error",
              title: "Transaksi Gagal!",
              text: data.message,
            });
          }
        })
        .catch((error) => {
          Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "Terjadi kesalahan saat memproses pembayaran.",
          });
        });
    }
  });
}

</script>


</body>
</html>
