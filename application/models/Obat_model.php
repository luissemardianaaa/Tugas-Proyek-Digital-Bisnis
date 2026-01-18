<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat_model extends CI_Model {

    public function get_all_obat() {
        return $this->db
            ->order_by('nama_obat', 'ASC')
            ->get('obat')
            ->result();
    }

    public function get_by_id($id) {
        return $this->db
            ->where('id_obat', $id)
            ->get('obat')
            ->row();
    }

    public function get_by_kode($kode) {
        return $this->db
            ->where('kode_obat', $kode)
            ->get('obat')
            ->row();
    }
    public function increase_stock($id_obat, $jumlah)
    {
        return $this->db
            ->set('stok', 'stok + ' . (int)$jumlah, false)
            ->where('id_obat', $id_obat)
            ->update('obat');
    }

    public function reduce_stock($id_obat, $jumlah)
    {
        return $this->db
            ->set('stok', 'stok - ' . (int)$jumlah, false)
            ->where('id_obat', $id_obat)
            ->update('obat');
    }

   public function create($data)
{
    $this->db->insert('obat', $data);
    return $this->db->insert_id(); 
}
    public function update($id, $data) {
        return $this->db
            ->where('id_obat', $id)
            ->update('obat', $data);
    }

    public function update_stock($id, $new_stock) {
        return $this->db
            ->where('id_obat', $id)
            ->update('obat', ['stok' => $new_stock]);
    }

    /**
     * Check if a drug is being used in sales transactions
     * @param int $id Drug ID
     * @return bool True if drug is used in sales, false otherwise
     */
    public function is_used_in_sales($id) {
        $count = $this->db
            ->where('id_obat', $id)
            ->count_all_results('detail_penjualan');
        return $count > 0;
    }

    /**
     * Check if a drug is being used in purchase transactions
     * @param int $id Drug ID
     * @return bool True if drug is used in purchases, false otherwise
     */
    public function is_used_in_purchases($id) {
        // Check if table exists first to avoid errors
        if (!$this->db->table_exists('detail_pembelian')) {
            return false; // Table doesn't exist, so drug is not used
        }
        
        $count = $this->db
            ->where('id_obat', $id)
            ->count_all_results('detail_pembelian');
        return $count > 0;
    }

    public function delete($id) {
        return $this->db
            ->where('id_obat', $id)
            ->delete('obat');
    }

    /**
     * Toggle drug status between 'aktif' and 'nonaktif'
     * @param int $id Drug ID
     * @param string $status New status ('aktif' or 'nonaktif')
     * @return bool
     */
    public function set_status($id, $status) {
        return $this->db
            ->where('id_obat', $id)
            ->update('obat', ['status' => $status]);
    }

    /**
     * Get current drug status
     * @param int $id Drug ID
     * @return string|null Status or null if not found
     */
    public function get_status($id) {
        $obat = $this->get_by_id($id);
        return $obat ? (isset($obat->status) ? $obat->status : 'aktif') : null;
    }

    /**
     * Get only active drugs (for customer-facing pages)
     * @return array
     */
    public function get_active_obat() {
        return $this->db
            ->where('status', 'aktif')
            ->order_by('nama_obat', 'ASC')
            ->get('obat')
            ->result();
    }

    public function search($keyword) {
        return $this->db
            ->group_start()
                ->like('nama_obat', $keyword)
                ->or_like('kode_obat', $keyword)
                ->or_like('jenis', $keyword)
            ->group_end()
            ->order_by('nama_obat', 'ASC')
            ->get('obat')
            ->result();
    }

    public function count_all() {
        return $this->db->count_all('obat');
    }

        public function get_obat_kritis() {
            return $this->db
                ->where('stok <=', 10)
                ->where('stok >', 0)
                ->get('obat')
                ->result();
        }

    public function get_obat_habis() {
        return $this->db
            ->where('stok', 0)
            ->get('obat')
            ->result();
    }
    public function get_obat_pelanggan() {
    return $this->db
        ->select('nama_obat, deskripsi, harga, jenis, satuan, stok')
        ->order_by('nama_obat', 'ASC')
        ->get('obat')
        ->result();
}
public function generate_kode_obat()
{
    // Format: OBT-0001, OBT-0002, dst
    $this->db->select('RIGHT(kode_obat,4) as kode', false);
    $this->db->order_by('kode_obat', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('obat');

    if ($query->num_rows() > 0) {
        $kode = intval($query->row()->kode) + 1;
    } else {
        $kode = 1;
    }

    return 'OBT-' . str_pad($kode, 4, '0', STR_PAD_LEFT);
}
    public function get_by_satuan($satuan)
    {
        return $this->db
            ->where('satuan', $satuan)
            ->order_by('nama_obat', 'ASC')
            ->get('obat')
            ->result();
    }

    public function get_by_jenis($jenis)
    {
        // Filter fleksibel menggunakan LIKE karena input user bisa beragam
        return $this->db
            ->like('jenis', $jenis) // Antibiotik, Vitamin, dll
            ->order_by('nama_obat', 'ASC')
            ->get('obat')
            ->result();
    }
public function cari_obat_rekomendasi($keyword)
{
    return $this->db
        ->from('obat')
        ->group_start()
            ->like('nama_obat', $keyword)
            ->or_like('jenis', $keyword)
            ->or_like('deskripsi', $keyword)
        ->group_end()
        ->order_by('stok', 'ASC') // ⭐ PALING PENTING
        ->get()
        ->result();
}
public function rekomendasi_obat($keyword)
{
    return $this->db
        ->group_start()
            ->like('nama_obat', $keyword)
            ->or_like('jenis', $keyword)
            ->or_like('deskripsi', $keyword)
        ->group_end()
        ->order_by('stok', 'ASC') // 🔥 paling sedikit dulu
        ->limit(4)
        ->get('obat')
        ->result();
}
public function search_by_name($keyword)
{
    return $this->db
        ->like('nama_obat', $keyword)
        ->order_by('nama_obat', 'ASC')
        ->get('obat')
        ->result();
}
    public function get_rekomendasi_ai($keluhan)
    {
        // Load konfigurasi Hugging Face
        $this->config->load('huggingface');
        $HF_TOKEN = $this->config->item('hf_access_token');
        $HF_URL = $this->config->item('hf_model_url');
        $HF_TIMEOUT = $this->config->item('hf_timeout');

        // Buat prompt untuk AI - minta rekomendasi obat UMUM berdasarkan keluhan
        $prompt = "<s>[INST] Kamu adalah apoteker profesional di Indonesia. 
Berdasarkan keluhan: \"$keluhan\"

Berikan rekomendasi 5 nama obat yang umum tersedia di apotek Indonesia untuk mengatasi keluhan tersebut.

Format jawaban HARUS seperti ini (gunakan format yang sama persis):
OBAT_1: [Nama Obat] | [Kegunaan singkat]
OBAT_2: [Nama Obat] | [Kegunaan singkat]
OBAT_3: [Nama Obat] | [Kegunaan singkat]
OBAT_4: [Nama Obat] | [Kegunaan singkat]
OBAT_5: [Nama Obat] | [Kegunaan singkat]

Contoh format:
OBAT_1: Paracetamol | Meredakan demam dan nyeri ringan
OBAT_2: Ibuprofen | Anti inflamasi dan pereda nyeri

Jawab dalam bahasa Indonesia. [/INST]</s>";

        $payload = json_encode([
            "inputs" => $prompt,
            "parameters" => [
                "max_new_tokens" => 400,
                "temperature" => 0.5,
                "return_full_text" => false
            ]
        ]);

        $ch = curl_init($HF_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $HF_TIMEOUT,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $HF_TOKEN,
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => $payload
        ]);

        $res = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Handle errors
        if ($curl_error) {
            log_message('error', 'HuggingFace API Error: ' . $curl_error);
            return $this->get_rekomendasi_fallback($keluhan);
        }

        $json = json_decode($res, true);

        // Jika model loading atau error, coba fallback
        if (isset($json['error'])) {
            log_message('error', 'HuggingFace API Response Error: ' . $json['error']);
            return $this->get_rekomendasi_fallback($keluhan);
        }

        // Parse response
        $ai_text = '';
        if (isset($json[0]['generated_text'])) {
            $ai_text = $json[0]['generated_text'];
        } elseif (isset($json['generated_text'])) {
            $ai_text = $json['generated_text'];
        }

        if (empty($ai_text)) {
            return $this->get_rekomendasi_fallback($keluhan);
        }

        // Parse AI response dan cek ketersediaan di database
        return $this->parse_dan_cek_ketersediaan($ai_text, $keluhan);
    }

    // Parse response AI dan cek ketersediaan obat di database
    private function parse_dan_cek_ketersediaan($ai_text, $keluhan)
    {
        $hasil = [
            'keluhan' => $keluhan,
            'rekomendasi' => [],
            'tersedia_di_apotek' => []
        ];

        // Parse format OBAT_N: Nama | Kegunaan
        preg_match_all('/OBAT_\d+:\s*(.+?)\s*\|\s*(.+?)(?=OBAT_\d+:|$)/s', $ai_text, $matches, PREG_SET_ORDER);
        
        if (empty($matches)) {
            // Coba parse format alternatif (numbered list)
            preg_match_all('/\d+[\.\)]\s*\*?\*?([^|\n\*]+)\*?\*?\s*(?:[-–:|]\s*)?([^\n]+)?/i', $ai_text, $matches, PREG_SET_ORDER);
        }

        foreach ($matches as $match) {
            $nama_obat = trim($match[1] ?? '');
            $kegunaan = trim($match[2] ?? 'Sesuai indikasi');
            
            // Bersihkan nama obat dari karakter khusus
            $nama_obat = preg_replace('/[*\[\]()]/', '', $nama_obat);
            $nama_obat = trim($nama_obat);
            
            if (empty($nama_obat) || strlen($nama_obat) < 3) continue;

            // Cek ketersediaan di database
            $obat_db = $this->cek_obat_di_database($nama_obat);
            
            $item = [
                'nama' => $nama_obat,
                'kegunaan' => $kegunaan,
                'tersedia' => !empty($obat_db),
                'data_db' => $obat_db
            ];
            
            $hasil['rekomendasi'][] = $item;
            
            if (!empty($obat_db)) {
                $hasil['tersedia_di_apotek'][] = $obat_db;
            }
        }

        return $hasil;
    }

    // Cek apakah obat ada di database
    private function cek_obat_di_database($nama_obat)
    {
        // Cari obat yang mirip di database
        $this->db->select('*');
        $this->db->from('obat');
        $this->db->group_start();
            $this->db->like('nama_obat', $nama_obat);
            // Coba juga cari dengan kata pertama
            $kata_pertama = explode(' ', $nama_obat)[0];
            if (strlen($kata_pertama) >= 3) {
                $this->db->or_like('nama_obat', $kata_pertama);
            }
        $this->db->group_end();
        $this->db->where('stok >', 0);
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        return $result;
    }

    // Fallback jika API gagal - menggunakan obat umum
    public function get_rekomendasi_fallback($keluhan)
    {
        // Daftar obat umum berdasarkan kategori keluhan
        $obat_umum = [
            'batuk' => [
                ['nama' => 'OBH Combi', 'kegunaan' => 'Meredakan batuk berdahak dan pilek'],
                ['nama' => 'Vicks Formula 44', 'kegunaan' => 'Meredakan batuk kering'],
                ['nama' => 'Woods', 'kegunaan' => 'Ekspektoran untuk batuk berdahak'],
                ['nama' => 'Bisolvon', 'kegunaan' => 'Mengencerkan dahak'],
                ['nama' => 'Siladex', 'kegunaan' => 'Meredakan batuk dan pilek']
            ],
            'demam' => [
                ['nama' => 'Paracetamol', 'kegunaan' => 'Menurunkan demam dan meredakan nyeri'],
                ['nama' => 'Sanmol', 'kegunaan' => 'Penurun panas untuk anak dan dewasa'],
                ['nama' => 'Bodrex', 'kegunaan' => 'Meredakan demam, sakit kepala, dan nyeri'],
                ['nama' => 'Panadol', 'kegunaan' => 'Pereda demam dan nyeri'],
                ['nama' => 'Tempra', 'kegunaan' => 'Penurun panas untuk anak']
            ],
            'sakit kepala' => [
                ['nama' => 'Paracetamol', 'kegunaan' => 'Meredakan sakit kepala ringan'],
                ['nama' => 'Bodrex', 'kegunaan' => 'Mengatasi sakit kepala dan pegal'],
                ['nama' => 'Paramex', 'kegunaan' => 'Pereda sakit kepala cepat'],
                ['nama' => 'Oskadon', 'kegunaan' => 'Meredakan sakit kepala dan nyeri'],
                ['nama' => 'Ibuprofen', 'kegunaan' => 'Anti nyeri dan inflamasi']
            ],
            'flu' => [
                ['nama' => 'Decolgen', 'kegunaan' => 'Meredakan gejala flu dan pilek'],
                ['nama' => 'Neozep', 'kegunaan' => 'Mengatasi flu dan hidung tersumbat'],
                ['nama' => 'Mixagrip', 'kegunaan' => 'Meredakan gejala flu lengkap'],
                ['nama' => 'Procold', 'kegunaan' => 'Mengatasi flu dan demam'],
                ['nama' => 'Ultraflu', 'kegunaan' => 'Meredakan gejala flu berat']
            ],
            'maag' => [
                ['nama' => 'Promag', 'kegunaan' => 'Menetralkan asam lambung'],
                ['nama' => 'Mylanta', 'kegunaan' => 'Mengatasi maag dan kembung'],
                ['nama' => 'Polycrol', 'kegunaan' => 'Meredakan nyeri lambung'],
                ['nama' => 'Antasida', 'kegunaan' => 'Menetralkan asam lambung berlebih'],
                ['nama' => 'Omeprazole', 'kegunaan' => 'Mengurangi produksi asam lambung']
            ],
            'diare' => [
                ['nama' => 'Entrostop', 'kegunaan' => 'Menghentikan diare'],
                ['nama' => 'New Diatabs', 'kegunaan' => 'Mengatasi diare akut'],
                ['nama' => 'Oralit', 'kegunaan' => 'Mengganti cairan tubuh yang hilang'],
                ['nama' => 'Neo Kaolana', 'kegunaan' => 'Meredakan diare'],
                ['nama' => 'Loperamide', 'kegunaan' => 'Menghentikan diare']
            ],
            'alergi' => [
                ['nama' => 'CTM', 'kegunaan' => 'Meredakan gejala alergi ringan'],
                ['nama' => 'Cetirizine', 'kegunaan' => 'Antihistamin untuk alergi'],
                ['nama' => 'Incidal', 'kegunaan' => 'Mengatasi gatal dan alergi'],
                ['nama' => 'Loratadine', 'kegunaan' => 'Obat alergi tanpa kantuk'],
                ['nama' => 'Dexamethasone', 'kegunaan' => 'Mengatasi peradangan dan alergi']
            ],
            'asma' => [
                ['nama' => 'Salbutamol', 'kegunaan' => 'Melegakan pernapasan saat asma'],
                ['nama' => 'Teosal', 'kegunaan' => 'Meredakan sesak napas'],
                ['nama' => 'Ventolin', 'kegunaan' => 'Inhaler untuk serangan asma'],
                ['nama' => 'Lasal', 'kegunaan' => 'Mengencerkan dahak dan asma'],
                ['nama' => 'Neo Napacin', 'kegunaan' => 'Meringankan sesak napas']
            ],
            'gatal' => [
                ['nama' => 'Salep 88', 'kegunaan' => 'Mengatasi jamur dan gatal'],
                ['nama' => 'Kalpanax', 'kegunaan' => 'Obat panu dan jamur kulit'],
                ['nama' => 'Bedak Salicyl', 'kegunaan' => 'Mengurangi gatal biang keringat'],
                ['nama' => 'Hydrocortisone', 'kegunaan' => 'Krim anti radang dan gatal'],
                ['nama' => 'Fungider', 'kegunaan' => 'Mengatasi infeksi jamur']
            ],
            'radang' => [
                ['nama' => 'Methylprednisolone', 'kegunaan' => 'Anti peradangan kuat'],
                ['nama' => 'Dexamethasone', 'kegunaan' => 'Mengatasi radang dan alergi'],
                ['nama' => 'Voltaren', 'kegunaan' => 'Meredakan nyeri dan radang'],
                ['nama' => 'Cataflam', 'kegunaan' => 'Pereda nyeri radang gigi/sendi'],
                ['nama' => 'Amoxsan', 'kegunaan' => 'Antibiotik (harus resep dokter)']
            ],
            'nyeri' => [
                ['nama' => 'Asam Mefenamat', 'kegunaan' => 'Meredakan nyeri gigi dan haid'],
                ['nama' => 'Neuralgin', 'kegunaan' => 'Meredakan nyeri saraf dan otot'],
                ['nama' => 'Paracetamol', 'kegunaan' => 'Pereda nyeri ringan'],
                ['nama' => 'Ibuprofen', 'kegunaan' => 'Pereda nyeri dan radang'],
                ['nama' => 'Counterpain', 'kegunaan' => 'Krim pereda nyeri otot']
            ],
            'vitamin' => [
                ['nama' => 'Enervon C', 'kegunaan' => 'Menjaga daya tahan tubuh'],
                ['nama' => 'Imboost', 'kegunaan' => 'Meningkatkan sistem imun'],
                ['nama' => 'Neurobion', 'kegunaan' => 'Vitamin saraf'],
                ['nama' => 'Sangobion', 'kegunaan' => 'Penambah darah'],
                ['nama' => 'CDR', 'kegunaan' => 'Kalsium dan vitamin tulang']
            ],
            'sariawan' => [
                ['nama' => 'Enkasari', 'kegunaan' => 'Obat kumur herbal sariawan'],
                ['nama' => 'Betadine Gargle', 'kegunaan' => 'Obat kumur antiseptik'],
                ['nama' => 'Kuldon', 'kegunaan' => 'Tablet herbal sariawan'],
                ['nama' => 'Efisol', 'kegunaan' => 'Tablet hisap pereda nyeri mulut'],
                ['nama' => 'Kenalog', 'kegunaan' => 'Salep sariawan']
            ],
            'asam lambung' => [
                ['nama' => 'Promag', 'kegunaan' => 'Menetralkan asam lambung berlebih'],
                ['nama' => 'Mylanta', 'kegunaan' => 'Mengatasi kembung dan asam lambung'],
                ['nama' => 'Omeprazole', 'kegunaan' => 'Mengurangi produksi asam lambung'],
                ['nama' => 'Lansoprazole', 'kegunaan' => 'Mengobati GERD dan tukak lambung'],
                ['nama' => 'Ranitidine', 'kegunaan' => 'Mengurangi asam lambung'],
                ['nama' => 'Antasida Doen', 'kegunaan' => 'Menetralkan asam lambung'],
                ['nama' => 'Polysilane', 'kegunaan' => 'Mengatasi kembung dan asam lambung']
            ],
            'sakit perut' => [
                ['nama' => 'Buscopan', 'kegunaan' => 'Meredakan kram dan nyeri perut'],
                ['nama' => 'Dulcolax', 'kegunaan' => 'Mengatasi sembelit/konstipasi'],
                ['nama' => 'Diapet', 'kegunaan' => 'Mengatasi diare dan sakit perut'],
                ['nama' => 'Norit', 'kegunaan' => 'Menyerap racun dalam perut'],
                ['nama' => 'Entrostop', 'kegunaan' => 'Menghentikan diare'],
                ['nama' => 'Promag', 'kegunaan' => 'Meredakan nyeri lambung'],
                ['nama' => 'Disflatyl', 'kegunaan' => 'Mengatasi perut kembung']
            ],
            'haid' => [
                ['nama' => 'Feminax', 'kegunaan' => 'Meredakan nyeri haid/menstruasi'],
                ['nama' => 'Kiranti', 'kegunaan' => 'Minuman herbal pereda nyeri haid'],
                ['nama' => 'Asam Mefenamat', 'kegunaan' => 'Meredakan nyeri haid dan kram'],
                ['nama' => 'Paracetamol', 'kegunaan' => 'Pereda nyeri ringan saat haid'],
                ['nama' => 'Ibuprofen', 'kegunaan' => 'Anti nyeri dan anti inflamasi'],
                ['nama' => 'Sangobion', 'kegunaan' => 'Penambah darah untuk menstruasi berat'],
                ['nama' => 'Kunyit Asam', 'kegunaan' => 'Herbal tradisional pereda nyeri haid']
            ],
            'luka bakar' => [
                ['nama' => 'Bioplacenton', 'kegunaan' => 'Mempercepat penyembuhan luka bakar'],
                ['nama' => 'Burnazin', 'kegunaan' => 'Krim antibakteri untuk luka bakar'],
                ['nama' => 'Silver Sulfadiazine', 'kegunaan' => 'Mencegah infeksi luka bakar'],
                ['nama' => 'Betadine Salep', 'kegunaan' => 'Antiseptik untuk luka bakar ringan'],
                ['nama' => 'Mebo', 'kegunaan' => 'Salep luka bakar alami'],
                ['nama' => 'Kenalog Orabase', 'kegunaan' => 'Mempercepat penyembuhan luka'],
                ['nama' => 'Dermazin', 'kegunaan' => 'Krim antibakteri luka bakar']
            ],
            'nyeri sendi' => [
                ['nama' => 'Counterpain', 'kegunaan' => 'Krim pereda nyeri otot dan sendi'],
                ['nama' => 'Voltaren Gel', 'kegunaan' => 'Gel anti radang untuk nyeri sendi'],
                ['nama' => 'Salonpas', 'kegunaan' => 'Koyo pereda nyeri sendi'],
                ['nama' => 'Glucosamine', 'kegunaan' => 'Suplemen kesehatan sendi'],
                ['nama' => 'Ibuprofen', 'kegunaan' => 'Anti nyeri dan anti inflamasi'],
                ['nama' => 'Piroxicam', 'kegunaan' => 'Obat anti radang sendi'],
                ['nama' => 'Cataflam', 'kegunaan' => 'Pereda nyeri sendi dan radang']
            ],
            'menstruasi' => [
                ['nama' => 'Feminax', 'kegunaan' => 'Meredakan nyeri haid/menstruasi'],
                ['nama' => 'Kiranti', 'kegunaan' => 'Minuman herbal pereda nyeri haid'],
                ['nama' => 'Asam Mefenamat', 'kegunaan' => 'Meredakan nyeri haid dan kram'],
                ['nama' => 'Paracetamol', 'kegunaan' => 'Pereda nyeri ringan saat haid'],
                ['nama' => 'Ibuprofen', 'kegunaan' => 'Anti nyeri dan anti inflamasi']
            ],
            'kram perut' => [
                ['nama' => 'Buscopan', 'kegunaan' => 'Meredakan kram dan nyeri perut'],
                ['nama' => 'Feminax', 'kegunaan' => 'Meredakan kram saat haid'],
                ['nama' => 'Asam Mefenamat', 'kegunaan' => 'Meredakan kram dan nyeri'],
                ['nama' => 'Promag', 'kegunaan' => 'Meredakan nyeri lambung'],
                ['nama' => 'Disflatyl', 'kegunaan' => 'Mengatasi perut kembung']
            ],
            'lambung' => [
                ['nama' => 'Promag', 'kegunaan' => 'Menetralkan asam lambung'],
                ['nama' => 'Mylanta', 'kegunaan' => 'Mengatasi maag dan kembung'],
                ['nama' => 'Omeprazole', 'kegunaan' => 'Mengurangi produksi asam lambung'],
                ['nama' => 'Polysilane', 'kegunaan' => 'Mengatasi kembung dan lambung'],
                ['nama' => 'Antasida', 'kegunaan' => 'Menetralkan asam lambung berlebih']
            ],
            'pegal' => [
                ['nama' => 'Counterpain', 'kegunaan' => 'Krim pereda pegal dan nyeri otot'],
                ['nama' => 'Salonpas', 'kegunaan' => 'Koyo pereda pegal linu'],
                ['nama' => 'Neo Rheumacyl', 'kegunaan' => 'Obat pegal linu'],
                ['nama' => 'Oskadon', 'kegunaan' => 'Meredakan pegal dan nyeri'],
                ['nama' => 'Paramex', 'kegunaan' => 'Pereda nyeri dan pegal']
            ],
            'radang tenggorokan' => [
                ['nama' => 'FG Troches', 'kegunaan' => 'Tablet hisap untuk radang tenggorokan'],
                ['nama' => 'Strepsils', 'kegunaan' => 'Pelega tenggorokan dan antiseptik'],
                ['nama' => 'Degirol', 'kegunaan' => 'Tablet hisap antiseptik tenggorokan'],
                ['nama' => 'Betadine Gargle', 'kegunaan' => 'Obat kumur antiseptik tenggorokan'],
                ['nama' => 'SP Troches', 'kegunaan' => 'Tablet hisap radang tenggorokan'],
                ['nama' => 'Isodine Gargle', 'kegunaan' => 'Obat kumur untuk infeksi tenggorokan'],
                ['nama' => 'Amoxicillin', 'kegunaan' => 'Antibiotik untuk infeksi tenggorokan (harus resep dokter)']
            ],
            'sakit gigi' => [
                ['nama' => 'Cataflam', 'kegunaan' => 'Pereda nyeri gigi dan radang'],
                ['nama' => 'Ponstan', 'kegunaan' => 'Meredakan nyeri gigi dan bengkak'],
                ['nama' => 'Asam Mefenamat', 'kegunaan' => 'Pereda nyeri gigi'],
                ['nama' => 'Ibuprofen', 'kegunaan' => 'Anti nyeri dan anti inflamasi gigi'],
                ['nama' => 'Paracetamol', 'kegunaan' => 'Pereda nyeri gigi ringan'],
                ['nama' => 'Dentasol', 'kegunaan' => 'Obat tetes sakit gigi'],
                ['nama' => 'Clove Oil', 'kegunaan' => 'Minyak cengkeh pereda nyeri gigi']
            ],
            'asam urat' => [
                ['nama' => 'Allopurinol', 'kegunaan' => 'Menurunkan kadar asam urat darah'],
                ['nama' => 'Colchicine', 'kegunaan' => 'Mengatasi serangan asam urat akut'],
                ['nama' => 'Piroxicam', 'kegunaan' => 'Anti nyeri dan radang sendi'],
                ['nama' => 'Diclofenac', 'kegunaan' => 'Pereda nyeri asam urat'],
                ['nama' => 'Indomethacin', 'kegunaan' => 'Anti radang untuk asam urat'],
                ['nama' => 'Tempuyung', 'kegunaan' => 'Herbal untuk menurunkan asam urat'],
                ['nama' => 'Sidaguri', 'kegunaan' => 'Herbal tradisional asam urat']
            ],
            'masuk angin' => [
                ['nama' => 'Tolak Angin', 'kegunaan' => 'Herbal meredakan masuk angin'],
                ['nama' => 'Antangin', 'kegunaan' => 'Meredakan gejala masuk angin'],
                ['nama' => 'Bintang Toedjoe Masuk Angin', 'kegunaan' => 'Obat herbal masuk angin'],
                ['nama' => 'Cap Lang Minyak Kayu Putih', 'kegunaan' => 'Menghangatkan dan meredakan perut kembung'],
                ['nama' => 'Mixagrip', 'kegunaan' => 'Meredakan gejala flu dan masuk angin'],
                ['nama' => 'Fresh Care', 'kegunaan' => 'Minyak angin roll-on'],
                ['nama' => 'Minyak Telon', 'kegunaan' => 'Menghangatkan tubuh dan meredakan kembung']
            ],
            'diabetes' => [
                ['nama' => 'Metformin', 'kegunaan' => 'Mengontrol gula darah (resep dokter)'],
                ['nama' => 'Glibenclamide', 'kegunaan' => 'Menurunkan gula darah (resep dokter)'],
                ['nama' => 'Acarbose', 'kegunaan' => 'Menghambat penyerapan gula (resep dokter)'],
                ['nama' => 'Glimepiride', 'kegunaan' => 'Merangsang produksi insulin (resep dokter)'],
                ['nama' => 'Diabetasol', 'kegunaan' => 'Susu rendah gula untuk penderita diabetes'],
                ['nama' => 'Glucovance', 'kegunaan' => 'Kombinasi obat diabetes (resep dokter)'],
                ['nama' => 'Tropicana Slim', 'kegunaan' => 'Pemanis rendah kalori untuk diabetes']
            ]
        ];

        // Cari kategori yang cocok
        $keluhan_lower = strtolower($keluhan);
        $obat_list = [];
        
        foreach ($obat_umum as $kategori => $daftar) {
            if (strpos($keluhan_lower, $kategori) !== false) {
                $obat_list = array_merge($obat_list, $daftar);
            }
        }
        
        // Jika tidak ada yang cocok dari daftar statis, cari DINAMIS di database
        if (empty($obat_list)) {
            $db_search = $this->cari_obat_rekomendasi($keluhan);
            
            if (!empty($db_search)) {
                // Konversi hasil DB ke format array obat_list
                foreach ($db_search as $row) {
                    $obat_list[] = [
                        'nama' => $row->nama_obat,
                        'kegunaan' => substr($row->deskripsi, 0, 50) . '...' // Ambil deskripsi singkat
                    ];
                }
            } else {
                // Jika DB juga kosong, bari kasih default (misal vitamin/demam)
                // Atau biarkan kosong biar "Tidak ditemukan"
                $obat_list = $obat_umum['demam']; // Fallback terakhir
            }
        }

        // Format hasil dan cek ketersediaan
        $hasil = [
            'keluhan' => $keluhan,
            'rekomendasi' => [],
            'tersedia_di_apotek' => [],
            'is_fallback' => true
        ];

        foreach (array_slice($obat_list, 0, 5) as $obat) {
            $obat_db = $this->cek_obat_di_database($obat['nama']);
            
            $item = [
                'nama' => $obat['nama'],
                'kegunaan' => $obat['kegunaan'],
                'tersedia' => !empty($obat_db),
                'data_db' => $obat_db
            ];
            
            $hasil['rekomendasi'][] = $item;
            
            if (!empty($obat_db)) {
                $hasil['tersedia_di_apotek'][] = $obat_db;
            }
        }

        return $hasil;
    }
}
