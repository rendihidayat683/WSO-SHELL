<?php
define('DB_HOST', 'localhost');       // Ganti sesuai host database
define('DB_NAME', 'nama_database');   // Ganti sesuai nama database
define('DB_USER', 'username_db');     // Ganti username database
define('DB_PASS', 'password_db');     // Ganti password database

// --- Sistem ---
define('BASE_URL', 'https://s-widodo.com/sw-library'); // URL folder sw-library
define('LIBRARY_NAME', 'S-Widodo Library');           // Nama library
define('DEBUG_MODE', true);                            // true untuk testing

// --- Session / Security ---
define('SESSION_NAME', 'sw_library_session');
define('SECRET_KEY', '9f2a1c3b4e5d6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b1c2d3e4f5a6b7c8d9e0f1a');

// --- Fungsi helper untuk ambil konfigurasi ---
function lib_config($key) {
    $configs = [
        'db_host' => DB_HOST,
        'db_name' => DB_NAME,
        'db_user' => DB_USER,
        'db_pass' => DB_PASS,
        'base_url' => BASE_URL,
        'library_name' => LIBRARY_NAME,
        'debug_mode' => DEBUG_MODE,
        'session_name' => SESSION_NAME,
        'secret_key' => SECRET_KEY
    ];
    return isset($configs[$key]) ? $configs[$key] : null;
}

// --- Debug sederhana ---
if (DEBUG_MODE) {
    echo "sw-library/sw-config.php berhasil di-load!<br>";
    echo "Database Host: " . DB_HOST . "<br>";
    echo "Library Name: " . LIBRARY_NAME . "<br>";
}
