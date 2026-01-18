<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Apotek Friendly</title>
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
    
    <!-- Navbar (Simplified) -->
    <nav class="bg-white border-b border-gray-100 fixed w-full z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="<?= base_url('home') ?>" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition-colors">
                    <i class="bi bi-arrow-left text-lg"></i>
                    <span class="font-medium">Kembali ke Beranda</span>
                </a>
                <h1 class="text-lg font-bold text-gray-900">Riwayat Pesanan</h1>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-12 px-4">
        <div class="max-w-4xl mx-auto">
            
            <?php if(empty($transaksi)): ?>
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mx-auto mb-6">
                        <i class="bi bi-receipt text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">Anda belum pernah melakukan transaksi.</p>
                    <a href="<?= base_url('home') ?>" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition-colors shadow-lg shadow-primary-200">
                        Mulai Belanja
                    </a>
                </div>
            <?php else: ?>
                
                <div class="space-y-4">
                    <?php foreach($transaksi as $t): ?>
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center text-primary-600">
                                            <i class="bi bi-bag-check"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">
                                                #<?= $t->id_penjualan ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <?= date('d M Y, H:i', strtotime($t->created_at)) ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php
                                        $statusClass = 'bg-gray-100 text-gray-600';
                                        $statusLabel = 'Pending';
                                        
                                        // Sesuaikan dengan data dari controller/model (tabel penjualan)
                                        if(isset($t->status)) {
                                            $s = strtolower($t->status);
                                            if($s == 'success' || $s == 'lunas' || $s == 'berhasil' || $s == 'selesai' || $s == 'dikirim') {
                                                $statusClass = 'bg-green-100 text-green-700';
                                            } else if($s == 'pending' || $s == 'menunggu' || $s == 'proses' || $s == 'dikemas' || $s == 'menunggu_pembayaran') {
                                                $statusClass = 'bg-yellow-100 text-yellow-700';
                                            } else if($s == 'cancelled' || $s == 'gagal' || $s == 'batal') {
                                                $statusClass = 'bg-red-100 text-red-700';
                                            }
                                            $statusLabel = ucwords(str_replace('_', ' ', $s));
                                        }
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold <?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Total Belanja</span>
                                        <span class="text-lg font-bold text-primary-600">
                                            Rp <?= number_format($t->total_harga, 0, ',', '.') ?>
                                        </span>
                                    </div>
                                    <a href="<?= base_url('pembayaran/detail/'.$t->id_penjualan) ?>" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all font-bold">
                                        Detail Pesanan <i class="bi bi-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
    </main>

</body>
</html>
