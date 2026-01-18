<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Midtrans Library for CodeIgniter
 * Wrapper untuk Midtrans Snap API
 */
class Midtrans_lib {
    
    protected $CI;
    protected $server_key;
    protected $client_key;
    protected $is_production;
    protected $api_url;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('midtrans');
        
        // Load configuration
        $this->server_key = $this->CI->config->item('midtrans_server_key');
        $this->client_key = $this->CI->config->item('midtrans_client_key');
        $this->is_production = $this->CI->config->item('midtrans_is_production');
        
        // Set API URL based on environment
        $this->api_url = $this->is_production 
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }
    
    /**
     * Get Client Key (untuk digunakan di frontend)
     */
    public function get_client_key() {
        return $this->client_key;
    }
    
    /**
     * Get Snap URL (untuk script tag)
     */
    public function get_snap_url() {
        return $this->is_production 
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }
    
    /**
     * Create Snap Token
     * 
     * @param array $params Transaction parameters
     * @return array Response with snap_token or error
     */
    public function create_snap_token($params) {
        $curl = curl_init();
        
        $auth = base64_encode($this->server_key . ':');
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Basic ' . $auth
            ],
        ]);
        
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        
        curl_close($curl);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $error
            ];
        }
        
        $result = json_decode($response, true);
        
        if ($http_code == 201 && isset($result['token'])) {
            return [
                'success' => true,
                'snap_token' => $result['token'],
                'redirect_url' => $result['redirect_url'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'message' => $result['error_messages'][0] ?? 'Failed to create transaction',
                'response' => $result
            ];
        }
    }
    
    /**
     * Get Transaction Status
     * 
     * @param string $order_id Order ID
     * @return array Transaction status
     */
    public function get_status($order_id) {
        $url = $this->is_production 
            ? "https://api.midtrans.com/v2/{$order_id}/status"
            : "https://api.sandbox.midtrans.com/v2/{$order_id}/status";
        
        $curl = curl_init();
        $auth = base64_encode($this->server_key . ':');
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Basic ' . $auth
            ],
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($response, true);
    }
    
    /**
     * Handle Notification from Midtrans
     * 
     * @return array Notification data
     */
    public function handle_notification() {
        $json = file_get_contents('php://input');
        $notification = json_decode($json, true);
        
        // Verify signature
        $order_id = $notification['order_id'];
        $status_code = $notification['status_code'];
        $gross_amount = $notification['gross_amount'];
        $signature_key = $notification['signature_key'];
        
        $expected_signature = hash('sha512', $order_id . $status_code . $gross_amount . $this->server_key);
        
        if ($signature_key !== $expected_signature) {
            return [
                'success' => false,
                'message' => 'Invalid signature'
            ];
        }
        
        return [
            'success' => true,
            'data' => $notification
        ];
    }
    
    /**
     * Map Midtrans transaction status to application status
     * 
     * @param string $transaction_status Midtrans transaction status
     * @param string $fraud_status Fraud status
     * @return string Application status
     */
    public function map_status($transaction_status, $fraud_status = null) {
        if ($transaction_status == 'capture') {
            if ($fraud_status == 'accept') {
                return 'dikemas'; // Payment success
            }
        } else if ($transaction_status == 'settlement') {
            return 'dikemas'; // Payment success
        } else if ($transaction_status == 'pending') {
            return 'menunggu_pembayaran'; // Waiting for payment
        } else if (in_array($transaction_status, ['deny', 'expire', 'cancel'])) {
            return 'dibatalkan'; // Payment failed/cancelled
        }
        
        return 'menunggu_pembayaran'; // Default
    }
}
