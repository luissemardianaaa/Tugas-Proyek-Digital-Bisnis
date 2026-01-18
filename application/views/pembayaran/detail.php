<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Apotek Friendly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 fixed w-full z-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="<?= base_url('pembayaran/riwayat') ?>" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition-colors">
                    <i class="bi bi-arrow-left text-lg"></i>
                    <span class="font-medium">Kembali</span>
                </a>
                <h1 class="text-lg font-bold text-gray-900">Detail Pesanan</h1>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-12 px-4">
        <div class="max-w-3xl mx-auto md:max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Left Column: Item List (Expanded for clarity as requested) -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="font-bold text-gray-900">Barang yang Dipesan</h2>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <?php if(!empty($items)): ?>
                                <?php foreach($items as $item): ?>
                                    <div class="p-5 flex items-start gap-4 hover:bg-gray-50 transition-colors">
                                        <!-- Placeholder Image or Real Image if available -->
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center text-gray-400">
                                            <i class="bi bi-capsule text-2xl"></i>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 mb-1">
                                                <?= $item->nama_obat ?>
                                            </h4>
                                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                                <span><?= $item->jumlah ?> x Rp <?= number_format($item->harga, 0, ',', '.') ?></span>
                                            </div>
                                            <p class="text-primary-600 font-bold text-sm">
                                                Total: Rp <?= number_format($item->jumlah * $item->harga, 0, ',', '.') ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="p-8 text-center text-gray-500">
                                    Tidak ada detail item.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Info -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="mb-4">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Pesanan</span>
                            <div class="mt-2 text-lg font-bold text-emerald-600 flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <?= isset($transaksi->status) ? ucwords(str_replace('_', ' ', $transaksi->status)) : '-' ?>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-500 text-sm">No. Transaksi</span>
                                <span class="font-bold text-gray-900 text-sm">#<?= $transaksi->id_penjualan ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 text-sm">Tanggal</span>
                                <span class="font-bold text-gray-900 text-sm"><?= date('d M Y, H:i', strtotime($transaksi->created_at)) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Rincian Pembayaran</h3>
                        
                        <div class="space-y-3 mb-4 pb-4 border-b border-gray-100">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Metode Bayar</span>
                                <span class="font-medium text-gray-900"><?= isset($transaksi->metode_pembayaran) ? ucfirst($transaksi->metode_pembayaran) : (isset($transaksi->metode_bayar) ? ucfirst($transaksi->metode_bayar) : '-') ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Penerima</span>
                                <span class="font-medium text-gray-900 text-right truncate w-1/2"><?= isset($transaksi->nama_penerima) ? $transaksi->nama_penerima : '-' ?></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900">Total Total</span>
                            <span class="text-xl font-bold text-primary-600">
                                Rp <?= number_format($transaksi->total_harga, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>

</body>
</html>
