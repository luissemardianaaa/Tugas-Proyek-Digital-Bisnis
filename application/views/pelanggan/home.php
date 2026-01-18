<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Friendly - Solusi Kesehatan Terpercaya</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981', // Emerald-500
                            600: '#059669', // Emerald-600 (Main)
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .hero-pattern {
            background-color: #ecfdf5;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2310b981' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

<?php 
$kategori = [
    ['Tablet', 'bi-capsule', 'bg-blue-50 text-blue-600'],
    ['Kapsul', 'bi-capsule-pill', 'bg-purple-50 text-purple-600'],
    ['Strip', 'bi-grid-3x3', 'bg-orange-50 text-orange-600'],
    ['Botol', 'bi-droplet', 'bg-cyan-50 text-cyan-600'],
    ['Tube', 'bi-eyedropper', 'bg-pink-50 text-pink-600'],
    ['Pieces', 'bi-box-seam', 'bg-emerald-50 text-emerald-600'],
]; 
?>

    <!-- NAVBAR -->
    <nav class="glass-nav fixed w-full z-50 top-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="window.location.href='<?= base_url() ?>'">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-primary-200">
                        <i class="bi bi-capsule"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight leading-none">Apotek<span class="text-primary-600">Friendly</span></h1>
                        <p class="text-xs text-gray-500 font-medium">Mitra Kesehatan Anda</p>
                    </div>
                </div>

                <!-- Desktop Search -->
                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <form action="<?= base_url('home') ?>" method="GET" class="relative w-full group">
                        <input type="text" 
                            name="search"
                            value="<?= isset($search_keyword) ? $search_keyword : '' ?>"
                            class="w-full bg-gray-100/80 border-0 rounded-full py-2.5 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all duration-300 placeholder-gray-400 group-hover:bg-white"
                            placeholder="Cari obat, vitamin, atau keluhan (misal: sakit kepala)...">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-search text-gray-400 group-focus-within:text-primary-500 transition-colors"></i>
                        </div>
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Cart -->
                    <a href="<?= site_url('keranjang/index') ?>" class="relative p-2 text-gray-500 hover:text-primary-600 transition-colors group">
                        <div class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white ring-2 ring-transparent group-hover:ring-red-100 transition-all"></div>
                        <i class="bi bi-cart text-xl"></i>
                    </a>

                    <div class="h-8 w-px bg-gray-200 mx-1 hidden sm:block"></div>

                    <!-- Auth Buttons -->
                    <?php if(!$this->session->userdata('id_user')): ?>
                        <div class="hidden sm:flex items-center gap-3">
                            <a href="<?= site_url('auth/login') ?>" class="text-sm font-semibold text-gray-600 hover:text-primary-600 transition-colors">
                                Masuk
                            </a>
                            <a href="<?= site_url('auth/register') ?>" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold py-2.5 px-6 rounded-full transition-all shadow-lg shadow-primary-200 hover:shadow-primary-300 transform hover:-translate-y-0.5">
                                Daftar
                            </a>
                        </div>
                        <!-- Mobile Menu Button -->
                        <button class="sm:hidden p-2 text-gray-600">
                             <i class="bi bi-list text-2xl"></i>
                        </button>
                    <?php else: ?>
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs text-gray-500">Halo,</p>
                                <p class="text-sm font-bold text-gray-900 leading-none clamp-1 max-w-[100px]"><?= $this->session->userdata('nama') ?></p>
                            </div>
                            <div class="relative group">
                                <button class="w-10 h-10 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center font-bold border-2 border-primary-200 group-hover:border-primary-400 transition-all">
                                    <?= substr($this->session->userdata('nama'), 0, 1) ?>
                                </button>
                                <!-- Dropdown -->
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right border border-gray-100">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700">Profile</a>
                                    <a href="<?= site_url('pembayaran/riwayat') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700">Riwayat Pesanan</a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="<?= site_url('auth/logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Mobile Search (Visible on small screens) -->
        <div class="md:hidden px-4 pb-4 border-b border-gray-100">
             <form action="<?= base_url('home') ?>" method="GET" class="relative w-full">
                <input type="text" 
                    name="search"
                    value="<?= isset($search_keyword) ? $search_keyword : '' ?>"
                    class="w-full bg-gray-100 border-0 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all"
                    placeholder="Cari obat...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-gray-400"></i>
                </div>
            </form>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="pt-[calc(6rem+1px)] pb-20">
        
        <!-- HERO SECTION -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="hero-pattern rounded-3xl p-8 md:p-12 relative overflow-hidden shadow-sm border border-primary-100">
                <div class="relative z-10 max-w-2xl">
                    <span class="inline-block py-1 px-3 rounded-full bg-primary-100 text-primary-700 text-xs font-bold tracking-wide uppercase mb-4 border border-primary-200">
                        <i class="bi bi-shield-check mr-1"></i> Terpercaya & Resmi
                    </span>
                    <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                        Kesehatan Anda Prioritas <span class="text-primary-600">Utama Kami</span>
                    </h2>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                        Temukan ribuan produk kesehatan, vitamin, dan obat-obatan asli dengan harga terbaik. Pengiriman cepat langsung ke rumah Anda.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <button onclick="document.getElementById('produk-section').scrollIntoView({behavior: 'smooth'})" 
                            class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-primary-200 transition-all hover:scale-105 active:scale-95">
                            Belanja Sekarang
                        </button>
                    </div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="absolute right-0 bottom-0 h-full w-1/3 bg-gradient-to-l from-primary-100/50 to-transparent hidden md:block"></div>
                <div class="absolute -right-10 -bottom-20 w-80 h-80 bg-primary-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
                <div class="absolute right-40 top-10 w-60 h-60 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
            </div>
        </div>

        <!-- CATEGORIES & INFO STORE -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Categories (Replaces Sidebar) -->
                <div class="flex-1 w-full order-2 lg:order-1">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Kategori Obat</h3>
                        <a href="<?= site_url('home') ?>" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
                    </div>

                    <!-- Horizontal Scroll for Categories -->
                    <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4 -mx-4 px-4 sm:mx-0 sm:px-0">
                        <?php 
                        foreach($kategori as $k): 
                        ?>
                        <a href="<?= site_url('home?satuan=' . urlencode($k[0])) ?>" 
                           class="flex-shrink-0 w-32 p-4 rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md hover:border-primary-200 transition-all group flex flex-col items-center gap-3 <?= (isset($selected_category) && $selected_category == $k[0]) ? 'ring-2 ring-primary-500 bg-primary-50' : '' ?>">
                            <div class="w-12 h-12 rounded-full <?= $k[2] ?> flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                <i class="bi <?= $k[1] ?>"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 group-hover:text-primary-600"><?= $k[0] ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                 <!-- Info Cards -->
                 <div class="w-full lg:w-1/3 order-1 lg:order-2 grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100">
                        <i class="bi bi-patch-check-fill text-3xl text-blue-500 mb-3 block"></i>
                        <h4 class="font-bold text-gray-800 mb-1">100% Asli</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Produk terjamin original & BPOM</p>
                    </div>
                    <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100">
                         <i class="bi bi-lightning-charge-fill text-3xl text-emerald-500 mb-3 block"></i>
                        <h4 class="font-bold text-gray-800 mb-1">Cepat</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Pengiriman instan setiap hari</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUCT GRID -->
        <div id="produk-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- SEARCH RESULTS & AI RECOMMENDATION -->
            <?php if((isset($search_keyword) && !empty($search_keyword)) || (isset($selected_category) && !empty($selected_category))): ?>
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <a href="<?= base_url() ?>" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    
                    <?php if(isset($selected_category) && !empty($selected_category)): ?>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Kategori Obat: <span class="text-primary-600">"<?= $selected_category ?>"</span>
                        </h3>
                    <?php else: ?>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Hasil Pencarian: <span class="text-primary-600">"<?= $search_keyword ?>"</span>
                        </h3>
                    <?php endif; ?>
                </div>

                <?php if(isset($rekomendasi) && !empty($rekomendasi['rekomendasi'])): ?>
                <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                            <i class="bi bi-robot text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h5 class="font-bold text-gray-900 mb-2">Rekomendasi Apoteker AI</h5>
                            <p class="text-sm text-gray-600 mb-4">Berdasarkan keluhan "<?= $search_keyword ?>", berikut adalah obat yang disarankan:</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <?php foreach($rekomendasi['rekomendasi'] as $rek): ?>
                                <div class="bg-white p-3 rounded-xl border <?= $rek['tersedia'] ? 'border-primary-200 shadow-sm' : 'border-gray-200 opacity-75' ?> flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full <?= $rek['tersedia'] ? 'bg-primary-50 text-primary-600' : 'bg-gray-100 text-gray-400' ?> flex items-center justify-center text-sm">
                                        <i class="bi <?= $rek['tersedia'] ? 'bi-check-lg' : 'bi-x' ?>"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-sm text-gray-900"><?= $rek['nama'] ?></h6>
                                        <p class="text-[10px] text-gray-500 line-clamp-1"><?= $rek['kegunaan'] ?></p>
                                    </div>
                                    <?php if($rek['tersedia']): ?>
                                        <span class="ml-auto text-[10px] font-bold text-primary-600 bg-primary-50 px-2 py-1 rounded-full">Tersedia</span>
                                    <?php else: ?>
                                        <span class="ml-auto text-[10px] font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded-full">Habis</span>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(empty($obat)): ?>
                <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mx-auto mb-4">
                        <i class="bi bi-search text-2xl"></i>
                    </div>
                    <h5 class="font-bold text-gray-900">Tidak ada produk ditemukan</h5>
                    <p class="text-gray-500 text-sm mt-1">Coba kata kunci lain atau konsultasikan dengan apoteker kami</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="flex items-center justify-between mb-8">
                <div>
                     <h3 class="text-2xl font-bold text-gray-900">
                         <?= ((isset($search_keyword) && !empty($search_keyword)) || (isset($selected_category) && !empty($selected_category))) ? 'Produk Terkait' : 'Rekomendasi Lainnya' ?>
                     </h3>
                     <p class="text-gray-500 text-sm mt-1">Pilihan obat terbaik untuk menjaga kesehatan Anda</p>
                </div>
               
                <!-- Filter Button (Functional Dropdown) -->
                <div class="relative">
                    <button onclick="document.getElementById('filterDropdown').classList.toggle('hidden')" 
                            class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 focus:ring-2 focus:ring-primary-100 transition-all">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 animate-fade-in-down origin-top-right">
                        <div class="p-2">
                             <h6 class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Golongan Obat</h6>
                             
                             <a href="<?= site_url('home?jenis=Vitamin') ?>" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                Vitamin & Suplemen
                             </a>

                             <a href="<?= site_url('home?jenis=Herbal') ?>" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Obat Herbal (Ringan)
                             </a>

                             <a href="<?= site_url('home?jenis=Antibiotik') ?>" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                Obat Keras (Resep)
                             </a>

                             <div class="border-t border-gray-100 my-1"></div>
                             <a href="<?= site_url('home') ?>" class="block px-3 py-2 rounded-lg text-sm text-red-500 hover:bg-red-50 transition-colors text-center">
                                Reset Filter
                             </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                <?php foreach($obat as $o): ?>
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-primary-500/10 hover:border-primary-100 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                    
                    <!-- Badge Stok -->
                    <div class="absolute top-3 left-3 z-10">
                        <span class="px-2 py-1 bg-white/90 backdrop-blur text-[10px] font-bold uppercase tracking-wider text-gray-500 border border-gray-100 rounded-md shadow-sm">
                            <?= $o->satuan ?>
                        </span>
                    </div>

                    <!-- Image -->
                    <div class="relative pt-[100%] bg-gray-50 overflow-hidden group-hover:bg-primary-50/30 transition-colors">
                        <img src="<?= base_url('uploads/obat/'.$o->gambar) ?>" 
                             alt="<?= $o->nama_obat ?>"
                             class="absolute inset-0 w-full h-full object-contain p-6 transform group-hover:scale-110 transition-transform duration-500">
                    </div>

                    <!-- Content -->
                    <div class="p-4 flex flex-col flex-1">
                        <div class="mb-2">
                            <h4 class="font-bold text-gray-900 line-clamp-2 text-sm leading-snug group-hover:text-primary-600 transition-colors" title="<?= $o->nama_obat ?>">
                                <?= $o->nama_obat ?>
                            </h4>
                        </div>
                        
                        <div class="mt-auto pt-2">
                             <!-- Stok Bar -->
                             <div class="flex items-center gap-2 mb-3">
                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary-400 rounded-full" style="width: 60%"></div>
                                </div>
                                <span class="text-[10px] text-gray-400 font-medium">Sisa <?= $o->stok ?></span>
                             </div>

                            <div class="flex items-center justify-between gap-3">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-gray-400">Harga per unit</span>
                                    <span class="text-base font-bold text-primary-600">
                                        Rp<?= number_format($o->harga,0,',','.') ?>
                                    </span>
                                </div>
                                
                                <button onclick="tambahKeranjang(<?= $o->id_obat ?>)" 
                                        class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center hover:bg-primary-600 hover:text-white transition-all shadow-sm hover:shadow-lg active:scale-95">
                                    <i class="bi bi-plus-lg text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Load More -->
            <div class="mt-12 text-center">
                <button class="inline-flex items-center gap-2 text-gray-500 font-medium hover:text-primary-600 transition-colors">
                    <span>Lihat Lebih Banyak</span>
                    <i class="bi bi-arrow-down-circle"></i>
                </button>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-2">
                     <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white text-sm">
                            <i class="bi bi-capsule"></i>
                        </div>
                        <h5 class="font-bold text-gray-900">Apotek<span class="text-primary-600">Friendly</span></h5>
                     </div>
                     <p class="text-gray-500 text-sm leading-relaxed max-w-xs">
                         Menyediakan produk kesehatan berkualitas tinggi dengan pelayanan ramah dan profesional. Sehat bersama kami.
                     </p>
                </div>
                <div>
                    <h6 class="font-bold text-gray-900 mb-4">Layanan</h6>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="<?= base_url('layanan/tanya_apoteker') ?>" class="hover:text-primary-600">Tanya Apoteker</a></li>
                        <!-- <li><a href="<?= base_url('layanan/upload_resep') ?>" class="hover:text-primary-600">Upload Resep</a></li> -->
                        <li><a href="<?= base_url('layanan/cek_lab') ?>" class="hover:text-primary-600">Cek Lab</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-bold text-gray-900 mb-4">Bantuan</h6>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="<?= base_url('bantuan/cara_belanja') ?>" class="hover:text-primary-600">Cara Belanja</a></li>
                        <li><a href="<?= base_url('bantuan/informasi_pengiriman') ?>" class="hover:text-primary-600">Informasi Pengiriman</a></li>
                        <li><a href="<?= base_url('bantuan/hubungi_kami') ?>" class="hover:text-primary-600">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-8 text-center">
                <p class="text-sm text-gray-400">&copy; <?= date('Y') ?> Apotek Friendly. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- TOAST NOTIFICATION (Tailwind) -->
    <div id="toast-container" class="fixed top-24 right-5 z-50 flex flex-col gap-2 pointer-events-none">
        <!-- Toast Item Template (Hidden) -->
        <div id="toast-template" class="hidden transform transition-all duration-300 translate-x-10 opacity-0 bg-white border border-green-100 shadow-lg shadow-green-500/10 rounded-xl p-4 flex items-center gap-3 w-80 pointer-events-auto">
            <div class="w-10 h-10 rounded-full bg-green-100 flex-shrink-0 flex items-center justify-center text-green-600">
                <i class="bi bi-check-lg text-xl"></i>
            </div>
            <div>
                <h6 class="text-sm font-bold text-gray-900">Berhasil!</h6>
                <p class="text-xs text-gray-500">Produk ditambahkan ke keranjang</p>
            </div>
        </div>
    </div>

    <!-- AI CHAT WIDGET -->
    <!-- Floating Button -->
    <button onclick="toggleChat()" class="fixed bottom-6 right-6 z-40 bg-gradient-to-tr from-primary-600 to-emerald-500 text-white p-4 rounded-full shadow-lg shadow-primary-500/30 hover:scale-110 active:scale-95 transition-all group">
        <i class="bi bi-chat-dots-fill text-2xl group-hover:rotate-12 transition-transform block"></i>
        <span class="absolute right-0 top-0 flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-red-400"></span>
        </span>
    </button>

    <!-- Chat Window -->
    <div id="ai-chat-window" class="fixed bottom-24 right-6 w-[90%] md:w-96 bg-white rounded-2xl shadow-2xl z-40 transform translate-y-10 opacity-0 pointer-events-none transition-all duration-300 border border-gray-100 flex flex-col max-h-[600px]">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-600 to-emerald-600 p-4 rounded-t-2xl flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                    <i class="bi bi-robot text-xl"></i>
                </div>
                <div>
                    <h5 class="font-bold text-sm">Asisten Apoteker AI</h5>
                    <p class="text-xs text-primary-100 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span> Online
                    </p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white/80 hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Chat Area -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4 h-80 min-h-[320px] scroll-smooth">
            <!-- Welcome Message -->
            <div class="flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 flex-shrink-0">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none border border-gray-100 shadow-sm text-sm text-gray-700 max-w-[85%]">
                    Halo! Saya asisten virtual Apotek Friendly. Ada yang bisa saya bantu terkait keluhan kesehatan atau rekomendasi obat hari ini? 😊
                </div>
            </div>
        </div>

        <!-- Footer Input -->
        <div class="p-3 border-t border-gray-100 bg-white rounded-b-2xl">
            <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex gap-2 relative">
                <input type="text" id="user-input" 
                    class="w-full bg-gray-100 border-0 rounded-xl py-3 pl-4 pr-12 text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all"
                    placeholder="Tulis keluhan Anda..." required autocomplete="off">
                <button type="submit" id="btn-send"
                    class="absolute right-2 top-1.5 w-9 h-9 bg-primary-600 text-white rounded-lg flex items-center justify-center hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="bi bi-send-fill text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // --- CHAT WIDGET LOGIC ---
        const chatWindow = document.getElementById('ai-chat-window');
        const chatMessages = document.getElementById('chat-messages');
        const userInput = document.getElementById('user-input');
        const btnSend = document.getElementById('btn-send');
        let isChatOpen = false;
        let chatHistory = []; // Simpan riwayat chat di sesi ini

        function toggleChat() {
            isChatOpen = !isChatOpen;
            if(isChatOpen) {
                chatWindow.classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
                setTimeout(() => userInput.focus(), 300);
            } else {
                chatWindow.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
            }
        }

        async function handleChatSubmit(e) {
            e.preventDefault();
            const message = userInput.value.trim();
            if(!message) return;

            // 1. Add User Message UI
            addMessage(message, 'user');
            
            // Simpan ke history
            chatHistory.push({sender: 'user', text: message});

            userInput.value = '';
            userInput.disabled = true;
            btnSend.disabled = true;
            
            // 2. Show Typing Indicator
            const loadingId = addLoadingIndicator();
            scrollToBottom();

            try {
                // 3. Fetch to Backend with History
                const formData = new URLSearchParams();
                formData.append('message', message);
                formData.append('history', JSON.stringify(chatHistory.slice(0, -1))); // Kirim pesan sebelumnya (exclude current msg yg sudah dikirim terpisah di 'message') (Wait, no. logic backend appends message. So history should be PREVIOUS messages.)
                // Revisi logika: Backend menggabungkan history + message baru.
                // Jadi 'history' adalah semua yang SEBELUM pesan ini.
                // chatHistory saat ini SUDAH include pesan baru. Jadi kita kirim slice(0, -1).
                
                const response = await fetch("<?= base_url('pelanggan/ai/chat') ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData.toString() // Gunakan URLSearchParams untuk otomatis encode
                });

                const data = await response.json();
                
                // Remove Loading
                document.getElementById(loadingId).remove();

                if(data.reply) {
                    addMessage(data.reply, 'ai');
                    // Simpan balasan AI ke history
                    chatHistory.push({sender: 'ai', text: data.reply.replace(/(<([^>]+)>)/gi, "")}); // Strip HTML tags for cleaner history context
                    
                } else if(data.error) {
                    addMessage("Maaf, terjadi kesalahan: " + data.error, 'ai', true);
                }

            } catch (error) {
                document.getElementById(loadingId).remove();
                addMessage("Gagal terhubung ke server. Coba lagi nanti.", 'ai', true);
                console.error(error);
            } finally {
                userInput.disabled = false;
                btnSend.disabled = false;
                userInput.focus();
                scrollToBottom();
            }
        }

        function addMessage(text, sender, isError = false) {
            const div = document.createElement('div');
            
            if(sender === 'user') {
                div.className = 'flex items-start gap-2.5 flex-row-reverse animate-fade-in';
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="bg-primary-600 p-3 rounded-2xl rounded-tr-none text-sm text-white max-w-[85%] shadow-sm">
                        ${text}
                    </div>
                `;
            } else {
                div.className = 'flex items-start gap-2.5 animate-fade-in';
                const bgClass = isError ? 'bg-red-50 border-red-100 text-red-600' : 'bg-white border-gray-100 text-gray-700';
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 flex-shrink-0">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="${bgClass} p-3 rounded-2xl rounded-tl-none border shadow-sm text-sm max-w-[85%]">
                        ${text}
                    </div>
                `;
            }
            
            chatMessages.appendChild(div);
        }

        function addLoadingIndicator() {
            const id = 'loading-' + Date.now();
            const div = document.createElement('div');
            div.id = id;
            div.className = 'flex items-start gap-2.5 animate-pulse';
            div.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 flex-shrink-0">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="bg-gray-100 p-3 rounded-2xl rounded-tl-none text-gray-500 text-sm flex gap-1">
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                </div>
            `;
            chatMessages.appendChild(div);
            return id;
        }

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // --- EXISTING FUNCTIONS ---

        function showToast() {
            const container = document.getElementById('toast-container');
            const template = document.getElementById('toast-template');
            
            // Clone template
            const toast = template.cloneNode(true);
            toast.id = '';
            toast.classList.remove('hidden');
            
            // Append
            container.appendChild(toast);
            
            // Animate In
            setTimeout(() => {
                toast.classList.remove('translate-x-10', 'opacity-0');
            }, 10);
            
            // Remove after 3s
            setTimeout(() => {
                toast.classList.add('translate-x-10', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        function tambahKeranjang(id_obat) {
            // Button Feedback (Optional - could add loading state visually)
            
            fetch("<?= base_url('keranjang/tambah/') ?>" + id_obat)
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Assuming success if no redirect (or status check)
                        showToast();
                        
                        // Update Cart Badge if possible (would need logic to fetch cart count)
                        // This is a future enhancement
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menambahkan ke keranjang');
                });
        }
    </script>
    <script>
        // Close Filter Dropdown on Click Outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('filterDropdown');
            const button = e.target.closest('button[onclick*="filterDropdown"]');
            
            if (!dropdown.classList.contains('hidden') && !dropdown.contains(e.target) && !button) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
