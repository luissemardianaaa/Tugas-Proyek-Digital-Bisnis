<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 pb-32">

    <!-- HEADER -->
    <div class="fixed top-0 left-0 right-0 bg-white z-50 shadow-sm">
        <div class="flex items-center gap-4 px-4 h-16 max-w-lg mx-auto">
            <a href="<?= site_url('keranjang') ?>" class="text-emerald-600 text-xl hover:bg-gray-50 p-2 rounded-full transition-colors">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="text-lg font-semibold text-gray-800">Checkout</h1>
        </div>
    </div>

    <form action="<?= site_url('keranjang/proses_pesanan') ?>" method="POST" id="checkoutForm" class="max-w-lg mx-auto mt-20 space-y-3 px-2">
        
        <!-- ALAMAT PENGIRIMAN -->
        <div class="bg-white p-4 rounded-xl shadow-sm border-t-[3px] border-t-[repeating-linear-gradient(45deg,#10b981,#10b981_10px,#fff_10px,#fff_20px,#ef4444_20px,#ef4444_30px,#fff_30px,#fff_40px)]">
            <div class="flex items-center gap-2 text-gray-800 font-medium mb-3">
                <i class="bi bi-geo-alt text-emerald-600"></i>
                <span>Alamat Pengiriman</span>
            </div>
            
            <div class="space-y-3">
                <input type="text" name="nama_penerima" value="<?= $this->session->userdata('nama') ?>" class="w-full text-sm border-gray-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Nama Penerima" required>
                <input type="text" name="no_hp" value="<?= $this->session->userdata('no_hp') ?>" class="w-full text-sm border-gray-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Nomor Telepon (+62)" required>
                
                <textarea name="alamat_lengkap" rows="2" class="w-full text-sm border-gray-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Jalan, RT/RW, Kecamatan, Kelurahan..." required><?= $this->session->userdata('alamat') ?></textarea>
                
                <select name="kota_tujuan" id="kotaTujuan" class="w-full text-sm border-gray-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" required>
                    <option value="" data-ongkir="0">-- Pilih Kota / Kabupaten --</option>
                    <?php $saved_kota = $this->session->userdata('kota'); ?>
                    <optgroup label="Jabodetabek (Promo)">
                        <option value="Jakarta Pusat" data-ongkir="9000" <?= $saved_kota == 'Jakarta Pusat' ? 'selected' : '' ?>>Jakarta Pusat (Rp9.000)</option>
                        <option value="Jakarta Selatan" data-ongkir="9000" <?= $saved_kota == 'Jakarta Selatan' ? 'selected' : '' ?>>Jakarta Selatan (Rp9.000)</option>
                        <option value="Jakarta Barat" data-ongkir="9000" <?= $saved_kota == 'Jakarta Barat' ? 'selected' : '' ?>>Jakarta Barat (Rp9.000)</option>
                        <option value="Jakarta Timur" data-ongkir="9000" <?= $saved_kota == 'Jakarta Timur' ? 'selected' : '' ?>>Jakarta Timur (Rp9.000)</option>
                        <option value="Jakarta Utara" data-ongkir="9000" <?= $saved_kota == 'Jakarta Utara' ? 'selected' : '' ?>>Jakarta Utara (Rp9.000)</option>
                        <option value="Bogor" data-ongkir="12000" <?= $saved_kota == 'Bogor' ? 'selected' : '' ?>>Bogor (Rp12.000)</option>
                        <option value="Depok" data-ongkir="12000" <?= $saved_kota == 'Depok' ? 'selected' : '' ?>>Depok (Rp12.000)</option>
                        <option value="Tangerang" data-ongkir="12000" <?= $saved_kota == 'Tangerang' ? 'selected' : '' ?>>Tangerang (Rp12.000)</option>
                        <option value="Bekasi" data-ongkir="12000" <?= $saved_kota == 'Bekasi' ? 'selected' : '' ?>>Bekasi (Rp12.000)</option>
                    </optgroup>
                    <optgroup label="Pulau Jawa">
                        <option value="Bandung" data-ongkir="22000" <?= $saved_kota == 'Bandung' ? 'selected' : '' ?>>Bandung (Rp22.000)</option>
                        <option value="Semarang" data-ongkir="22000" <?= $saved_kota == 'Semarang' ? 'selected' : '' ?>>Semarang (Rp22.000)</option>
                        <option value="Yogyakarta" data-ongkir="22000" <?= $saved_kota == 'Yogyakarta' ? 'selected' : '' ?>>Yogyakarta (Rp22.000)</option>
                        <option value="Surabaya" data-ongkir="24000" <?= $saved_kota == 'Surabaya' ? 'selected' : '' ?>>Surabaya (Rp24.000)</option>
                        <option value="Malang" data-ongkir="24000" <?= $saved_kota == 'Malang' ? 'selected' : '' ?>>Malang (Rp24.000)</option>
                    </optgroup>
                    <optgroup label="Luar Pulau Jawa">
                        <option value="Luar Pulau Jawa (Auto)" data-ongkir="50000">Luar Pulau Jawa (Promo Flat) - Rp50.000</option>
                        <option value="Medan" data-ongkir="45000" <?= $saved_kota == 'Medan' ? 'selected' : '' ?>>Medan (Rp45.000)</option>
                        <option value="Palembang" data-ongkir="40000" <?= $saved_kota == 'Palembang' ? 'selected' : '' ?>>Palembang (Rp40.000)</option>
                        <option value="Makassar" data-ongkir="50000" <?= $saved_kota == 'Makassar' ? 'selected' : '' ?>>Makassar (Rp50.000)</option>
                        <option value="Denpasar" data-ongkir="35000" <?= $saved_kota == 'Denpasar' ? 'selected' : '' ?>>Denpasar (Rp35.000)</option>
                        <option value="Lainnya" data-ongkir="55000" <?= $saved_kota == 'Lainnya' ? 'selected' : '' ?>>Kota Lainnya (Rp55.000)</option>
                    </optgroup>
                </select>
            </div>
        </div>

        <!-- LIST PRODUK -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                <span class="bg-emerald-600 text-white text-[10px] font-bold px-1 rounded-sm">Official</span>
                <h3 class="font-bold text-gray-800 text-sm">Apotek Friendly</h3>
            </div>

            <?php 
            $subtotal_produk = 0;
            foreach($items as $i): 
                $subtotal = $i->harga * $i->jumlah;
                $subtotal_produk += $subtotal;
            ?>
            <div class="p-4 bg-gray-50 flex gap-3 border-b border-white last:border-0">
                <div class="w-16 h-16 bg-white rounded border border-gray-200 overflow-hidden flex-shrink-0">
                    <img src="<?= base_url('uploads/obat/' . $i->gambar) ?>" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h4 class="text-sm text-gray-800 line-clamp-1"><?= $i->nama_obat ?></h4>
                    <p class="text-xs text-gray-500 mt-1">Variasi: <?= $i->satuan ?></p>
                    <div class="flex justify-between items-end mt-2">
                        <span class="text-sm text-gray-800">Rp<?= number_format($i->harga,0,',','.') ?></span>
                        <span class="text-sm text-gray-500">x<?= $i->jumlah ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- INFO PEMBAYARAN -->
        <div class="bg-gradient-to-r from-emerald-50 to-blue-50 p-4 rounded-xl shadow-sm border border-emerald-100">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-shield-check text-white text-lg"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-sm mb-1">Pembayaran Aman dengan Midtrans</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">Setelah klik "Buat Pesanan", Anda akan diarahkan ke halaman pembayaran. Pilih metode pembayaran favorit Anda: <strong>Bank Transfer, E-Wallet (GoPay, OVO, DANA), QRIS,</strong> atau <strong>COD</strong>.</p>
                </div>
            </div>
        </div>

        <!-- PESAN -->
        <div class="bg-white p-4 rounded-xl shadow-sm flex items-center justify-between">
            <span class="text-sm text-gray-800">Pesan:</span>
            <input type="text" name="pesan" placeholder="(Opsional) Tinggalkan pesan..." class="text-sm text-right flex-1 ml-4 border-none focus:ring-0 text-gray-600 placeholder-gray-400">
        </div>

        <!-- RINCIAN PEMBAYARAN -->
        <div class="bg-white p-4 rounded-xl shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Subtotal untuk Produk</span>
                <span class="text-sm text-gray-800">Rp<?= number_format($subtotal_produk,0,',','.') ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Subtotal Pengiriman</span>
                <span class="text-sm text-gray-800" id="displayOngkir">Rp0</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Biaya Layanan</span>
                <span class="text-sm text-gray-800">Rp1.000</span>
                <input type="hidden" name="biaya_layanan" value="1000">
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                <span class="text-base font-bold text-gray-800">Total Pembayaran</span>
                <span class="text-lg font-bold text-emerald-600" id="displayTotal">Rp<?= number_format($subtotal_produk + 1000,0,',','.') ?></span>
            </div>
        </div>
        
        <!-- HIDDEN INPUTS -->
        <input type="hidden" name="ongkir" id="inputOngkir" value="0">
        <input type="hidden" name="total_akhir" id="inputTotal" value="<?= $subtotal_produk + 1000 ?>">

        <!-- PADDING BOTTOM for Sticky Button -->
        <div class="h-6"></div>

        <!-- BOTTOM BUTTON -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-50">
            <div class="max-w-lg mx-auto flex items-center justify-end h-16 px-4">
                <div class="flex flex-col items-end mr-4">
                    <span class="text-xs text-gray-600">Total Pembayaran</span>
                    <span class="text-emerald-600 font-bold text-lg" id="displayTotalBottom">Rp<?= number_format($subtotal_produk + 1000,0,',','.') ?></span>
                </div>
                <button type="submit" class="bg-emerald-600 text-white font-bold h-10 px-8 rounded-lg hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">
                    Buat Pesanan
                </button>
            </div>
        </div>

    </form>
    
    <!-- TOAST NOTIFICATION -->
    <div id="toastPromo" class="fixed top-4 right-4 bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-xl transform translate-x-full transition-transform duration-300 flex items-center gap-3 z-[100]">
        <i class="bi bi-gift-fill text-yellow-300 text-xl"></i>
        <div>
            <h4 class="font-bold text-sm">Promo Ongkir Otomatis!</h4>
            <p class="text-xs text-emerald-100">Lokasi jauh terdeteksi, promo flat rate aktif.</p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const subtotalProduk = <?= $subtotal_produk ?>;
            const biayaLayanan = 1000;

            function formatRupiah(angka) {
                return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function updateCalculations() {
                const selectedOption = $('#kotaTujuan').find('option:selected');
                const ongkir = parseInt(selectedOption.data('ongkir')) || 0;
                const totalAkhir = subtotalProduk + biayaLayanan + ongkir;

                // Update UI
                $('#displayOngkir').text(formatRupiah(ongkir));
                $('#displayTotal').text(formatRupiah(totalAkhir));
                $('#displayTotalBottom').text(formatRupiah(totalAkhir));

                // Update Input Value
                $('#inputOngkir').val(ongkir);
                $('#inputTotal').val(totalAkhir);
            }

            $('#kotaTujuan').change(updateCalculations);

            // AUTO TRIGGER if session data pre-filled
            if ($('#kotaTujuan').val()) {
                updateCalculations();
            }

            // AUTO DETECT LUAR PULAU JAWA
            const keywordsLuarJawa = [
                'sumatera', 'medan', 'padang', 'palembang', 'pekanbaru', 'batam', 'lampung', 'bengkulu', 'jambi', 'aceh', 'riau',
                'kalimantan', 'pontianak', 'banjarmasin', 'samarinda', 'balikpapan', 'palangkaraya',
                'sulawesi', 'makassar', 'manado', 'palu', 'kendari', 'gorontalo',
                'papua', 'jayapura', 'sorong', 'merauke', 'manokwari',
                'maluku', 'ambon', 'ternate',
                'bali', 'denpasar', 
                'nusa tenggara', 'kupang', 'mataram', 'ntb', 'ntt'
            ];

            $('textarea[name="alamat_lengkap"]').on('input', function() {
                const address = $(this).val().toLowerCase();
                let isLuarJawa = false;

                // Cek apakah alamat mengandung salah satu keyword
                for (let word of keywordsLuarJawa) {
                    if (address.includes(word)) {
                        isLuarJawa = true;
                        break;
                    }
                }

                if (isLuarJawa) {
                    // Auto select opsi 'Luar Pulau Jawa (Auto)'
                    const targetValue = "Luar Pulau Jawa (Auto)";
                    
                    if ($('#kotaTujuan').val() !== targetValue) {
                        $('#kotaTujuan').val(targetValue).trigger('change');
                        
                        // Show toast
                        $('#toastPromo').removeClass('translate-x-full');
                        setTimeout(() => {
                            $('#toastPromo').addClass('translate-x-full');
                        }, 4000);
                    }
                }
            });
        });
    </script>
</body>
</html>
