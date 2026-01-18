<?php $this->load->view('templates/sidebar'); ?>
<div class="p-8">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="<?= site_url('karyawan/penjualan') ?>" class="w-10 h-10 bg-white shadow-sm rounded-xl flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:shadow-md transition-all">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan #<?= $penjualan->id_penjualan ?></h1>
                <p class="text-gray-500 text-sm mt-1">
                    Tanggal: <?= date('d M Y H:i', strtotime($penjualan->created_at)) ?>
                </p>
            </div>
        </div>
        
        <!-- STATUS BADGE -->
        <?php 
            $status_class = [
                'menunggu_pembayaran' => 'bg-gray-100 text-gray-600',
                'menunggu_konfirmasi' => 'bg-yellow-100 text-yellow-700',
                'dikemas' => 'bg-blue-100 text-blue-700',
                'dikirim' => 'bg-purple-100 text-purple-700',
                'selesai' => 'bg-emerald-100 text-emerald-700',
                'dibatalkan' => 'bg-red-100 text-red-700'
            ];
            $bg = $status_class[$penjualan->status] ?? 'bg-gray-100 text-gray-600';
            $label = ucfirst(str_replace('_', ' ', $penjualan->status));
        ?>
        <div class="px-4 py-2 rounded-xl font-bold <?= $bg ?> text-sm">
            <?= $label ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN: ITEMS -->
        <div class="lg:col-span-2 space-y-6">
            <!-- ITEMS CARD -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden p-6">
                <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-cart text-emerald-600"></i> Daftar Produk
                </h2>
                <div class="space-y-4">
                    <?php foreach($items as $i): ?>
                    <div class="flex items-center gap-4 border-b border-gray-50 pb-4 last:border-0 last:pb-0">
                        <div class="w-16 h-16 bg-gray-50 rounded-lg flex items-center justify-center text-gray-300">
                             <!-- Placeholder Image or Real Image -->
                             <i class="bi bi-image text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-800"><?= $i->nama_obat ?></h3>
                            <p class="text-xs text-gray-500"><?= $i->jumlah ?> x Rp<?= number_format($i->harga,0,',','.') ?></p>
                        </div>
                        <span class="font-bold text-gray-800">Rp<?= number_format($i->harga * $i->jumlah,0,',','.') ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- PAYMENT INFO -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden p-6">
                <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-credit-card text-emerald-600"></i> Info Pembayaran
                </h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Metode Pembayaran</p>
                        <p class="font-semibold text-gray-800"><?= $penjualan->metode_pembayaran ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Total Tagihan</p>
                        <p class="font-bold text-emerald-600">Rp<?= number_format($penjualan->total_harga,0,',','.') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ACTION & ADDRESS -->
        <div class="space-y-6">
            
            <!-- ACTION CARD -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden p-6">
                <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-gear text-emerald-600"></i> Aksi Pesanan
                </h2>
                
                <div class="space-y-3">
                    <?php if($penjualan->status == 'menunggu_konfirmasi'): ?>
                        <form action="<?= site_url('karyawan/penjualan/update_status/'.$penjualan->id_penjualan) ?>" method="POST">
                            <input type="hidden" name="status" value="dikemas">
                            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-200">
                                <i class="bi bi-box-seam"></i> Terima & Kemas
                            </button>
                        </form>
                    <?php elseif($penjualan->status == 'dikemas'): ?>
                        <form action="<?= site_url('karyawan/penjualan/update_status/'.$penjualan->id_penjualan) ?>" method="POST">
                            <input type="hidden" name="status" value="dikirim">
                            <button type="submit" class="w-full bg-purple-600 text-white font-semibold py-3 rounded-xl hover:bg-purple-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-purple-200">
                                <i class="bi bi-truck"></i> Kirim Pesanan
                            </button>
                        </form>
                    <?php elseif($penjualan->status == 'dikirim'): ?>
                        <form action="<?= site_url('karyawan/penjualan/update_status/'.$penjualan->id_penjualan) ?>" method="POST">
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit" onclick="return confirm('Apakah pesanan sudah diterima pelanggan?')" class="w-full bg-emerald-600 text-white font-semibold py-3 rounded-xl hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-200">
                                <i class="bi bi-check-lg"></i> Tandai Selesai
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if(!in_array($penjualan->status, ['selesai', 'dibatalkan'])): ?>
                    <form action="<?= site_url('karyawan/penjualan/update_status/'.$penjualan->id_penjualan) ?>" method="POST" class="mt-4">
                        <input type="hidden" name="status" value="dibatalkan">
                        <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')" class="w-full border border-red-200 text-red-600 font-semibold py-3 rounded-xl hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-x-circle"></i> Batalkan Pesanan
                        </button>
                    </form>
                    <?php endif; ?>

                    <?php if($penjualan->status == 'selesai'): ?>
                        <div class="bg-emerald-50 text-emerald-700 p-3 rounded-lg text-center text-sm">
                            <i class="bi bi-check-circle-fill"></i> Pesanan Selesai
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ADDRESS CARD -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden p-6">
                <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-geo-alt text-emerald-600"></i> Tujuan Pengiriman
                </h2>
                <div class="text-sm">
                    <p class="font-semibold text-gray-800"><?= $penjualan->nama ?></p>
                    <p class="text-gray-500 mt-2 leading-relaxed">
                        <?= $penjualan->alamat_pengiriman ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $this->load->view('templates/footer'); ?>
