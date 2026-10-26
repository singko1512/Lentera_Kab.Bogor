<!-- Peringatan Tunggal Cek Data pada Form Rekomendasi -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('layanan-form');
        if (!form) return;

        function applyStepReminder() {
            // Hanya satu peringatan per tahapan form (Langkah 1, Langkah 2, Langkah 3)
            document.querySelectorAll('.step-content h2').forEach(h2 => {
                if (h2.nextElementSibling && h2.nextElementSibling.classList.contains('step-check-banner')) return;
                const banner = document.createElement('div');
                banner.className = 'step-check-banner flex items-center gap-2.5 px-4 py-3 rounded-xl bg-amber-500/10 border border-amber-500/25 text-amber-900 dark:text-amber-200 text-xs font-medium my-3 select-none';
                banner.innerHTML = `
                    <span class="material-symbols-outlined text-[18px] text-amber-600 flex-shrink-0">info</span>
                    <span><strong>Peringatan:</strong> Mohon cek kembali data yang Anda masukkan, pastikan sudah benar dan sesuai sebelum melanjutkan.</span>
                `;
                h2.parentNode.insertBefore(banner, h2.nextSibling);
            });
        }

        applyStepReminder();
    });
</script>
