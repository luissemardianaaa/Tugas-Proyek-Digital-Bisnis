<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Apotek Friendly</title>
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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>* { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-emerald-100 flex items-center justify-center p-4">

    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-10 right-10 w-72 h-72 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-10 left-10 w-72 h-72 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
    </div>

    <!-- Register Card -->
    <div class="relative z-10 w-full max-w-4xl">
        <div class="bg-white rounded-3xl shadow-2xl shadow-emerald-100 overflow-hidden flex flex-col md:flex-row">
            
            <!-- Left Side - Branding -->
            <div class="md:w-2/5 bg-gradient-to-br from-emerald-600 to-emerald-500 p-8 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-5xl">💊</span>
                </div>
                <h1 class="text-3xl font-bold text-white mb-3">APOTEK FRIENDLY</h1>
                <p class="text-emerald-100 text-sm">Sistem Informasi Apotek Terpercaya</p>
                <div class="mt-8 space-y-3 text-left">
                    <div class="flex items-center gap-3 text-white/80 text-sm">
                        <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Kelola stok dengan mudah
                    </div>
                    <div class="flex items-center gap-3 text-white/80 text-sm">
                        <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        AI Rekomendasi Obat
                    </div>
                    <div class="flex items-center gap-3 text-white/80 text-sm">
                        <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Laporan terintegrasi
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="md:w-3/5 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
                <p class="text-gray-500 mb-6">Daftar untuk mulai menggunakan sistem</p>

                <!-- Error Messages -->
                <?php if(validation_errors()): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-red-700 text-sm"><?= validation_errors('<div>', '</div>'); ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($this->session->flashdata('success')): ?>
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-emerald-700 text-sm"><?= $this->session->flashdata('success'); ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('auth/register_action') ?>" method="POST" class="space-y-4">
                    <!-- CSRF TOKEN -->
                    <input type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>">

                    <!-- Pilih Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Sebagai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <select name="role" id="role-select" required onchange="toggleFields()" class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
                                <option value="pelanggan">Pelanggan (Konsumen)</option>
                                <option value="karyawan">Karyawan (Staf Apotek)</option>
                                <option value="admin">Admin (Pengelola)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div id="nama-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="nama" 
                                   placeholder="Masukkan nama lengkap" 
                                   value="<?= set_value('nama'); ?>" 
                                   required
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   placeholder="Masukkan email" 
                                   value="<?= set_value('email'); ?>" 
                                   required
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
                        </div>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="username" 
                                   placeholder="Masukkan username" 
                                   value="<?= set_value('username'); ?>" 
                                   required
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
                        </div>
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="text-gray-400 text-lg bi bi-telephone"></i>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <input type="text" 
                                   name="no_hp" 
                                   placeholder="Contoh: 08123456789" 
                                   value="<?= set_value('no_hp'); ?>" 
                                   required
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
                        </div>
                    </div>

                    <!-- Kota -->
                    <div id="kota-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota / Kabupaten</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <select name="kota" required class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
                                <option value="">-- Pilih Kota --</option>
                                <optgroup label="Jabodetabek (Promo)">
                                    <option value="Jakarta Pusat">Jakarta Pusat</option>
                                    <option value="Jakarta Selatan">Jakarta Selatan</option>
                                    <option value="Jakarta Barat">Jakarta Barat</option>
                                    <option value="Jakarta Timur">Jakarta Timur</option>
                                    <option value="Jakarta Utara">Jakarta Utara</option>
                                    <option value="Bogor">Bogor</option>
                                    <option value="Depok">Depok</option>
                                    <option value="Tangerang">Tangerang</option>
                                    <option value="Bekasi">Bekasi</option>
                                </optgroup>
                                <optgroup label="Pulau Jawa">
                                    <option value="Bandung">Bandung</option>
                                    <option value="Semarang">Semarang</option>
                                    <option value="Yogyakarta">Yogyakarta</option>
                                    <option value="Surabaya">Surabaya</option>
                                    <option value="Malang">Malang</option>
                                </optgroup>
                                <optgroup label="Luar Pulau Jawa">
                                    <option value="Medan">Medan</option>
                                    <option value="Palembang">Palembang</option>
                                    <option value="Makassar">Makassar</option>
                                    <option value="Denpasar">Denpasar</option>
                                    <option value="Lainnya">Lainnya</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div id="alamat-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                        <div class="relative">
                            <textarea name="alamat" rows="3" required placeholder="Jalan, RT/RW, Nomor Rumah..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white"><?= set_value('alamat'); ?></textarea>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   placeholder="Masukkan password" 
                                   required
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" 
                            class="w-full bg-emerald-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 mt-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-500">
                        Sudah punya akun? 
                        <a href="<?= site_url('auth/login') ?>" class="text-emerald-600 font-semibold hover:text-emerald-700 hover:underline transition-all">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-sm mt-6">
            &copy; <?= date('Y') ?> Apotek Friendly. All rights reserved.
        </p>
    </div>

    <script>
        function toggleFields() {
            const role = document.getElementById('role-select').value;
            const kotaContainer = document.getElementById('kota-container');
            const alamatContainer = document.getElementById('alamat-container');
            const kotaSelect = kotaContainer.querySelector('select');
            const alamatTextarea = alamatContainer.querySelector('textarea');
            const nameContainer = document.getElementById('nama-container');
            const nameInput = nameContainer.querySelector('input');

            // Toggle Nama (Hanya Admin yang tidak perlu Nama Lengkap)
            if (role === 'admin') {
                nameContainer.style.display = 'none';
                nameInput.required = false;
            } else {
                nameContainer.style.display = 'block';
                nameInput.required = true;
            }

            // Toggle Kota & Alamat (Admin & Karyawan tidak perlu)
            if (role === 'karyawan' || role === 'admin') {
                kotaContainer.style.display = 'none';
                alamatContainer.style.display = 'none';
                kotaSelect.required = false;
                alamatTextarea.required = false;
            } else {
                kotaContainer.style.display = 'block';
                alamatContainer.style.display = 'block';
                kotaSelect.required = true;
                alamatTextarea.required = true;
            }
        }
        // Run on load
        toggleFields();
    </script>
</body>
</html>
