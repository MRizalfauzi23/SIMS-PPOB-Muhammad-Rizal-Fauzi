<main class="container my-5">
<div class="row">
        <!-- Profile & Saldo -->
        <div class="col-md-4 text-center">
            <img src="<?= esc($profile['profile_image'] ?? base_url('assets/default-avatar.png')) ?>"> 
            
                    <h5 class="text-muted mb-0">Selamat datang,</h5>
                    <h4 class="fw-bold"><?= esc($profile['first_name'] ?? 'Nama') ?> <?= esc($profile['last_name'] ?? 'Pengguna') ?></h4>
                   
                </div>
            
            <div class="col-md-8">
        <div class="balance-card ">
            
                <p class="mb-1">Saldo Anda</p>
                <h2 class="fw-bold" id="saldo">Rp ••••••••</h2>
                <button class="btn btn-sm text-white" onclick="toggleSaldo()">
                    <i class="fa-solid fa-eye"></i> <span id="toggleText">Lihat Saldo</span>
                </button>
            </div>
        </div>
    </div>
<style>
    /* Efek hover untuk menu */
.hover-effect {
    transition: all 0.3s ease-in-out;
}

.hover-effect:hover {
    background-color: rgba(0, 123, 255, 0.1);
    color: #007bff !important;
}

/* Animasi dropdown */
.dropdown-menu {
    transform: translateY(10px);
    opacity: 0;
    transition: all 0.3s ease-in-out;
    display: block;
    visibility: hidden;
}

.nav-item.dropdown:hover .dropdown-menu {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

/* Tombol toggler lebih elegan */
.navbar-toggler {
    padding: 6px 10px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.navbar-toggler:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

        .balance-card {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
            border-radius: 15px;
            padding: 20px;
        }
        .icon-card img {
            width: 50px;
            height: 50px;
        }
        .hidden-balance {
            letter-spacing: 5px;
        }
    
</style>
<script>
        let saldoTerlihat = false;
        let saldoAsli = "<?= number_format($balance, 0, ',', '.') ?>";

        function toggleSaldo() {
            let saldoElement = document.getElementById('saldo');
            let toggleText = document.getElementById('toggleText');
            saldoTerlihat = !saldoTerlihat;
            saldoElement.innerText = saldoTerlihat ? `Rp ${saldoAsli}` : "Rp ••••••••";
            toggleText.innerText = saldoTerlihat ? "Sembunyikan Saldo" : "Lihat Saldo";
        }
    </script>