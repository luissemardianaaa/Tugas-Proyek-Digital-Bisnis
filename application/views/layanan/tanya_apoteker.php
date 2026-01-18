<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanya Apoteker - Apotek Friendly</title>
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
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
                            400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
                            800: '#065f46', 900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased flex flex-col min-h-screen">
    <!-- NAVBAR -->
    <nav class="bg-white/90 backdrop-blur fixed w-full z-50 top-0 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="window.location.href='<?= base_url() ?>'">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-primary-200">
                        <i class="bi bi-capsule"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight leading-none">Apotek<span class="text-primary-600">Friendly</span></h1>
                    </div>
                </div>
                <!-- Simple Back Button -->
                <a href="<?= base_url() ?>" class="text-gray-500 hover:text-primary-600 font-medium text-sm flex items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow pt-28 pb-20 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Konsultasi Apoteker</h2>
                <p class="text-gray-500">Dapatkan saran profesional tentang penggunaan obat dan kesehatan Anda</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Card Chat AI -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl shadow-primary-500/5 hovered-card transition-all hover:scale-[1.02]">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        <i class="bi bi-robot"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">AI Asisten Apoteker</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Konsultasi instan 24/7 dengan kecerdasan buatan kami. Deteksi gejala dini dan rekomendasi obat over-the-counter.</p>
                    <button onclick="document.querySelector('.ai-chat-trigger').click()" class="w-full py-3 px-6 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                        Mulai Chat AI
                    </button>
                </div>

                <!-- Card Live Chat -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl shadow-primary-500/5 hovered-card transition-all hover:scale-[1.02]">
                    <div class="w-16 h-16 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Chat Apoteker Ahli</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Berbicara langsung dengan apoteker berlisensi kami pada jam kerja (08.00 - 20.00 WIB).</p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="block text-center w-full py-3 px-6 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-colors">
                        Chat via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER SIMPLE -->
    <footer class="bg-white border-t border-gray-200 py-8 text-center text-gray-500 text-sm">
        <p>&copy; <?= date('Y') ?> Apotek Friendly. All rights reserved.</p>
    </footer>

    <!-- Hidden Chat Trigger used by Home page logic if we want to integrate later, but for now just placeholder -->
    <div class="hidden ai-chat-trigger" onclick="alert('Fitur Chat AI ada di pojok kanan bawah halaman Beranda!')"></div>

</body>
</html>
