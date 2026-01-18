<?php $this->load->view('templates/sidebar'); ?>

        <!-- Content Area -->
        <div class="p-8">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Data Obat</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola data obat dan rekomendasi AI apotek</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm text-gray-600"><?= date('d M Y') ?></span>
                    </div>
                    <?php if($this->session->userdata('role') == 'admin' || $this->session->userdata('role') == 'karyawan'): ?>
                    <a href="<?= site_url('karyawan/obat/create') ?>" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Obat
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($this->session->flashdata('error')) : ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-red-700"><?= $this->session->flashdata('error') ?></span>
            </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')) : ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-emerald-700"><?= $this->session->flashdata('success') ?></span>
            </div>
            <?php endif; ?>

            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Obat -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Total Obat</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?= number_format($total_obat ?? 0) ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                    </div>
                </div>
                
                <!-- Stok Kritis -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Stok Kritis</p>
                            <h3 class="text-3xl font-bold text-orange-500"><?= isset($obat_kritis) ? count($obat_kritis) : 0 ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                </div>
                
                <!-- Stok Habis -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Stok Habis</p>
                            <h3 class="text-3xl font-bold text-red-500"><?= isset($obat_habis) ? count($obat_habis) : 0 ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI SEARCH CARD -->
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 rounded-2xl p-6 mb-8 shadow-xl shadow-emerald-200">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <span class="text-3xl">🤖</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Konsultasi Obat dengan AI</h3>
                        <p class="text-emerald-100 text-sm mt-1">Ceritakan keluhan Anda, AI akan merekomendasikan obat yang sesuai dari stok apotek</p>
                    </div>
                </div>
                <form method="get" action="<?= site_url('karyawan/obat') ?>">
                    <textarea name="keluhan" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border-0 bg-white/95 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-white/30 resize-none"
                        placeholder="Contoh: saya mengalami demam tinggi disertai batuk dan pilek sejak 2 hari lalu..."><?= htmlspecialchars($this->input->get('keluhan') ?? '') ?></textarea>
                    <button type="submit" class="mt-3 w-full bg-white text-emerald-600 font-semibold py-3 px-6 rounded-xl hover:bg-emerald-50 transition-all flex items-center justify-center gap-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Dapatkan Rekomendasi AI
                    </button>
                </form>
            </div>

            <!-- AI RECOMMENDATION RESULTS -->
            <?php if (!empty($rekomendasi) && is_array($rekomendasi)): ?>
            <div class="bg-white rounded-2xl p-6 mb-8 shadow-sm border border-emerald-200">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <span class="text-xl">🤖</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Rekomendasi AI Apoteker</h4>
                    <p class="text-sm text-gray-500">Berdasarkan keluhan: "<?= htmlspecialchars(isset($rekomendasi['keluhan']) ? $rekomendasi['keluhan'] : $this->input->get('keluhan')) ?>"</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <?php if (isset($rekomendasi['rekomendasi']) && !empty($rekomendasi['rekomendasi'])): ?>
                        <?php foreach ($rekomendasi['rekomendasi'] as $idx => $rekom_obat): ?>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-emerald-200 transition-all">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="w-7 h-7 bg-emerald-600 text-white rounded-lg flex items-center justify-center text-sm font-bold"><?= ($idx + 1) ?></span>
                                            <h5 class="font-semibold text-gray-800"><?= htmlspecialchars(isset($rekom_obat['nama']) ? $rekom_obat['nama'] : '') ?></h5>
                                            <?php if (isset($rekom_obat['tersedia']) && $rekom_obat['tersedia']): ?>
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">✓ TERSEDIA</span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-full">✗ TIDAK TERSEDIA</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-gray-600 text-sm ml-10"><?= htmlspecialchars(isset($rekom_obat['kegunaan']) ? $rekom_obat['kegunaan'] : '') ?></p>
                                    </div>
                                    <?php if (isset($rekom_obat['tersedia']) && $rekom_obat['tersedia'] && isset($rekom_obat['data_db']) && !empty($rekom_obat['data_db'])): ?>
                                        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100 text-right min-w-[140px]">
                                            <p class="text-xs text-emerald-600 font-medium">Harga Apotek</p>
                                            <p class="text-lg font-bold text-emerald-700">Rp <?= number_format($rekom_obat['data_db']->harga, 0, ',', '.') ?></p>
                                            <p class="text-xs text-emerald-600">Stok: <?= $rekom_obat['data_db']->stok ?> <?= isset($rekom_obat['data_db']->satuan) ? $rekom_obat['data_db']->satuan : '' ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">Tidak ada rekomendasi yang tersedia.</p>
                    <?php endif; ?>
                </div>

                <?php if (isset($rekomendasi['tersedia_di_apotek']) && !empty($rekomendasi['tersedia_di_apotek'])): ?>
                    <div class="mt-4 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                        <p class="text-emerald-700 font-semibold">🏪 <?= count($rekomendasi['tersedia_di_apotek']) ?> obat tersedia di Apotek Kami!</p>
                        <p class="text-emerald-600 text-sm mt-1">Anda bisa langsung membeli obat yang bertanda hijau.</p>
                    </div>
                <?php endif; ?>

                <div class="mt-4 p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-amber-700 text-sm">⚠️ <strong>Disclaimer:</strong> Rekomendasi ini bersifat informatif. Konsultasikan dengan dokter/apoteker.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- OBAT LIST SECTION -->
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-800">
                        <?php if (!empty($this->input->get('keluhan'))): ?>
                            Obat Tersedia Sesuai Rekomendasi
                        <?php else: ?>
                            Daftar Obat
                        <?php endif; ?>
                    </h3>
                    <div class="flex items-center gap-3">
                        <!-- Search by Name -->
                        <form method="get" action="<?= site_url('karyawan/obat') ?>" class="flex items-center gap-2">
                            <input type="text" name="search" value="<?= htmlspecialchars($this->input->get('search') ?? '') ?>" 
                                placeholder="Cari nama obat..." 
                                class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent w-48">
                            <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </form>
                        <?php if ($this->input->get('search') || $this->input->get('keluhan')): ?>
                        <a href="<?= site_url('karyawan/obat') ?>" class="px-3 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-all text-sm">Reset</a>
                        <?php endif; ?>
                        <a href="<?= site_url('karyawan/stok') ?>" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Kelola Stok
                        </a>
                    </div>
                </div>

                <?php if (!empty($obat)): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <?php foreach ($obat as $o): ?>
                        <?php 
                            $stok = (int) $o->stok; 
                            $status = isset($o->status) ? $o->status : 'aktif';
                            $is_aktif = ($status === 'aktif');
                        ?>
                        <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg hover:border-emerald-200 transition-all group cursor-pointer <?= !$is_aktif ? 'opacity-60' : '' ?>">
                            <!-- Badge -->
                            <div class="relative">
                                <img src="<?= base_url('uploads/obat/' . $o->gambar) ?>" alt="<?= $o->nama_obat ?>" class="w-full h-32 object-cover group-hover:scale-105 transition-transform">
                                
                                <!-- Status Badge (Top Left) -->
                                <?php if (!$is_aktif): ?>
                                    <span class="absolute top-2 left-2 px-2 py-1 bg-gray-600 text-white text-xs font-semibold rounded-lg">Nonaktif</span>
                                <?php elseif ($stok === 0): ?>
                                    <span class="absolute top-2 left-2 px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-lg">Habis</span>
                                <?php elseif ($stok <= 10): ?>
                                    <span class="absolute top-2 left-2 px-2 py-1 bg-orange-500 text-white text-xs font-semibold rounded-lg">Hampir Habis</span>
                                <?php else: ?>
                                    <span class="absolute top-2 left-2 px-2 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-lg">Tersedia</span>
                                <?php endif; ?>
                            </div>
                            <div class="p-3">
                                <h6 class="font-semibold text-gray-800 text-sm truncate"><?= $o->nama_obat ?></h6>
                                <p class="text-emerald-600 font-bold text-base mt-1">Rp <?= number_format($o->harga, 0, ',', '.') ?></p>
                                <p class="text-gray-500 text-xs mt-1">Stok: <?= $stok ?> <?= $o->satuan ?></p>
                                
                                <?php if($this->session->userdata('role') == 'admin' || $this->session->userdata('role') == 'karyawan'): ?>
                                <div class="mt-3 flex flex-col gap-2">
                                    <!-- Row 1: Edit & Delete -->
                                    <div class="flex gap-2">
                                        <a href="<?= site_url('karyawan/obat/edit/' . $o->id_obat) ?>" 
                                           class="flex-1 bg-amber-400 text-white text-center py-1.5 rounded-lg text-sm hover:bg-amber-500 transition-colors font-medium shadow-sm hover:shadow-md">
                                            Edit
                                        </a>
                                        <a href="<?= site_url('karyawan/obat/delete/' . $o->id_obat) ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus obat <?= $o->nama_obat ?>?')" 
                                           class="flex-1 bg-red-500 text-white text-center py-1.5 rounded-lg text-sm hover:bg-red-600 transition-colors font-medium shadow-sm hover:shadow-md">
                                            Hapus
                                        </a>
                                    </div>
                                    <!-- Row 2: Toggle Status -->
                                    <a href="<?= site_url('karyawan/obat/toggle_status/' . $o->id_obat) ?>" 
                                       onclick="return confirm('<?= $is_aktif ? 'Nonaktifkan obat ini? Obat tidak akan ditampilkan kepada pelanggan.' : 'Aktifkan kembali obat ini?' ?>')"
                                       class="w-full <?= $is_aktif ? 'bg-gray-500 hover:bg-gray-600' : 'bg-emerald-500 hover:bg-emerald-600' ?> text-white text-center py-1.5 rounded-lg text-sm transition-colors font-medium shadow-sm hover:shadow-md flex items-center justify-center gap-1">
                                        <?php if ($is_aktif): ?>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                            Nonaktifkan
                                        <?php else: ?>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Aktifkan
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-gray-500 mb-2">Data obat tidak ditemukan</p>
                    <p class="text-gray-400 text-sm">Coba gunakan AI untuk mencari rekomendasi obat</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>
