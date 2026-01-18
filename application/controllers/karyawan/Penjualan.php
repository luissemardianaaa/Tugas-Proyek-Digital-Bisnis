<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Penjualan_model');
        $this->load->model('Obat_model');
        $this->load->library('session');
        $this->load->helper('shift');
        
        // Cek Login & Role
        // Cek Login & Role (Admin & Karyawan diperbolehkan)
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $role = $this->session->userdata('role');
        if ($role !== 'karyawan' && $role !== 'admin') {
             redirect('auth');
        }

        // Check if employee is outside working hours (only for karyawan, not admin)
        if ($role === 'karyawan' && is_outside_working_hours($this)) {
            $shift_info = get_user_shift_info($this);
            $this->session->set_flashdata('error', 
                'Akses ke halaman Transaksi Penjualan dibatasi di luar jam kerja Anda (' . 
                $shift_info->keterangan . ': ' . $shift_info->jam_masuk . ' - ' . $shift_info->jam_pulang . '). ' .
                'Silakan kembali pada jam kerja Anda untuk melakukan transaksi.'
            );
            redirect('karyawan/dashboard');
        }
    }

    public function index() {
        $data['penjualan'] = $this->Penjualan_model->get_all();
        $this->load->view('penjualan/index', $data);
    }

    public function create() {
        $data['obat'] = $this->Obat_model->get_all_obat();
        $this->load->view('penjualan/create', $data);
    }

public function store()
{
    // ======================
    // USER (DUMMY UNTUK TEST)
    // ======================
    $id_user = $this->session->userdata('id_user');
    if (!$id_user) {
        $id_user = 1; // ⚠️ WAJIB ADA DI tabel pengguna
    }

    $items = $this->input->post('items');
    $bayar = (int) $this->input->post('bayar');

    if (empty($items)) {
        show_error('Data transaksi kosong');
    }

    // ======================
    // HITUNG TOTAL
    // ======================
    $total = 0;
    foreach ($items as $i) {
        if (
            !isset($i['qty'], $i['harga'], $i['id_obat'])
        ) continue;

        $qty   = (int) $i['qty'];
        $harga = (int) $i['harga'];

        if ($qty > 0) {
            $total += $qty * $harga;
        }
    }

    if ($total <= 0) {
        show_error('Total transaksi tidak boleh 0');
    }

    if ($bayar < $total) {
        show_error('Uang bayar kurang');
    }

    // ======================
    // INSERT PENJUALAN
    // ======================
    $penjualan = [
    'id_karyawan' => $id_user,
    'total_harga' => $total,
    'bayar'       => $bayar,
    'kembalian'   => $bayar - $total,
    'created_at'  => date('Y-m-d H:i:s') // 🔥 WAJIB
];

    $id_penjualan = $this->Penjualan_model->insert_penjualan($penjualan);

    // ======================
    // INSERT DETAIL + UPDATE STOK
    // ======================
    foreach ($items as $i) {

        if (
            !isset($i['qty'], $i['harga'], $i['id_obat'])
        ) continue;

        $qty   = (int) $i['qty'];
        $harga = (int) $i['harga'];
        $id_obat = (int) $i['id_obat'];

        if ($qty <= 0) continue;

       $detail = [
    'id_penjualan' => $id_penjualan,
    'id_obat'      => $i['id_obat'],
    'jumlah'       => $qty,
    'harga'        => $harga
];

        $this->Penjualan_model->insert_detail($detail);

        // update stok
        $this->db->set('stok', 'stok-'.$qty, false)
                 ->where('id_obat', $id_obat)
                 ->update('obat');
    }

    // ======================
    // FLASH MESSAGE
    // ======================
    $this->session->set_flashdata(
        'success',
        'Pembayaran berhasil disimpan'
    );

    redirect('karyawan/penjualan');
}
    public function detail($id) {
        $data['penjualan'] = $this->Penjualan_model->get_by_id($id);
        
        // Manual Join name if not in get_by_id (User name needed)
        // Note: get_by_id in model was simple get_where. we might need to join user. 
        // Let's rely on get_by_id returning object. Use logic in view or update model. 
        // Actually get_by_id doesn't join. Let's patch model or do simple query here?
        // Let's update index method to reusable get_all logic or just simple query here for speed.
        
        // Re-fetching with name join
        $this->db->select('penjualan.*, users.nama');
        $this->db->from('penjualan');
        $this->db->join('users', 'users.id_user = penjualan.id_karyawan', 'left');
        $this->db->where('id_penjualan', $id);
        $data['penjualan'] = $this->db->get()->row();

        $data['items'] = $this->Penjualan_model->get_detail($id);
        
        if(!$data['penjualan']) show_404();

        $this->load->view('penjualan/detail', $data);
    }

    public function update_status($id) {
        $status = $this->input->post('status');
        if($status) {
            // Record who confirmed this order
            $id_petugas = $this->session->userdata('id_user');
            $this->db->where('id_penjualan', $id);
            $this->db->update('penjualan', [
                'status' => $status,
                'id_petugas_konfirmasi' => $id_petugas
            ]);
            
            $this->session->set_flashdata('success', 'Status pesanan berhasil diperbarui menjadi '. ucwords(str_replace('_', ' ', $status)));
        }
        redirect('karyawan/penjualan/detail/' . $id);
    }
}