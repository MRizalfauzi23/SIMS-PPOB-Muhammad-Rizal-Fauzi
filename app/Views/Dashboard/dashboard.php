<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMS PPOB - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        .service-option {
            width: 80px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .service-option:hover {
            transform: scale(1.1);
        }
        .promo-card {
            width: 180px;
        }
        .promo-card img {
            max-height: 120px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">
    <?= $this->include('layout/navbar') ?>
    <?= $this->include('layout/balance') ?>

    <!-- Layanan -->
    <div class="container mt-4 text-center">
        <h5 class="fw-bold">Layanan</h5>
        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
            <?php
            $icons = [
                ["PBB", "PBB.png"], ["Listrik", "listrik.png"], ["Pulsa", "pulsa.png"],
                ["PDAM", "PDAM.png"], ["PGN", "PGN.png"], ["TV Langganan", "Televisi.png"],
                ["Musik", "Musik.png"], ["Game", "Game.png"], ["Voucher Makanan", "Voucher Makanan.png"],
                ["Kurban", "Kurban.png"], ["Zakat", "Zakat.png"], ["Paket Data", "Paket Data.png"]
            ];
            foreach ($icons as $icon) : ?>
                <div class="text-center p-2 service-option" onclick="redirectToPayment('<?= esc($icon[0]) ?>')">
                    <img src="<?= base_url('img/' . $icon[1]) ?>" alt="<?= esc($icon[0]) ?>" class="img-fluid" style="height: 50px;">
                    <p class="mt-1 mb-0 small fw-semibold"><?= esc($icon[0]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Promo Banner -->
    <div class="container mt-4">
        <h5 class="fw-bold">Temukan Promo Menarik</h5>
        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
            <?php if (!empty($banners) && is_array($banners)) : ?>
                <?php foreach ($banners as $banner) : ?>
                    <div class="card shadow-sm border-0 text-center promo-card">
                        <img src="<?= isset($banner['banner_image']) ? esc($banner['banner_image']) : base_url('img/default-banner.jpg') ?>" class="card-img-top rounded-top" alt="Promo">
                        <div class="card-body p-2">
                            <h6 class="fw-bold small"> <?= esc($banner['banner_name'] ?? 'Promo') ?> </h6>
                            <p class="text-muted small m-0"> <?= esc($banner['description'] ?? 'Promo menarik tersedia.') ?> </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-muted text-center">Tidak ada promo saat ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function redirectToPayment(serviceCode) {
            window.location.href = "<?= base_url('transaction/payment') ?>" + "/" + encodeURIComponent(serviceCode);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
