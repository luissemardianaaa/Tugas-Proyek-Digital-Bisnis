<?php $this->load->view('templates/sidebar'); ?>

        <!-- Content Area -->
        <div class="p-8">
            <!-- WARNING ALERT: OUTSIDE WORKING HOURS -->
            <?php if(isset($luar_jam_kerja) && $luar_jam_kerja): ?>
                <div class="mb-8 bg-rose-50 border border-rose-100 rounded-3xl p-6 shadow-xl shadow-rose-100/50 flex flex-col md:flex-row items-center gap-5 animate-bounce-subtle">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-rose-50">
                        ⚠️
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h4 class="text-rose-800 font-bold text-lg">Perhatian! Akses Luar Jam Kerja</h4>
                        <p class="text-rose-600/80 text-sm">Sistem mendeteksi Anda login di luar jadwal resmi. Pastikan aktivitas ini telah dilaporkan ke Admin.</p>
                    </div>
                    <div class="px-5 py-2 bg-rose-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-rose-200 uppercase tracking-widest whitespace-nowrap">
                        Mode Terbatas
                    </div>
                </div>
            <?php endif; ?>

            <!-- ERROR ALERT: Redirect from restricted pages -->
            <?php if($this->session->flashdata('error')): ?>
                <div class="mb-8 bg-red-50 border border-red-200 rounded-3xl p-6 shadow-xl shadow-red-100/50 flex flex-col md:flex-row items-center gap-5">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-red-50">
                        🚫
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h4 class="text-red-800 font-bold text-lg">Akses Ditolak</h4>
                        <p class="text-red-600/80 text-sm"><?= $this->session->flashdata('error') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- SUCCESS ALERT -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="mb-8 bg-emerald-50 border border-emerald-200 rounded-3xl p-6 shadow-xl shadow-emerald-100/50 flex flex-col md:flex-row items-center gap-5">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-emerald-50">
                        ✅
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h4 class="text-emerald-800 font-bold text-lg">Berhasil</h4>
                        <p class="text-emerald-600/80 text-sm"><?= $this->session->flashdata('success') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Toko</h1>
                    <p class="text-gray-500 text-sm mt-1">Sistem Informasi Apotek Friendly</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- LIVE CLOCK & DATE -->
                    <div class="flex flex-col md:flex-row items-center gap-3 bg-white px-5 py-2.5 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span id="live-clock" class="font-bold text-lg tracking-tight">00:00:00 <span class="text-[10px] font-normal text-gray-400">WIB</span></span>
                        </div>
                        <div class="hidden md:block w-px h-6 bg-gray-100"></div>
                        <div class="flex items-center gap-2 text-gray-500">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-medium"><?= date('d M Y') ?></span>
                        </div>
                    </div>

                    <!-- SHIFT BADGE -->
                    <?php if($shift_hari_ini && $shift_hari_ini->keterangan != 'Belum Diatur'): ?>
                        <div class="hidden lg:flex items-center gap-3 bg-indigo-50 px-5 py-2.5 rounded-2xl border border-indigo-100 shadow-sm shadow-indigo-100/50">
                            <div class="w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center text-sm shadow-md shadow-indigo-200">
                                ⏳
                            </div>
                            <div>
                                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">Shift Hari Ini</p>
                                <p class="text-sm font-bold text-indigo-700">
                                    <?= substr($shift_hari_ini->jam_masuk, 0, 5) ?> - <?= substr($shift_hari_ini->jam_pulang, 0, 5) ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SHIFT INFO -->
            <?php if($shift_hari_ini && $shift_hari_ini->keterangan != 'Belum Diatur'): ?>
                <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 shadow-xl text-white relative overflow-hidden">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl">
                                📅
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">Jadwal Tugas Hari Ini</h2>
                                <p class="text-blue-100 opacity-90"><?= ucfirst($shift_hari_ini->keterangan) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-center">
                                <p class="text-xs text-blue-100 uppercase tracking-wider mb-1">Jam Masuk</p>
                                <p class="text-2xl font-bold"><?= substr($shift_hari_ini->jam_masuk, 0, 5) ?></p>
                            </div>
                            <div class="w-px h-10 bg-white/20"></div>
                            <div class="text-center">
                                <p class="text-xs text-blue-100 uppercase tracking-wider mb-1">Jam Pulang</p>
                                <p class="text-2xl font-bold">
                                    <?= substr($shift_hari_ini->jam_pulang, 0, 5) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                </div>
            <?php endif; ?>

            <!-- MESSAGE ALERT: UNREAD MESSAGES -->
            <?php if(isset($unread_chat) && $unread_chat > 0): ?>
                <div class="mb-6 bg-blue-50 border border-blue-100 rounded-3xl p-6 shadow-xl shadow-blue-100/50 flex flex-col md:flex-row items-center gap-5">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-blue-50 text-blue-600">
                        📩
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h4 class="text-blue-900 font-bold text-lg">Pesan Baru dari Pelanggan</h4>
                        <p class="text-blue-700/80 text-sm">Ada <span class="font-bold"><?= $unread_chat ?></span> tiket konsultasi yang perlu ditanggapi oleh staf.</p>
                    </div>
                    <a href="<?= site_url('karyawan/pesan') ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
                        <i class="bi bi-reply-fill"></i> Balas Sekarang
                    </a>
                </div>
            <?php endif; ?>

            <!-- STATS CARDS ROW 1 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Total Obat</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?= $total_obat ?? 0 ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">💊</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Total Kategori</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?= $total_kategori ?? 0 ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-pink-100 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">📂</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Total Pemasok</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?= $total_pemasok ?? 0 ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">🚚</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Total Unit</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?= $total_unit ?? 0 ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">📦</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STATS CARDS ROW 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm mb-1">Total Penjualan (Bulan Ini)</p>
                            <h3 class="text-3xl font-bold text-white">Rp <?= number_format($total_penjualan ?? 0, 0, ",", ".") ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">💰</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-400 to-red-500 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm mb-1">Total Pembelian (Bulan Ini)</p>
                            <h3 class="text-3xl font-bold text-white">Rp <?= number_format($total_pembelian ?? 0, 0, ",", ".") ?></h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">🛒</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4">📊 Grafik Penjualan Per Hari</h4>
                    <canvas id="salesChartDaily" height="200"></canvas>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4">📈 Grafik Penjualan Per Bulan</h4>
                    <canvas id="salesChartMonthly" height="200"></canvas>
                </div>
            </div>
        </div>

<?php $this->load->view('templates/footer'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const hariLabels = <?= json_encode(array_column($grafik_harian ?? [], 'hari')) ?>;
const hariData = <?= json_encode(array_column($grafik_harian ?? [], 'total')) ?>;

new Chart(document.getElementById('salesChartDaily'), {
    type: 'line',
    data: {
        labels: hariLabels,
        datasets: [{
            label: 'Penjualan Harian',
            data: hariData,
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22, 163, 74, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});

const bulanLabels = <?= json_encode(array_column($grafik_bulanan ?? [], 'bulan')) ?>;
const bulanData = <?= json_encode(array_column($grafik_bulanan ?? [], 'total')) ?>;

new Chart(document.getElementById('salesChartMonthly'), {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Penjualan Bulanan',
            data: bulanData,
            backgroundColor: '#16a34a',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});

// Live Clock Script
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const timeString = `${hours}:${minutes}:${seconds} <span class="text-[10px] font-normal text-gray-400">WIB</span>`;
    document.getElementById('live-clock').innerHTML = timeString;
}
setInterval(updateClock, 1000);
updateClock(); // Initial call
</script>
