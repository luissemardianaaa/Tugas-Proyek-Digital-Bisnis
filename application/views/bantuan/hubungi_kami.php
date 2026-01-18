<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami - Apotek Friendly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: { primary: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857' } }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased flex flex-col min-h-screen">
    <nav class="bg-white/90 backdrop-blur fixed w-full z-50 top-0 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2 font-bold text-xl cursor-copy" onclick="location.href='<?= base_url() ?>'">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white"><i class="bi bi-capsule"></i></div>
                <span>Apotek<span class="text-primary-600">Friendly</span></span>
            </div>
            <a href="<?= base_url() ?>" class="text-gray-500 hover:text-primary-600 text-sm font-medium"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </nav>

    <main class="flex-grow pt-28 pb-20 px-4">
        <!-- Toast Notification -->
        <div id="toast-success" class="fixed top-24 right-5 z-50 transform transition-all duration-500 translate-x-full opacity-0">
            <div class="bg-white border border-green-100 shadow-xl shadow-green-500/10 rounded-xl p-4 flex items-center gap-3 w-80">
                <div class="w-10 h-10 rounded-full bg-green-100 flex-shrink-0 flex items-center justify-center text-green-600">
                    <i class="bi bi-check-lg text-xl"></i>
                </div>
                <div>
                    <h6 class="text-sm font-bold text-gray-900">Pesan Terkirim!</h6>
                    <p class="text-xs text-gray-500">Terima kasih telah menggunakan layanan kami.</p>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row h-auto md:min-h-[600px] border border-gray-100">
            <!-- Contact Info / Sidebar -->
            <div class="bg-primary-600 p-8 md:p-12 text-white md:w-5/12 flex flex-col justify-between relative overflow-hidden">
                <div class="relative z-10 h-full flex flex-col">
                    <h2 class="text-3xl font-bold mb-6">Hubungi Kami</h2>
                    <p class="text-primary-100 mb-8 leading-relaxed">Punya pertanyaan, kritik, atau saran? Tim kami siap membantu Anda setiap hari.</p>
                    
                    <div class="space-y-6 mt-auto">
                        <div class="flex items-start gap-4">
                            <i class="bi bi-geo-alt-fill text-2xl text-primary-200"></i>
                            <div>
                                <h5 class="font-bold mb-1">Alamat</h5>
                                <p class="text-sm text-primary-100">Jl. Kesehatan No. 123, Jakarta Pusat</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="bi bi-envelope-fill text-2xl text-primary-200"></i>
                            <div>
                                <h5 class="font-bold mb-1">Email</h5>
                                <p class="text-sm text-primary-100">bantuan@apotekfriendly.com</p>
                            </div>
                        </div>
                         <div class="flex items-center gap-4">
                            <i class="bi bi-telephone-fill text-2xl text-primary-200"></i>
                            <div>
                                <h5 class="font-bold mb-1">Telepon</h5>
                                <p class="text-sm text-primary-100">(021) 555-0123</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 rounded-full bg-primary-500 opacity-50"></div>
                <div class="absolute -top-24 -left-24 w-64 h-64 rounded-full bg-primary-500 opacity-50"></div>
            </div>

            <!-- Content Area: Form or Chat Room -->
            <div class="p-8 md:p-12 md:w-7/12 bg-white relative">
                
                <!-- Loading Overlay -->
                <div id="loading-overlay" class="absolute inset-0 bg-white/80 backdrop-blur z-20 hidden flex-col items-center justify-center">
                    <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mb-4"></div>
                    <p class="text-gray-500 font-medium animate-pulse">Mengirim pesan...</p>
                </div>

                <?php if(isset($active_ticket) && $active_ticket): ?>
                <!-- ACTIVE CHAT VIEW -->
                <div id="chat-room" class="h-full flex flex-col">
                     <div class="border-b border-gray-100 pb-4 mb-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Ruang Diskusi</h3>
                            <div class="flex items-center gap-2">
                                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Open</span>
                                <p class="text-xs text-gray-500">ID: #<?= $active_ticket->id_konsultasi ?></p>
                            </div>
                        </div>
                        <a href="<?= site_url('bantuan/close_chat/'.$active_ticket->id_konsultasi) ?>" onclick="return confirm('Apakah Anda yakin ingin mengakhiri sesi percakapan ini?')" class="text-xs text-red-500 font-medium hover:text-red-700 hover:underline">
                            <i class="bi bi-x-circle"></i> Akhiri Sesi
                        </a>
                    </div>

                    <!-- Messages -->
                    <div id="chat-history" class="flex-1 overflow-y-auto pr-2 space-y-4 max-h-[400px]">
                        <?php foreach($messages as $msg): ?>
                            <?php if($msg->pengirim == 'user'): ?>
                            <div class="flex flex-row-reverse items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="bg-primary-600 text-white p-3 rounded-2xl rounded-tr-none text-sm max-w-[85%] shadow-sm">
                                    <p><?= nl2br(htmlspecialchars($msg->pesan)) ?></p>
                                    <span class="text-[10px] text-primary-200 block mt-1 text-right"><?= date('H:i', strtotime($msg->created_at)) ?></span>
                                </div>
                            </div>
                            <?php else: // Admin or AI ?>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-sm font-bold text-primary-600">
                                    <?= ($msg->pengirim == 'ai') ? 'AI' : 'CS' ?>
                                </div>
                                <div class="<?= ($msg->pengirim == 'ai') ? 'bg-white border border-gray-100' : 'bg-green-50 border border-green-100' ?> text-gray-700 p-3 rounded-2xl rounded-tl-none text-sm max-w-[85%] shadow-sm">
                                    <p><?= nl2br(htmlspecialchars($msg->pesan)) ?></p>
                                    <span class="text-[10px] text-gray-400 block mt-1"><?= ($msg->pengirim == 'ai') ? 'Asisten Virtual' : 'Customer Service' ?> &bull; <?= date('H:i', strtotime($msg->created_at)) ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Input Reply -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <?php if(isset($can_reply) && $can_reply): ?>
                            <form id="reply-form" onsubmit="sendReply(event)" class="flex gap-2">
                                <input type="hidden" id="reply-ticket-id" value="<?= $active_ticket->id_konsultasi ?>">
                                <input type="text" id="reply-message" class="flex-1 bg-gray-50 border-0 rounded-xl text-sm px-4 py-3 focus:ring-1 focus:ring-primary-500" placeholder="Ketik balasan Anda..." required>
                                <button type="submit" class="bg-primary-600 text-white w-12 h-12 rounded-xl flex items-center justify-center hover:bg-primary-700 transition">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="bg-yellow-50 p-4 rounded-xl flex gap-3 items-center animate-pulse">
                                <i class="bi bi-hourglass-split text-yellow-600 text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-yellow-800">Menunggu Respon Staf</p>
                                    <p class="text-xs text-yellow-700">Anda dapat membalas pesan setelah staf kami merespon tiket ini.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Hide Form -->
                <form id="contact-form" onsubmit="submitForm(event)" class="hidden h-full flex flex-col justify-center space-y-5 transition-opacity duration-300">
                
                <?php else: ?>
                <!-- FORM VIEW -->
                
                <form id="contact-form" onsubmit="submitForm(event)" class="h-full flex flex-col justify-center space-y-5 transition-opacity duration-300">
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">NAMA LENGKAP</label>
                            <input type="text" name="nama" value="<?= isset($nama) ? $nama : '' ?>" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="John Doe" required>
                        </div>
                         <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">EMAIL</label>
                            <input type="email" name="email" value="<?= isset($email) ? $email : '' ?>" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="email@example.com" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">SUBJEK</label>
                         <input type="text" name="subjek" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Perihal pesan Anda" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">PESAN</label>
                        <textarea name="pesan" rows="4" class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Tuliskan pesan Anda disini..." required></textarea>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-xl hover:bg-gray-800 transition-all transform active:scale-[0.99] shadow-lg">
                        Kirim Pesan
                    </button>
                </form>
                
                <!-- Hidden Chat Room for JS Transition -->
                <div id="chat-room" class="hidden h-full flex flex-col">
                    <div class="border-b border-gray-100 pb-4 mb-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Ruang Diskusi</h3>
                            <p class="text-xs text-gray-500">ID Tiket: #<span id="ticket-id">-</span></p>
                        </div>
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Open</span>
                    </div>
                    <div id="chat-history" class="flex-1 overflow-y-auto pr-2 space-y-4 max-h-[400px]"></div>
                     <div class="mt-4 pt-4 border-t border-gray-100">
                         <div class="bg-yellow-50 p-3 rounded-xl flex gap-3 items-start">
                             <i class="bi bi-info-circle-fill text-yellow-600 mt-0.5"></i>
                             <p class="text-xs text-yellow-800 leading-relaxed">
                                 Pesan balasan dari staf kami akan muncul di sini.
                             </p>
                        </div>
                    </div>
                </div>

                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-400">
        &copy; <?= date('Y') ?> Apotek Friendly
    </footer>

    <script>
        function submitForm(e) {
            e.preventDefault();
            const form = document.getElementById('contact-form');
            const loading = document.getElementById('loading-overlay');
            const toast = document.getElementById('toast-success');
            
            // Show Loading
            loading.classList.remove('hidden');
            loading.classList.add('flex');

            const formData = new FormData(form);

            fetch('<?= base_url("bantuan/submit_contact") ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loading.classList.add('hidden');
                loading.classList.remove('flex');

                if(data.status) {
                    // 1. Show Toast
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        toast.classList.add('translate-x-full', 'opacity-0');
                    }, 4000);

                    // 2. Hide Form, Show Chat
                    document.getElementById('contact-form').classList.add('hidden');
                    const chatRoom = document.getElementById('chat-room');
                    chatRoom.classList.remove('hidden');
                    
                    // 3. Populate Chat
                    document.getElementById('ticket-id').innerText = data.ticket_id;
                    const history = document.getElementById('chat-history');
                    
                    // User Message
                    const userMsg = formData.get('pesan');
                    const userName = formData.get('nama');
                    history.innerHTML += `
                        <div class="flex flex-row-reverse items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600">
                                ${userName.charAt(0).toUpperCase()}
                            </div>
                            <div class="bg-primary-600 text-white p-3 rounded-2xl rounded-tr-none text-sm max-w-[85%] shadow-sm">
                                <p>${userMsg}</p>
                                <span class="text-[10px] text-primary-200 block mt-1 text-right">Baru saja</span>
                            </div>
                        </div>
                    `;

                    // AI Reply with slight delay effect
                    setTimeout(() => {
                        history.innerHTML += `
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-sm font-bold text-primary-600">
                                    CS
                                </div>
                                <div class="bg-white border border-gray-100 text-gray-700 p-3 rounded-2xl rounded-tl-none text-sm max-w-[85%] shadow-sm">
                                    <p>${data.ai_reply}</p>
                                    <span class="text-[10px] text-gray-400 block mt-1">CS Virtual &bull; Baru saja</span>
                                </div>
                            </div>
                        `;
                    }, 500);

                } else {
                    alert('Gagal mengirim: ' + data.message);
                }
            })
            .catch(err => {
                loading.classList.add('hidden');
                loading.classList.remove('flex');
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            });
        }

        function sendReply(e) {
            e.preventDefault();
            const input = document.getElementById('reply-message');
            const ticketId = document.getElementById('reply-ticket-id').value;
            const message = input.value;
            const history = document.getElementById('chat-history');

            if(!message.trim()) return;

            // Optimistic UI Update
            input.value = '';
            
            // Append User Message immediately
            history.innerHTML += `
                <div class="flex flex-row-reverse items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="bg-primary-600 text-white p-3 rounded-2xl rounded-tr-none text-sm max-w-[85%] shadow-sm">
                        <p>${message.replace(/\n/g, '<br>')}</p>
                        <span class="text-[10px] text-primary-200 block mt-1 text-right">Baru saja</span>
                    </div>
                </div>
            `;
            history.scrollTop = history.scrollHeight;

            // Send to Backend
            const formData = new FormData();
            formData.append('id_ticket', ticketId);
            formData.append('pesan', message);

            fetch('<?= base_url("bantuan/send_reply") ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(!data.status) {
                    alert('Gagal mengirim pesan: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mengirim pesan. Periksa koneksi internet.');
            });
        }
    </script>
</body>
</html>
