<?php $this->load->view('templates/sidebar'); ?>

<div class="p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="text-3xl">🛡️</span>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard Administrator</h1>
            </div>
            <p class="text-gray-500 font-medium">Pantau aktivitas apotek, verifikasi staf, dan kelola operasional harian.</p>
        </div>
        <div class="flex flex-col items-end gap-2">
             <div class="text-right hidden md:block">
                <p class="text-[11px] uppercase tracking-widest text-gray-400 font-bold mb-1">System Date</p>
                <div class="px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-700 font-mono font-bold flex items-center gap-2">
                    <span>📅</span> <?= isset($server_date) ? date('d M Y', strtotime($server_date)) : date('d M Y') ?>
                </div>
            </div>
            
            <!-- Toggle Button for Alert -->
            <?php if(!empty($peringatan_absensi)): ?>
                <button id="btn-show-alert" onclick="toggleSecurityAlert()" class="hidden group bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 px-4 py-2.5 rounded-xl transition-all flex items-center gap-2 shadow-sm hover:shadow-md">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                    </span>
                    <span class="text-sm font-bold">Peringatan Keamanan (<?= count($peringatan_absensi) ?>)</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Card 1 -->
        <div class="group bg-white p-6 rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 hover:border-blue-200 transition-all hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform scale-150 text-6xl select-none">📦</div>
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Total Produk</p>
                    <h3 class="text-3xl font-black text-gray-800"><?= number_format($total_obat) ?></h3>
                    <p class="text-xs text-blue-500 font-medium mt-2 bg-blue-50 px-2 py-1 rounded-lg w-fit">Item Obat Terdaftar</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    📦
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="group bg-white p-6 rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 hover:border-emerald-200 transition-all hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform scale-150 text-6xl select-none">💰</div>
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Penjualan Bulan Ini</p>
                    <h3 class="text-3xl font-black text-gray-800"><?= number_format($total_penjualan) ?></h3>
                    <p class="text-xs text-emerald-500 font-medium mt-2 bg-emerald-50 px-2 py-1 rounded-lg w-fit">Transaksi Sukses</p>
                </div>
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    💰
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="group bg-white p-6 rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 hover:border-purple-200 transition-all hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform scale-150 text-6xl select-none">👥</div>
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Total Staf</p>
                    <h3 class="text-3xl font-black text-gray-800"><?= number_format($total_karyawan) ?></h3>
                    <p class="text-xs text-purple-500 font-medium mt-2 bg-purple-50 px-2 py-1 rounded-lg w-fit">Karyawan Aktif</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    👥
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    <div class="space-y-6 mb-10">
        <!-- Flash Messages -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-4 shadow-sm animate-fade-in-down">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xl shrink-0">✅</div>
                <div>
                    <h4 class="font-bold text-emerald-800 text-sm">Berhasil!</h4>
                    <p class="text-emerald-700 text-sm"><?= $this->session->flashdata('success') ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Security Alert -->
        <?php if(!empty($peringatan_absensi)): ?>
            <div id="security-alert" class="bg-gradient-to-r from-rose-500 to-red-600 rounded-2xl shadow-lg shadow-rose-200 overflow-hidden text-white relative">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-9xl transform translate-x-10 -translate-y-10 rotate-12 select-none">🚨</div>
                
                <div class="p-6 relative z-10">
                    <div class="flex items-start justify-between">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-2xl shadow-inner">⚠️</div>
                            <div>
                                <h3 class="text-lg font-bold">Peringatan Keamanan: Akses Luar Jam Kerja</h3>
                                <p class="text-rose-100 text-sm mt-1 max-w-2xl">Sistem mendeteksi aktivitas login karyawan di luar jadwal shift resmi mereka. Harap tinjau daftar di bawah ini.</p>
                            </div>
                        </div>
                        <button onclick="toggleSecurityAlert()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all border border-white/10 backdrop-blur-sm flex items-center gap-2">
                            <span>Sembunyikan</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path></svg>
                        </button>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach($peringatan_absensi as $p): ?>
                            <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-xl p-3 flex items-center justify-between hover:bg-white/20 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white text-rose-600 flex items-center justify-center font-bold text-sm shadow-sm">
                                        <?= strtoupper(substr($p['nama'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm truncate w-32"><?= $p['nama'] ?></p>
                                        <p class="text-[10px] text-rose-100 bg-rose-800/30 px-1.5 py-0.5 rounded inline-block">Shift: <?= $p['shift'] ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] opacity-70 uppercase font-bold">Login</p>
                                    <p class="font-mono font-bold text-sm bg-white/20 px-2 py-0.5 rounded"><?= substr($p['jam_masuk'], 0, 5) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        <!-- SECTION: VERIFIKASI KARYAWAN -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-xl shadow-sm">⏳</div>
                    <div>
                        <h3 class="font-bold text-gray-800">Verifikasi Karyawan</h3>
                        <p class="text-xs text-gray-500">Aktivasi akun pendaftar baru</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold border border-amber-200">
                    <?= count($pending_karyawan) ?> Tertunda
                </span>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kandidat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Set Jadwal</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(empty($pending_karyawan)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-4xl opacity-50">✨</span>
                                        <p class="text-sm">Tidak ada permintaan verifikasi baru.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($pending_karyawan as $u): ?>
                                <tr class="hover:bg-amber-50/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-xs ring-2 ring-white shadow-sm group-hover:bg-amber-100 group-hover:text-amber-600 transition-colors">
                                                <?= strtoupper(substr($u->nama, 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800 text-sm"><?= $u->nama ?></div>
                                                <div class="text-[11px] text-gray-400"><?= $u->email ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-mono">@<?= $u->username ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form id="form-v-<?= $u->id_user ?>" action="<?= site_url('admin/aktivasi_karyawan') ?>" method="POST">
                                            <input type="hidden" name="id_user" value="<?= $u->id_user ?>">
                                            <div class="relative">
                                                <select name="shift" required class="w-40 px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                                    <option value="" disabled selected>Pilih Shift...</option>
                                                    <option value="pagi">☀️ Pagi (07:00)</option>
                                                    <option value="siang">🌤️ Siang (12:30)</option>
                                                    <option value="malam">🌙 Malam (17:00)</option>
                                                </select>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button form="form-v-<?= $u->id_user ?>" type="submit" class="bg-emerald-600 text-white hover:bg-emerald-700 px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-md shadow-emerald-100 active:scale-95 flex items-center gap-2 ml-auto">
                                            <span>✅</span> Aktifkan
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECTION: STAF AKTIF -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl shadow-sm">👥</div>
                    <div>
                        <h3 class="font-bold text-gray-800">Status Staf Aktif</h3>
                        <p class="text-xs text-gray-500">Monitoring kehadiran & jadwal</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold border border-blue-200">
                    <?= count($active_karyawan) ?> Total
                </span>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Staf</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jadwal & Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(empty($active_karyawan)): ?>
                            <tr><td colspan="3" class="px-6 py-12 text-center text-gray-400 opacity-60">Data karyawan kosong.</td></tr>
                        <?php else: ?>
                            <?php foreach($active_karyawan as $u): 
                                // --- DATA PROCESSING LOGIC (SAME AS BEFORE) ---
                                $jam_hari_ini = null;
                                foreach($today_attendance as $att) { if($att->id_user == $u->id_user) { $jam_hari_ini = $att; break; } }
                                if (!$jam_hari_ini && !empty($all_recent_attendance) && isset($business_date)) {
                                    foreach($all_recent_attendance as $att) {
                                        if($att->id_user == $u->id_user && $att->tanggal == $business_date) { $jam_hari_ini = $att; break; }
                                    }
                                }

                                // Shift & Badge Logic
                                $display_shift = 'Unassigned'; $display_time = '--:--'; $shift_icon = '⚪';
                                $badge_style = 'bg-gray-100 text-gray-500 border-gray-200';
                                $source_data = null; 
                                $is_today = !empty($u->id_jam);

                                if ($is_today) {
                                    $source_data = (object)[ 'keterangan' => $u->shift_harian, 'jam_masuk' => $u->jam_masuk_harian, 'jam_pulang' => $u->jam_pulang_harian ];
                                } elseif (!empty($all_recent_attendance)) {
                                    foreach($all_recent_attendance as $rec) { if($rec->id_user == $u->id_user) { $source_data = $rec; break; } }
                                }

                                if ($source_data) {
                                    $k = strtolower($source_data->keterangan);
                                    $display_shift = ucfirst($k);
                                    $s_time = substr($source_data->jam_masuk ?? '', 0, 5);
                                    $e_time = substr($source_data->jam_pulang ?? '', 0, 5);
                                    
                                    if(strpos($k, 'pagi')!==false) { $shift_icon='☀️'; $badge_style='bg-sky-50 text-sky-700 border-sky-100'; }
                                    elseif(strpos($k, 'siang')!==false) { $shift_icon='🌤️'; $badge_style='bg-amber-50 text-amber-700 border-amber-100'; }
                                    elseif(strpos($k, 'malam')!==false) { $shift_icon='🌙'; $badge_style='bg-indigo-50 text-indigo-700 border-indigo-100'; }
                                    
                                    // Fix Empty End Time
                                    if ($e_time == '' || $e_time == '??:??') {
                                        if(isset($shift_definitions[$k])) $e_time = substr($shift_definitions[$k]['end'], 0, 5);
                                    }
                                    $display_time = ($s_time ?: '??:??') . ' - ' . ($e_time ?: '??:??');
                                } elseif (!empty($u->shift)) {
                                    $k = strtolower($u->shift);
                                    $display_shift = ucfirst($k);
                                    if(isset($shift_definitions[$k])) $display_time = $shift_definitions[$k]['label'];
                                    
                                    if(strpos($k, 'pagi')!==false) { $shift_icon='☀️'; $badge_style='bg-sky-50 text-sky-600 border-sky-100 opacity-70'; }
                                    elseif(strpos($k, 'siang')!==false) { $shift_icon='🌤️'; $badge_style='bg-amber-50 text-amber-600 border-amber-100 opacity-70'; }
                                    elseif(strpos($k, 'malam')!==false) { $shift_icon='🌙'; $badge_style='bg-indigo-50 text-indigo-600 border-indigo-100 opacity-70'; }
                                }

                                // Online Status Logic
                                $is_online = false; $is_late_login = false;
                                if ($u->last_login) {
                                    $is_online = (time() - strtotime($u->last_login)) <= 300; 
                                }
                            ?>
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs">
                                                <?= strtoupper(substr($u->nama, 0, 1)) ?>
                                            </div>
                                            <?php if($is_online): ?>
                                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm"><?= $u->nama ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start gap-1">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border <?= $badge_style ?>">
                                            <span class="text-sm"><?= $shift_icon ?></span>
                                            <span class="text-xs font-bold"><?= $display_shift ?></span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-mono ml-1"><?= $display_time ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <button onclick="editShift('<?= $u->id_user ?>', '<?= $u->shift ?>')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-2 py-1 rounded transition-colors">
                                            ⚙️ Atur Shift
                                        </button>
                                        
                                        <?php if($jam_hari_ini && !$jam_hari_ini->jam_pulang): ?>
                                            <a href="<?= site_url('admin/pulang/'.$jam_hari_ini->id_jam) ?>" class="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-1 rounded hover:bg-amber-200 transition-colors border border-amber-200">
                                                Force Out
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Shift -->
<div id="modal-shift" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden scale-100 transform transition-all">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="text-xl">⚙️</span>
                Ubah Shift Permanen
            </h3>
            <button onclick="closeShiftModal()" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors text-lg font-bold">
                &times;
            </button>
        </div>
        
        <form action="<?= site_url('admin/assign_shift') ?>" method="POST" class="p-5">
            <input type="hidden" name="id_user" id="shift_id_user">
            
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-4">Pilih shift default baru:</p>
            
            <div class="space-y-3"> 
                <?php 
                // Emojis for the modal
                $m_icons = ['pagi' => '☀️', 'siang' => '🌤️', 'malam' => '🌙'];
                
                foreach($shift_definitions as $key => $def): 
                    $icon = $m_icons[$key] ?? '📅';
                ?>
                <label class="relative block group cursor-pointer">
                    <input type="radio" name="shift" value="<?= $key ?>" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-gray-100 bg-white hover:border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center text-xl peer-checked:bg-indigo-200 peer-checked:text-indigo-700">
                            <?= $icon ?>
                        </div>
                        <div>
                            <p class="font-bold text-gray-700 peer-checked:text-indigo-700 capitalize">Shift <?= ucfirst($key) ?></p>
                            <p class="text-xs text-gray-400 peer-checked:text-indigo-500 font-mono"><?= substr($def['start'],0,5) ?> - <?= substr($def['end'],0,5) ?> WIB</p>
                        </div>
                        <div class="absolute right-4 text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity">
                            ✅
                        </div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="w-full mt-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                💾 Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<script>
    function editShift(userId, currentShift) {
        document.getElementById('shift_id_user').value = userId;
        document.getElementById('modal-shift').classList.remove('hidden');
        
        // Reset and Select
        document.querySelectorAll('input[name="shift"]').forEach(r => r.checked = false);
        if(currentShift) {
            let val = '';
            if(currentShift.includes('pagi')) val = 'pagi';
            if(currentShift.includes('siang')) val = 'siang';
            if(currentShift.includes('malam')) val = 'malam';
            
            const radio = document.querySelector(`input[name="shift"][value="${val}"]`);
            if(radio) radio.checked = true;
        }
    }

    function closeShiftModal() {
        document.getElementById('modal-shift').classList.add('hidden');
    }

    function toggleSecurityAlert() {
        const alert = document.getElementById('security-alert');
        const btn = document.getElementById('btn-show-alert');
        
        if(alert.classList.contains('hidden')) {
            alert.classList.remove('hidden');
            btn.classList.add('hidden');
        } else {
            alert.classList.add('hidden');
            btn.classList.remove('hidden');
        }
    }
</script>

<?php $this->load->view('templates/footer'); ?>
