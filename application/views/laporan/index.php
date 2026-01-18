<?php $this->load->view('templates/sidebar'); ?>

        <!-- Content Area -->
        <div class="p-8">
            <!-- HEADER -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">📑 Laporan Apotek</h1>
                <p class="text-gray-500 text-sm mt-1">Ringkasan laporan penjualan & pembelian</p>
            </div>

            <!-- SUMMARY CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Jumlah Transaksi</p>
                            <h3 class="text-3xl font-bold text-emerald-600"><?= $jumlah_transaksi ?? 0 ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">💊</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Pendapatan</p>
                            <h3 class="text-2xl font-bold text-emerald-600">Rp <?= number_format($rekap['penjualan'] ?? 0) ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">💰</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Keuntungan</p>
                            <h3 class="text-2xl font-bold text-emerald-600">Rp <?= number_format($rekap['laba'] ?? 0) ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">📈</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- REKAP BULANAN -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-emerald-500 rounded-full"></span>
                        Rekap Bulanan
                    </h4>
                    <ul class="space-y-2 text-gray-600">
                        <li>Total Penjualan: <span class="font-semibold text-emerald-600">Rp <?= number_format($rekap['penjualan'] ?? 0) ?></span></li>
                        <li>Total Pembelian: <span class="font-semibold text-red-500">Rp <?= number_format($rekap['pembelian'] ?? 0) ?></span></li>
                        <li>Laba Bersih: <span class="font-semibold text-emerald-600">Rp <?= number_format($rekap['laba'] ?? 0) ?></span></li>
                    </ul>
                </div>
                
                <!-- TOTAL OMZET -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg text-white">
                    <h4 class="font-bold mb-2">Total Omzet</h4>
                    <h2 class="text-4xl font-bold">Rp <?= number_format((float) ($omzet->total ?? 0), 0, ',', '.') ?></h2>
                </div>
            </div>

            <!-- PENJUALAN TABLE -->
            <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h4 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-1 h-6 bg-emerald-500 rounded-full"></span>
                        Penjualan
                    </h4>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(!empty($penjualan)): ?>
                            <?php foreach ($penjualan as $p): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-600"><?= date('d-m-Y', strtotime($p->tanggal)) ?></td>
                                <td class="px-6 py-3 font-semibold text-emerald-600">Rp <?= number_format($p->total ?? 0, 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PEMBELIAN TABLE -->
            <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h4 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-1 h-6 bg-emerald-500 rounded-full"></span>
                        Pembelian
                    </h4>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(!empty($pembelian)): ?>
                            <?php foreach($pembelian as $p): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-600"><?= $p->tanggal ?></td>
                                <td class="px-6 py-3 font-semibold text-red-500">Rp <?= number_format($p->total_harga ?? 0) ?></td>
                            </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- OBAT TERLARIS -->
            <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h4 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-1 h-6 bg-emerald-500 rounded-full"></span>
                        Obat Terlaris
                    </h4>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nama Obat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Terjual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(!empty($terlaris)): ?>
                            <?php foreach($terlaris as $o): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-gray-800"><?= $o->nama_obat ?></td>
                                <td class="px-6 py-3 text-gray-600 font-bold"><?= $o->total_terjual ?? 0 ?> Item</td>
                            </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- EXPORT BUTTON -->
            <div class="bg-white rounded-2xl p-6 shadow-sm mb-8">
                <h5 class="font-bold text-gray-800 mb-4">📥 Export Laporan</h5>
                <button onclick="document.getElementById('exportModal').classList.remove('hidden')" class="flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-cyan-600 text-white px-8 py-3 rounded-xl hover:from-emerald-700 hover:to-cyan-700 transition-all shadow-lg font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Laporan
                </button>
            </div>

            <!-- Export Modal -->
            <div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-8 relative animate-fade-in">
                    <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">📥 Pilih Jenis Export</h3>
                    <p class="text-gray-500 mb-6">Pilih jenis laporan dan format file yang ingin Anda export</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <!-- Pembelian -->
                        <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-emerald-500 transition-all cursor-pointer" onclick="selectExportType('pembelian', this)">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="text-2xl">📊</span>
                                </div>
                                <h4 class="font-bold text-gray-800 mb-1">Pembelian</h4>
                                <p class="text-xs text-gray-500">Data pembelian obat</p>
                            </div>
                        </div>
                        
                        <!-- Penjualan -->
                        <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-cyan-500 transition-all cursor-pointer" onclick="selectExportType('penjualan', this)">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="text-2xl">💰</span>
                                </div>
                                <h4 class="font-bold text-gray-800 mb-1">Penjualan</h4>
                                <p class="text-xs text-gray-500">Data penjualan obat</p>
                            </div>
                        </div>
                        
                        <!-- Keduanya -->
                        <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-purple-500 transition-all cursor-pointer" onclick="selectExportType('keduanya', this)">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span class="text-2xl">📑</span>
                                </div>
                                <h4 class="font-bold text-gray-800 mb-1">Keduanya</h4>
                                <p class="text-xs text-gray-500">Pembelian & Penjualan</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t pt-6">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Pilih Format:</p>
                        <div class="flex gap-3">
                            <button id="btnExcel" onclick="doExport('excel')" class="flex-1 flex items-center justify-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"></path><path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path></svg>
                                Excel (.xls)
                            </button>
                            <button id="btnPdf" onclick="doExport('pdf')" class="flex-1 flex items-center justify-center gap-2 bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                                PDF (.pdf)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            let selectedType = null;
            
            function selectExportType(type, element) {
                selectedType = type;
                
                // Reset all borders
                document.querySelectorAll('#exportModal .border-2').forEach(el => {
                    el.classList.remove('border-emerald-500', 'border-cyan-500', 'border-purple-500', 'bg-gray-50');
                    el.classList.add('border-gray-200');
                });
                
                // Highlight selected
                element.classList.remove('border-gray-200');
                element.classList.add('bg-gray-50');
                if(type === 'pembelian') element.classList.add('border-emerald-500');
                else if(type === 'penjualan') element.classList.add('border-cyan-500');
                else element.classList.add('border-purple-500');
                
                // Enable buttons
                document.getElementById('btnExcel').disabled = false;
                document.getElementById('btnPdf').disabled = false;
            }
            
            function doExport(format) {
                if(!selectedType) {
                    alert('Silakan pilih jenis laporan terlebih dahulu');
                    return;
                }
                
                const baseUrl = '<?= site_url("karyawan/laporan/") ?>';
                
                if(selectedType === 'keduanya') {
                    // Use ZIP export implementation
                    const endpoint = format === 'excel' ? 'export_combined/excel' : 'export_combined/pdf';
                    window.open(baseUrl + endpoint, '_blank');
                } else if(selectedType === 'pembelian') {
                    window.open(baseUrl + (format === 'excel' ? 'export_excel' : 'export_pdf'), '_blank');
                } else {
                    window.open(baseUrl + (format === 'excel' ? 'export_penjualan_excel' : 'export_penjualan_pdf'), '_blank');
                }
                
                // Close modal
                document.getElementById('exportModal').classList.add('hidden');
                selectedType = null;
                
                // Reset selection
                document.querySelectorAll('#exportModal .border-2').forEach(el => {
                    el.classList.remove('border-emerald-500', 'border-cyan-500', 'border-purple-500', 'bg-gray-50');
                    el.classList.add('border-gray-200');
                });
                document.getElementById('btnExcel').disabled = true;
                document.getElementById('btnPdf').disabled = true;
            }
            </script>

            <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }
            .animate-fade-in {
                animation: fadeIn 0.2s ease-out;
            }
            </style>

            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>
