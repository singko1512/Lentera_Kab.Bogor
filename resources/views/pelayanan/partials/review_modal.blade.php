<!-- Modal Review & Feedback Aplikasi (Uji Coba Pengguna) -->
<div id="app-review-modal" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity opacity-0 duration-300" id="review-backdrop" onclick="skipAppReview()"></div>

    <!-- Modal Box -->
    <div id="review-box" class="relative bg-surface-container-lowest rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl opacity-0 scale-95 transition-all duration-300 ease-out border border-outline-variant/30">
        
        <!-- Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-[#003366] to-[#115cb9] text-white flex items-start justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center text-amber-300 shrink-0">
                    <span class="material-symbols-outlined text-[24px]">rate_review</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg leading-tight">Kritik &amp; Saran Layanan</h3>
                    <p class="text-xs text-white/80 mt-0.5">Bantu kami meningkatkan kualitas layanan Bangga Online Kabupaten Bogor</p>
                </div>
            </div>
            <button type="button" onclick="skipAppReview()" class="text-white/70 hover:text-white transition-colors p-1 rounded-lg">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Body Form (Scrollable) -->
        <form id="app-review-form" class="overflow-y-auto p-6 space-y-6 flex-1 text-on-surface">
            <input type="hidden" id="review_permohonan_id" name="permohonan_layanan_id" value="">
            <input type="hidden" name="rating_ui_ux" id="rating_ui_ux" value="">
            <input type="hidden" name="rating_kemudahan" id="rating_kemudahan" value="">
            <input type="hidden" name="rating_kecepatan" id="rating_kecepatan" value="">
            <input type="hidden" name="rating_kejelasan" id="rating_kejelasan" value="">

            <div class="p-3.5 bg-blue-50/70 border border-blue-200/60 rounded-2xl flex items-center gap-3 text-xs text-blue-900">
                <span class="material-symbols-outlined text-primary text-[20px] shrink-0">info</span>
                <span>Pengajuan Anda telah berhasil dikirim! Silakan beri nilai pada 4 pertanyaan di bawah (skala 1 - 10) atau klik <strong>Lewati</strong> jika ingin langsung selesai.</span>
            </div>

            <!-- Pertanyaan 1: UI/UX -->
            <div class="space-y-2.5">
                <div class="flex justify-between items-baseline">
                    <label class="font-bold text-sm text-on-surface flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">1</span>
                        <span>Desain &amp; Antarmuka (UI/UX)</span>
                    </label>
                    <span id="label_score_ui_ux" class="text-xs font-bold text-primary">Pilih nilai:</span>
                </div>
                <p class="text-xs text-on-surface-variant pl-8">Kenyamanan tampilan, keserasian warna, tata letak, dan kerapian antarmuka.</p>
                <div class="flex items-center justify-between gap-1 sm:gap-2 pt-1 pl-8">
                    @for($i = 1; $i <= 10; $i++)
                    <button type="button" onclick="selectReviewScore('ui_ux', {{ $i }})" data-group="ui_ux" data-value="{{ $i }}" class="review-btn-ui_ux flex-1 h-9 rounded-lg border border-outline-variant/60 hover:border-primary hover:bg-surface-container-low text-xs font-bold text-on-surface transition-all flex items-center justify-center">
                        {{ $i }}
                    </button>
                    @endfor
                </div>
                <div class="flex justify-between text-[10px] text-outline pl-8 pt-0.5">
                    <span>1 (Sangat Buruk)</span>
                    <span>10 (Sangat Bagus &amp; Menarik)</span>
                </div>
            </div>

            <!-- Pertanyaan 2: Kemudahan Penggunaan -->
            <div class="space-y-2.5 pt-3 border-t border-outline-variant/20">
                <div class="flex justify-between items-baseline">
                    <label class="font-bold text-sm text-on-surface flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">2</span>
                        <span>Kemudahan Penggunaan (Usability)</span>
                    </label>
                    <span id="label_score_kemudahan" class="text-xs font-bold text-primary">Pilih nilai:</span>
                </div>
                <p class="text-xs text-on-surface-variant pl-8">Kemudahan memahami alur tahapan form dan pengisian data.</p>
                <div class="flex items-center justify-between gap-1 sm:gap-2 pt-1 pl-8">
                    @for($i = 1; $i <= 10; $i++)
                    <button type="button" onclick="selectReviewScore('kemudahan', {{ $i }})" data-group="kemudahan" data-value="{{ $i }}" class="review-btn-kemudahan flex-1 h-9 rounded-lg border border-outline-variant/60 hover:border-primary hover:bg-surface-container-low text-xs font-bold text-on-surface transition-all flex items-center justify-center">
                        {{ $i }}
                    </button>
                    @endfor
                </div>
                <div class="flex justify-between text-[10px] text-outline pl-8 pt-0.5">
                    <span>1 (Sangat Rumit)</span>
                    <span>10 (Sangat Mudah &amp; Intuitif)</span>
                </div>
            </div>

            <!-- Pertanyaan 3: Kecepatan & Kinerja -->
            <div class="space-y-2.5 pt-3 border-t border-outline-variant/20">
                <div class="flex justify-between items-baseline">
                    <label class="font-bold text-sm text-on-surface flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">3</span>
                        <span>Kecepatan &amp; Kinerja Sistem (Performance)</span>
                    </label>
                    <span id="label_score_kecepatan" class="text-xs font-bold text-primary">Pilih nilai:</span>
                </div>
                <p class="text-xs text-on-surface-variant pl-8">Kecepatan respons halaman, upload berkas persyaratan, dan kelancaran transisi.</p>
                <div class="flex items-center justify-between gap-1 sm:gap-2 pt-1 pl-8">
                    @for($i = 1; $i <= 10; $i++)
                    <button type="button" onclick="selectReviewScore('kecepatan', {{ $i }})" data-group="kecepatan" data-value="{{ $i }}" class="review-btn-kecepatan flex-1 h-9 rounded-lg border border-outline-variant/60 hover:border-primary hover:bg-surface-container-low text-xs font-bold text-on-surface transition-all flex items-center justify-center">
                        {{ $i }}
                    </button>
                    @endfor
                </div>
                <div class="flex justify-between text-[10px] text-outline pl-8 pt-0.5">
                    <span>1 (Sangat Lambat)</span>
                    <span>10 (Sangat Cepat &amp; Responsif)</span>
                </div>
            </div>

            <!-- Pertanyaan 4: Kejelasan Informasi -->
            <div class="space-y-2.5 pt-3 border-t border-outline-variant/20">
                <div class="flex justify-between items-baseline">
                    <label class="font-bold text-sm text-on-surface flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">4</span>
                        <span>Kejelasan Informasi &amp; Alur Layanan</span>
                    </label>
                    <span id="label_score_kejelasan" class="text-xs font-bold text-primary">Pilih nilai:</span>
                </div>
                <p class="text-xs text-on-surface-variant pl-8">Petunjuk berkas persyaratan, estimasi waktu verifikasi, dan kejelasan instruksi.</p>
                <div class="flex items-center justify-between gap-1 sm:gap-2 pt-1 pl-8">
                    @for($i = 1; $i <= 10; $i++)
                    <button type="button" onclick="selectReviewScore('kejelasan', {{ $i }})" data-group="kejelasan" data-value="{{ $i }}" class="review-btn-kejelasan flex-1 h-9 rounded-lg border border-outline-variant/60 hover:border-primary hover:bg-surface-container-low text-xs font-bold text-on-surface transition-all flex items-center justify-center">
                        {{ $i }}
                    </button>
                    @endfor
                </div>
                <div class="flex justify-between text-[10px] text-outline pl-8 pt-0.5">
                    <span>1 (Membingungkan)</span>
                    <span>10 (Sangat Jelas &amp; Terstruktur)</span>
                </div>
            </div>

            <!-- Kolom Kritik & Saran -->
            <div class="space-y-2 pt-3 border-t border-outline-variant/20">
                <label for="review_kritik_saran" class="font-bold text-sm text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-primary">comment</span>
                    <span>Kritik dan Saran</span>
                </label>
                <p class="text-xs text-on-surface-variant">Tuliskan kendala yang Anda alami atau masukan perbaikan antarmuka untuk Super Admin:</p>
                <textarea id="review_kritik_saran" name="kritik_saran" rows="3" placeholder="Contoh: Tampilan sudah rapi dan mudah dipahami, tetapi opsi upload berkas bisa dibuat lebih besar..." class="w-full p-3.5 bg-surface border border-outline-variant/60 rounded-xl text-xs sm:text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all"></textarea>
            </div>
        </form>

        <!-- Footer Actions -->
        <div class="p-4 sm:p-5 bg-surface-container-low border-t border-outline-variant/20 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 shrink-0">
            <button type="button" onclick="skipAppReview()" class="w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-semibold text-on-surface-variant hover:text-on-surface transition-colors rounded-xl">
                Lewati &amp; Kembali ke Beranda
            </button>
            <button type="button" id="btn-submit-review" onclick="submitAppReview()" class="w-full sm:w-auto px-6 py-2.5 bg-primary text-white text-xs sm:text-sm font-bold rounded-xl shadow-md hover:bg-primary-container transition-all flex items-center justify-center gap-2">
                <span>Kirim Kritik &amp; Saran</span>
                <span class="material-symbols-outlined text-[18px]">send</span>
            </button>
        </div>
    </div>
</div>

<script>
    // State review
    const reviewScores = {
        ui_ux: null,
        kemudahan: null,
        kecepatan: null,
        kejelasan: null
    };

    function selectReviewScore(group, score) {
        reviewScores[group] = score;
        const hiddenInput = document.getElementById('rating_' + group);
        if (hiddenInput) hiddenInput.value = score;

        const labelScore = document.getElementById('label_score_' + group);
        if (labelScore) {
            labelScore.textContent = `Skor: ${score}/10`;
            labelScore.className = 'text-xs font-extrabold text-emerald-600 dark:text-emerald-400';
        }

        // Highlight selected button
        const buttons = document.querySelectorAll('.review-btn-' + group);
        buttons.forEach(btn => {
            const val = parseInt(btn.getAttribute('data-value'));
            if (val === score) {
                btn.className = 'review-btn-' + group + ' flex-1 h-9 rounded-lg bg-primary text-white border-primary shadow-sm font-extrabold text-xs transition-all flex items-center justify-center scale-105';
            } else {
                btn.className = 'review-btn-' + group + ' flex-1 h-9 rounded-lg border border-outline-variant/60 hover:border-primary hover:bg-surface-container-low text-xs font-bold text-on-surface transition-all flex items-center justify-center';
            }
        });
    }

    let currentPermohonanId = null;

    window.openAppReviewModal = function(permohonanId = null) {
        const modal = document.getElementById('app-review-modal');
        if (!modal) return;

        const effectiveId = permohonanId || currentPermohonanId;
        if (effectiveId) {
            const inputId = document.getElementById('review_permohonan_id');
            if (inputId) inputId.value = effectiveId;
        }

        // Hide success modal first if open
        const successModal = document.getElementById('success-modal');
        if (successModal) successModal.classList.add('hidden');

        modal.classList.remove('hidden');
        setTimeout(() => {
            const backdrop = document.getElementById('review-backdrop');
            if (backdrop) backdrop.classList.replace('opacity-0', 'opacity-100');
            const box = document.getElementById('review-box');
            if (box) {
                box.classList.replace('opacity-0', 'opacity-100');
                box.classList.replace('scale-95', 'scale-100');
            }
        }, 10);
    };

    window.handleLayananSubmitSuccess = function(data, storageKey, formDataKey) {
        if (storageKey) sessionStorage.removeItem(storageKey);
        if (formDataKey) sessionStorage.removeItem(formDataKey);

        if (data && data.permohonan_id) {
            currentPermohonanId = data.permohonan_id;
        }

        const modal = document.getElementById('success-modal');
        if (modal) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                const backdrop = document.getElementById('success-backdrop');
                if (backdrop) backdrop.classList.replace('opacity-0', 'opacity-100');
                const box = document.getElementById('success-box');
                if (box) {
                    box.classList.replace('opacity-0', 'opacity-100');
                    box.classList.replace('scale-90', 'scale-100');
                }
                const icon = document.getElementById('success-icon');
                if (icon) icon.classList.replace('scale-0', 'scale-100');
            }, 10);

            // Tidak otomatis hilang: tetap terbuka hingga pemohon mengklik tombol (X), Lewati, atau Beri Penilaian Aplikasi
        } else {
            window.openAppReviewModal(currentPermohonanId);
        }
    };

    function skipAppReview() {
        const modal = document.getElementById('app-review-modal');
        if (modal) modal.classList.add('hidden');
        window.location.href = "{{ route('home') }}";
    }

    function submitAppReview() {
        const kritikSaranVal = (document.getElementById('review_kritik_saran')?.value || '').trim();

        // Jika pemohon mengisi kritik/saran, nilai rating yang belum dipilih otomatis diisi nilai default (10) agar pengiriman tidak terhambat
        if (kritikSaranVal.length > 0) {
            if (!reviewScores.ui_ux) selectReviewScore('ui_ux', 10);
            if (!reviewScores.kemudahan) selectReviewScore('kemudahan', 10);
            if (!reviewScores.kecepatan) selectReviewScore('kecepatan', 10);
            if (!reviewScores.kejelasan) selectReviewScore('kejelasan', 10);
        } else {
            // Jika kritik/saran kosong DAN rating juga belum dipilih sama sekali
            const missing = [];
            if (!reviewScores.ui_ux) missing.push('Desain & Antarmuka (UI/UX)');
            if (!reviewScores.kemudahan) missing.push('Kemudahan Penggunaan');
            if (!reviewScores.kecepatan) missing.push('Kecepatan & Kinerja');
            if (!reviewScores.kejelasan) missing.push('Kejelasan Informasi');

            if (missing.length > 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Penilaian Belum Dipilih',
                        text: 'Silakan pilih nilai rating atau tuliskan kritik dan saran Anda.',
                        confirmButtonText: 'Baik, Saya Isi',
                        showCancelButton: true,
                        cancelButtonText: 'Lewati & Selesai',
                        confirmButtonColor: '#003366',
                        cancelButtonColor: '#64748b',
                        customClass: { popup: 'rounded-3xl shadow-xl' }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            skipAppReview();
                        }
                    });
                }
                return;
            }
        }

        const btn = document.getElementById('btn-submit-review');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span>Mengirim...</span>';
        btn.disabled = true;

        const formEl = document.getElementById('app-review-form');
        const formData = new FormData(formEl);

        fetch('{{ route("app_review.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;

            if (data.status === 'success') {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terima Kasih!',
                        text: 'Kritik dan saran Anda telah berhasil terkirim.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-3xl shadow-xl' }
                    }).then(() => {
                        window.location.href = "{{ route('home') }}";
                    });
                } else {
                    window.location.href = "{{ route('home') }}";
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: data.message || 'Gagal menyimpan ulasan.',
                        confirmButtonColor: '#003366'
                    });
                }
            }
        })
        .catch(err => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            console.error(err);
            window.location.href = "{{ route('home') }}";
        });
    }
</script>
