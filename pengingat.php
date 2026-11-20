<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/includes/auth_admin.php';

$page_title = 'Pengingat Jatuh Tempo';
$active     = 'pengingat';

$daftar = $pdo->query("
    SELECT t.*, p.nama, p.no_hp
    FROM tagihan t
    JOIN penyewa p ON p.id = t.penyewa_id
    WHERE t.status = 'belum_lunas'
    ORDER BY t.tanggal_jatuh_tempo ASC, t.id ASC
")->fetchAll();

$hariIni   = strtotime(date('Y-m-d'));
$terlambat = [];
$mendekati = [];
$akanDatang = [];

foreach ($daftar as $t) {
    $selisih = (int) floor((strtotime($t['tanggal_jatuh_tempo']) - $hariIni) / 86400);
    $t['selisih'] = $selisih;
    if ($selisih < 0) {
        $terlambat[] = $t;
    } elseif ($selisih <= 7) {
        $mendekati[] = $t;
    } else {
        $akanDatang[] = $t;
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="topbar">
    <div>
        <h1>Pengingat Jatuh Tempo</h1>
        <p class="text-muted mb-0">Pantau tagihan yang perlu segera ditagih</p>
    </div>
</div>

<?php render_flash(); ?>

<div class="card">
    <h3>Sudah Lewat Jatuh Tempo (<?= count($terlambat) ?>)</h3>
    <?php if (empty($terlambat)): ?>
        <div class="empty-state">Tidak ada tagihan yang lewat jatuh tempo.</div>
    <?php else: ?>
        <?php foreach ($terlambat as $t): ?>
            <div class="reminder-item overdue">
                <div class="info">
                    <strong><?= e($t['nama']) ?></strong>
                    <span>Periode <?= bulan_nama((int) $t['periode_bulan']) . ' ' . (int) $t['periode_tahun'] ?> — <?= rupiah($t['jumlah_tagihan']) ?> — Jatuh tempo: <?= tanggal_id($t['tanggal_jatuh_tempo']) ?> — <?= e($t['no_hp']) ?></span>
                </div>
                <span class="badge badge-danger">Terlambat <?= abs($t['selisih']) ?> hari</span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Jatuh Tempo dalam 7 Hari (<?= count($mendekati) ?>)</h3>
    <?php if (empty($mendekati)): ?>
        <div class="empty-state">Tidak ada tagihan yang mendekati jatuh tempo.</div>
    <?php else: ?>
        <?php foreach ($mendekati as $t): ?>
            <div class="reminder-item">
                <div class="info">
                    <strong><?= e($t['nama']) ?></strong>
                    <span>Periode <?= bulan_nama((int) $t['periode_bulan']) . ' ' . (int) $t['periode_tahun'] ?> — <?= rupiah($t['jumlah_tagihan']) ?> — Jatuh tempo: <?= tanggal_id($t['tanggal_jatuh_tempo']) ?> — <?= e($t['no_hp']) ?></span>
                </div>
                <span class="badge badge-warning"><?= $t['selisih'] === 0 ? 'Hari ini' : $t['selisih'] . ' hari lagi' ?></span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Akan Datang (<?= count($akanDatang) ?>)</h3>
    <?php if (empty($akanDatang)): ?>
        <div class="empty-state">Tidak ada tagihan lain yang tertunda.</div>
    <?php else: ?>
        <?php foreach ($akanDatang as $t): ?>
            <div class="reminder-item">
                <div class="info">
                    <strong><?= e($t['nama']) ?></strong>
                    <span>Periode <?= bulan_nama((int) $t['periode_bulan']) . ' ' . (int) $t['periode_tahun'] ?> — <?= rupiah($t['jumlah_tagihan']) ?> — Jatuh tempo: <?= tanggal_id($t['tanggal_jatuh_tempo']) ?> — <?= e($t['no_hp']) ?></span>
                </div>
                <span class="badge badge-neutral"><?= $t['selisih'] ?> hari lagi</span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
