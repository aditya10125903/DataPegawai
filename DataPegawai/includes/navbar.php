<?php
// File ini berisi struktur navbar atas.
// Pastikan sesi sudah dimulai.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="top-navbar">
    <!-- Jam Digital Futuristik -->
    <div id="clock-container">
        <span id="clock">Loading system time...</span>
    </div>
</header>