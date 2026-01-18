<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Belanja - Apotek Friendly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: { primary: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857' } }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased flex flex-col min-h-screen">
    <nav class="bg-white/90 backdrop-blur fixed w-full z-50 top-0 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2 font-bold text-xl cursor-pointer" onclick="location.href='<?= base_url() ?>'">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white"><i class="bi bi-capsule"></i></div>
                <span>Apotek<span class="text-primary-600">Friendly</span></span>
            </div>
            <a href="<?= base_url() ?>" class="text-gray-500 hover:text-primary-600 text-sm font-medium"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </nav>

    <main class="flex-grow pt-28 pb-20 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Cara Berbelanja</h2>
                <p class="text-gray-500">Panduan mudah berbelanja obat dan alat kesehatan di Apotek Friendly</p>
            </div>

            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                
                <!-- Step 1 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-primary-100 group-[.is-active]:bg-primary-600 text-primary-600 group-[.is-active]:text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors">
                        <span class="font-bold">1</span>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="text-primary-600 text-3xl mb-3"><i class="bi bi-search"></i></div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2">Cari Produk</h3>
                        <p class="text-gray-500 text-sm">Gunakan kolom pencarian di halaman utama atau jelajahi kategori obat untuk menemukan produk yang Anda butuhkan.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-primary-100 group-[.is-active]:bg-primary-600 text-primary-600 group-[.is-active]:text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors">
                        <span class="font-bold">2</span>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="text-primary-600 text-3xl mb-3"><i class="bi bi-cart-plus"></i></div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2">Masukkan Keranjang</h3>
                        <p class="text-gray-500 text-sm">Klik tombol "Tambah" pada produk yang diinginkan. Anda bisa mengatur jumlah barang di halaman Keranjang.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-primary-100 group-[.is-active]:bg-primary-600 text-primary-600 group-[.is-active]:text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors">
                        <span class="font-bold">3</span>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="text-primary-600 text-3xl mb-3"><i class="bi bi-credit-card"></i></div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2">Checkout & Pembayaran</h3>
                        <p class="text-gray-500 text-sm">Isi alamat pengiriman dengan lengkap, pilih metode pengiriman, dan lakukan pembayaran melalui metode yang tersedia.</p>
                    </div>
                </div>

                 <!-- Step 4 -->
                 <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-primary-100 group-[.is-active]:bg-primary-600 text-primary-600 group-[.is-active]:text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors">
                        <span class="font-bold">4</span>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="text-primary-600 text-3xl mb-3"><i class="bi bi-box-seam"></i></div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2">Terima Pesanan</h3>
                        <p class="text-gray-500 text-sm">Duduk manis di rumah, obat Anda akan kami antarkan segera dengan aman dan terjamin keasliannya.</p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-400">
        &copy; <?= date('Y') ?> Apotek Friendly
    </footer>
</body>
</html>
