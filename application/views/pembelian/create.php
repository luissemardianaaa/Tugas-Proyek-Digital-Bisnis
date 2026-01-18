<?php $this->load->view('templates/sidebar'); ?>
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Transaksi Pembelian</h1>
                <p class="text-gray-500 text-sm mt-1">Input data pembelian obat dari pemasok</p>
            </div>
            <a href="<?= site_url('karyawan/pembelian') ?>" class="text-gray-600 hover:text-gray-800 font-medium">
                <i class="bi bi-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="<?= site_url('karyawan/pembelian/store') ?>" method="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>

                    <!-- ID Pemasok -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID Pemasok</label>
                        <div class="relative">
                            <input type="text" name="id_pemasok" placeholder="Masukkan ID Pemasok (Contoh: 1)" required 
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 pl-11 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-person-badge text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">*Cukup masukkan angka ID Pemasok</p>
                    </div>

                    <!-- Obat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Obat</label>
                        <div class="relative">
                            <input type="text" name="nama_obat" placeholder="Masukkan Nama Obat" required 
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 pl-11 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-capsule text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Beli</label>
                        <input type="number" name="jumlah" id="jumlah" min="1" required 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all"
                               oninput="hitungTotal()">
                    </div>

                    <!-- Harga Satuan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Satuan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">Rp</span>
                            <input type="number" name="harga_satuan" id="harga_satuan" min="0" required 
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 pl-10 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all"
                                   oninput="hitungTotal()">
                        </div>
                    </div>

                    <!-- Total (Auto) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                        <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between border border-gray-200">
                            <span class="text-gray-500 font-medium">Total:</span>
                            <span class="text-2xl font-bold text-emerald-600" id="total_display">Rp 0</span>
                        </div>
                        <input type="hidden" name="total_harga" id="total_harga">
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all"></textarea>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <i class="bi bi-save mr-2"></i> Simpan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function hitungTotal() {
    let jumlah = document.getElementById('jumlah').value || 0;
    let harga = document.getElementById('harga_satuan').value || 0;
    let total = jumlah * harga;

    // Update input hidden
    document.getElementById('total_harga').value = total;

    // Update display currency
    document.getElementById('total_display').innerText = new Intl.NumberFormat('id-ID', { 
        style: 'currency', 
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(total);
}
</script>

<?php $this->load->view('templates/footer'); ?>
