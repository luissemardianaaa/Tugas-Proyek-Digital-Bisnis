<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Hugging Face API Configuration
|--------------------------------------------------------------------------
|
| Konfigurasi untuk koneksi ke Hugging Face Inference API
| Digunakan untuk fitur rekomendasi obat berbasis AI
|
*/

// Access Token Hugging Face
$config['hf_access_token'] = getenv('HF_ACCESS_TOKEN') ?: 'YOUR_TOKEN_HERE';

// Model URL - Menggunakan Mistral untuk text generation yang lebih baik
$config['hf_model_url'] = 'https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2';

// Fallback model jika model utama tidak tersedia
$config['hf_fallback_url'] = 'https://api-inference.huggingface.co/models/google/flan-t5-large';

// Timeout untuk request (dalam detik)
$config['hf_timeout'] = 30;
