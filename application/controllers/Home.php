<?php
class Home extends CI_Controller {


public function __construct() {
parent::__construct();
date_default_timezone_set('Asia/Jakarta');
$this->load->model('Obat_model');
$this->load->helper(['form','url']);
$this->load->library('upload');
}


    public function index() {
        $search = $this->input->get('search');
        $satuan = $this->input->get('satuan');
        $jenis  = $this->input->get('jenis');
        
        $data['rekomendasi'] = null;
        $data['search_keyword'] = $search;
        $data['selected_category'] = $satuan ?? $jenis; // Tampilkan jenis sebagai kategori terpilih jika ada

        if (!empty($search)) {
            // Cek dulu apakah user mencari gejala atau nama obat
            $data['rekomendasi'] = $this->Obat_model->get_rekomendasi_ai($search);
            
            // Gabungkan hasil rekomendasi yang tersedia dengan pencarian nama biasa
            if (isset($data['rekomendasi']['tersedia_di_apotek']) && !empty($data['rekomendasi']['tersedia_di_apotek'])) {
                 $data['obat'] = $data['rekomendasi']['tersedia_di_apotek'];
            } else {
                 // Jika tidak ada rekomendasi, cari berdasarkan nama biasa
                 $data['obat'] = $this->Obat_model->search_by_name($search);
            }
        } elseif (!empty($jenis)) {
            // Filter berdasarkan JENIS (Dosis/Golongan)
            $data['obat'] = $this->Obat_model->get_by_jenis($jenis);
        } elseif (!empty($satuan)) {
            // Filter berdasarkan SATUAN (Tablet/Kapsul/dll)
            $data['obat'] = $this->Obat_model->get_by_satuan($satuan);
        } else {
            $data['obat'] = $this->Obat_model->get_all_obat();
        }
        
        $this->load->view('pelanggan/home', $data);
    }


public function create() {
$this->load->view('obat/create');
}


public function store() {
$config['upload_path'] = './uploads/obat/';
$config['allowed_types'] = 'jpg|png|jpeg';
$config['max_size'] = 2048;
$config['encrypt_name'] = TRUE;


$this->upload->initialize($config);


if (!$this->upload->do_upload('gambar')) {
echo $this->upload->display_errors();
return;
}


$upload = $this->upload->data();


$data = [
'kode_obat' => $this->input->post('kode_obat'),
'nama_obat' => $this->input->post('nama_obat'),
'jenis' => $this->input->post('jenis'),
'satuan' => $this->input->post('satuan'),
'harga' => $this->input->post('harga'),
'stok' => $this->input->post('stok'),
'stok_minimum' => $this->input->post('stok_minimum'),
'deskripsi' => $this->input->post('deskripsi'),
'gambar' => $upload['file_name'],
'created_at' => date('Y-m-d H:i:s')
];


$this->Obat_model->insert($data);
redirect('obat');
}
}