<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaction Page</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">

    <?= $this->include('layout/navbar') ?> <!-- Navbar -->
    <?= $this->include('layout/balance') ?> <!-- Balance Section -->
    
    <!-- Transactions Section -->
    <section class="mt-4">
        <h2 class="h5 fw-bold mb-3">Semua Transaksi</h2>
        <div class="list-group" id="transactionsList">
            <?php if (!empty($transactions) && is_array($transactions)): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center fade-in">
                        <div>
                            <p class="fw-bold mb-0 <?= ($transaction['transaction_type'] === 'TOPUP') ? 'text-success' : 'text-danger' ?>">
                                <?= ($transaction['transaction_type'] === 'TOPUP') ? '+ ' : '- ' ?>Rp <?= number_format($transaction['total_amount'], 0, ',', '.') ?>
                            </p>
                            <small class="text-muted">
                                <?= date('d M Y H:i', strtotime($transaction['created_on'])) ?> WIB
                            </small>
                        </div>
                        <span class="text-muted"> <?= esc($transaction['description'] ?? 'Transaksi') ?> </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">Tidak ada transaksi.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($transactions) && count($transactions) >= 10): ?>
        <div class="text-center mt-3">
            <button id="showMoreBtn" class="btn btn-link text-danger fw-bold" data-page="1">Show more</button>
        </div>
    <?php endif; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const showMoreBtn = document.getElementById('showMoreBtn');
            const transactionsContainer = document.getElementById('transactionsList');

            if (showMoreBtn) {
                showMoreBtn.addEventListener('click', function () {
                    let page = parseInt(showMoreBtn.getAttribute('data-page')) + 1;
                    fetch("<?= base_url('transaction/more') ?>?page=" + page, {
                        method: 'GET',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.transactions.length > 0) {
                            data.transactions.forEach(transaction => {
                                let isCredit = (transaction.transaction_type === 'TOPUP');
                                let row = `
                                    <div class="list-group-item d-flex justify-content-between align-items-center fade-in">
                                        <div>
                                            <p class="fw-bold mb-0 ${isCredit ? 'text-success' : 'text-danger'}">
                                                ${isCredit ? '+ ' : '- '}Rp ${new Intl.NumberFormat('id-ID').format(transaction.total_amount)}
                                            </p>
                                            <small class="text-muted">
                                                ${new Date(transaction.created_on).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })} WIB
                                            </small>
                                        </div>
                                        <span class="text-muted">${transaction.description || 'Transaksi'}</span>
                                    </div>`;
                                transactionsContainer.insertAdjacentHTML('beforeend', row);
                            });
                            showMoreBtn.setAttribute('data-page', page);
                        }
                        if (!data.hasMore) {
                            showMoreBtn.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            }
        });
    </script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
