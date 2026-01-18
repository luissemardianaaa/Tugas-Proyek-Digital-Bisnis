<?php $this->load->view('templates/sidebar'); ?>

        <!-- Content Area -->
        <div class="p-8">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">📦 Data Pemasok</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola data pemasok obat</p>
                </div>
                <a href="<?= site_url('karyawan/pemasok/create') ?>" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pemasok Baru
                </a>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-emerald-700"><?= $this->session->flashdata('success') ?></span>
            </div>
            <?php endif; ?>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pemasok</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alamat & Keterangan</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Input</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($pemasok)): ?>
                                <?php foreach ($pemasok as $p): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="bg-gray-100 text-gray-600 py-1 px-2 rounded font-mono text-xs"><?= $p->kode_pemasok ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900"><?= $p->nama_pemasok ?></div>
                                            <div class="text-xs text-gray-500">ID: <?= $p->id_pemasok ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600"><i class="bi bi-telephone mr-1"></i> <?= $p->telepon ?: '-' ?></div>
                                            <div class="text-sm text-gray-600"><i class="bi bi-envelope mr-1"></i> <?= $p->email ?: '-' ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate" title="<?= $p->alamat ?>"><?= $p->alamat ?: '-' ?></div>
                                            <?php if($p->keterangan): ?>
                                                <div class="text-xs text-gray-500 mt-1 italic"><?= $p->keterangan ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-xs text-gray-500">
                                                In: <?= date('d/m/y', strtotime($p->created_at)) ?><br>
                                                Up: <?= $p->updated_at ? date('d/m/y', strtotime($p->updated_at)) : '-' ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                            <a href="<?= site_url('karyawan/pemasok/edit/'.$p->id_pemasok) ?>" 
                                               class="inline-block px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-semibold hover:bg-amber-100 transition-colors">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="<?= site_url('karyawan/pemasok/delete/'.$p->id_pemasok) ?>" 
                                               onclick="return confirm('Yakin ingin menghapus pemasok ini?')"
                                               class="inline-block px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition-colors">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-inbox text-4xl mb-2 opacity-50"></i>
                                            <p>Belum ada data pemasok</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>
