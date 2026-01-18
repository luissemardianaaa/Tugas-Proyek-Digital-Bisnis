<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('session');
        // Cek Login & Role
        if (!$this->session->userdata('logged_in')) {
             redirect('auth');
        }

        $role = $this->session->userdata('role');
        if ($role !== 'karyawan' && $role !== 'admin') {
             redirect('auth');
        }
        $this->load->model('Laporan_model');
        $this->load->model('Jamkerja_model');
    }

    public function index() {
        $bulan = date('m');
        $tahun = date('Y');

        $data['penjualan'] = $this->Laporan_model->penjualan($bulan, $tahun);
        $data['pembelian'] = $this->Laporan_model->pembelian($bulan, $tahun);
        $data['rekap']     = $this->Laporan_model->rekap_bulanan($bulan, $tahun);
        $data['terlaris']  = $this->Laporan_model->obat_terlaris();
        $data['jumlah_transaksi'] = $this->Laporan_model->jumlah_transaksi($bulan, $tahun);


        // 🔴 INI YANG BIKIN ERROR SEBELUMNYA
        // pastikan omzet adalah 1 object (row), bukan result
        $data['omzet'] = $this->Laporan_model->total_omzet($bulan, $tahun);

        // 🔐 pengaman kalau query kosong
        if (!$data['omzet']) {
            $data['omzet'] = (object)['total' => 0];
        }

        // Ambil data jam kerja jika admin
        $data['jam_kerja'] = ($this->session->userdata('role') == 'admin') ? $this->Jamkerja_model->get_all() : [];

        $this->load->view('laporan/index', $data);
    }


public function export_pdf()
{
    $this->load->library('pdf');
    $this->load->model('Laporan_model');

    $data['laporan'] = $this->Laporan_model->getLaporanPembelian();

    // === INI KUNCI UTAMA ===
    $path = FCPATH.'assets/img/ttd_keylia.png';

    if (file_exists($path)) {
        $imageData = base64_encode(file_get_contents($path));
        $data['ttd'] = 'data:image/png;base64,'.$imageData;
    } else {
        $data['ttd'] = '';
    }

    $html = $this->load->view('laporan/export_pdf', $data, true);

    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'portrait');
    $this->pdf->render();

    $this->pdf->stream("Laporan_Pembelian.pdf", [
        "Attachment" => true
    ]);
}
public function export_excel()
{
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Laporan_Pembelian.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data['laporan'] = $this->Laporan_model->getLaporanPembelian();
    $this->load->view('laporan/export_excel', $data);
}

public function export_penjualan_pdf()
{
    $this->load->library('pdf');
    $this->load->model('Laporan_model');

    $data['laporan'] = $this->Laporan_model->getLaporanPenjualan();

    $path = FCPATH.'assets/img/ttd_keylia.png';
    if (file_exists($path)) {
        $imageData = base64_encode(file_get_contents($path));
        $data['ttd'] = 'data:image/png;base64,'.$imageData;
    } else {
        $data['ttd'] = '';
    }

    $html = $this->load->view('laporan/export_penjualan_pdf', $data, true);

    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'landscape'); // Landscape for more columns
    $this->pdf->render();

    $this->pdf->stream("Laporan_Penjualan.pdf", [
        "Attachment" => true
    ]);
}

public function export_penjualan_excel()
{
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Laporan_Penjualan.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $data['laporan'] = $this->Laporan_model->getLaporanPenjualan();
    $this->load->view('laporan/export_penjualan_excel', $data);
}

    public function export_combined($format = 'pdf')
    {
        $this->load->library('zip');
        $this->load->model('Laporan_model');

        $filename_zip = "Laporan_Lengkap_" . date('d-m-Y') . ".zip";

        if ($format === 'pdf') {
            // 1. Generate PDF Pembelian
            $pdf_pembelian = $this->_generate_pembelian_pdf_content();
            $this->zip->add_data('Laporan_Pembelian.pdf', $pdf_pembelian);

            // 2. Generate PDF Penjualan
            $pdf_penjualan = $this->_generate_penjualan_pdf_content();
            $this->zip->add_data('Laporan_Penjualan.pdf', $pdf_penjualan);

        } else {
            // 1. Generate Excel Pembelian
            $xls_pembelian = $this->_generate_pembelian_excel_content();
            $this->zip->add_data('Laporan_Pembelian.xls', $xls_pembelian);

            // 2. Generate Excel Penjualan
            $xls_penjualan = $this->_generate_penjualan_excel_content();
            $this->zip->add_data('Laporan_Penjualan.xls', $xls_penjualan);
        }

        $this->zip->download($filename_zip);
    }

    private function _generate_pembelian_pdf_content()
    {
        $this->load->library('pdf');
        $data['laporan'] = $this->Laporan_model->getLaporanPembelian();
        $data['ttd'] = $this->_get_ttd_image();
        
        // Load view into HTML
        $html = $this->load->view('laporan/export_pdf', $data, true);
        
        // Reset PDF instance if needed (or create new one if library doesn't handle reset well)
        // Since we are loading library 'pdf', CI usually returns the same instance. 
        // Dompdf safe way:
        $current_pdf = new \Dompdf\Dompdf(new \Dompdf\Options(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]));
        $current_pdf->loadHtml($html);
        $current_pdf->setPaper('A4', 'portrait');
        $current_pdf->render();
        return $current_pdf->output();
    }

    private function _generate_penjualan_pdf_content()
    {
        $this->load->library('pdf');
        $data['laporan'] = $this->Laporan_model->getLaporanPenjualan();
        $data['ttd'] = $this->_get_ttd_image();

        $html = $this->load->view('laporan/export_penjualan_pdf', $data, true);
        
        $current_pdf = new \Dompdf\Dompdf(new \Dompdf\Options(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]));
        $current_pdf->loadHtml($html);
        $current_pdf->setPaper('A4', 'landscape');
        $current_pdf->render();
        return $current_pdf->output();
    }

    private function _generate_pembelian_excel_content()
    {
        $data['laporan'] = $this->Laporan_model->getLaporanPembelian();
        return $this->load->view('laporan/export_excel', $data, true);
    }

    private function _generate_penjualan_excel_content()
    {
        $data['laporan'] = $this->Laporan_model->getLaporanPenjualan();
        return $this->load->view('laporan/export_penjualan_excel', $data, true);
    }

    private function _get_ttd_image()
    {
        $path = FCPATH.'assets/img/ttd_keylia.png';
        if (file_exists($path)) {
            $imageData = base64_encode(file_get_contents($path));
            return 'data:image/png;base64,'.$imageData;
        }
        return '';
    }
}