<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Lab - Apotek Friendly</title>
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
            <div class="flex items-center gap-2 font-bold text-xl cursor-pointer" onclick="location.href='<?= base_url() ?>'">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white"><i class="bi bi-capsule"></i></div>
                <span>Apotek<span class="text-primary-600">Friendly</span></span>
            </div>
            <a href="<?= base_url() ?>" class="text-gray-500 hover:text-primary-600 text-sm font-medium"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </nav>

    <main class="flex-grow pt-28 pb-20 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <span class="inline-block py-1 px-3 rounded-full bg-primary-100 text-primary-700 text-xs font-bold tracking-wide uppercase mb-3">Layanan Baru</span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Cek Kesehatan & Laboratorium</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Pantau kondisi kesehatan Anda secara rutin dengan layanan cek lab kami yang akurat dan terjangkau.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Paket 1 -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                    <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cek Gula Darah</h3>
                    <p class="text-sm text-gray-500 mb-4 h-10">Pemeriksaan kadar gula darah sewaktu/puasa.</p>
                    <div class="flex items-end gap-1 mb-6">
                        <span class="text-2xl font-bold text-gray-900">Rp 25.000</span>
                    </div>
                    <a href="<?= site_url('layanan/booking/1') ?>" class="block w-full text-center py-2.5 bg-gray-50 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-colors">Booking</a>
                </div>

                <!-- Paket 2 -->
                <div class="bg-white p-6 rounded-3xl border-2 border-primary-500 shadow-xl relative overflow-hidden transform scale-105 z-10">
                    <div class="absolute top-0 right-0 bg-primary-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl">POPULER</div>
                    <div class="w-14 h-14 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cek Kolesterol Lengkap</h3>
                    <p class="text-sm text-gray-500 mb-4 h-10">Termasuk LDL, HDL, dan Trigliserida.</p>
                    <div class="flex items-end gap-1 mb-6">
                        <span class="text-2xl font-bold text-primary-600">Rp 45.000</span>
                    </div>
                    <a href="<?= site_url('layanan/booking/2') ?>" class="block w-full text-center py-2.5 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition-colors shadow-lg shadow-primary-200">Booking Sekarang</a>
                </div>

                <!-- Paket 3 -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                    <div class="w-14 h-14 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="bi bi-activity"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cek Asam Urat</h3>
                    <p class="text-sm text-gray-500 mb-4 h-10">Pemeriksaan kadar asam urat dalam darah.</p>
                    <div class="flex items-end gap-1 mb-6">
                        <span class="text-2xl font-bold text-gray-900">Rp 30.000</span>
                    </div>
                    <a href="<?= site_url('layanan/booking/3') ?>" class="block w-full text-center py-2.5 bg-gray-50 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-colors">Booking</a>
                </div>
            </div>
            
            <div class="mt-12 bg-indigo-50 rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8 border border-indigo-100">
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Butuh Paket Medical Checkup Lengkap?</h4>
                    <p class="text-indigo-800/80 text-sm">Kami bekerjasama dengan laboratorium klinik terkemuka untuk menyediakan layanan medical checkup lengkap dengan harga khusus member.</p>
                </div>
                <button onclick="toggleChat()" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors whitespace-nowrap shadow-lg shadow-indigo-200">
                    Hubungi CS
                </button>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-400">
        &copy; <?= date('Y') ?> Apotek Friendly
    </footer>

    <!-- AI CHAT WIDGET (Reused Component) -->
    <!-- Chat Window -->
    <div id="ai-chat-window" class="fixed bottom-6 right-6 w-[90%] md:w-96 bg-white rounded-2xl shadow-2xl z-50 transform translate-y-10 opacity-0 pointer-events-none transition-all duration-300 border border-gray-100 flex flex-col max-h-[600px]">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-primary-600 p-4 rounded-t-2xl flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                    <i class="bi bi-robot text-xl"></i>
                </div>
                <div>
                    <h5 class="font-bold text-sm">CS Laboratorium</h5>
                    <p class="text-xs text-primary-100 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span> Online
                    </p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white/80 hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Chat Area -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4 h-96 min-h-[300px] scroll-smooth">
            <!-- Welcome Message -->
            <div class="flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none border border-gray-100 shadow-sm text-sm text-gray-700 max-w-[85%]">
                    <span id="ai-greeting">Halo! Ada yang bisa saya bantu terkait layanan Cek Lab dan Kesehatan kami? 😊</span>
                </div>
            </div>
        </div>

        <!-- Footer Input -->
        <div class="p-3 border-t border-gray-100 bg-white rounded-b-2xl">
            <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex gap-2 relative">
                <input type="text" id="user-input" 
                    class="w-full bg-gray-100 border-0 rounded-xl py-3 pl-4 pr-12 text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    placeholder="Tanya soal harga cek darah..." required autocomplete="off">
                <button type="submit" id="btn-send"
                    class="absolute right-2 top-1.5 w-9 h-9 bg-indigo-600 text-white rounded-lg flex items-center justify-center hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="bi bi-send-fill text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const chatWindow = document.getElementById('ai-chat-window');
        const chatMessages = document.getElementById('chat-messages');
        const userInput = document.getElementById('user-input');
        const btnSend = document.getElementById('btn-send');
        let isChatOpen = false;
        let chatHistory = [];

        function toggleChat() {
            isChatOpen = !isChatOpen;
            if(isChatOpen) {
                chatWindow.classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
                setTimeout(() => userInput.focus(), 300);
            } else {
                chatWindow.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
            }
        }

        async function handleChatSubmit(e) {
            e.preventDefault();
            const message = userInput.value.trim();
            if(!message) return;

            // 1. Add User Message
            addMessage(message, 'user');
            chatHistory.push({sender: 'user', text: message});

            userInput.value = '';
            userInput.disabled = true;
            btnSend.disabled = true;
            
            // 2. Loading
            const loadingId = addLoadingIndicator();
            scrollToBottom();

            try {
                // 3. Fetch Backend
                const formData = new URLSearchParams();
                formData.append('message', message);
                formData.append('history', JSON.stringify(chatHistory.slice(0, -1)));
                
                // Gunakan URL endpoint AI yang sama
                const response = await fetch("<?= base_url('pelanggan/ai/chat') ?>", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData.toString()
                });

                const data = await response.json();
                document.getElementById(loadingId).remove();

                if(data.reply) {
                    addMessage(data.reply, 'ai');
                    chatHistory.push({sender: 'ai', text: data.reply.replace(/(<([^>]+)>)/gi, "")});
                } else if(data.error) {
                    addMessage("Maaf: " + data.error, 'ai', true);
                }

            } catch (error) {
                document.getElementById(loadingId).remove();
                addMessage("Gagal terhubung. Cek koneksi internet.", 'ai', true);
            } finally {
                userInput.disabled = false;
                btnSend.disabled = false;
                userInput.focus();
                scrollToBottom();
            }
        }

        function addMessage(text, sender, isError = false) {
            const div = document.createElement('div');
            
            if(sender === 'user') {
                div.className = 'flex items-start gap-2.5 flex-row-reverse animate-fade-in';
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="bg-indigo-600 p-3 rounded-2xl rounded-tr-none text-sm text-white max-w-[85%] shadow-sm">
                        ${text}
                    </div>
                `;
            } else {
                div.className = 'flex items-start gap-2.5 animate-fade-in';
                const bgClass = isError ? 'bg-red-50 border-red-100 text-red-600' : 'bg-white border-gray-100 text-gray-700';
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="${bgClass} p-3 rounded-2xl rounded-tl-none border shadow-sm text-sm max-w-[85%]">
                        ${text}
                    </div>
                `;
            }
            chatMessages.appendChild(div);
        }

        function addLoadingIndicator() {
            const id = 'loading-' + Date.now();
            const div = document.createElement('div');
            div.id = id;
            div.className = 'flex items-start gap-2.5 animate-pulse';
            div.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="bg-gray-100 p-3 rounded-2xl rounded-tl-none text-gray-500 text-sm flex gap-1">
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                </div>
            `;
            chatMessages.appendChild(div);
            return id;
        }

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function setGreeting() {
            const hour = new Date().getHours();
            let greeting = 'Halo';
            if(hour >= 4 && hour < 11) greeting = 'Selamat Pagi';
            else if(hour >= 11 && hour < 15) greeting = 'Selamat Siang';
            else if(hour >= 15 && hour < 18) greeting = 'Selamat Sore';
            else greeting = 'Selamat Malam';

            const element = document.getElementById('ai-greeting');
            if(element) {
                element.innerText = `${greeting}! Ada yang bisa saya bantu terkait layanan Cek Lab dan Kesehatan kami? 😊`;
            }
        }

        // Initialize Greeting on Load
        document.addEventListener('DOMContentLoaded', setGreeting);
    </script>
</body>
</html>
