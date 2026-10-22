<!-- Compact Tag Input Anggota Kelompok Terdaftar -->
<div id="container-dynamic-anggota" style="display: none;" class="md:col-span-2 flex flex-col gap-stack-sm">
    <div class="flex items-center justify-between">
        <label class="font-label-md text-label-md text-on-surface font-medium flex items-center gap-1.5">
            <span class="material-symbols-outlined text-secondary text-[18px]">person_add</span>
            <span>Nama Anggota Kelompok</span> <span class="text-error">*</span>
        </label>
        <span id="badge-total-anggota" class="text-xs font-semibold text-secondary bg-secondary/10 px-2.5 py-0.5 rounded-full border border-secondary/20">
            0 Anggota Di-tag
        </span>
    </div>
    <p class="font-caption text-caption text-on-surface-variant">Ketik nama untuk men-tag akun anggota yang telah terdaftar di Bangga Online</p>

    <!-- Compact Input Box (Menyerupai input form standar) -->
    <div class="relative">
        <div id="tag-box-wrapper" class="w-full min-h-[46px] rounded-lg border border-outline-variant bg-surface-bright p-1.5 flex flex-wrap items-center gap-1.5 focus-within:border-secondary focus-within:ring-2 focus-within:ring-secondary/20 transition-all cursor-text" onclick="document.getElementById('input-tag-search').focus()">
            <!-- Inline Tag Chips -->
            <div id="tag-chips-list" class="flex flex-wrap items-center gap-1.5"></div>

            <!-- Inline Search Input -->
            <input type="text" id="input-tag-search" 
                   class="flex-1 min-w-[140px] bg-transparent border-0 p-1 text-sm text-on-surface focus:outline-none focus:ring-0 placeholder:text-on-surface-variant/60" 
                   placeholder="Ketik nama atau kampus anggota..." 
                   autocomplete="off">
        </div>

        <!-- Floating Dropdown Suggestions (Melayang absolut) -->
        <div id="tag-suggestions-popup" class="hidden absolute left-0 right-0 top-full mt-1 bg-surface-container-lowest border border-outline-variant/40 rounded-xl shadow-2xl z-50 max-h-56 overflow-y-auto divide-y divide-outline-variant/15">
        </div>
    </div>

    <!-- Hidden input container -->
    <div id="tag-hidden-inputs"></div>
    <input type="hidden" id="input-jumlah-anggota" name="jumlah_anggota_count" value="1">
</div>

<script>
    (function() {
        const availableUsers = @json($registeredUsers ?? []);
        let selectedAnggota = [];

        const container = document.getElementById('container-dynamic-anggota');
        const boxWrapper = document.getElementById('tag-box-wrapper');
        const chipsList = document.getElementById('tag-chips-list');
        const searchInput = document.getElementById('input-tag-search');
        const popup = document.getElementById('tag-suggestions-popup');
        const hiddenInputs = document.getElementById('tag-hidden-inputs');
        const badge = document.getElementById('badge-total-anggota');
        const inputJumlah = document.getElementById('input-jumlah-anggota');

        function updateTagsUI() {
            if (!chipsList || !hiddenInputs) return;

            // Render Chips
            chipsList.innerHTML = selectedAnggota.map((user, idx) => `
                <span class="inline-flex items-center gap-1.5 bg-secondary/10 text-secondary text-xs font-semibold px-2.5 py-1 rounded-md border border-secondary/25 shadow-xs">
                    <span class="material-symbols-outlined text-[14px]">person</span>
                    <span>${user.name}</span>
                    <span class="text-on-surface-variant/70 font-normal">(${user.asal_instansi || 'Bangga Online'})</span>
                    <button type="button" onclick="window.removeAnggotaTag(${idx})" class="w-4 h-4 rounded-full flex items-center justify-center hover:bg-secondary/20 hover:text-error transition-colors font-bold ml-0.5 cursor-pointer" title="Hapus tag">&times;</button>
                </span>
            `).join('');

            // Render Hidden Inputs
            hiddenInputs.innerHTML = selectedAnggota.map(user => `
                <input type="hidden" name="nama_anggota[]" value="${user.name}">
                <input type="hidden" name="anggota_user_ids[]" value="${user.id}">
            `).join('');

            // Update Badge & Counter
            const count = selectedAnggota.length;
            if (badge) {
                badge.textContent = `${count} Anggota Di-tag (Total: ${count + 1} Orang)`;
                if (count > 0) {
                    badge.className = "text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-300";
                } else {
                    badge.className = "text-xs font-semibold text-secondary bg-secondary/10 px-2.5 py-0.5 rounded-full border border-secondary/20";
                }
            }

            if (inputJumlah) {
                inputJumlah.value = count + 1;
            }

            // Trigger kuota dinas update
            if (typeof updateRealtimeDinasKuota === 'function') {
                updateRealtimeDinasKuota();
            }
        }

        window.removeAnggotaTag = function(index) {
            selectedAnggota.splice(index, 1);
            updateTagsUI();
            if (searchInput) searchInput.focus();
        };

        window.selectAnggotaUser = function(userId) {
            const user = availableUsers.find(u => u.id == userId);
            if (!user) return;

            // Cegah duplikasi
            if (!selectedAnggota.some(u => u.id == user.id)) {
                selectedAnggota.push(user);
                updateTagsUI();
            }

            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
            if (popup) {
                popup.classList.add('hidden');
            }
        };

        function renderSuggestions(query) {
            if (!popup) return;
            const q = (query || '').trim().toLowerCase();

            // Filter users yang belum dipilih
            const filtered = availableUsers.filter(u => {
                const alreadySelected = selectedAnggota.some(s => s.id == u.id);
                if (alreadySelected) return false;
                if (!q) return true;
                return (u.name && u.name.toLowerCase().includes(q)) ||
                       (u.asal_instansi && u.asal_instansi.toLowerCase().includes(q)) ||
                       (u.email && u.email.toLowerCase().includes(q));
            });

            if (filtered.length === 0) {
                popup.innerHTML = `
                    <div class="p-3 text-xs text-center text-on-surface-variant">
                        Tidak ada akun peserta terdaftar yang cocok dengan <em>"${query}"</em>.
                    </div>
                `;
            } else {
                popup.innerHTML = filtered.slice(0, 10).map(u => `
                    <div onclick="window.selectAnggotaUser(${u.id})" class="p-2.5 hover:bg-surface-container cursor-pointer flex items-center justify-between gap-2 text-xs transition-colors">
                        <div class="flex items-center gap-2 truncate">
                            <span class="w-6 h-6 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-[11px] shrink-0">
                                ${(u.name || 'U').charAt(0).toUpperCase()}
                            </span>
                            <div class="truncate text-left">
                                <strong class="text-on-surface text-sm block truncate">${u.name}</strong>
                                <span class="text-on-surface-variant text-[11px]">${u.asal_instansi || 'Instansi/Kampus'} &bull; ${u.email}</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-secondary text-[16px] shrink-0">add_circle</span>
                    </div>
                `).join('');
            }

            popup.classList.remove('hidden');
        }

        if (searchInput) {
            searchInput.addEventListener('focus', () => {
                renderSuggestions(searchInput.value);
            });

            searchInput.addEventListener('input', (e) => {
                renderSuggestions(e.target.value);
            });

            // Backspace menghapus tag terakhir jika input kosong
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && searchInput.value === '' && selectedAnggota.length > 0) {
                    selectedAnggota.pop();
                    updateTagsUI();
                } else if (e.key === 'Escape') {
                    if (popup) popup.classList.add('hidden');
                }
            });
        }

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', (e) => {
            if (boxWrapper && !boxWrapper.contains(e.target) && popup && !popup.contains(e.target)) {
                popup.classList.add('hidden');
            }
        });

        // Global getter helper untuk form validation & kuota calculation
        window.getJumlahPendaftarSaatIni = function() {
            const form = document.querySelector('form');
            const checkedRadio = form ? form.querySelector('input[name="jumlah_peserta"]:checked') : null;
            if (checkedRadio && checkedRadio.value.includes('Kelompok')) {
                return selectedAnggota.length + 1;
            }
            return 1;
        };

        window.getSelectedAnggotaKelompok = function() {
            return selectedAnggota;
        };
    })();
</script>
