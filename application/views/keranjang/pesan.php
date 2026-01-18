<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Saya - Apotek Friendly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col h-screen">

    <!-- HEADER -->
    <div class="bg-white border-b border-gray-100 flex items-center gap-4 px-4 h-16 shrink-0 shadow-sm z-10 w-full max-w-lg mx-auto">
        <a href="<?= site_url('keranjang') ?>" class="text-emerald-600 text-xl p-2 rounded-full hover:bg-gray-50 transition-colors">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Status Pesanan</h1>
            <p class="text-[10px] text-emerald-600 font-medium">Apotek Friendly Official</p>
        </div>
        <div class="ml-auto w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
            <span class="text-emerald-600 text-xl font-bold">💊</span>
        </div>
    </div>

    <!-- CHAT AREA -->
    <div class="flex-1 overflow-y-auto p-4 space-y-6 w-full max-w-lg mx-auto no-scrollbar">
        
        <!-- Date Divider -->
        <div class="flex justify-center">
            <span class="text-[10px] bg-gray-200 text-gray-500 px-3 py-1 rounded-full font-medium uppercase tracking-widest">Hari Ini</span>
        </div>

        <?php if(empty($notifikasi)): ?>
            <div class="flex flex-col items-center justify-center h-full text-center py-20">
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-chat-left-dots text-4xl text-emerald-200"></i>
                </div>
                <h3 class="text-gray-800 font-semibold italic">Belum ada pesan status.</h3>
                <p class="text-gray-400 text-xs px-10">Pesan dari apotek akan muncul di sini saat pesananmu diproses.</p>
            </div>
        <?php else: ?>
            <?php foreach($notifikasi as $n): ?>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white shrink-0 shadow-md">
                    <span class="text-xs">💊</span>
                </div>
                <div class="flex flex-col gap-1 max-w-[85%]">
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-emerald-50 relative">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            <?= $n->pesan ?>
                        </p>
                        <!-- Order ID tag -->
                        <div class="mt-2 pt-2 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">PESANAN #<?= $n->id_penjualan ?></span>
                            <span class="text-[9px] text-gray-400"><?= date('H:i', strtotime($n->created_at)) ?> WIB</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <!-- FOOTER INFO -->
    <div class="bg-white p-4 border-t border-gray-100 shrink-0 w-full max-w-lg mx-auto text-center">
        <p class="text-[10px] text-gray-400 italic">Pesan ini dikirim secara otomatis oleh sistem saat status pesanan diperbarui.</p>
    </div>

</body>
</html>
