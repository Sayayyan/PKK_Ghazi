<?php
/**
 * KelolaKost — Fungsi bantu umum
 */

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka): string
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function bulan_nama(int $bulan): string
{
    $daftar = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    return $daftar[$bulan] ?? '';
}

function bulan_singkat(int $bulan): string
{
    $daftar = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Des',
    ];
    return $daftar[$bulan] ?? '';
}

function tanggal_id($tanggal): string
{
    if (empty($tanggal)) {
        return '-';
    }
    $ts = strtotime($tanggal);
    if (!$ts) {
        return '-';
    }
    return date('d', $ts) . ' ' . bulan_singkat((int) date('n', $ts)) . ' ' . date('Y', $ts);
}

function tanggal_jam_id($datetime): string
{
    $ts = strtotime((string) $datetime);
    if (!$ts) {
        return '-';
    }
    return date('d', $ts) . ' ' . bulan_singkat((int) date('n', $ts)) . ' ' . date('Y H:i', $ts);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash(string $tipe, string $pesan): void
{
    $_SESSION['flash'][] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function render_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    foreach ($_SESSION['flash'] as $f) {
        $tipe = in_array($f['tipe'], ['success', 'danger', 'warning'], true) ? $f['tipe'] : 'success';
        echo '<div class="alert alert-' . $tipe . '">' . e($f['pesan']) . '</div>';
    }
    unset($_SESSION['flash']);
}

function pengaturan(PDO $pdo): array
{
    static $data = null;
    if ($data === null) {
        $data = $pdo->query('SELECT * FROM pengaturan WHERE id = 1')->fetch();
        if (!$data) {
            $data = [
                'nama_kost'       => 'KelolaKost',
                'alamat'          => '',
                'deskripsi'       => '',
                'kontak_wa'       => '',
                'kontak_email'    => '',
                'jam_operasional' => '',
            ];
        }
    }
    return $data;
}

/**
 * Samakan status kamar dengan ada/tidaknya penyewa aktif.
 */
function sync_kamar_status(PDO $pdo, int $kamarId): void
{
    if ($kamarId <= 0) {
        return;
    }
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM penyewa WHERE kamar_id = ? AND status = 'aktif'");
    $stmt->execute([$kamarId]);
    $terisi = (int) $stmt->fetchColumn() > 0;
    $update = $pdo->prepare('UPDATE kamar SET status = ? WHERE id = ?');
    $update->execute([$terisi ? 'terisi' : 'kosong', $kamarId]);
}

/**
 * Total pembayaran untuk satu tagihan.
 */
function total_dibayar(PDO $pdo, int $tagihanId): float
{
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(jumlah_bayar), 0) FROM pembayaran WHERE tagihan_id = ?');
    $stmt->execute([$tagihanId]);
    return (float) $stmt->fetchColumn();
}

/**
 * Perbarui status lunas/belum lunas berdasarkan total pembayaran.
 */
function sync_status_tagihan(PDO $pdo, int $tagihanId): void
{
    $stmt = $pdo->prepare('SELECT jumlah_tagihan FROM tagihan WHERE id = ?');
    $stmt->execute([$tagihanId]);
    $jumlah = $stmt->fetchColumn();
    if ($jumlah === false) {
        return;
    }
    $status = total_dibayar($pdo, $tagihanId) >= (float) $jumlah ? 'lunas' : 'belum_lunas';
    $update = $pdo->prepare('UPDATE tagihan SET status = ? WHERE id = ?');
    $update->execute([$status, $tagihanId]);
}

/**
 * Nomor referensi pembayaran QRIS: QRIS-YYYYMMDD-XXXXXX
 */
function buat_kode_referensi(string $tanggal): string
{
    $ts = strtotime($tanggal) ?: time();
    return 'QRIS-' . date('Ymd', $ts) . '-' . strtoupper(bin2hex(random_bytes(3)));
}
