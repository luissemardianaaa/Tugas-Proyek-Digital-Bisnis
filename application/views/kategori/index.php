<?php $this->load->view('templates/sidebar'); ?>

        <!-- Content Area -->
        <div class="p-8">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Daftar Kategori</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola kategori/jenis obat</p>
                </div>
                <span class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full font-semibold text-sm">
                    Total: <?= $total_kategori ?? 0 ?> Kategori
                </span>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-emerald-700"><?= $this->session->flashdata('success') ?></span>
            </div>
            <?php endif; ?>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Nama Kategori</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Jumlah Obat</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($kategori)): ?>
                            <?php foreach ($kategori as $i => $k): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-600"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 font-medium text-gray-800"><?= htmlspecialchars($k->nama_kategori) ?></td>
                                    <td class="px-6 py-4">
                                        <?php if (isset($k->total_obat)): ?>
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
                                                <?= $k->total_obat ?> Obat
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600"><?= date('d M Y H:i', strtotime($k->created_at)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    Belum ada kategori
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>
