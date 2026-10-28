<!-- Modal Ringkasan Pengajuan (Submission Summary Modal) -->
<div id="layanan-summary-modal" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto">
    <!-- Backdrop Blur -->
    <div id="summary-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeLayananSummaryModal()"></div>

    <!-- Modal Box -->
    <div id="summary-box" class="relative bg-white dark:bg-slate-900 rounded-2xl md:rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 max-w-2xl w-full my-auto z-10 opacity-0 scale-95 transition-all duration-300 flex flex-col max-h-[90vh]">
        
        <!-- Header Modal -->
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0 bg-slate-50/70 dark:bg-slate-800/40 rounded-t-2xl md:rounded-t-3xl">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">fact_check</span>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white leading-tight">Ringkasan Pengajuan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Periksa kembali rincian data Anda sebelum dikirim ke Bakesbangpol</p>
                </div>
            </div>
            <button type="button" onclick="closeLayananSummaryModal()" class="w-9 h-9 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors" title="Tutup">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Scrollable Content Body -->
        <div class="p-6 overflow-y-auto space-y-4 text-sm">
            <!-- Alert Info -->
            <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 flex items-start gap-2.5 text-xs text-amber-900 dark:text-amber-300">
                <span class="material-symbols-outlined text-[18px] text-amber-600 dark:text-amber-400 shrink-0 mt-0.5">info</span>
                <span>Pastikan data identitas, tujuan instansi, dan seluruh dokumen yang Anda unggah sudah benar dan sesuai ketentuan pelayanan.</span>
            </div>

            <!-- Card 1: Data Pemohon -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50/50 dark:bg-slate-800/30 space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                    <span class="material-symbols-outlined text-blue-600 text-[18px]">person</span>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700 dark:text-slate-300">Data Pemohon</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Nama Pemohon (Ketua/Perwakilan):</span>
                        <p id="sum-nama" class="font-bold text-slate-900 dark:text-white text-sm">-</p>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Nomor WhatsApp / HP:</span>
                        <p id="sum-nohp" class="font-bold text-slate-900 dark:text-white text-sm">-</p>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Asal Instansi / Universitas / Sekolah:</span>
                        <p id="sum-instansi" class="font-bold text-slate-900 dark:text-white text-sm">-</p>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Tipe Kepesertaan:</span>
                        <span id="sum-tipe-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800">
                            -
                        </span>
                        <div id="sum-anggota-wrapper" class="hidden mt-2.5 pt-2.5 border-t border-dashed border-slate-200 dark:border-slate-800">
                            <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400 block mb-1.5">Anggota Terdaftar / Di-tag:</span>
                            <ul id="sum-anggota-list" class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300 pl-2"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Rincian Kegiatan -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50/50 dark:bg-slate-800/30 space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                    <span class="material-symbols-outlined text-blue-600 text-[18px]">event_note</span>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700 dark:text-slate-300">Rincian Kegiatan / Penelitian</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="sm:col-span-2">
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Judul Kegiatan / Penelitian:</span>
                        <p id="sum-judul" class="font-semibold text-slate-900 dark:text-white text-sm leading-relaxed">-</p>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Instansi Tujuan / Tempat Pelaksanaan:</span>
                        <p id="sum-lokasi" class="font-bold text-blue-700 dark:text-blue-400 text-sm flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[17px] text-blue-600">apartment</span>
                            <span id="sum-lokasi-text">-</span>
                        </p>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Tanggal Mulai:</span>
                        <p id="sum-tgl-mulai" class="font-semibold text-slate-900 dark:text-white text-xs sm:text-sm">-</p>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Tanggal Selesai:</span>
                        <p id="sum-tgl-selesai" class="font-semibold text-slate-900 dark:text-white text-xs sm:text-sm">-</p>
                    </div>
                    <div class="sm:col-span-2" id="sum-durasi-row">
                        <span class="text-slate-500 dark:text-slate-400 block mb-0.5">Durasi Waktu Kegiatan:</span>
                        <span id="sum-durasi" class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-0.5 rounded border border-emerald-200 dark:border-emerald-800 text-xs">
                            -
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Dokumen Persyaratan yang Diunggah -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50/50 dark:bg-slate-800/30 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 text-[18px]">attachment</span>
                        <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700 dark:text-slate-300">Dokumen Lampiran</h4>
                    </div>
                    <span id="sum-total-berkas" class="text-[11px] font-bold text-emerald-700 bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-400 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                        0 Berkas Siap
                    </span>
                </div>
                <div id="sum-files-list" class="space-y-2">
                    <!-- Populated dynamically -->
                </div>
            </div>

            <!-- Card 4: Keterangan (Opsional) -->
            <div id="sum-keterangan-card" class="hidden rounded-xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50/50 dark:bg-slate-800/30 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-[18px]">notes</span>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700 dark:text-slate-300">Keterangan Tambahan</h4>
                </div>
                <p id="sum-keterangan" class="text-xs text-slate-700 dark:text-slate-300 italic whitespace-pre-line">-</p>
            </div>
            
            <!-- Pernyataan / Disclaimer -->
            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed text-center px-2">
                Dengan mengklik tombol <strong class="text-slate-700 dark:text-slate-200">Kirim Sekarang</strong>, Anda menyatakan bahwa seluruh data yang diisi dan berkas yang dilampirkan adalah benar, sah, dan dapat dipertanggungjawabkan.
            </p>
        </div>

        <!-- Footer Actions -->
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 rounded-b-2xl md:rounded-b-3xl flex flex-col-reverse sm:flex-row items-center justify-end gap-3 shrink-0">
            <button type="button" onclick="closeLayananSummaryModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shadow-xs">
                Periksa Kembali
            </button>
            <button type="button" id="btn-confirm-submit-summary" onclick="confirmLayananSubmit()" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[17px]">send</span>
                <span id="btn-confirm-text">Kirim Sekarang</span>
            </button>
        </div>
    </div>
</div>

<script>
    let onSummaryConfirmedCallback = null;

    function formatIndoDate(dateStr) {
        if (!dateStr) return '-';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const d = parseInt(parts[2], 10);
        const m = months[parseInt(parts[1], 10) - 1] || '';
        const y = parts[0];
        return `${d} ${m} ${y}`;
    }

    function calculateDuration(startStr, endStr) {
        if (!startStr || !endStr) return '';
        const d1 = new Date(startStr);
        const d2 = new Date(endStr);
        const diffTime = d2 - d1;
        if (isNaN(diffTime) || diffTime < 0) return '';
        const days = Math.round(diffTime / (1000 * 60 * 60 * 24)) + 1;
        const months = Math.floor(days / 30);
        const remainingDays = days % 30;
        
        let text = `${days} Hari`;
        if (months > 0) {
            text += ` (± ${months} Bulan${remainingDays > 0 ? ` ${remainingDays} Hari` : ''})`;
        }
        return text;
    }

    function formatFileSize(bytes) {
        if (!bytes || bytes === 0) return '';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function getFileInputLabel(input) {
        const label = input.closest('label');
        if (label) {
            const titleSpan = label.querySelector('.font-label-md, span:nth-of-type(2)');
            if (titleSpan) return titleSpan.textContent.replace('Upload ', '').trim();
        }
        const names = {
            'file_ktp': 'KTP / Identitas',
            'file_ktm': 'KTM / Kartu Mahasiswa',
            'file_kartu_pelajar': 'Kartu Pelajar',
            'file_id_card': 'ID Card / Tanda Pengenal',
            'file_surat_permohonan': 'Surat Permohonan / Pengantar',
            'file_surat_pengantar': 'Surat Pengantar Asli',
            'file_surat_lokasi': 'Surat Izin Lokasi Kegiatan',
            'file_proposal': 'Proposal Kegiatan',
            'file_surat_kesbangpol_jabar': 'Surat Kesbangpol Jabar',
            'file_surat_kemendagri': 'Surat Kemendagri',
            'file_surat_rekomendasi_lama': 'Surat Rekomendasi Lama',
            'file_pendukung': 'Dokumen Pendukung Lainnya',
            'file_daftar_peserta': 'Daftar Nama Peserta',
        };
        return names[input.name] || input.name;
    }

    window.openLayananSummaryModal = function(form, callback) {
        onSummaryConfirmedCallback = callback;

        // 1. Data Pemohon
        const nama = form.querySelector('[name="atas_nama"]')?.value || '{{ Auth::user()->name ?? "" }}';
        const noHp = form.querySelector('[name="no_hp"]')?.value || '{{ Auth::user()->no_hp ?? "" }}';
        const instansi = form.querySelector('[name="asal_instansi"]')?.value || '{{ Auth::user()->asal_instansi ?? "" }}';

        document.getElementById('sum-nama').textContent = nama || '-';
        document.getElementById('sum-nohp').textContent = noHp || '-';
        document.getElementById('sum-instansi').textContent = instansi || '-';

        // Tipe Peserta & Anggota
        let isKelompok = false;
        const checkedRadio = form.querySelector('input[name="jumlah_peserta"]:checked, input[name="jenis_permohonan"]:checked');
        if (checkedRadio && checkedRadio.value.toLowerCase().includes('kelompok')) {
            isKelompok = true;
        }

        const memberNames = [];
        form.querySelectorAll('input[name="nama_anggota[]"], input[name="anggota[]"]').forEach(inp => {
            if (inp.value && inp.value.trim() && !memberNames.includes(inp.value.trim())) {
                memberNames.push(inp.value.trim());
            }
        });
        document.querySelectorAll('#tag-chips-list .tag-chip-name, #tag-chips-list span.font-medium').forEach(chip => {
            const txt = chip.textContent.trim();
            if (txt && !memberNames.includes(txt)) {
                memberNames.push(txt);
            }
        });

        const tipeBadge = document.getElementById('sum-tipe-badge');
        const anggotaWrapper = document.getElementById('sum-anggota-wrapper');
        const anggotaList = document.getElementById('sum-anggota-list');

        if (isKelompok) {
            const totalCount = memberNames.length > 0 ? (memberNames.length + 1) : (parseInt(form.querySelector('#input-jumlah-anggota')?.value) || 2);
            tipeBadge.innerHTML = `<span class="material-symbols-outlined text-[15px]">groups</span> Kelompok (${totalCount} Orang: 1 Ketua + ${totalCount - 1} Anggota)`;
            tipeBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/50 dark:text-purple-300 dark:border-purple-800';
            
            anggotaWrapper.classList.remove('hidden');
            anggotaList.innerHTML = '';
            if (memberNames.length > 0) {
                memberNames.forEach((name, idx) => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center gap-2';
                    li.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> <span class="font-medium text-slate-800 dark:text-slate-200">Anggota ${idx + 2}: ${name}</span>`;
                    anggotaList.appendChild(li);
                });
            } else {
                anggotaList.innerHTML = '<li class="italic text-slate-400">Belum ada anggota yang di-tag.</li>';
            }
        } else {
            tipeBadge.innerHTML = `<span class="material-symbols-outlined text-[15px]">person</span> Individu (1 Orang)`;
            tipeBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800';
            anggotaWrapper.classList.add('hidden');
        }

        // 2. Detail Kegiatan
        const judul = form.querySelector('[name="judul_kegiatan"]')?.value || '-';
        document.getElementById('sum-judul').textContent = judul;

        let lokasiText = '-';
        const selectDinas = form.querySelector('select[name="dinas_id"]');
        if (selectDinas && selectDinas.value && selectDinas.selectedIndex >= 0) {
            const opt = selectDinas.options[selectDinas.selectedIndex];
            lokasiText = opt.getAttribute('data-original-name') || opt.text.split(' (Sisa Kuota')[0].split(' (Sisa:')[0].split(' (Kuota')[0].trim();
        } else {
            const tempatInput = form.querySelector('input[name="tempat_kegiatan"], input[name="tempat_pkl"], input[name="tempat_kkn"]');
            if (tempatInput && tempatInput.value) {
                lokasiText = tempatInput.value.trim();
            }
        }
        document.getElementById('sum-lokasi-text').textContent = lokasiText;

        const tglMulai = form.querySelector('input[name="tanggal_mulai"]')?.value;
        const tglSelesai = form.querySelector('input[name="tanggal_selesai"]')?.value;
        document.getElementById('sum-tgl-mulai').textContent = formatIndoDate(tglMulai);
        document.getElementById('sum-tgl-selesai').textContent = formatIndoDate(tglSelesai);
        
        const durasiText = calculateDuration(tglMulai, tglSelesai);
        const durasiRow = document.getElementById('sum-durasi-row');
        if (durasiText) {
            durasiRow.classList.remove('hidden');
            document.getElementById('sum-durasi').textContent = durasiText;
        } else {
            durasiRow.classList.add('hidden');
        }

        // 3. Dokumen Lampiran
        const fileInputs = form.querySelectorAll('input[type="file"]');
        let fileCount = 0;
        let filesHtml = '';

        fileInputs.forEach(input => {
            if (input.files && input.files.length > 0) {
                fileCount++;
                const file = input.files[0];
                const docLabel = getFileInputLabel(input);
                const sizeFormatted = formatFileSize(file.size);
                
                filesHtml += `
                    <div class="flex items-center justify-between p-2.5 rounded-lg border border-emerald-200 dark:border-emerald-800/60 bg-emerald-50/50 dark:bg-emerald-950/20 text-xs">
                        <div class="flex items-center gap-2.5 overflow-hidden mr-2">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[20px] shrink-0">check_circle</span>
                            <div class="truncate">
                                <p class="font-bold text-slate-900 dark:text-white truncate">${docLabel}</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">${file.name} ${sizeFormatted ? `(${sizeFormatted})` : ''}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300 shrink-0">
                            Terlampir
                        </span>
                    </div>
                `;
            }
        });

        if (fileCount === 0) {
            filesHtml = '<p class="text-xs text-amber-600 dark:text-amber-400 italic p-2">Belum ada berkas fisik yang dipilih.</p>';
        }

        document.getElementById('sum-files-list').innerHTML = filesHtml;
        document.getElementById('sum-total-berkas').textContent = `${fileCount} Berkas Terlampir`;

        // 4. Keterangan
        const keteranganVal = form.querySelector('textarea[name="keterangan"]')?.value?.trim();
        const keteranganCard = document.getElementById('sum-keterangan-card');
        if (keteranganVal) {
            keteranganCard.classList.remove('hidden');
            document.getElementById('sum-keterangan').textContent = `"${keteranganVal}"`;
        } else {
            keteranganCard.classList.add('hidden');
        }

        // Tampilkan Modal
        const modal = document.getElementById('layanan-summary-modal');
        const backdrop = document.getElementById('summary-backdrop');
        const box = document.getElementById('summary-box');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            backdrop.classList.replace('opacity-0', 'opacity-100');
            box.classList.replace('opacity-0', 'opacity-100');
            box.classList.replace('scale-95', 'scale-100');
        }, 10);
    };

    window.closeLayananSummaryModal = function() {
        const modal = document.getElementById('layanan-summary-modal');
        const backdrop = document.getElementById('summary-backdrop');
        const box = document.getElementById('summary-box');
        if (!modal) return;

        backdrop.classList.replace('opacity-100', 'opacity-0');
        box.classList.replace('opacity-100', 'opacity-0');
        box.classList.replace('scale-100', 'scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            
            // Reset state tombol submit summary
            const btnConfirm = document.getElementById('btn-confirm-submit-summary');
            const textEl = document.getElementById('btn-confirm-text');
            if (btnConfirm && textEl) {
                btnConfirm.disabled = false;
                textEl.textContent = 'Kirim Sekarang';
            }
        }, 250);
    };

    window.confirmLayananSubmit = function() {
        const btnConfirm = document.getElementById('btn-confirm-submit-summary');
        const textEl = document.getElementById('btn-confirm-text');
        if (btnConfirm && textEl) {
            btnConfirm.disabled = true;
            textEl.innerHTML = '<span class="inline-block animate-spin mr-1.5">◌</span> Mengirim Pengajuan...';
        }

        if (typeof onSummaryConfirmedCallback === 'function') {
            onSummaryConfirmedCallback();
        }
    };

    // Auto-intercept form submission on landing forms
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('layanan-form');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            if (!form.dataset.confirmed) {
                e.stopImmediatePropagation();
                e.preventDefault();

                // Validasi input pada langkah terakhir
                const currentStepEl = document.getElementById('step-' + (window.currentStep || 3));
                if (currentStepEl) {
                    const inputs = currentStepEl.querySelectorAll('input, select, textarea');
                    for (let i = 0; i < inputs.length; i++) {
                        if (!inputs[i].checkValidity()) {
                            inputs[i].reportValidity();
                            return;
                        }
                    }
                }

                // Buka Summary Modal
                openLayananSummaryModal(form, function() {
                    form.dataset.confirmed = 'true';
                    const submitBtn = document.getElementById('btn-submit');
                    if (submitBtn) {
                        submitBtn.click();
                    } else {
                        form.dispatchEvent(new Event('submit', { cancelable: true }));
                    }
                });
            } else {
                delete form.dataset.confirmed;
            }
        }, true); // Use capture phase to intercept before form's bubbling listener

        // Auto close summary modal when handleLayananSubmitSuccess is called
        if (typeof window.handleLayananSubmitSuccess === 'function') {
            const originalSuccess = window.handleLayananSubmitSuccess;
            window.handleLayananSubmitSuccess = function(data, storageKey, formDataKey) {
                closeLayananSummaryModal();
                originalSuccess(data, storageKey, formDataKey);
            };
        }

        // Also observe success-modal in case it is triggered directly
        const successModal = document.getElementById('success-modal');
        if (successModal) {
            const successObserver = new MutationObserver(function() {
                if (!successModal.classList.contains('hidden')) {
                    closeLayananSummaryModal();
                }
            });
            successObserver.observe(successModal, { attributes: true, attributeFilter: ['class'] });
        }

        // Re-enable summary button if the form's submit button gets re-enabled (e.g. on error)
        const btnSubmit = document.getElementById('btn-submit');
        if (btnSubmit) {
            const observer = new MutationObserver(function() {
                if (!btnSubmit.disabled) {
                    const btnConfirm = document.getElementById('btn-confirm-submit-summary');
                    const textEl = document.getElementById('btn-confirm-text');
                    if (btnConfirm && textEl && btnConfirm.disabled) {
                        btnConfirm.disabled = false;
                        textEl.textContent = 'Kirim Sekarang';
                    }
                }
            });
            observer.observe(btnSubmit, { attributes: true, attributeFilter: ['disabled'] });
        }
    });
</script>
