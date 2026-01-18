<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ai extends CI_Controller {

    // API Key sebaiknya disimpan di config atau environment variable.
    // Namun sesuai request, kita simpan di sini dengan aman (server-side).
    private $api_key = 'AIzaSyBzW4Ems9mtVlP9t_0Smre4tUgy2xKM_fA';
    private $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    // Modifikasi: Ubah akses ke public agar bisa dipanggil dari Controller lain
    public function get_ai_response($message, $history_text = "", $context_prompt = "") {
        // Default Prompt: Persona Profesional
        if(empty($context_prompt)) {
            $date = date('l, d F Y H:i') . ' WIB';
            $context_prompt = "Peran: Anda adalah Asisten Apoteker Senior di 'Apotek Friendly'.
            
            Konteks:
            - Waktu: $date.
            - Karakteristik: Profesional, sopan, berempati, dan berpengetahuan luas tentang farmasi dasar.
            - Gaya Bahasa: Formal namun hangat (menggunakan 'Anda', 'Kami'). Hindari bahasa gaul.
            
            Tugas:
            - Jawab pertanyaan pelanggan dengan tepat dan ringkas.
            - Jika menyangkut gejala serius, sarankan berkonsultasi ke dokter dengan nada peduli.
            - Jangan meresepkan obat keras (antibiotik, psikotropika) secara langsung.
            - Selalu tawarkan bantuan lebih lanjut di akhir percakapan jika relevan.
            - **PENTING:** Cukup ucapkan salam pembuka (Selamat Pagi/Siang/Sore/Malam) SEKALI saja di awal percakapan. Jangan mengulangnya di setiap balasan.
            
            Format:
            - Gunakan paragraf pendek agar mudah dibaca.";
        }

        $full_prompt = $context_prompt . "\n\nRiwayat Chat:\n" . $history_text . "\nPelanggan: " . $message . "\nAsisten Apoteker:";

        $data = [
            "contents" => [[ "parts" => [["text" => $full_prompt]] ]],
            "generationConfig" => [ 
                "temperature" => 0.4, 
                "maxOutputTokens" => 1000 // Diperbesar agar jawaban tuntas
            ]
        ];

        $ch = curl_init($this->api_url . '?key=' . $this->api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) return "Mohon maaf, sistem kami sedang mengalami kendala jaringan. Silakan coba lagi beberapa saat lagi.";
        
        curl_close($ch);
        $result = json_decode($response, true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }
        
        return "Mohon maaf, saya kurang memahami pertanyaan Anda. Bisakah Anda menjelaskannya dengan lebih detail?";
    }

    public function chat()
    {
        $user_message = $this->input->post('message');
        
        // Parsing History yang lebih baik untuk konteks
        $history_json = $this->input->post('history');
        $history_text = "";
        if (!empty($history_json)) {
            $history_array = json_decode($history_json, true);
            if(is_array($history_array)) {
                // Ambil 5 chat terakhir saja agar fokus
                $recent_chats = array_slice($history_array, -5); 
                foreach($recent_chats as $chat) {
                    $sender = ($chat['sender'] == 'user') ? 'Pelanggan' : 'Asisten';
                    $history_text .= "$sender: " . $chat['text'] . "\n";
                }
            }
        }

        $reply = $this->get_ai_response($user_message, $history_text);
        
        // Format HTML
        $reply = htmlspecialchars($reply);
        $reply = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $reply);
        $reply = nl2br($reply);

        echo json_encode(['reply' => $reply]);
    }
}
