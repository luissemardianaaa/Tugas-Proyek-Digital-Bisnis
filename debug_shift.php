<?php
// Load codeigniter framework
ob_start();
require_once 'index.php';
ob_end_clean();

$CI =& get_instance();

echo "=== DIAGNOSTIC START ===\n";
echo "Server Time: " . date('Y-m-d H:i:s') . "\n";
date_default_timezone_set('Asia/Jakarta');
echo "Jakarta Time: " . date('Y-m-d H:i:s') . "\n";

$hour = (int)date('H');
$today_str = ($hour < 6) ? date('Y-m-d', strtotime('-1 day')) : date('Y-m-d');
echo "Business Date: " . $today_str . "\n\n";

echo "=== USERS (Active) ===\n";
$users = $CI->db->get_where('users', ['role' => 'karyawan', 'status' => 'aktif'])->result();
foreach($users as $u) {
    echo "ID: {$u->id_user} | Name: {$u->nama} | Shift (Perm): " . ($u->shift ?? 'NULL') . "\n";
}

echo "\n=== JAM KERJA (Today: $today_str) ===\n";
$attendance = $CI->db->get_where('jam_kerja', ['tanggal' => $today_str])->result();
if(empty($attendance)) {
    echo "No attendance records found for today.\n";
} else {
    foreach($attendance as $a) {
        echo "ID Jam: {$a->id_jam} | ID User: {$a->id_user} | Masuk: {$a->jam_masuk} | Ket: {$a->keterangan}\n";
    }
}

echo "\n=== RECENT JAM KERJA ===\n";
$recent = $CI->db->order_by('tanggal', 'DESC')->limit(10)->get('jam_kerja')->result();
foreach($recent as $r) {
    echo "Date: {$r->tanggal} | ID User: {$r->id_user} | Ket: {$r->keterangan}\n";
}
