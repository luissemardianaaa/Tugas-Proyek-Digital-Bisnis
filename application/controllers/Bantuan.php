<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bantuan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Konsultasi_model');
        // Set Timezone
        date_default_timezone_set('Asia/Jakarta');
    }

    public function cara_belanja() {
        $this->load->view('bantuan/cara_belanja');
    }

    public function informasi_pengiriman() {
        $this->load->view('bantuan/informasi_pengiriman');
    }

    public function hubungi_kami() {
        $id_user = $this->session->userdata('id_user');
        $data['id_user'] = $id_user;
        $data['nama'] = $this->session->userdata('nama');
        $data['email'] = $this->session->userdata('email');
        
        // Cek Tiket Aktif
        $active_ticket = $this->Konsultasi_model->get_active_ticket($id_user);
        
        if($active_ticket) {
            $data['active_ticket'] = $active_ticket;
            $data['messages'] = $this->Konsultasi_model->get_messages($active_ticket->id_konsultasi);
            
            // Logic Cek "Karyawan Sibuk" (12 Jam)
            $this->_check_delay_response($active_ticket, $data['messages']);
            // Refresh messages after potential AI insert
            $data['messages'] = $this->Konsultasi_model->get_messages($active_ticket->id_konsultasi);
            
            // Check if human (admin) has replied
            $has_human_reply = false;
            foreach($data['messages'] as $m) {
                if($m->pengirim == 'admin') {
                    $has_human_reply = true;
                    break;
                }
            }
            $data['can_reply'] = $has_human_reply;
        }

        $this->load->view('bantuan/hubungi_kami', $data);
    }
    
    // Helper: Logic Sibuk (12 Hours)
    private function _check_delay_response($ticket, $messages) {
        if(empty($messages)) return;
        
        $last_msg = end($messages);
        
        // Jika pengirim terakhir adalah USER dan sudah > 12 jam
        if($last_msg->pengirim == 'user') {
            $last_time = strtotime($last_msg->created_at);
            $now = time();
            $diff_hours = ($now - $last_time) / 3600;
            
            if($diff_hours >= 12) {
                // Konfirmasi belum ada pesan AI "sibuk" sebelumnya untuk menghindari spam
                $already_apologized = false;
                // Cek 3 pesan terakhir
                $recent_msgs = array_slice($messages, -3);
                foreach($recent_msgs as $m) {
                    if($m->pengirim == 'ai' && strpos($m->pesan, 'Mohon bersabar') !== false) {
                        $already_apologized = true;
                    }
                }
                
                if(!$already_apologized) {
                    $apology_msg = "Mohon maaf atas keterlambatan respon. Saat ini staf kami sedang menangani antrian yang cukup padat. Mohon bersabar, kami akan membalas pesan Anda dalam 1x24 jam. Terima kasih telah menunggu. 🙏";
                    $this->Konsultasi_model->add_message([
                        'id_konsultasi' => $ticket->id_konsultasi,
                        'pengirim' => 'ai',
                        'pesan' => $apology_msg
                    ]);
                }
            }
        }
    }

    // Handle Pesan Baru
    public function submit_contact() {
        $nama = $this->input->post('nama');
        $email = $this->input->post('email');
        $subjek = $this->input->post('subjek');
        $pesan = $this->input->post('pesan');
        $id_user = $this->session->userdata('id_user');

        if(empty($pesan)) {
            echo json_encode(['status' => false, 'message' => 'Pesan kosong.']);
            return;
        }
        
        // Cek jika sudah ada tiket aktif, pakai itu
        $ticket = $this->Konsultasi_model->get_active_ticket($id_user);
        $id_ticket = 0;

        if($ticket) {
            $id_ticket = $ticket->id_konsultasi;
        } else {
             // Validate Name/Email only for new ticket
            if(empty($nama) || empty($email)) {
                echo json_encode(['status' => false, 'message' => 'Data diri kurang lengkap.']);
                return;
            }
            $data_ticket = [
                'id_user' => $id_user,
                'nama_pengirim' => $nama,
                'email' => $email,
                'subjek' => $subjek,
                'status' => 'open'
            ];
            $id_ticket = $this->Konsultasi_model->create_ticket($data_ticket);
        }

        // 2. Simpan Pesan User
        $data_msg = [
            'id_konsultasi' => $id_ticket,
            'pengirim' => 'user',
            'pesan' => $pesan
        ];
        $this->Konsultasi_model->add_message($data_msg);

        // 3. Auto Reply AI (Only if new ticket OR explicit request - keep simple: Auto reply for new ticket only to avoid spamming in long chat)
        $ai_reply = null;
        if(!$ticket) { // Hanya reply otomatis untuk TIKET BARU
            $ai_reply = $this->_get_ai_reply($pesan, $nama, $subjek);
             $data_ai = [
                'id_konsultasi' => $id_ticket,
                'pengirim' => 'ai',
                'pesan' => $ai_reply
            ];
            $this->Konsultasi_model->add_message($data_ai);
        }

        echo json_encode([
            'status' => true, 
            'message' => 'Pesan terkirim.',
            'ticket_id' => $id_ticket,
            'ai_reply' => $ai_reply
        ]);
    }

    // 4. Send Reply Function (Khusus untuk Chat Room)
    public function send_reply() {
        $pesan = $this->input->post('pesan');
        $id_ticket = $this->input->post('id_ticket');
        $id_user = $this->session->userdata('id_user');

        if(empty($pesan) || empty($id_ticket)) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }

        // Verifikasi kepemilikan tiket
        $ticket = $this->Konsultasi_model->get_ticket($id_ticket, $id_user);
        if(!$ticket || $ticket->status != 'open') {
            echo json_encode(['status' => false, 'message' => 'Tiket tidak aktif atau tidak ditemukan.']);
            return;
        }

        // Simpan Pesan
        $data_msg = [
            'id_konsultasi' => $id_ticket,
            'pengirim' => 'user',
            'pesan' => $pesan
        ];
        $this->Konsultasi_model->add_message($data_msg);
        
        // Response AI (Opsional: Jika ingin AI membalas setiap chat user di room ini layaknya chatbot biasa)
        // Untuk saat ini, user meminta hanya "bisa mengirim teks", kita biarkan manual handle oleh CS
        // TAPI: Jika ini dimaksudkan chatbot hybrid, kita bisa panggil _get_ai_reply lagi.
        // Asumsi: Ini hanya kirim pesan ke CS. AI hanya saat "Greeting" awal.
        
        echo json_encode(['status' => true]);
    }

    public function close_chat($id_ticket) {
        $id_user = $this->session->userdata('id_user');
        
        // Verifikasi kepemilikan
        $ticket = $this->Konsultasi_model->get_ticket($id_ticket, $id_user);
        
        if($ticket) {
            $this->Konsultasi_model->update_status($id_ticket, 'closed');
            $this->session->set_flashdata('success', 'Percakapan telah diakhiri. Silakan mulai topik baru jika diperlukan.');
        }
        
        redirect('bantuan/hubungi_kami');
    }

    private function _get_ai_reply($user_message, $user_name, $subject) {
        $api_key = 'AIzaSyBzW4Ems9mtVlP9t_0Smre4tUgy2xKM_fA'; 
        $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent'; // Upgrade to Pro for better language
        
        $waktu = date('l, d F Y H:i');
        
        $system_prompt = "Peran: Anda adalah Customer Service Profesional dari 'Apotek Friendly', sebuah jaringan apotek terpercaya.
        
        Konteks:
        - Waktu saat ini: $waktu WIB.
        - Pelanggan: $user_name.
        - Topik Pesan: $subject.
        
        Tugas:
        1. Berikan respon penerimaan pesan yang sangat sopan, profesional, dan menenangkan. Gunakan bahasa Indonesia formal namun hangat (Hospitality standard).
        2. Ucapkan terima kasih dan konfirmasi bahwa pesan telah diterima sistem.
        3. Informasikan bahwa tim Farmasi/Laboratorium akan menganalisis pertanyaan ini dan membalas dalam waktu singkat.
        4. Jangan berikan diagnosa medis.
        5. Akhiri dengan kalimat penutup yang melayani.
        
        Contoh Nada Bicara:
        'Selamat pagi, Bapak/Ibu. Terima kasih telah menghubungi Apotek Friendly. Pertanyaan Anda mengenai ... telah kami terima dan segera diteruskan ke Apoteker kami untuk penanganan lebih lanjut.'
        
        Batasan: Maksimal 4 kalimat. Jangan terpotong.";

        $full_prompt = $system_prompt . "\n\nPesan Pelanggan: " . $user_message . "\nCS Profesional:";

        $data = [
            "contents" => [[ "parts" => [["text" => $full_prompt]] ]],
            "generationConfig" => [ 
                "temperature" => 0.4, // Lebih rendah agar lebih konsisten/formal
                "maxOutputTokens" => 800 
            ]
        ];

        $ch = curl_init($api_url . '?key=' . $api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }
        return "Terima kasih telah menghubungi Apotek Friendly. Pesan Anda telah kami terima dan akan segera ditindaklanjuti oleh staf profesional kami. Mohon kesediaan Anda untuk menunggu sebentar.";
    }
}
