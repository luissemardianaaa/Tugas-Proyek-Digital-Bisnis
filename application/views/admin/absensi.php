<?php $this->load->view('templates/sidebar'); ?>

<!-- Content Area -->
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Absensi Staf</h1>
            <p class="text-gray-500 text-sm mt-1">Input manual jam kerja dan konfirmasi kehadiran karyawan.</p>
        </div>
        <button onclick="document.getElementById('modal-absensi').classList.remove('hidden')" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Rekap Manual
        </button>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="mb-6 px-4 py-3 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium animate-pulse">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- ATTENDANCE TABLE -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 bg-gray-50/30">
            <h3 class="font-bold text-gray-800">📋 Daftar Kehadiran Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-widest">Nama Staf</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-widest">Jam Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-widest">Jam Pulang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Kerja</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-widest">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(empty($jam_kerja)): ?>
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data absensi tercatat.</td></tr>
                    <?php else: ?>
                        <?php foreach($jam_kerja as $j): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-600"><?= date('d M Y', strtotime($j->tanggal)) ?></td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800"><?= $j->nama ?></div>
                                    <div class="text-xs text-indigo-500 font-semibold uppercase tracking-tighter">Staff</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold ring-1 ring-emerald-200"><?= substr($j->jam_masuk, 0, 5) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($j->jam_pulang): ?>
                                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold ring-1 ring-amber-200"><?= substr($j->jam_pulang, 0, 5) ?></span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic">Belum pulang</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($j->total_jam): ?>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                            <span class="text-sm font-bold text-gray-700"><?= number_format($j->total_jam, 2) ?> <span class="text-[10px] font-normal text-gray-400">jam</span></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-300">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-[150px] truncate"><?= $j->keterangan ?></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick='editAbsensi(<?= json_encode($j) ?>)' class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors" title="Edit Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <a href="<?= site_url('admin/hapus_absensi/'.$j->id_jam) ?>" onclick="return confirm('Hapus data ini?')" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors" title="Hapus Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </a>
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

<!-- MODAL FORM ABSENSI -->
<div id="modal-absensi" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-indigo-50/50">
            <h3 id="modal-title" class="text-xl font-bold text-gray-800">Tambah Rekap Absensi</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path></svg>
            </button>
        </div>
        <form action="<?= site_url('admin/update_absensi') ?>" method="POST" class="p-8 space-y-5">
            <input type="hidden" name="id_jam" id="id_jam">
            
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-600 px-1">Pilih Karyawan</label>
                <select name="id_user" id="id_user" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                    <?php foreach($karyawan as $k): ?>
                        <option value="<?= $k->id_user ?>"><?= $k->nama ?> (@<?= $k->username ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-gray-600 px-1">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-gray-600 px-1">Keterangan Shift</label>
                    <input type="text" name="keterangan" id="keterangan" placeholder="Pagi / Siang / Malam" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-emerald-600 px-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Jam Masuk
                    </label>
                    <input type="time" name="jam_masuk" id="jam_masuk" required class="w-full px-4 py-3 bg-emerald-50/50 border border-emerald-100 rounded-xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 outline-none transition-all text-emerald-700 font-bold">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-amber-600 px-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Jam Pulang
                    </label>
                    <input type="time" name="jam_pulang" id="jam_pulang" class="w-full px-4 py-3 bg-amber-50/50 border border-amber-100 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-400 outline-none transition-all text-amber-700 font-bold">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-500 rounded-2xl font-bold hover:bg-gray-200 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">Simpan Konfirmasi</button>
            </div>
            <p class="text-[10px] text-center text-gray-400 italic mt-2">Total jam kerja akan dihitung secara otomatis oleh sistem.</p>
        </form>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

<script>
function editAbsensi(data) {
    document.getElementById('modal-title').innerText = 'Edit Rekap Absensi';
    document.getElementById('id_jam').value = data.id_jam;
    document.getElementById('id_user').value = data.id_user;
    document.getElementById('tanggal').value = data.tanggal;
    document.getElementById('jam_masuk').value = data.jam_masuk;
    document.getElementById('jam_pulang').value = data.jam_pulang || '';
    document.getElementById('keterangan').value = data.keterangan || '';
    document.getElementById('modal-absensi').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal-absensi').classList.add('hidden');
    document.getElementById('modal-title').innerText = 'Tambah Rekap Absensi';
    document.getElementById('id_jam').value = '';
}
</script>
