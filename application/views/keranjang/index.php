<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Saya</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar hide for clean UI */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Custom toggle switch styles */
        .toggle-checkbox:checked {
            @apply: bg-emerald-600;
            right: 0;
            border-color: #10B981; /* emerald-500 */
        }
        .toggle-checkbox:checked + .toggle-label {
            @apply: bg-emerald-600;
        }
        .toggle-label {
            @apply: bg-gray-300;
        }
        .toggle-checkbox {
            @apply: border-gray-300;
        }
    </style>
</head>
<body class="bg-gray-50 pb-32">

    <!-- HEADER -->
    <div class="fixed top-0 left-0 right-0 bg-white z-50 shadow-sm">
        <div class="flex items-center gap-4 px-4 h-16 max-w-lg mx-auto relative">
            <a href="<?= site_url('home') ?>" class="text-emerald-600 text-xl hover:bg-gray-50 p-2 rounded-full transition-colors">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="text-lg font-semibold text-gray-800">Keranjang Saya <span class="text-gray-500 text-sm">(<?= count($items) ?>)</span></h1>
            <a href="#" class="ml-auto text-emerald-600 font-medium text-sm hover:text-emerald-700">Ubah</a>
            <a href="<?= site_url('keranjang/pesan') ?>" class="text-emerald-600 text-xl relative p-2">
                <i class="bi bi-chat-dots-fill"></i>
                <?php if(isset($notif_count) && $notif_count > 0): ?>
                    <span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] px-1 rounded-full border border-white">
                        <?= $notif_count > 99 ? '99+' : $notif_count ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </div>

    <!-- NOTIFICATION BAR -->
    <div class="mt-16 max-w-lg mx-auto">
        <div class="bg-yellow-50 px-4 py-3 flex items-start gap-3 border-b border-yellow-100">
            <i class="bi bi-coin text-yellow-500 mt-0.5"></i>
            <p class="text-xs text-gray-700 leading-tight">
                Pakai Koin Shopee di halaman checkout agar bayar lebih hemat!
            </p>
        </div>
        
        <div class="bg-white px-4 py-2 flex items-center gap-2 border-b border-gray-100 overflow-x-auto no-scrollbar whitespace-nowrap">
            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1 py-0.5 border border-emerald-200 rounded">GRATIS ONGKIR</span>
            <p class="text-xs text-gray-600 truncate">
                Gratis Ongkir s/d Rp20.000 dengan min. belanja Rp80.000
            </p>
            <i class="bi bi-chevron-right text-gray-400 text-xs ml-auto"></i>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-lg mx-auto space-y-2 mt-2">
        
        <!-- SHOP HEADER -->
        <div class="bg-white p-4 pb-2 shadow-sm rounded-t-xl mx-2 mt-2">
            <div class="flex items-center gap-3">
                <input type="checkbox" class="w-5 h-5 accent-emerald-600 rounded border-gray-300 cursor-pointer" checked>
                <div class="flex items-center gap-1">
                    <span class="bg-emerald-600 text-white text-[10px] font-bold px-1 rounded-sm">Official</span>
                    <h3 class="font-bold text-gray-800 text-sm">Apotek Friendly</h3>
                    <i class="bi bi-chevron-right text-gray-400 text-xs"></i>
                </div>
                <span class="ml-auto text-gray-500 text-sm cursor-pointer hover:text-emerald-600">Ubah</span>
            </div>
        </div>

        <!-- ITEMS LIST -->
        <?php 
        $grand_total = 0;
        if(empty($items)): 
        ?>
            <div class="bg-white p-12 text-center mx-2 rounded-b-xl shadow-sm">
                <div class="mb-4 text-6xl text-emerald-100">🛒</div>
                <h3 class="text-gray-800 font-medium mb-2">Keranjang Kosong</h3>
                <p class="text-gray-500 text-sm mb-6">Yuk cari obat kebutuhanmu sekarang!</p>
                <a href="<?= site_url('home') ?>" class="bg-emerald-600 text-white px-8 py-2.5 rounded-full text-sm font-medium hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">Belanja Sekarang</a>
            </div>
        <?php else: ?>
            <div class="bg-white pb-2 mx-2 rounded-b-xl shadow-sm">
            <?php foreach($items as $index => $i): 
                $subtotal = $i->harga * $i->jumlah;
                $grand_total += $subtotal;
            ?>
            <div class="p-4 pt-0">
                <div class="flex gap-3 py-4 border-b border-gray-50 last:border-0">
                    <!-- Checkbox -->
                    <div class="flex items-center">
                        <input type="checkbox" class="w-5 h-5 accent-emerald-600 rounded border-gray-300 item-checkbox cursor-pointer" 
                               data-price="<?= $subtotal ?>" checked>
                    </div>

                    <!-- Image -->
                    <div class="w-20 h-20 flex-shrink-0 bg-gray-50 rounded-lg border border-gray-100 overflow-hidden relative group">
                        <img src="<?= base_url('uploads/obat/' . $i->gambar) ?>" alt="<?= $i->nama_obat ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-800 line-clamp-1 mb-1"><?= $i->nama_obat ?></h4>
                            <div class="inline-flex items-center justify-between w-full bg-gray-50 border border-gray-100 rounded px-2 py-1 mb-2 cursor-pointer hover:bg-gray-100 transition-colors">
                                <span class="text-xs text-gray-500">Variasi: <?= $i->satuan ?></span>
                                <i class="bi bi-chevron-down text-gray-400 text-[10px]"></i>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="bg-red-50 text-red-600 text-[10px] font-bold px-1 rounded border border-red-100">Diskon 5%</span>
                                <span class="text-xs text-gray-400 line-through">Rp<?= number_format($i->harga * 1.05, 0, ',', '.') ?></span>
                            </div>
                        </div>
                        
                        <div class="flex items-end justify-between mt-2">
                            <span class="text-emerald-600 font-bold text-sm">Rp<?= number_format($i->harga, 0, ',', '.') ?></span>
                            
                            <!-- Quantity Control -->
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Kurang -->
                                <?php if($i->jumlah > 1): ?>
                                    <button onclick="kurangItem(<?= $i->id_obat ?>)" class="w-8 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-emerald-600 border-r border-gray-200 text-xs transition-colors">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="<?= site_url('keranjang/hapus/'.$i->id_detail) ?>" class="w-8 h-7 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 border-r border-gray-200 text-xs transition-colors">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <input type="text" value="<?= $i->jumlah ?>" class="w-10 h-7 text-center text-sm text-gray-700 focus:outline-none bg-white font-medium" readonly>
                                
                                <!-- Tambah -->
                                <button onclick="tambahItem(<?= $i->id_obat ?>)" class="w-8 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-emerald-600 border-l border-gray-200 text-xs transition-colors">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stock Footer -->
                <?php if(isset($i->stok) && $i->stok < 10): ?>
                <div class="flex justify-end mt-1">
                    <span class="text-[10px] text-red-500 bg-red-50 px-2 py-0.5 rounded-full">Tersisa <?= $i->stok ?> buah</span>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- PROMO SECTION -->
    <?php if(!empty($items)): ?>
    <div class="max-w-lg mx-auto space-y-2 mt-2 mb-24 px-2">
        <div class="bg-white p-4 flex items-center justify-between rounded-xl shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-2">
                <i class="bi bi-ticket-perforated text-emerald-600"></i>
                <span class="text-sm text-gray-800">Voucher Toko</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">Gunakan / masukkan kode</span>
                <i class="bi bi-chevron-right text-gray-400 text-xs"></i>
            </div>
        </div>
        
        <div class="bg-white p-4 flex items-center justify-between rounded-xl shadow-sm">
            <div class="flex items-center gap-3">
                <i class="bi bi-currency-bitcoin text-yellow-500 text-lg"></i>
                <div class="flex flex-col">
                    <span class="text-sm text-gray-800 font-medium">Koin</span>
                    <span class="text-[10px] text-gray-400">Saldo: 0 Koin</span>
                </div>
            </div>
            <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                <input type="checkbox" name="toggle" id="toggle" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300"/>
                <label for="toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- BOTTOM CHECKOUT BAR -->
    <?php if(!empty($items)): ?>
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="max-w-lg mx-auto flex items-center h-16 pl-6">
            <div class="flex items-center gap-2 mr-auto">
                <input type="checkbox" id="checkAll" class="w-5 h-5 accent-emerald-600 rounded border-gray-300 cursor-pointer" checked>
                <label for="checkAll" class="text-sm text-gray-600 cursor-pointer select-none">Semua</label>
            </div>
            
            <div class="flex items-center">
                <div class="flex flex-col items-end mr-4">
                    <div class="flex items-center gap-1">
                        <span class="text-xs text-gray-600">Total</span>
                        <span class="text-emerald-600 font-bold text-lg">Rp<?= number_format($grand_total, 0, ',', '.') ?></span>
                    </div>
                    <span class="text-[10px] text-emerald-600 bg-emerald-50 px-1 rounded">Hemat Rp<?= number_format($grand_total * 0.05, 0, ',', '.') ?></span>
                </div>
                
                <a href="<?= site_url('keranjang/checkout') ?>" class="bg-emerald-600 text-white font-bold h-16 px-8 hover:bg-emerald-700 transition-colors flex items-center">
                    Checkout (<?= count($items) ?>)
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- JS Logic -->
    <script>
    function tambahItem(id_obat) {
        fetch("<?= base_url('keranjang/tambah/') ?>" + id_obat)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    location.reload(); 
                }
            });
    }

    function kurangItem(id_obat) {
        fetch("<?= base_url('keranjang/kurang/') ?>" + id_obat)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    location.reload(); 
                }
            });
    }

    // Toggle All Checkboxes
    const checkAll = document.getElementById('checkAll');
    if(checkAll) {
        checkAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
    </script>

</body>
</html>
