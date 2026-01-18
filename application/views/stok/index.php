<?php $this->load->view('templates/sidebar'); ?>

        <!-- Content Area -->
        <div class="p-8">
            <?php if($this->session->flashdata('success')): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-emerald-700"><?= $this->session->flashdata('success') ?></span>
            </div>
            <?php endif; ?>

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">📦 Manajemen Stok Obat</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola stok obat masuk, keluar, dan penyesuaian</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportLog()" class="flex items-center gap-2 border border-emerald-600 text-emerald-600 px-4 py-2 rounded-xl hover:bg-emerald-50 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export Log
                    </button>
                    <a href="<?= site_url('karyawan/obat/create') ?>" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Obat Baru
                    </a>
                </div>
            </div>

            <!-- STOCK CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Stok Masuk</h4>
                    <p class="text-gray-500 text-sm mb-4">Tambahkan stok obat baru dari pembelian</p>
                    <a href="<?= site_url('karyawan/pembelian/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-blue-600 text-blue-600 rounded-xl hover:bg-blue-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Stok Masuk
                    </a>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Stok Keluar</h4>
                    <p class="text-gray-500 text-sm mb-4">Kurangi stok karena penjualan atau kerusakan</p>
                    <button onclick="setStokType('keluar')" class="inline-flex items-center gap-2 px-4 py-2 border border-emerald-600 text-emerald-600 rounded-xl hover:bg-emerald-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        Stok Keluar
                    </button>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Penyesuaian</h4>
                    <p class="text-gray-500 text-sm mb-4">Koreksi stok karena stock opname</p>
                    <button onclick="setStokType('adjust')" class="inline-flex items-center gap-2 px-4 py-2 border border-amber-600 text-amber-600 rounded-xl hover:bg-amber-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        Adjust Stok
                    </button>
                </div>
            </div>

            <!-- CRITICAL STOCK ALERTS -->
            <?php if(!empty($obat_kritis) || !empty($obat_habis)): ?>
            <div class="mb-8 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="flex items-start justify-between">
                    <div>
                        <h6 class="font-semibold text-amber-800 mb-2">⚠️ Stok Obat Perlu Perhatian!</h6>
                        <div class="flex flex-wrap gap-4">
                            <?php if(!empty($obat_habis)): ?>
                            <div>
                                <span class="text-red-700 font-medium">❌ Stok Habis (<?= count($obat_habis) ?>):</span>
                                <?php foreach($obat_habis as $index => $item): ?>
                                    <?php if($index < 3): ?>
                                        <span class="ml-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs"><?= $item->nama_obat ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if(count($obat_habis) > 3): ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">+<?= count($obat_habis) - 3 ?> lainnya</span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($obat_kritis)): ?>
                            <div>
                                <span class="text-amber-700 font-medium">⚠️ Stok Kritis (<?= count($obat_kritis) ?>):</span>
                                <?php foreach($obat_kritis as $index => $item): ?>
                                    <?php if($index < 3): ?>
                                        <span class="ml-1 px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs"><?= $item->nama_obat ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if(count($obat_kritis) > 3): ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">+<?= count($obat_kritis) - 3 ?> lainnya</span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button onclick="loadCriticalStock()" class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-sm hover:bg-amber-200 transition-colors">
                        🔄 Refresh
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- STOCK LOG TABLE -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h5 class="font-semibold text-gray-800">📜 Riwayat Perubahan Stok</h5>
                    <span class="text-gray-500 text-sm"><?= count($logs ?? []) ?> transaksi terakhir</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">No</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Waktu</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Obat</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Jenis</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Jumlah</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Stok Sebelum</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Stok Sesudah</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Petugas</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if(!empty($logs)): ?>
                                <?php $no=1; foreach($logs as $log): 
                                    $stok_sebelum = $log->stok_sesudah - $log->jumlah_perubahan;
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-600"><?= $no++ ?></td>
                                    <td class="px-4 py-3 text-gray-600"><?= date('d/m/Y H:i', strtotime($log->created_at)) ?></td>
                                    <td class="px-4 py-3 font-medium text-gray-800"><?= $log->nama_obat ?></td>
                                    <td class="px-4 py-3 text-gray-600"><?= ucfirst($log->jenis) ?></td>
                                    <td class="px-4 py-3 text-gray-600"><?= $log->jumlah_perubahan ?></td>
                                    <td class="px-4 py-3 text-gray-600"><?= $stok_sebelum ?></td>
                                    <td class="px-4 py-3 text-gray-600"><?= $log->stok_sesudah ?></td>
                                    <td class="px-4 py-3 text-gray-600"><?= $log->nama ?></td>
                                    <td class="px-4 py-3 text-gray-500"><?= $log->keterangan ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                        Belum ada riwayat perubahan stok
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ANALISIS OBAT MENGENDAP (DEAD STOCK) - HASIL REKAYASA SISTEM -->
        <div class="mt-12 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Analisis Obat Mengendap (Dead Stock)</h3>
                        <p class="text-sm text-gray-500">Hasil Analisis Rekayasa Perangkat Lunak - Jangka Waktu 60 Hari</p>
                    </div>
                </div>
                <?php if(!empty($dead_stock)): ?>
                    <span class="px-4 py-2 bg-red-100 text-red-700 rounded-xl text-sm font-bold animate-pulse">
                        ⚠️ Terdeteksi <?= count($dead_stock) ?> Obat Berisiko Rugi!
                    </span>
                <?php endif; ?>
            </div>

            <?php if(empty($dead_stock)): ?>
                <div class="p-12 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-400">Semua obat bersirkulasi dengan baik dalam 60 hari terakhir.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Obat</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Terakhir Terjual</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Stok Saat Ini</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Harga Jual</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Potensi Kerugian</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php 
                            $total_potensi_rugi = 0;
                            foreach($dead_stock as $item): 
                                $potensi_rugi = $item->stok * $item->harga;
                                $total_potensi_rugi += $potensi_rugi;
                                $tgl_terakhir = $item->terakhir_terjual ? date('d/m/Y', strtotime($item->terakhir_terjual)) : 'Belum Pernah';
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-800"><?= $item->nama_obat ?></td>
                                <td class="px-4 py-3 text-gray-600"><?= $tgl_terakhir ?></td>
                                <td class="px-4 py-3 text-gray-600"><?= $item->stok ?> <?= $item->satuan ?></td>
                                <td class="px-4 py-3 text-gray-600">Rp <?= number_format($item->harga, 0, ',', '.') ?></td>
                                <td class="px-4 py-3 text-rose-600 font-bold">Rp <?= number_format($potensi_rugi, 0, ',', '.') ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold uppercase">Danger</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="bg-rose-50">
                                <td colspan="4" class="px-4 py-4 text-right font-bold text-gray-700">Total Estimasi Modal Mengendap (Kerugian):</td>
                                <td colspan="2" class="px-4 py-4 text-rose-700 font-black text-lg">
                                    Rp <?= number_format($total_potensi_rugi, 0, ',', '.') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-amber-800 italic">
                        <strong>Hasil Analisis :</strong> Data di atas mengidentifikasi item yang tidak memiliki transaksi dalam 60 hari terakhir. Stok ini dianggap "mati" dan menyebabkan modal macet. Segera lakukan promosi atau retur ke pemasok.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- MODAL TRANSAKSI STOK -->
        <div id="stokModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeModal()">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="<?= site_url('karyawan/stok/tambah_stok') ?>" method="POST">
                        <div class="bg-white px-6 py-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Transaksi Stok</h3>
                                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <input type="hidden" name="jenis" id="stok_jenis">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Obat</label>
                                    <select name="id_obat" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
                                        <option value="">-- Pilih Obat --</option>
                                        <?php foreach($obat as $o): ?>
                                            <option value="<?= $o->id_obat ?>"><?= $o->nama_obat ?> (Sisa: <?= $o->stok ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div id="jumlahContainer">
                                    <label class="block text-sm font-medium text-gray-700 mb-1" id="jumlahLabel">Jumlah</label>
                                    <input type="number" name="jumlah" required min="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none" placeholder="Masukkan angka...">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none" placeholder="Alasan perubahan stok..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeModal()" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>

<script>
function exportLog() {
    window.location.href = "<?= site_url('karyawan/stok/export_log') ?>";
}

function setStokType(type) {
    const modal = document.getElementById('stokModal');
    const title = document.getElementById('modalTitle');
    const jenisInput = document.getElementById('stok_jenis');
    const jumlahLabel = document.getElementById('jumlahLabel');
    
    jenisInput.value = type;
    
    if (type === 'keluar') {
        title.innerText = '📉 Konfirmasi Stok Keluar';
        jumlahLabel.innerText = 'Jumlah Stok Keluar';
    } else if (type === 'adjust') {
        title.innerText = '🔄 Penyesuaian Stok (Adjust)';
        jumlahLabel.innerText = 'Stok Akhir yang Benar';
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('stokModal').classList.add('hidden');
}

function loadCriticalStock() {
    location.reload();
}
</script>
