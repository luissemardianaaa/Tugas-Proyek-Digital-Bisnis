<?php $this->load->view('templates/sidebar'); ?>

        <!-- Content Area -->
        <div class="p-8">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">🚚 Transaksi Pembelian</h1>
                    <p class="text-gray-500 text-sm mt-1">Daftar transaksi pembelian obat dari pemasok</p>
                </div>
                <a href="<?= site_url('karyawan/pembelian/create') ?>" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pembelian
                </a>
            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h5 class="font-semibold text-gray-800">📋 Data Pembelian</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">No</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Pemasok</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Obat</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Jumlah</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">Harga Satuan</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">Total</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($pembelian)): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    Belum ada data pembelian
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($pembelian as $row): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-center text-gray-600"><?= $no++; ?></td>
                                    <td class="px-6 py-4 text-center text-gray-600"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                    <td class="px-6 py-4 text-gray-800"><?= $row->nama_pemasok ?? '-' ?></td>
                                    <td class="px-6 py-4 font-medium text-gray-800"><?= $row->nama_obat ?? '-' ?></td>
                                    <td class="px-6 py-4 text-center text-gray-600"><?= $row->jumlah ?></td>
                                    <td class="px-6 py-4 text-right text-gray-600">Rp <?= number_format($row->harga_satuan, 0, ',', '.'); ?></td>
                                    <td class="px-6 py-4 text-right font-semibold text-emerald-600">Rp <?= number_format($row->total_harga, 0, ',', '.'); ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?= !empty($row->keterangan) ? $row->keterangan : '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>
