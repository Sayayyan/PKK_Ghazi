<?php
/**
 * Header & sidebar area admin.
 *
 * Variabel opsional:
 * - $page_title : judul halaman (tanpa "— KelolaKost")
 * - $active     : key menu aktif (dashboard, kamar, penyewa, tagihan, ...)
 */
if (!isset($page_title)) {
    $page_title = 'KelolaKost';
}
if (!isset($active)) {
    $active = '';
}
?>
<!DOCTYPE html>
<html lang="id"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> — KelolaKost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">Kelola<span>Kost</span></div>
        <nav>
            <a href="dashboard.php"<?= $active === 'dashboard' ? ' class="active"' : '' ?>>Dashboard</a>
            <a href="kamar.php"<?= $active === 'kamar' ? ' class="active"' : '' ?>>Data Kamar</a>
            <a href="penyewa.php"<?= $active === 'penyewa' ? ' class="active"' : '' ?>>Penyewa</a>
            <a href="tagihan.php"<?= $active === 'tagihan' ? ' class="active"' : '' ?>>Tagihan</a>
            <a href="pembayaran.php"<?= $active === 'pembayaran' ? ' class="active"' : '' ?>>Pembayaran</a>
            <a href="pengingat.php"<?= $active === 'pengingat' ? ' class="active"' : '' ?>>Pengingat Jatuh Tempo</a>
            <a href="laporan.php"<?= $active === 'laporan' ? ' class="active"' : '' ?>>Laporan Keuangan</a>
            <a href="pengaturan.php"<?= $active === 'pengaturan' ? ' class="active"' : '' ?>>Pengaturan Kost</a>
            <a href="kelola_admin.php"<?= $active === 'kelola_admin' ? ' class="active"' : '' ?>>Kelola Admin</a>
            <a href="index.php">Lihat Halaman Depan ↗</a>
        </nav>
        <div class="logout">
            <a href="logout.php">Keluar</a>
        </div>
    </aside>
    <main class="main-content">
