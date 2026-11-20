// =========================================================
// KelolaKost — Script Utama
// =========================================================

document.addEventListener('DOMContentLoaded', function () {

    // Konfirmasi sebelum menghapus data
    document.querySelectorAll('.confirm-delete').forEach(function (el) {
        el.addEventListener('click', function (e) {
            const pesan = el.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini?';
            if (!confirm(pesan)) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide alert notifikasi setelah 4 detik
    document.querySelectorAll('.alert').forEach(function (alertEl) {
        setTimeout(function () {
            alertEl.style.transition = 'opacity 0.4s ease';
            alertEl.style.opacity = '0';
            setTimeout(function () { alertEl.remove(); }, 400);
        }, 4000);
    });

    // Tandai menu sidebar aktif berdasarkan URL saat ini
    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.sidebar nav a').forEach(function (link) {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });

    // Isi otomatis jumlah tagihan saat form pembayaran memilih tagihan
    const selectTagihan = document.getElementById('tagihan_id');
    const inputJumlahBayar = document.getElementById('jumlah_bayar');
    if (selectTagihan && inputJumlahBayar) {
        selectTagihan.addEventListener('change', function () {
            const opt = selectTagihan.options[selectTagihan.selectedIndex];
            const sisa = opt.getAttribute('data-sisa');
            if (sisa) inputJumlahBayar.value = sisa;
        });
    }
});
