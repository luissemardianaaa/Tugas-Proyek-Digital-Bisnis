<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - Apotek Friendly</title>
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
    
    <nav class="bg-white border-b border-gray-100 fixed w-full z-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="<?= base_url('pembayaran/riwayat') ?>" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition-colors">
                    <i class="bi bi-arrow-left text-lg"></i>
                    <span class="font-medium">Riwayat</span>
                </a>
                <h1 class="text-lg font-bold text-gray-900">Pembayaran</h1>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-12 px-4">
        <div class="max-w-xl mx-auto space-y-6">
            
            <!-- Success Message -->
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600">
                    <i class="bi bi-check-lg text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Pemesanan Berhasil!</h2>
                <p class="text-gray-600 text-sm">Kode Transaksi: <span class="font-mono font-bold text-gray-900"><?= $transaksi->kode_transaksi ?></span></p>
            </div>

            <!-- Amount Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <span class="text-gray-500 text-sm font-medium">Total Yang Harus Dibayar</span>
                <div class="text-3xl font-bold text-gray-900 mt-2 mb-1">
                    Rp <?= number_format($transaksi->total_harga, 0, ',', '.') ?>
                </div>
                <div class="text-xs text-orange-500 font-medium bg-orange-50 inline-block px-3 py-1 rounded-full border border-orange-100">
                    Batas Pembayaran: 24 Jam
                </div>
            </div>

            <!-- Bank Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-bank"></i> Transfer Bank
                </h3>
                <div class="p-4 border border-gray-200 rounded-xl flex items-center justify-between bg-gray-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                            <span class="font-bold text-blue-800 italic">BCA</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Bank Central Asia</p>
                            <p class="font-mono font-bold text-lg text-gray-900">1234567890</p>
                            <p class="text-xs text-gray-500">a.n Apotek Friendly</p>
                        </div>
                    </div>
                    <button onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor rekening disalin!')" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Salin
                    </button>
                </div>
            </div>

            <!-- Upload Proof -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Konfirmasi Pembayaran</h3>
                
                <?php if($this->session->flashdata('error')): ?>
                    <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-4">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <?php if($this->session->flashdata('success')): ?>
                    <div class="bg-emerald-50 text-emerald-600 p-3 rounded-xl text-sm mb-4">
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?= form_open_multipart('pembayaran/upload_bukti/' . $transaksi->id_transaksi) ?>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                            <input type="file" name="bukti_pembayaran" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-xl file:border-0
                                file:text-sm file:font-semibold
                                file:bg-primary-50 file:text-primary-700
                                hover:file:bg-primary-100
                            " required>
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF. Maks 2MB.</p>
                        </div>
                        <button type="submit" class="w-full bg-primary-600 text-white font-bold py-3.5 rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-200">
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
            
            <a href="<?= site_url('bantuan/hubungi_kami') ?>" class="block text-center text-sm text-gray-500 hover:text-primary-600">
                Butuh bantuan? Hubungi CS
            </a>

        </div>
    </main>
</body>
</html>
