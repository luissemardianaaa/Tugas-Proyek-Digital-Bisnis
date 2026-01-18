<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#16a34a',
                'primary-dark': '#15803d',
                'primary-light': '#22c55e',
            }
        }
    }
}
</script>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>* { font-family: 'Inter', sans-serif; }</style>

<?php 
// Determine active page for sidebar highlighting
$current_page = $this->uri->segment(1);
?>

<div class="flex min-h-screen bg-gray-100">
    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-lg flex flex-col fixed h-full z-50">
        <!-- Logo Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                    <span class="text-white text-lg">💊</span>
                </div>
                <div>
                    <h1 class="font-bold text-gray-800 text-lg">APOTEK</h1>
                    <p class="text-xs text-gray-500">Friendly Pharmacy</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-3 px-3">Menu Utama</p>
            
            <?php if($this->session->userdata('role') == 'admin'): ?>
            <a href="<?= site_url('admin/dashboard') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'admin' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-medium">Admin Panel</span>
            </a>
            <?php endif; ?>

            <a href="<?= site_url('karyawan/dashboard') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'dashboard' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Dashboard Toko</span>
            </a>
            
            <a href="<?= site_url('karyawan/obat') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'obat' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                <span class="font-medium">Data Obat</span>
            </a>
            
            <a href="<?= site_url('karyawan/kategori') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'kategori' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                <span class="font-medium">Kategori</span>
            </a>
            
            <a href="<?= site_url('karyawan/pemasok') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'pemasok' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <span class="font-medium">Pemasok</span>
            </a>
            
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-3 px-3 mt-6">Transaksi</p>
            
            <a href="<?= site_url('karyawan/penjualan') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'penjualan' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="font-medium flex-1">Penjualan</span>
                <?php 
                    // Simple count for notification (In a larger app, this should be in a Service/Provider)
                    // We access the global CI instance to load model if not loaded
                    $ci =& get_instance();
                    $ci->load->model('Penjualan_model');
                    // We need a count method or fetch all and count. Fetch all is okay for now.
                    // Optimally: $ci->Penjualan_model->count_pending();
                    // Using raw query for speed and avoiding model change recursion if model not ready
                    $pending_count_sidebar = $ci->db
                        ->where_in('status', ['menunggu_konfirmasi', 'dikemas'])
                        ->count_all_results('penjualan');
                ?>
                <?php if($pending_count_sidebar > 0): ?>
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        <?= $pending_count_sidebar ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="<?= site_url('karyawan/pembelian') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'pembelian' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="font-medium">Pembelian</span>
            </a>
            
            <a href="<?= site_url('karyawan/stok') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'stok' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span class="font-medium">Kelola Stok</span>
            </a>

            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-3 px-3 mt-6">Komunikasi</p>

            <a href="<?= site_url('karyawan/pesan') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'pesan' || $this->uri->segment(2) == 'pesan' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <i class="bi bi-chat-dots text-lg"></i>
                <span class="font-medium flex-1">Pesan Pelanggan</span>
                <?php 
                    $ci =& get_instance();
                    $ci->load->model('Konsultasi_model');
                    $unread_chat = $ci->Konsultasi_model->count_unread();
                ?>
                <?php if($unread_chat > 0): ?>
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        <?= $unread_chat ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-3 px-3 mt-6">Laporan</p>
            
            <a href="<?= site_url('karyawan/laporan') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $current_page == 'laporan' ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Laporan</span>
            </a>

            <?php if ($this->session->userdata('role') == 'admin') : ?>
                <a href="<?= site_url('admin/absensi') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $this->uri->segment(2) == 'absensi' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">Absensi Staf</span>
                </a>
            <?php endif; ?>
        </nav>
        
        <!-- User Section -->
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold"><?= strtoupper(substr($this->session->userdata('nama') ?? 'A', 0, 1)) ?></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate"><?= $this->session->userdata('nama') ?? 'Admin' ?></p>
                    <p class="text-xs text-gray-500 capitalize"><?= $this->session->userdata('role') ?? 'User' ?></p>
                </div>
                <a href="<?= site_url('auth/logout') ?>" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="flex-1 ml-64">
