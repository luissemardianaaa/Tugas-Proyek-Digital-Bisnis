<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

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
                'Akses ke halaman Transaksi Pembelian dibatasi di luar jam kerja Anda (' . 
                $shift_info->keterangan . ': ' . $shift_info->jam_masuk . ' - ' . $shift_info->jam_pulang . '). ' .
                'Silakan kembali pada jam kerja Anda untuk melakukan transaksi.'
            );
            redirect('karyawan/dashboard');
        }

        // Set active page for sidebar
        $this->data['active_page'] = 'transaksi';
        
        // Load models
        $this->load->model('Pembelian_model');
        $this->load->model('Pemasok_model');
        $this->load->model('Obat_model');
        $this->load->model('Stok_model');
        

    }
public function index()
{
    $data['title'] = 'Transaksi Pembelian';
    $data['breadcrumb'] = [
        ['name' => 'Dashboard', 'link' => site_url('karyawan/dashboard'), 'active' => false],
        ['name' => 'Transaksi', 'link' => '', 'active' => false],
        ['name' => 'Pembelian', 'link' => '', 'active' => true]
    ];

    // Data utama tabel (INI YANG DIPAKAI VIEW KAMU)
    $data['pembelian'] = $this->Pembelian_model->get_data_pembelian();

    // Optional (kalau mau dipakai di view)
    $data['pembelian_hari_ini'] = $this->Pembelian_model->get_pembelian_hari_ini();
    $data['total_pembelian_hari_ini'] = $this->Pembelian_model->get_total_pembelian_hari_ini();

    // Dropdown / kebutuhan lain
    $data['obat_list'] = $this->Obat_model->get_all_obat();
    $data['pemasok']   = $this->Pemasok_model->get_all();
    $data['riwayat_pemasok'] = $this->Pembelian_model->get_riwayat_pemasok();

    // PANGGIL VIEW SEKALI SAJA
    $this->load->view('pembelian/index', $data);
}

    public function riwayat() {
        $this->data['title'] = 'Riwayat Pembelian';
        $this->data['breadcrumb'] = [
            ['name' => 'Dashboard', 'link' => site_url('karyawan/dashboard'), 'active' => false],
            ['name' => 'Transaksi', 'link' => '', 'active' => false],
            ['name' => 'Riwayat Pembelian', 'link' => '', 'active' => true]
        ];
        
        // Filter dates
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-d');
        
        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;
        $this->data['pembelian'] = $this->Pembelian_model->get_pembelian_periode($start_date, $end_date);
        $this->data['total_pembelian'] = $this->Pembelian_model->get_total_pembelian_periode($start_date, $end_date);
        
        $this->render('pembelian/riwayat');
    }
    
    public function proses() {
        // Check if user has permission
        if($this->data['user_role'] != 'admin' && $this->data['user_role'] != 'karyawan') {
            $this->json_response(['success' => false, 'message' => 'Anda tidak memiliki izin'], 403);
            return;
        }
        
        $this->form_validation->set_rules('items', 'Items', 'required');
        $this->form_validation->set_rules('id_supplier', 'Supplier', 'required');
        
        if($this->form_validation->run() == FALSE) {
            $this->json_response(['success' => false, 'message' => 'Data tidak lengkap'], 400);
            return;
        }
        
        $items = json_decode($this->input->post('items'), true);
        $id_supplier = $this->input->post('id_supplier');
        $catatan = $this->input->post('catatan');
        
        if(empty($items)) {
            $this->json_response(['success' => false, 'message' => 'Keranjang kosong'], 400);
            return;
        }
        
        // Calculate total
        $total_beli = 0;
        foreach($items as $item) {
            $total_beli += $item['subtotal'];
        }
        
        // Generate purchase number
        $no_pembelian = $this->Pembelian_model->generate_purchase_number();
        
        // Get supplier info
        $supplier = $this->Supplier_model->get_by_id($id_supplier);
        
        // Prepare transaction data
        $transaksi_data = [
            'no_pembelian' => $no_pembelian,
            'id_user' => $this->session->userdata('user_id'),
            'id_supplier' => $id_supplier,
            'supplier' => $supplier ? $supplier->nama_supplier : '',
            'tanggal' => date('Y-m-d'),
            'total_beli' => $total_beli,
            'catatan' => $catatan,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->trans_start();
        
        // Save transaction
        $id_pembelian = $this->Pembelian_model->save_transaksi($transaksi_data);
        
        // Save details and update stock
        foreach($items as $item) {
            $detail_data = [
                'id_pembelian' => $id_pembelian,
                'id_obat' => $item['id_obat'],
                'id_supplier' => $id_supplier,
                'jumlah' => $item['jumlah'],
                'harga_beli' => $item['harga_beli'],
                'subtotal' => $item['subtotal'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->Pembelian_model->save_detail($detail_data);
            
            // Increase stock
            $this->Obat_model->increase_stock($item['id_obat'], $item['jumlah']);
            
            // Add stock log
            $log_data = [
                'id_obat' => $item['id_obat'],
                'id_user' => $this->session->userdata('user_id'),
                'jenis' => 'masuk',
                'jumlah_perubahan' => $item['jumlah'],
                'keterangan' => 'Pembelian dari ' . ($supplier ? $supplier->nama_supplier : 'Supplier') . ' - ' . $no_pembelian,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->Stok_model->add_log($log_data);
        }
        
        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->json_response(['success' => false, 'message' => 'Transaksi gagal disimpan'], 500);
        } else {
            $this->json_response([
                'success' => true, 
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'id_pembelian' => $id_pembelian,
                    'no_pembelian' => $no_pembelian,
                    'total_beli' => $total_beli
                ]
            ]);
        }
    }
    
    public function detail($id) {
        $this->data['title'] = 'Detail Transaksi Pembelian';
        $this->data['breadcrumb'] = [
            ['name' => 'Dashboard', 'link' => site_url('karyawan/dashboard'), 'active' => false],
            ['name' => 'Transaksi', 'link' => site_url('karyawan/pembelian'), 'active' => false],
            ['name' => 'Detail Pembelian', 'link' => '', 'active' => true]
        ];
        
        $this->data['transaksi'] = $this->Pembelian_model->get_transaksi_by_id($id);
        
        if(!$this->data['transaksi']) {
            show_404();
        }
        
        $this->data['detail'] = $this->Pembelian_model->get_detail_by_transaksi($id);
        
        $this->render('pembelian/detail');
    }
    
    public function po($id) {
        $transaksi = $this->Pembelian_model->get_purchase_order($id);
        $detail = $this->Pembelian_model->get_detail_by_transaksi($id);
        
        if(!$transaksi) {
            show_404();
        }
        
        $data = [
            'transaksi' => $transaksi,
            'detail' => $detail,
            'apotek' => [
                'nama' => 'APOTEK SEHAT',
                'alamat' => 'Jl. Contoh No. 123, Kota Contoh',
                'telepon' => '(021) 1234567'
            ]
        ];
        
        // Load PDF library
        $this->load->library('pdf');
        
        $html = $this->load->view('pembelian/po_pdf', $data, true);
        
        $this->pdf->load_html($html);
        $this->pdf->set_paper('A4', 'portrait');
        $this->pdf->render();
        
        $this->pdf->stream("purchase_order_" . $transaksi->no_pembelian . ".pdf");
    }
    
    public function get_supplier($id) {
        $supplier = $this->Supplier_model->get_by_id($id);
        
        if(!$supplier) {
            $this->json_response(['success' => false, 'message' => 'Supplier tidak ditemukan'], 404);
            return;
        }
        
        $this->json_response([
            'success' => true,
            'data' => [
                'id_supplier' => $supplier->id_supplier,
                'kode_supplier' => $supplier->kode_supplier,
                'nama_supplier' => $supplier->nama_supplier,
                'telepon' => $supplier->telepon,
                'email' => $supplier->email
            ]
        ]);
    }
    
    public function cancel($id) {
        // Check if user has permission
        if($this->data['user_role'] != 'admin') {
            $this->json_response(['success' => false, 'message' => 'Anda tidak memiliki izin'], 403);
            return;
        }
        
        $transaksi = $this->Pembelian_model->get_transaksi_by_id($id);
        
        if(!$transaksi) {
            $this->json_response(['success' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
            return;
        }
        
        $detail = $this->Pembelian_model->get_detail_by_transaksi($id);
        
        $this->db->trans_start();
        
        // Reduce stock for each item
        foreach($detail as $item) {
            $this->Obat_model->reduce_stock($item->id_obat, $item->jumlah);
            
            // Add stock log
            $log_data = [
                'id_obat' => $item->id_obat,
                'id_user' => $this->session->userdata('user_id'),
                'jenis' => 'keluar',
                'jumlah_perubahan' => $item['jumlah'] * -1,
                'keterangan' => 'Pembatalan pembelian ' . $transaksi->no_pembelian,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->Stok_model->add_log($log_data);
        }
        
        // Mark transaction as cancelled
        $this->db->where('id_pembelian', $id);
        $this->db->update('transaksi_pembelian', ['status' => 'cancelled', 'cancelled_at' => date('Y-m-d H:i:s')]);
        
        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->json_response(['success' => false, 'message' => 'Gagal membatalkan transaksi'], 500);
        } else {
            $this->json_response(['success' => true, 'message' => 'Transaksi berhasil dibatalkan']);
        }
    }
    
    public function export($type = 'pdf') {
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-d');
        
        $data['pembelian'] = $this->Pembelian_model->get_pembelian_periode($start_date, $end_date);
        $data['total_pembelian'] = $this->Pembelian_model->get_total_pembelian_periode($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        if($type == 'pdf') {
            // Load PDF library
            $this->load->library('pdf');
            
            $html = $this->load->view('pembelian/export_pdf', $data, true);
            
            $this->pdf->load_html($html);
            $this->pdf->set_paper('A4', 'landscape');
            $this->pdf->render();
            
            $this->pdf->stream("laporan_pembelian_" . date('Y-m-d') . ".pdf");
        } else {
            // Excel export
            $this->load->library('excel');
            
            $objPHPExcel = new PHPExcel();
            
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Apotek System")
                                       ->setLastModifiedBy("Apotek System")
                                       ->setTitle("Laporan Pembelian");
            
            // Add data
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'Tanggal')
                        ->setCellValue('C1', 'No Pembelian')
                        ->setCellValue('D1', 'Supplier')
                        ->setCellValue('E1', 'Total')
                        ->setCellValue('F1', 'Petugas');
            
            $row = 2;
            $no = 1;
            foreach($data['pembelian'] as $item) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $row, $no)
                            ->setCellValue('B' . $row, $item->tanggal)
                            ->setCellValue('C' . $row, $item->no_pembelian)
                            ->setCellValue('D' . $row, $item->nama_supplier)
                            ->setCellValue('E' . $row, $item->total_beli)
                            ->setCellValue('F' . $row, $item->nama_pembeli);
                $row++;
                $no++;
            }
            
            // Add total
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('D' . $row, 'TOTAL:')
                        ->setCellValue('E' . $row, $data['total_pembelian']);
            
            // Set title
            $objPHPExcel->getActiveSheet()->setTitle('Laporan Pembelian');
            
            // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="laporan_pembelian.xls"');
            header('Cache-Control: max-age=0');
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
    }
public function create()
{
    $this->data['title'] = 'Tambah Pembelian';

    $this->data['obat']    = $this->Obat_model->get_all_obat();
    $this->data['pemasok'] = $this->Pemasok_model->get_all();

    $this->load->view('pembelian/create', $this->data);
}

    public function store() {
        $jumlah    = (int) $this->input->post('jumlah');
        $harga     = (int) $this->input->post('harga_satuan');
        $nama_obat = $this->input->post('nama_obat');

        // Cari ID Obat berdasarkan nama
        $obat = $this->db->get_where('obat', ['nama_obat' => $nama_obat])->row();

        if (!$obat) {
            // Jika obat tidak ditemukan, coba cari yang mirip atau batalkan
            // Untuk saat ini kita anggap harus persis atau kembalikan error
             $this->session->set_flashdata('error', 'Obat "' . $nama_obat . '" tidak ditemukan. Pastikan nama obat benar.');
             redirect('karyawan/pembelian/create');
             return;
        }

        $data = [
            'tanggal'      => $this->input->post('tanggal'),
            'id_obat'      => $obat->id_obat, // Pakai ID yang ditemukan
            'id_pemasok'   => $this->input->post('id_pemasok'),
            'jumlah'       => $jumlah,
            'harga_satuan' => $harga,
            'total_harga'  => $jumlah * $harga,
            'keterangan'   => $this->input->post('keterangan')
        ];

        $this->Pembelian_model->insertPembelian($data);

        // update stok
        $this->Obat_model->increase_stock($data['id_obat'], $jumlah);

        $this->session->set_flashdata('success', 'Transaksi pembelian berhasil disimpan!');
        redirect('karyawan/pembelian');
    }
}