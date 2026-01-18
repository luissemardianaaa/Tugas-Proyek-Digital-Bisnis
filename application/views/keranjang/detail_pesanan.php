<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pesanan - Apotek Friendly</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
<style>* { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl pb-8">
        <!-- HEADER -->
        <div class="p-4 flex items-center gap-4 bg-emerald-600 text-white sticky top-0 z-10 shadow-md">
            <a href="<?= site_url('home') ?>" class="hover:bg-white/20 p-2 rounded-full transition-colors"><i class="bi bi-arrow-left text-xl"></i></a>
            <h1 class="font-bold text-lg">Rincian Pesanan</h1>
        </div>

        <!-- STATUS TIMELINE -->
        <div class="bg-emerald-600 text-white p-6 pb-12 rounded-b-3xl mb-[-2rem]">
            <p class="text-emerald-100 text-sm mb-1">Status Pesanan</p>
            <h2 class="text-2xl font-bold bg-white/20 inline-block px-3 py-1 rounded-lg backdrop-blur-sm">
                <?php 
                    $status_label = [
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                        'dikemas' => 'Sedang Dikemas',
                        'dikirim' => 'Sedang Dikirim',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan'
                    ];
                    echo $status_label[$penjualan->status] ?? $penjualan->status; 
                ?>
            </h2>
            <div class="mt-4 flex items-center justify-between text-xs text-emerald-200">
                <div class="flex flex-col items-center gap-2 <?= in_array($penjualan->status, ['menunggu_konfirmasi','dikemas','dikirim','selesai']) ? 'text-white' : 'opacity-50' ?>">
                    <div class="w-8 h-8 rounded-full border-2 border-current flex items-center justify-center"><i class="bi bi-check-lg"></i></div>
                    <span>Bayar</span>
                </div>
                <div class="h-0.5 bg-emerald-400 flex-1 mx-2"></div>
                <div class="flex flex-col items-center gap-2 <?= in_array($penjualan->status, ['dikemas','dikirim','selesai']) ? 'text-white' : 'opacity-50' ?>">
                    <div class="w-8 h-8 rounded-full border-2 border-current flex items-center justify-center"><i class="bi bi-box-seam"></i></div>
                    <span>Kemas</span>
                </div>
                <div class="h-0.5 bg-emerald-400 flex-1 mx-2"></div>
                <div class="flex flex-col items-center gap-2 <?= in_array($penjualan->status, ['dikirim','selesai']) ? 'text-white' : 'opacity-50' ?>">
                    <div class="w-8 h-8 rounded-full border-2 border-current flex items-center justify-center"><i class="bi bi-truck"></i></div>
                    <span>Kirim</span>
                </div>
                <div class="h-0.5 bg-emerald-400 flex-1 mx-2"></div>
                <div class="flex flex-col items-center gap-2 <?= in_array($penjualan->status, ['selesai']) ? 'text-white' : 'opacity-50' ?>">
                    <div class="w-8 h-8 rounded-full border-2 border-current flex items-center justify-center"><i class="bi bi-star"></i></div>
                    <span>Selesai</span>
                </div>
            </div>
        </div>

        <!-- ESTIMATED ARRIVAL -->
        <div class="px-4">
            <div class="bg-white rounded-2xl shadow-lg p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 text-xl">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Estimasi Tiba</p>
                    <p class="font-bold text-gray-800">
                        <?= date('d M Y', strtotime('+2 days')) ?> - <?= date('d M Y', strtotime('+4 days')) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- CUSTOMER DETAILS -->
        <div class="px-4 mt-6">
            <h3 class="font-bold text-gray-800 mb-3">Info Pengiriman</h3>
            <div class="bg-white border border-gray-100 rounded-xl p-4 space-y-3">
                <div class="flex items-start gap-3">
                    <i class="bi bi-geo-alt mt-1 text-gray-400"></i>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">Alamat Penerima</p>
                        <p class="text-xs text-gray-500 leading-relaxed mt-1"><?= $penjualan->alamat_pengiriman ?></p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="bi bi-telephone mt-1 text-gray-400"></i>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">Nomor Kontak</p>
                        <p class="text-xs text-gray-500 mt-1"><?= $this->session->userdata('no_hp') ?? '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ORDER ITEMS -->
        <div class="px-4 mt-6">
            <h3 class="font-bold text-gray-800 mb-3">Daftar Produk</h3>
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <?php foreach($items as $i): ?>
                <div class="bg-white p-3 flex gap-3 border-b border-gray-50 last:border-0">
                    <div class="w-16 h-16 bg-gray-50 rounded-lg overflow-hidden shrink-0">
                         <!-- Image placeholder if needed, or query from DB -->
                         <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="bi bi-image"></i>
                         </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-800 line-clamp-2"><?= $i->nama_obat ?></h4>
                        <div class="flex justify-between items-end mt-2">
                            <p class="text-xs text-gray-500"><?= $i->jumlah ?> x Rp<?= number_format($i->harga,0,',','.') ?></p>
                            <p class="text-sm font-bold text-gray-800">Rp<?= number_format($i->harga * $i->jumlah, 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- PAYMENT SUMMARY -->
        <div class="px-4 mt-6">
             <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Metode Pembayaran</span>
                    <span class="font-medium text-gray-800"><?= $penjualan->metode_pembayaran ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total Belanja</span>
                    <span class="font-bold text-emerald-600 text-lg">Rp<?= number_format($penjualan->total_harga, 0, ',', '.') ?></span>
                </div>
             </div>
        </div>

    </div>

</body>
</html>
