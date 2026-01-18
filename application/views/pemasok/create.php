<?php $this->load->view('templates/sidebar'); ?>
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Pemasok Baru</h1>
                <p class="text-gray-500 text-sm mt-1">Input data supplier rekanan</p>
            </div>
            <a href="<?= site_url('karyawan/pemasok') ?>" class="text-gray-600 hover:text-gray-800 font-medium">
                <i class="bi bi-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="<?= site_url('karyawan/pemasok/store') ?>" method="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kode Auto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pemasok (Otomatis)</label>
                        <input type="text" name="kode_pemasok" value="<?= $kode ?>" readonly 
                               class="w-full bg-gray-50 text-gray-500 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none">
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemasok</label>
                        <input type="text" name="nama_pemasok" required 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                        <input type="text" name="telepon" 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" 
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all"></textarea>
                </div>

                <!-- Keterangan -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="2" 
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all"></textarea>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <i class="bi bi-save mr-2"></i> Simpan Data Pemasok
                </button>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('templates/footer'); ?>
