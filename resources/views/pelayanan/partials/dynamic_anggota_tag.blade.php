<!-- Container Kolom Nama Anggota Otomatis Bertambah / Berkurang dengan Fitur Tag Akun Terdaftar -->
<div id="container-dynamic-anggota" style="display: none;" class="md:col-span-2 flex flex-col gap-stack-md"></div>

<script>
    const registeredUsersList = @json($registeredUsers ?? []);
    let anggotaSelectedUsers = {}; // map of { index: userObj }

    function renderDynamicAnggota(totalPeserta) {
        const container = document.getElementById('container-dynamic-anggota');
        if (!container) return;

        // Simpan nilai input saat ini jika ada
        const existingData = {};
        for (let i = 2; i <= 50; i++) {
            const input = document.getElementById(`input-anggota-${i}`);
            const userIdInput = document.getElementById(`user-id-anggota-${i}`);
            if (input) {
                existingData[i] = {
                    name: input.value,
                    userId: userIdInput ? userIdInput.value : '',
                    userObj: anggotaSelectedUsers[i] || null
                };
            }
        }

        let html = '';
        for (let i = 2; i <= totalPeserta; i++) {
            const cur = existingData[i] || { name: '', userId: '', userObj: null };
            if (cur.userObj) {
                anggotaSelectedUsers[i] = cur.userObj;
            }
            const campusText = cur.userObj ? (cur.userObj.asal_instansi || '') : '';

            html += `
                <div class="flex flex-col gap-stack-sm" id="row-anggota-${i}">
                    <div class="flex items-center justify-between">
                        <label class="font-label-md text-label-md text-on-surface flex items-center gap-1.5 font-medium">
                            <span class="material-symbols-outlined text-secondary text-[18px]">person</span>
                            <span>Nama Anggota ${i}</span> <span class="text-error">*</span>
                        </label>
                        <span class="text-[11px] text-on-surface-variant font-normal">Tag akun terdaftar di Bangga Online</span>
                    </div>

                    <div class="relative">
                        <div class="flex items-center rounded-lg border border-outline-variant bg-surface-bright focus-within:border-secondary focus-within:ring-2 focus-within:ring-secondary/20 transition-all">
                            <span class="material-symbols-outlined text-secondary ml-3 text-[20px] shrink-0">person_search</span>
                            <input type="text" 
                                   id="input-anggota-${i}" 
                                   name="nama_anggota[]" 
                                   class="w-full bg-transparent border-0 p-3 text-sm text-on-surface focus:outline-none focus:ring-0 placeholder:text-on-surface-variant/60" 
                                   placeholder="Ketik untuk mencari nama anggota terdaftar..." 
                                   autocomplete="off" 
                                   required 
                                   value="${cur.name || ''}"
                                   onfocus="showAnggotaSuggestions(${i})"
                                   oninput="filterAnggotaSuggestions(${i}, this.value)">
                            <input type="hidden" id="user-id-anggota-${i}" name="anggota_user_ids[]" value="${cur.userId || ''}">
                            <button type="button" id="btn-clear-anggota-${i}" onclick="clearAnggotaInput(${i})" class="${cur.name ? '' : 'hidden'} mr-2 p-1 text-on-surface-variant hover:text-error rounded-full transition-colors cursor-pointer" title="Hapus / ganti anggota">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>

                        <!-- Floating Suggestions Dropdown -->
                        <div id="suggestions-anggota-${i}" class="hidden absolute left-0 right-0 top-full mt-1 bg-surface-container-lowest border border-outline-variant/40 rounded-xl shadow-2xl z-50 max-h-52 overflow-y-auto divide-y divide-outline-variant/15">
                        </div>
                    </div>

                    <div id="badge-anggota-${i}" class="${campusText ? '' : 'hidden'} text-xs text-secondary font-medium flex items-center gap-1 mt-0.5 ml-1">
                        <span class="material-symbols-outlined text-[14px]">school</span>
                        <span>Terdaftar: <strong class="badge-kampus-text">${campusText}</strong></span>
                    </div>
                </div>
            `;
        }

        container.innerHTML = html;
    }

    function showAnggotaSuggestions(index) {
        // Tutup semua suggestion lain
        document.querySelectorAll('[id^="suggestions-anggota-"]').forEach(el => {
            if (el.id !== `suggestions-anggota-${index}`) el.classList.add('hidden');
        });

        const input = document.getElementById(`input-anggota-${index}`);
        filterAnggotaSuggestions(index, input ? input.value : '');
    }

    function filterAnggotaSuggestions(index, query) {
        const popup = document.getElementById(`suggestions-anggota-${index}`);
        const clearBtn = document.getElementById(`btn-clear-anggota-${index}`);
        if (!popup) return;

        if (clearBtn) {
            if (query && query.trim().length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        const q = (query || '').trim().toLowerCase();

        // Cari user yang sudah dipilih di baris anggota lain agar tidak duplikat
        const selectedUserIds = [];
        for (let k in anggotaSelectedUsers) {
            if (parseInt(k) !== index && anggotaSelectedUsers[k]) {
                selectedUserIds.push(anggotaSelectedUsers[k].id);
            }
        }

        const filtered = registeredUsersList.filter(u => {
            if (selectedUserIds.includes(u.id)) return false;
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
                <div onclick="selectAnggotaForIndex(${index}, ${u.id})" class="p-2.5 hover:bg-surface-container cursor-pointer flex items-center justify-between gap-2 text-xs transition-colors">
                    <div class="flex items-center gap-2.5 truncate">
                        <span class="w-7 h-7 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs shrink-0">
                            ${(u.name || 'U').charAt(0).toUpperCase()}
                        </span>
                        <div class="truncate text-left">
                            <strong class="text-on-surface text-sm block truncate">${u.name}</strong>
                            <span class="text-on-surface-variant text-[11px]">${u.asal_instansi || 'Instansi / Kampus'} &bull; ${u.email}</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-secondary text-[18px] shrink-0">check_circle</span>
                </div>
            `).join('');
        }

        popup.classList.remove('hidden');
    }

    function selectAnggotaForIndex(index, userId) {
        const user = registeredUsersList.find(u => u.id == userId);
        if (!user) return;

        const input = document.getElementById(`input-anggota-${index}`);
        const userIdInput = document.getElementById(`user-id-anggota-${index}`);
        const badge = document.getElementById(`badge-anggota-${index}`);
        const popup = document.getElementById(`suggestions-anggota-${index}`);
        const clearBtn = document.getElementById(`btn-clear-anggota-${index}`);

        if (input) input.value = user.name;
        if (userIdInput) userIdInput.value = user.id;
        anggotaSelectedUsers[index] = user;

        if (badge) {
            badge.classList.remove('hidden');
            const kampusSpan = badge.querySelector('.badge-kampus-text');
            if (kampusSpan) kampusSpan.textContent = user.asal_instansi || 'Akun Terdaftar Bangga Online';
        }

        if (clearBtn) clearBtn.classList.remove('hidden');
        if (popup) popup.classList.add('hidden');
    }

    function clearAnggotaInput(index) {
        const input = document.getElementById(`input-anggota-${index}`);
        const userIdInput = document.getElementById(`user-id-anggota-${index}`);
        const badge = document.getElementById(`badge-anggota-${index}`);
        const popup = document.getElementById(`suggestions-anggota-${index}`);
        const clearBtn = document.getElementById(`btn-clear-anggota-${index}`);

        if (input) {
            input.value = '';
            input.focus();
        }
        if (userIdInput) userIdInput.value = '';
        delete anggotaSelectedUsers[index];

        if (badge) badge.classList.add('hidden');
        if (clearBtn) clearBtn.classList.add('hidden');
        if (popup) popup.classList.add('hidden');
    }

    // Tutup popup jika klik di luar
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[id^="row-anggota-"]')) {
            document.querySelectorAll('[id^="suggestions-anggota-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });
</script>
