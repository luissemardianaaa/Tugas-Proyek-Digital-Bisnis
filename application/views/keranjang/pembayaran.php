<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran - Apotek Friendly</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

<!-- Midtrans Snap.js -->
<script src="<?= $snap_url ?>" data-client-key="<?= $client_key ?>"></script>

<style>* { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl relative flex flex-col">
        <!-- HEADER -->
        <div class="p-4 flex items-center gap-4 bg-white sticky top-0 z-10 border-b border-gray-100">
            <h1 class="font-bold text-lg text-gray-800">Pembayaran</h1>
        </div>

        <!-- CONTENT -->
        <div class="p-6 flex-1 flex flex-col items-center justify-center text-center">
            
            <?php if(!empty($penjualan->snap_token)): ?>
                <!-- Midtrans Payment -->
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                    <i class="bi bi-wallet2 text-4xl text-emerald-600"></i>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-2">Proses Pembayaran</h2>
                <p class="text-gray-500 text-sm mb-8">Popup pembayaran akan terbuka otomatis...</p>

                <div class="bg-emerald-50 w-full p-6 rounded-2xl border border-emerald-100 mb-8">
                    <p class="text-xs text-emerald-600 font-semibold uppercase tracking-wider mb-1">Total Tagihan</p>
                    <h3 class="text-3xl font-bold text-emerald-700">Rp<?= number_format($penjualan->total_harga, 0, ',', '.') ?></h3>
                </div>

                <div class="w-full text-left space-y-4">
                    <div class="bg-gradient-to-r from-blue-50 to-emerald-50 p-4 rounded-xl border border-blue-100">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-shield-check text-2xl text-emerald-600"></i>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm mb-1">Pembayaran Aman dengan Midtrans</h3>
                                <p class="text-xs text-gray-600 leading-relaxed">Pilih metode pembayaran favorit Anda: Bank Transfer, E-Wallet (GoPay, OVO, DANA), QRIS, atau COD.</p>
                            </div>
                        </div>
                    </div>

                    <button id="pay-button" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all transform hover:scale-[1.02]">
                        <i class="bi bi-credit-card mr-2"></i> Bayar Sekarang
                    </button>

                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-2 text-gray-500">atau</span>
                        </div>
                    </div>

                    <a href="<?= site_url('keranjang/konfirmasi_bayar/' . $penjualan->id_penjualan) ?>" class="block w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all text-center">
                        <i class="bi bi-check-circle mr-2"></i> Sudah Bayar
                    </a>

                    <a href="<?= site_url('home') ?>" class="block w-full text-center py-4 text-gray-500 font-medium hover:text-gray-800">
                        Lakukan Nanti
                    </a>
                </div>

            <?php else: ?>
                <!-- Error: No Snap Token -->
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <i class="bi bi-exclamation-triangle text-4xl text-red-600"></i>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-2">Terjadi Kesalahan</h2>
                <p class="text-gray-500 text-sm mb-8">Token pembayaran tidak ditemukan. Silakan coba lagi.</p>

                <a href="<?= site_url('keranjang') ?>" class="bg-emerald-600 text-white font-bold py-4 px-8 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">
                    Kembali ke Keranjang
                </a>
            <?php endif; ?>

        </div>
    </div>

    <?php if(!empty($penjualan->snap_token)): ?>
    <script>
        const snapToken = '<?= $penjualan->snap_token ?>';
        const orderId = '<?= $penjualan->id_penjualan ?>';

        // Auto-trigger Snap popup on page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                payNow();
            }, 500); // Small delay for better UX
        });

        // Manual trigger via button
        document.getElementById('pay-button').addEventListener('click', function() {
            payNow();
        });

        function payNow() {
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    // Redirect to order detail
                    window.location.href = '<?= site_url("keranjang/detail_pesanan/") ?>' + orderId;
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    // Show pending message
                    alert('Pembayaran tertunda. Silakan selesaikan pembayaran Anda.');
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // User closed the popup without completing payment
                }
            });
        }
    </script>
    <?php endif; ?>

</body>
</html>
