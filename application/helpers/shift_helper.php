<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Check if user is outside their working hours
 * 
 * @param object $CI CodeIgniter instance
 * @param int $id_user User ID to check
 * @return bool True if outside working hours, false if within working hours
 */
function is_outside_working_hours($CI, $id_user = null) {
    // If no user ID provided, get from session
    if ($id_user === null) {
        $id_user = $CI->session->userdata('id_user');
    }
    
    // Admin always has access
    if ($CI->session->userdata('role') == 'admin') {
        return false;
    }
    
    // Shift definitions
    $shift_defs = [
        'pagi'  => ['start' => '07:00', 'end' => '13:00'],
        'siang' => ['start' => '12:30', 'end' => '17:30'],
        'malam' => ['start' => '17:00', 'end' => '23:00']
    ];
    
    // Priority 1: Check shift for today
    $shift_today = $CI->db->get_where('jam_kerja', [
        'id_user' => $id_user,
        'tanggal' => date('Y-m-d')
    ])->row();
    
    // Priority 2: Check shift history (last recorded)
    $shift_history = $CI->db->select('*')
        ->from('jam_kerja')
        ->where('id_user', $id_user)
        ->order_by('tanggal', 'DESC')
        ->limit(1)
        ->get()
        ->row();
    
    // Priority 3: Check permanent shift from user profile
    $user_profile = $CI->db->get_where('users', ['id_user' => $id_user])->row();
    
    // Determine final shift
    $jam_masuk = '--:--';
    $jam_pulang = '--:--';
    $shift_name = 'Belum Diatur';
    
    if ($shift_today) {
        $jam_masuk = $shift_today->jam_masuk;
        $jam_pulang = $shift_today->jam_pulang;
        $shift_name = $shift_today->keterangan;
    } elseif ($shift_history) {
        $jam_masuk = $shift_history->jam_masuk;
        $jam_pulang = $shift_history->jam_pulang;
        $shift_name = $shift_history->keterangan;
    } elseif ($user_profile && !empty($user_profile->shift)) {
        $key = strtolower($user_profile->shift);
        $shift_name = ucfirst($key);
        if (isset($shift_defs[$key])) {
            $jam_masuk = $shift_defs[$key]['start'];
            $jam_pulang = $shift_defs[$key]['end'];
        }
    }
    
    // Fix jam_pulang if empty but we know the shift name
    if (empty($jam_pulang) || $jam_pulang == '--:--') {
        $key = strtolower($shift_name);
        $def_key = null;
        if (strpos($key, 'pagi') !== false) $def_key = 'pagi';
        elseif (strpos($key, 'siang') !== false) $def_key = 'siang';
        elseif (strpos($key, 'malam') !== false) $def_key = 'malam';
        
        if ($def_key && isset($shift_defs[$def_key])) {
            $jam_pulang = $shift_defs[$def_key]['end'];
        }
    }
    
    // Check if current time is outside working hours
    if ($jam_masuk != '--:--' && $jam_pulang != '--:--') {
        $now = date('H:i:s');
        $start_time = $jam_masuk . ":00";
        $end_time = $jam_pulang . ":00";
        
        // If current time is before start or after end, user is outside working hours
        if ($now < $start_time || $now > $end_time) {
            return true;
        }
        return false;
    }
    
    // If no shift is set at all, consider as outside working hours
    return true;
}

/**
 * Get current shift info for a user
 * 
 * @param object $CI CodeIgniter instance
 * @param int $id_user User ID
 * @return object Shift info with keterangan, jam_masuk, jam_pulang
 */
function get_user_shift_info($CI, $id_user = null) {
    if ($id_user === null) {
        $id_user = $CI->session->userdata('id_user');
    }
    
    $shift_defs = [
        'pagi'  => ['start' => '07:00', 'end' => '13:00'],
        'siang' => ['start' => '12:30', 'end' => '17:30'],
        'malam' => ['start' => '17:00', 'end' => '23:00']
    ];
    
    $shift_today = $CI->db->get_where('jam_kerja', [
        'id_user' => $id_user,
        'tanggal' => date('Y-m-d')
    ])->row();
    
    $shift_history = $CI->db->select('*')
        ->from('jam_kerja')
        ->where('id_user', $id_user)
        ->order_by('tanggal', 'DESC')
        ->limit(1)
        ->get()
        ->row();
    
    $user_profile = $CI->db->get_where('users', ['id_user' => $id_user])->row();
    
    $result = (object)[
        'keterangan' => 'Belum Diatur',
        'jam_masuk' => '--:--',
        'jam_pulang' => '--:--'
    ];
    
    if ($shift_today) {
        $result->keterangan = $shift_today->keterangan;
        $result->jam_masuk = $shift_today->jam_masuk;
        $result->jam_pulang = $shift_today->jam_pulang;
    } elseif ($shift_history) {
        $result->keterangan = $shift_history->keterangan;
        $result->jam_masuk = $shift_history->jam_masuk;
        $result->jam_pulang = $shift_history->jam_pulang;
    } elseif ($user_profile && !empty($user_profile->shift)) {
        $key = strtolower($user_profile->shift);
        $result->keterangan = ucfirst($key);
        if (isset($shift_defs[$key])) {
            $result->jam_masuk = $shift_defs[$key]['start'];
            $result->jam_pulang = $shift_defs[$key]['end'];
        }
    }
    
    return $result;
}
