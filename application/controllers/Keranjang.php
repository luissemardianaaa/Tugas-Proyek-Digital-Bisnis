<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keranjang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Keranjang_model');
        $this->load->model('Obat_model');
        $this->load->library('session');
    }

    public function index() {
        $id_user = $this->session->userdata('id_user');

        $data['items'] = $this->Keranjang_model->get_items($id_user);
        
        // Count unread notifications
        $data['notif_count'] = $this->db->get_where('notifikasi_pesanan', ['id_user' => $id_user, 'is_read' => 0])->num_rows();

        $this->load->view('keranjang/index', $data);
    }

    public function pesan() {
        $id_user = $this->session->userdata('id_user');
        if (!$id_user) redirect('auth/login');

        // Mark as read
        $this->db->where('id_user', $id_user)->update('notifikasi_pesanan', ['is_read' => 1]);

        $this->db->order_by('created_at', 'DESC');
        $data['notifikasi'] = $this->db->get_where('notifikasi_pesanan', ['id_user' => $id_user])->result();

        $this->load->view('keranjang/pesan', $data);
    }

    public function tambah($id_obat) {
        $id_user = $this->session->userdata('id_user');
        
        if (!$id_user) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu',
                'redirect' => site_url('auth/login')
            ]);
            return;
        }

        $obat = $this->Obat_model->get_by_id($id_obat);
        if ($obat) {
            $this->Keranjang_model->tambah($id_user, $obat);
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Berhasil ditambahkan'
        ]);
    }

    public function kurang($id_obat) {
        $id_user = $this->session->userdata('id_user');
        if (!$id_user) return; 

        $this->Keranjang_model->kurang($id_user, $id_obat);
        echo json_encode(['status' => 'success']);
    }

    public function hapus($id_detail) {
        $this->Keranjang_model->hapus($id_detail);
        redirect('keranjang');
    }

    public function checkout() {
        $id_user = $this->session->userdata('id_user');
        if (!$id_user) redirect('auth/login');

        $data['items'] = $this->Keranjang_model->get_items($id_user);
        
        if(empty($data['items'])) {
            redirect('keranjang');
        }

        $this->load->view('keranjang/checkout', $data);
    }

    public function proses_pesanan() {
        $id_user = $this->session->userdata('id_user');
        if (!$id_user) redirect('auth/login');

        $items = $this->Keranjang_model->get_items($id_user);
        if(empty($items)) redirect('keranjang');

        // Ambil Data dari Form
        $nama_penerima = $this->input->post('nama_penerima');
        $kota_tujuan = $this->input->post('kota_tujuan');
        $alamat_lengkap = $this->input->post('alamat_lengkap');
        $no_hp = $this->input->post('no_hp');
        $ongkir = $this->input->post('ongkir');
        $biaya_layanan = $this->input->post('biaya_layanan');
        
        // Hitung total manual (untuk validasi server side)
        $subtotal_produk = 0;
        foreach($items as $item) {
            $subtotal_produk += ($item->harga * $item->jumlah);
        }
        $total_akhir = $subtotal_produk + $ongkir + $biaya_layanan;

        // Generate Unique Order ID
        $order_id = 'ORDER-' . time() . '-' . $id_user;
        
        // Data Penjualan
        $data_penjualan = [
            'id_karyawan'       => $id_user,
            'total_harga'       => $total_akhir,
            'metode_pembayaran' => 'Midtrans',
            'alamat_pengiriman' => $alamat_lengkap . ' (' . $no_hp . ') - ' . $kota_tujuan,
            'status'            => 'menunggu_pembayaran',
            'order_id'          => $order_id,
            'created_at'        => date('Y-m-d H:i:s')
        ];

        // Load model penjualan jika belum
        $this->load->model('Penjualan_model');
        $id_penjualan = $this->Penjualan_model->insert_penjualan($data_penjualan);

        // Insert Detail
        $item_details = [];
        foreach($items as $item) {
            $this->Penjualan_model->insert_detail([
                'id_penjualan' => $id_penjualan,
                'id_obat' => $item->id_obat,
                'jumlah' => $item->jumlah,
                'harga' => $item->harga
            ]);
            
            // Prepare item details for Midtrans
            $item_details[] = [
                'id' => $item->id_obat,
                'price' => (int)$item->harga,
                'quantity' => (int)$item->jumlah,
                'name' => substr($item->nama_obat, 0, 50) // Midtrans limit 50 chars
            ];
            
            // KURANGI STOK OTOMATIS
            $this->Obat_model->reduce_stock($item->id_obat, $item->jumlah);
            
            $this->Keranjang_model->hapus($item->id_detail);
        }
        
        // Add shipping and service fee to item details
        if ($ongkir > 0) {
            $item_details[] = [
                'id' => 'SHIPPING',
                'price' => (int)$ongkir,
                'quantity' => 1,
                'name' => 'Ongkos Kirim ke ' . $kota_tujuan
            ];
        }
        
        if ($biaya_layanan > 0) {
            $item_details[] = [
                'id' => 'SERVICE',
                'price' => (int)$biaya_layanan,
                'quantity' => 1,
                'name' => 'Biaya Layanan'
            ];
        }

        // Load Midtrans Library
        $this->load->library('midtrans_lib');
        
        // Get user data
        $user = $this->db->get_where('users', ['id_user' => $id_user])->row();
        
        // Prepare Midtrans transaction parameters
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => (int)$total_akhir
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $nama_penerima,
                'email' => $user->email ?? 'customer@apotek.com',
                'phone' => $no_hp,
                'shipping_address' => [
                    'first_name' => $nama_penerima,
                    'phone' => $no_hp,
                    'address' => $alamat_lengkap,
                    'city' => $kota_tujuan
                ]
            ]
        ];
        
        // Create Snap Token
        $snap_result = $this->midtrans_lib->create_snap_token($params);
        
        if ($snap_result['success']) {
            // Update penjualan with snap_token
            $this->db->where('id_penjualan', $id_penjualan)->update('penjualan', [
                'snap_token' => $snap_result['snap_token']
            ]);
            
            // Redirect ke Halaman Pembayaran
            redirect('keranjang/pembayaran/' . $id_penjualan);
        } else {
            // Jika gagal create snap token, tampilkan error
            $this->session->set_flashdata('error', 'Gagal membuat transaksi pembayaran: ' . $snap_result['message']);
            redirect('keranjang');
        }
    }


    public function pembayaran($id_penjualan) {
        if (!$this->session->userdata('id_user')) redirect('auth/login');
        
        $this->load->model('Penjualan_model');
        $this->load->library('midtrans_lib');
        
        $data['penjualan'] = $this->Penjualan_model->get_by_id($id_penjualan);
        
        if(!$data['penjualan']) show_404();
        
        // Pass Midtrans config to view
        $data['client_key'] = $this->midtrans_lib->get_client_key();
        $data['snap_url'] = $this->midtrans_lib->get_snap_url();

        $this->load->view('keranjang/pembayaran', $data);
    }

    public function konfirmasi_bayar($id_penjualan) {
        if (!$this->session->userdata('id_user')) redirect('auth/login');
        
        $this->load->model('Penjualan_model');
        
        // Logika: Jika COD langsung dikemas, jika Transfer dikemas (simulasi verify auto)
        // Sesuai request: "klik sudah bayar -> detail pesanan... sedang dikemas"
        $this->Penjualan_model->update_status($id_penjualan, 'dikemas');
        
        $this->session->set_flashdata('success', 'Pembayaran dikonfirmasi! Pesanan sedang dikemas.');
        redirect('keranjang/detail_pesanan/' . $id_penjualan);
    }

    public function detail_pesanan($id_penjualan) {
        if (!$this->session->userdata('id_user')) redirect('auth/login');
        
        $this->load->model('Penjualan_model');
        $data['penjualan'] = $this->Penjualan_model->get_by_id($id_penjualan);
        $data['items']     = $this->Penjualan_model->get_detail($id_penjualan);
        
        if(!$data['penjualan']) show_404();

        $this->load->view('keranjang/detail_pesanan', $data);
    }

    // =====================
    // MIDTRANS NOTIFICATION WEBHOOK
    // =====================
    public function midtrans_notification() {
        $this->load->library('midtrans_lib');
        $this->load->model('Penjualan_model');
        
        // Handle notification
        $notification = $this->midtrans_lib->handle_notification();
        
        if (!$notification['success']) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
            return;
        }
        
        $data = $notification['data'];
        $order_id = $data['order_id'];
        $transaction_status = $data['transaction_status'];
        $fraud_status = $data['fraud_status'] ?? null;
        $payment_type = $data['payment_type'];
        
        // Get penjualan by order_id
        $penjualan = $this->db->get_where('penjualan', ['order_id' => $order_id])->row();
        
        if (!$penjualan) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Order not found']);
            return;
        }
        
        // Map status
        $new_status = $this->midtrans_lib->map_status($transaction_status, $fraud_status);
        
        // Update penjualan
        $this->db->where('id_penjualan', $penjualan->id_penjualan)->update('penjualan', [
            'status' => $new_status,
            'payment_type' => $payment_type,
            'transaction_id' => $data['transaction_id'],
            'transaction_time' => $data['transaction_time'],
            'transaction_status' => $transaction_status
        ]);
        
        // Log notification (optional)
        log_message('info', 'Midtrans Notification: ' . json_encode($data));
        
        http_response_code(200);
        echo json_encode(['status' => 'success']);
    }

}
