<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cek Lab - Apotek Friendly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            <div class="flex items-center gap-2 font-bold text-xl">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white"><i class="bi bi-capsule"></i></div>
                <span>Apotek<span class="text-primary-600">Friendly</span></span>
            </div>
            <a href="<?= site_url('layanan/cek_lab') ?>" class="text-gray-500 hover:text-primary-600 text-sm font-medium"><i class="bi bi-arrow-left"></i> Batal</a>
        </div>
    </nav>

    <main class="flex-grow pt-28 pb-20 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-primary-600 p-8 text-white text-center">
                    <p class="text-primary-100 font-medium mb-1">Formulir Pemesanan</p>
                    <h2 class="text-2xl font-bold">Booking Jadwal Cek Lab</h2>
                </div>
                
                <form id="booking-form" onsubmit="submitBooking(event)" class="p-8 space-y-6">
                    <!-- Paket Info -->
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Paket Dipilih</p>
                            <h3 class="font-bold text-lg text-gray-900"><?= $paket['nama'] ?></h3>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase font-bold">Total Harga</p>
                            <p class="font-bold text-lg text-primary-600">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Pasien</label>
                            <input type="text" value="<?= $user['nama'] ?>" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-500" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pemeriksaan <span class="text-red-500">*</span></label>
                            <input type="text" id="tanggal" name="tanggal" class="w-full bg-white border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-primary-500 cursor-pointer" placeholder="Pilih Tanggal" required>
                            <p class="text-xs text-orange-500 mt-1"><i class="bi bi-info-circle"></i> Wajib booking H-3 sebelum pemeriksaan.</p>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="border-t border-dashed border-gray-200 pt-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Total Tagihan</span>
                            <span class="font-bold">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between items-center text-lg">
                            <span class="text-gray-900 font-bold">Wajib DP (50%)</span>
                            <span class="font-bold text-primary-600">Rp <?= number_format($paket['dp'], 0, ',', '.') ?></span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-center">Pelunasan dilakukan di kasir klinik saat kedatangan.</p>
                    </div>

                    <input type="hidden" name="nama_paket" value="<?= $paket['nama'] ?>">
                    <input type="hidden" name="harga" value="<?= $paket['harga'] ?>">
                    <input type="hidden" name="dp_amount" value="<?= $paket['dp'] ?>">

                    <button type="submit" class="w-full py-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition shadow-lg transform active:scale-[0.99]">
                        Konfirmasi Booking
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // H-1 Restriction: Min Date = Tomorrow
        flatpickr("#tanggal", {
            minDate: new Date().fp_incr(1), // Tomorrow
            dateFormat: "Y-m-d",
            disableMobile: "true"
        });

        function submitBooking(e) {
            e.preventDefault();
            const form = document.querySelector('#booking-form');
            const formData = new FormData(form);

            fetch('<?= site_url("layanan/submit_booking") ?>', {
                method: 'POST',
                body: formData
            })
            .then(async res => {
                const text = await res.text();
                try {
                    const data = JSON.parse(text);
                if(data.status) {
                    alert('Booking Berhasil! Anda akan diarahkan ke halaman rincian tagihan.');
                    window.location.href = data.redirect;
                } else {
                     alert(data.message);
                }
                } catch(e) {
                    console.error('Server Error:', text);
                    alert('Terjadi kesalahan server:\n' + text.substring(0, 100) + '...');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal menghubungi server. Periksa koneksi internet.');
            });
        }
    </script>
</body>
</html>
