<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Midtrans Configuration
|--------------------------------------------------------------------------
|
| Configuration for Midtrans Payment Gateway
| Get your API keys from: https://dashboard.midtrans.com/settings/config_info
|
*/

// Server Key (RAHASIA - Jangan expose ke client!)
$config['midtrans_server_key'] = getenv('MIDTRANS_SERVER_KEY') ?: 'YOUR_SERVER_KEY_HERE';

// Client Key (Aman untuk digunakan di frontend)
$config['midtrans_client_key'] = getenv('MIDTRANS_CLIENT_KEY') ?: 'YOUR_CLIENT_KEY_HERE';

// Environment: false = Sandbox (Testing), true = Production
$config['midtrans_is_production'] = false;

// Enable sanitization (recommended)
$config['midtrans_is_sanitized'] = true;

// Enable 3D Secure (recommended for credit card)
$config['midtrans_is_3ds'] = true;

// Snap API URL
$config['midtrans_snap_url'] = 'https://app.sandbox.midtrans.com/snap/snap.js';
// For production: https://app.midtrans.com/snap/snap.js
