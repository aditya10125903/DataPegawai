<?php
/**
 * =================================================================================
 * File Sesi Terpusat
 * =================================================================================
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Memulai sesi login untuk pengguna.
 * @param array $user Data pengguna dari database (harus berisi id_pengguna dan username).
 */
function login_user($user) {
    session_regenerate_id(true);
    $_SESSION['id_pengguna'] = $user['id_pengguna'];
    $_SESSION['username'] = $user['username'];
    if (isset($user['user_data'])) {
        $_SESSION['user_data'] = $user['user_data'];
    }
}

/**
 * Mengakhiri sesi pengguna (logout).
 */
function logout_user() {
    $_SESSION = array();
    session_destroy();
}

/**
 * Memeriksa apakah pengguna sudah login.
 * @return bool True jika pengguna sudah login, false jika tidak.
 */
function is_user_logged_in() {
    return isset($_SESSION['username']);
}

/**
 * Mengatur pesan flash yang hanya akan ditampilkan sekali.
 * @param string $key Kunci untuk pesan (misal: 'success', 'error').
 * @param string $message Isi pesan.
 */
function set_flash_message($key, $message) {
    $_SESSION['flash_messages'][$key] = $message;
}

/**
 * Mengambil pesan flash. Pesan akan dihapus setelah diambil.
 * @param string $key Kunci pesan yang ingin diambil.
 * @return string|null Pesan atau null jika tidak ada.
 */
function get_flash_message($key) {
    if (isset($_SESSION['flash_messages'][$key])) {
        $message = $_SESSION['flash_messages'][$key];
        unset($_SESSION['flash_messages'][$key]);
        return $message;
    }
    return null;
}

?>