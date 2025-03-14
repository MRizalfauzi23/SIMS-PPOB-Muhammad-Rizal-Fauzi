function processPayment() {
  let serviceCode = "<?= esc($serviceCode) ?>";
  let serviceName = "<?= esc($service_name) ?>";
  let serviceIcon = "<?= base_url('img/' . $selectedService[1]) ?>";
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
