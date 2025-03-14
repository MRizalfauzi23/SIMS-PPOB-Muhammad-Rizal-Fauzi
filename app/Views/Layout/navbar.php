<!-- Header -->
<header class="bg-white shadow-sm py-3">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <!-- Logo & Brand -->
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url() . "/dashboard" ?>">
                <img src="<?= base_url('img/logo.png') ?>" alt="SIMS PPOB Logo" height="40" class="me-2">
                <h5 class="mb-0 fw-bold text-dark">SIMS PPOB</h5>
            </a>

            <!-- Toggle Button untuk Mobile -->
            <button class="navbar-toggler border-0 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Menu Navbar -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 py-2 rounded-3 hover-effect" href="<?= base_url("/transaction/topup") ?>">Top Up</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 py-2 rounded-3 hover-effect" href="<?= base_url() . "/transaction" ?>">Transaction</a>
                    </li>

                    <!-- Dropdown Akun -->
                    <li class="nav-item dropdown">
                        <button class="btn btn-primary dropdown-toggle px-3 py-2 d-flex align-items-center gap-2 rounded-pill shadow-sm" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user"></i> <?= esc($profile['name'] ?? 'Akun') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                            <li><a class="dropdown-item py-2" href="<?= base_url('/profile') ?>">Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?= base_url('logout') ?>" method="get">
                                    <button type="submit" class="dropdown-item py-2 text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<!-- CSS tambahan untuk efek hover -->
<style>
    .hover-effect {
        color: #333;
        transition: all 0.3s ease-in-out;
    }
    .hover-effect:hover {
        background-color: rgba(0, 123, 255, 0.1);
        color: #007bff;
    }
    .navbar-toggler {
        font-size: 1.2rem;
    }
</style>
