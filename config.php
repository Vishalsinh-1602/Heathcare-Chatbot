<?php
// config.php

// Database configuration
$host = 'localhost';
$db   = 'healthmate_db';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Encryption configuration
// Use a strong secret key. In production, store this outside your codebase.
define('ENCRYPTION_KEY', '1qafg562rsf#$2hdkahjkjq!qwjhgk#r'); // 32 chars for AES-256

// Encryption function using AES-256-CBC
function encryptData($data) {
    $cipher = "AES-256-CBC";
    $key = ENCRYPTION_KEY;
    // Generate a random IV
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    // Encrypt data
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    // Combine IV with encrypted data (base64-encode for safe storage)
    return base64_encode($iv . $encrypted);
}

// Decryption function
function decryptData($data) {
    $cipher = "AES-256-CBC";
    $key = ENCRYPTION_KEY;
    // Base64-decode data
    $data = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher);
    // Extract the IV and the encrypted text
    $iv = substr($data, 0, $ivlen);
    $encrypted = substr($data, $ivlen);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}
?>