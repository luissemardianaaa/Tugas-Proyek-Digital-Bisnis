<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Resep - Apotek Friendly</title>
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
            <div class="flex items-center gap-2 font-bold text-xl cursor-copy" onclick="location.href='<?= base_url() ?>'">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white"><i class="bi bi-capsule"></i></div>
                <span>Apotek<span class="text-primary-600">Friendly</span></span>
            </div>
            <a href="<?= base_url() ?>" class="text-gray-500 hover:text-primary-600 text-sm font-medium"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </nav>

    <main class="flex-grow pt-28 pb-20 px-4">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary-50 text-primary-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="bi bi-file-earmark-medical"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Upload Resep Dokter</h2>
                <p class="text-gray-500 mt-2 text-sm">Tebus obat resep dengan mudah tanpa antri</p>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Resep</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-primary-500 hover:bg-primary-50 transition-all cursor-pointer group">
                        <div class="space-y-1 text-center">
                            <i class="bi bi-cloud-upload text-3xl text-gray-400 group-hover:text-primary-500 transition-colors"></i>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                    <span>Upload file</span>
                                    <input id="file-upload" name="resep" type="file" class="sr-only">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                    <textarea rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm bg-gray-50 px-4 py-3" placeholder="Contoh: Tolong antar sore hari..."></textarea>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl flex gap-3 items-start">
                    <i class="bi bi-info-circle-fill text-blue-500 mt-0.5"></i>
                    <p class="text-xs text-blue-700 leading-relaxed">Apoteker kami akan memeriksa resep Anda dan mengirimkan rincian harga via WhatsApp atau Email yang terdaftar dalam waktu maksimal 15 menit.</p>
                </div>

                <button type="submit" onclick="alert('Fitur ini akan segera aktif! Silakan hubungi WA kami untuk sementara.')" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:scale-[1.01]">
                    Kirim Resep
                </button>
            </form>
        </div>
    </main>
    
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-400">
        &copy; <?= date('Y') ?> Apotek Friendly
    </footer>
</body>
</html>
