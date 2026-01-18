<?php $this->load->view('templates/sidebar'); ?>

        <!-- Content Area -->
        <div class="p-8">
            <?php if ($this->session->flashdata('success')): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-emerald-700"><?= $this->session->flashdata('success') ?></span>
            </div>
            <?php endif; ?>

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">📋 Data Penjualan</h1>
                    <p class="text-gray-500 text-sm mt-1">Riwayat transaksi penjualan</p>
                </div>
                <a href="<?= site_url('karyawan/penjualan/create') ?>" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Transaksi Baru
                </a>
            </div>

            <!-- NOTIFICATION BANNER -->
            <?php 
                $pending_count = 0;
                if(!empty($penjualan)) {
                    foreach($penjualan as $p) {
                        if($p->status == 'menunggu_konfirmasi' || $p->status == 'dikemas') $pending_count++;
                    }
                }
            ?>
            <?php if($pending_count > 0): ?>
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl flex items-center justify-between animate-pulse">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                    </span>
                    <span class="text-yellow-800 font-medium">Ada <?= $pending_count ?> pesanan perlu diproses!</span>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="text-yellow-600 hover:text-yellow-800"><i class="bi bi-x-lg"></i></button>
            </div>
            <?php endif; ?>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-emerald-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Pelanggan/Karyawan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Metode</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Total</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($penjualan)): ?>
                            <?php $no=1; foreach($penjualan as $p): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-600"><?= $no++ ?></td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-800"><?= date('d/m/Y', strtotime($p->created_at)) ?></span>
                                        <span class="text-xs text-gray-500"><?= date('H:i', strtotime($p->created_at)) ?> WIB</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-800"><?= isset($p->nama) ? $p->nama : 'Umum' ?></span>
                                        <?php if($p->id_karyawan > 1): // Asumsi ID 1 admin/dummy ?>
                                            <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full w-fit">Online</span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Offline</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                        $status_class = [
                                            'menunggu_pembayaran' => 'bg-gray-100 text-gray-600',
                                            'menunggu_konfirmasi' => 'bg-yellow-100 text-yellow-700',
                                            'dikemas' => 'bg-blue-100 text-blue-700',
                                            'dikirim' => 'bg-purple-100 text-purple-700',
                                            'selesai' => 'bg-emerald-100 text-emerald-700',
                                            'dibatalkan' => 'bg-red-100 text-red-700'
                                        ];
                                        $bg = $status_class[$p->status] ?? 'bg-gray-100 text-gray-600';
                                        $label = ucfirst(str_replace('_', ' ', $p->status));
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $bg ?>">
                                        <?= $label ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm"><?= $p->metode_pembayaran ?? '-' ?></td>
                                <td class="px-6 py-4 font-bold text-gray-800">Rp <?= number_format($p->total_harga) ?></td>
                                <td class="px-6 py-4">
                                    <?php if(in_array($p->status, ['menunggu_konfirmasi', 'dikemas', 'dikirim'])): ?>
                                        <a href="<?= site_url('karyawan/penjualan/detail/' . $p->id_penjualan) ?>" class="flex items-center gap-1 bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-emerald-200 transition-colors">
                                            <i class="bi bi-pencil-square"></i> Proses
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= site_url('karyawan/penjualan/detail/' . $p->id_penjualan) ?>" class="flex items-center gap-1 bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    Belum ada data penjualan
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>
