<?php
/** Wajib login sebagai admin/pengelola. */
if (empty($_SESSION['admin_id'])) {
    redirect('login.php');
}
