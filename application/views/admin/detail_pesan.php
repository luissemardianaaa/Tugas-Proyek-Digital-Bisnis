<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Room Chat - Admin</title>
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
<body class="bg-gray-100 h-screen flex flex-col font-sans">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center shadow-sm z-10">
        <div class="flex items-center gap-4">
            <?php 
                $role_segment = $this->uri->segment(1);
                $back_url = ($role_segment == 'karyawan') ? 'karyawan/pesan' : 'admin/pesan';
            ?>
            <a href="<?= site_url($back_url) ?>" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 transition">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="font-bold text-gray-900 leading-tight"><?= htmlspecialchars($ticket->nama_pengirim ?? 'Pelanggan') ?></h1>
                <p class="text-xs text-gray-500"><?= htmlspecialchars($ticket->subjek) ?> &bull; <span class="text-gray-400">#<?= $ticket->id_konsultasi ?></span></p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
             <?php if($ticket->status == 'open'): ?>
                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full">OPEN</span>
                <?php 
                    $close_url = ($this->uri->segment(1) == 'karyawan') ? 'karyawan/pesan/tutup_tiket/'.$ticket->id_konsultasi : 'admin/tutup_tiket/'.$ticket->id_konsultasi;
                ?>
                <a href="<?= site_url($close_url) ?>" onclick="return confirm('Tandai tiket ini selesai?')" class="text-sm font-medium text-gray-500 hover:text-red-600 transition ml-2">
                    <i class="bi bi-check-circle"></i> Tandai Selesai
                </a>
            <?php else: ?>
                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-full">CLOSED</span>
            <?php endif; ?>
        </div>
    </header>

    <!-- Chat Area -->
    <main class="flex-1 overflow-y-auto p-6 space-y-4" id="chat-container">
        <?php foreach($messages as $msg): ?>
            
            <?php if($msg->pengirim == 'admin'): ?>
                <!-- Admin Message (Right) -->
                <div class="flex flex-row-reverse items-end gap-2">
                    <div class="bg-primary-600 text-white px-4 py-3 rounded-2xl rounded-br-none max-w-xl shadow-sm text-sm">
                        <p><?= nl2br(htmlspecialchars($msg->pesan)) ?></p>
                        <span class="text-[10px] text-primary-200 block mt-1 text-right">CS &bull; <?= date('H:i', strtotime($msg->created_at)) ?></span>
                    </div>
                </div>

            <?php elseif($msg->pengirim == 'ai'): ?>
                 <!-- AI Message (Left/Neutral) -->
                 <div class="flex flex-row items-end gap-2">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-xs font-bold text-purple-600 flex-shrink-0">AI</div>
                    <div class="bg-white border border-purple-100 text-gray-700 px-4 py-3 rounded-2xl rounded-bl-none max-w-xl shadow-sm text-sm">
                        <p><?= nl2br(htmlspecialchars($msg->pesan)) ?></p>
                        <span class="text-[10px] text-gray-400 block mt-1">AI Assistant &bull; <?= date('H:i', strtotime($msg->created_at)) ?></span>
                    </div>
                </div>

            <?php else: ?>
                <!-- User Message (Left) -->
                <div class="flex flex-row items-end gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold flex-shrink-0">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="bg-white text-gray-800 px-4 py-3 rounded-2xl rounded-bl-none max-w-xl shadow-sm text-sm">
                        <p><?= nl2br(htmlspecialchars($msg->pesan)) ?></p>
                        <span class="text-[10px] text-gray-400 block mt-1">Pelanggan &bull; <?= date('H:i', strtotime($msg->created_at)) ?></span>
                    </div>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </main>

    <!-- Input Area -->
    <?php if($ticket->status == 'open'): ?>
    <footer class="bg-white p-4 border-t border-gray-200">
        <?php 
            $role_segment = $this->uri->segment(1); // 'admin' or 'karyawan'
            $submit_url = ($role_segment == 'karyawan') ? 'karyawan/pesan/reply' : 'admin/reply_pesan';
        ?>
        <form action="<?= site_url($submit_url) ?>" method="post" class="max-w-4xl mx-auto flex gap-3">
            <input type="hidden" name="id_konsultasi" value="<?= $ticket->id_konsultasi ?>">
            <input type="text" name="pesan" class="flex-1 bg-gray-100 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white transition" placeholder="Tulis balasan..." required autocomplete="off">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white w-12 h-12 rounded-xl flex items-center justify-center transition shadow-lg shadow-primary-200">
                <i class="bi bi-send-fill"></i>
            </button>
        </form>
    </footer>
    <?php else: ?>
    <footer class="bg-gray-50 p-4 border-t border-gray-200 text-center text-gray-500 text-sm">
        Tiket ini telah ditutup.
    </footer>
    <?php endif; ?>

    <script>
        // Auto scroll to bottom
        const chatContainer = document.getElementById('chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    </script>
</body>
</html>
