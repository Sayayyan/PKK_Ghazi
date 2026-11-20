<?php
/** Wajib login sebagai penyewa. */
if (empty($_SESSION['penyewa_id'])) {
    redirect('login_penyewa.php');
}
