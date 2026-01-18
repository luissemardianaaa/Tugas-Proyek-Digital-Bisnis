<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesan Pelanggan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#ecfdf5', 100:'#d1fae5', 500:'#10b981', 600:'#059669', 700:'#047857' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <div class="max-w-5xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kotak Masuk Pesan</h1>
                <p class="text-sm text-gray-500">Kelola keluhan dan pertanyaan pelanggan</p>
            </div>
            <?php 
                $dashboard_url = ($this->uri->segment(1) == 'karyawan') ? 'karyawan/dashboard' : 'admin/dashboard';
            ?>
            <a href="<?= site_url($dashboard_url) ?>" class="text-gray-600 hover:text-primary-600 font-medium text-sm flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- List Ticket -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Pengirim</th>
                        <th class="px-6 py-4">Subjek</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(empty($tickets)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada pesan dari pelanggan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($tickets as $t): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-gray-400">#<?= $t->id_konsultasi ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($t->nama_pengirim ?? 'Pelanggan') ?></div>
                                <div class="text-xs text-gray-400"><?= htmlspecialchars($t->email ?? '-') ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                <?= htmlspecialchars($t->subjek) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($t->status == 'open'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Open
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                        Closed
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">
                                <?= date('d M Y, H:i', strtotime($t->created_at)) ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php 
                                    $role = $this->uri->segment(1); // 'admin' or 'karyawan'
                                    // if segment 1 is neither (e.g. direct access), default to role session or admin
                                    if($role != 'admin' && $role != 'karyawan') $role = 'admin';
                                    
                                    $detail_url = ($role == 'karyawan') ? 'karyawan/pesan/detail/'.$t->id_konsultasi : 'admin/detail_pesan/'.$t->id_konsultasi;
                                ?>
                                <a href="<?= site_url($detail_url) ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition">
                                    <i class="bi bi-chat-text-fill"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
