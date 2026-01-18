<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pengiriman - Apotek Friendly</title>
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
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Informasi Pengiriman</h2>
                <p class="text-gray-500">Kami menjamin obat Anda sampai dengan aman dan cepat</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Card Instant -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Pengiriman Instan</h3>
                            <p class="text-sm text-gray-500 mb-3">Pesanan sampai dalam waktu maks 2 jam.</p>
                            <ul class="text-xs text-gray-500 space-y-1">
                                <li class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-primary-500"></i> Area dalam kota (maks 10km)</li>
                                <li class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-primary-500"></i> Tersedia 08.00 - 20.00</li>
                            </ul>
                        </div>
                    </div>
                </div>

                 <!-- Card Regular -->
                 <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Reguler (JNE/J&T)</h3>
                            <p class="text-sm text-gray-500 mb-3">Estimasi sampai 2-3 hari kerja.</p>
                            <ul class="text-xs text-gray-500 space-y-1">
                                <li class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-primary-500"></i> Melayani seluruh Indonesia</li>
                                <li class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-primary-500"></i> Resi otomatis H+1</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-orange-50 rounded-2xl p-6 border border-orange-100">
                <h4 class="font-bold text-orange-800 mb-2 flex items-center gap-2"><i class="bi bi-exclamation-circle-fill"></i> Ketentuan Pengiriman Obat Keras</h4>
                <p class="text-sm text-orange-800/80 leading-relaxed">
                    Khusus untuk pembelian obat keras (bertanda lingkaran merah), wajib menyertakan resep dokter asli saat kurir mengantarkan barang atau upload resep terlebih dahulu melalui fitur layanan kami.
                </p>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-400">
        &copy; <?= date('Y') ?> Apotek Friendly
    </footer>
</body>
</html>
