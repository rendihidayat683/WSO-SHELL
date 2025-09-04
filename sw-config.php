<?php
/**
 * sw-config.php
 * File konfigurasi dasar untuk sistem S-Widodo
 */

// Konfigurasi database
define('DB_HOST', 'localhost');       // Host database
define('DB_NAME', 'nama_database');   // Nama database
define('DB_USER', 'username_db');     // Username database
define('DB_PASS', 'password_db');     // Password database

// Konfigurasi sistem
define('BASE_URL', 'https://s-widodo.com');  // URL base website
define('SITE_NAME', 'S-Widodo');             // Nama situs
define('DEBUG_MODE', true);                   // Mode debug (true/false)

// Konfigurasi session / security
define('SESSION_NAME', 'sw_session');        // Nama session
define('SECRET_KEY', 'ubah_dengan_key_acak'); // Kunci rahasia untuk hashing / token

// Fungsi helper (opsional)
function config($key) {
    $configs = [
        'db_host' => DB_HOST,
        'db_name' => DB_NAME,
        'db_user' => DB_USER,
        'db_pass' => DB_PASS,
        'base_url' => BASE_URL,
        'site_name' => SITE_NAME,
        'debug_mode' => DEBUG_MODE,
        'session_name' => SESSION_NAME,
        'secret_key' => SECRET_KEY
    ];

    return isset($configs[$key]) ? $configs[$key] : null;
}
