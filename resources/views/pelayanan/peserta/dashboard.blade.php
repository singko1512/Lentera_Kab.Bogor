@extends('layouts.app')

@section('title', 'Dashboard Peserta Magang')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    :root {
        --radius-lg: 16px;
        --radius-md: 12px;
        --radius-sm: 8px;
        --border-color: #e2e8f0;
        --primary-blue: #2563eb;
        --primary-blue-hover: #1d4ed8;
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05); border-radius: 1.25rem; }
    .gradient-text { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    /* ── Workspace Header ── */
    .workspace-header h5 {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 0.25rem 0;
    }
    .workspace-header span {
        font-size: 0.82rem;
        color: #64748b;
    }

    /* ── Modern Card Base ── */
    .presensi-card, .stat-widget, .history-wrap {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 2px 8px -1px rgba(0, 0, 0, 0.02);
        padding: 1.5rem;
    }

    .card-heading {
        font-size: 0.98rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }
    .card-heading i {
        color: var(--primary-blue);
        font-size: 0.95rem;
    }

    /* ── Status Grid & Cards ── */
    .form-label-custom {
        font-size: 0.84rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.55rem;
        display: block;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }
    @media (max-width: 640px) {
        .status-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .status-card {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: #ffffff;
        padding: 1rem 0.85rem;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        height: 100%;
        margin-bottom: 0;
    }
    .status-card:hover {
        border-color: #93c5fd;
        background: #f8faff;
    }
    .btn-check:checked + .status-card {
        border: 2px solid var(--primary-blue) !important;
        background: #ffffff;
        box-shadow: 0 0 0 1px var(--primary-blue);
    }
    .btn-check:disabled + .status-card {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8fafc;
        border-color: #e2e8f0;
        pointer-events: none;
    }

    .s-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }
    .s-icon-hadir { background: #d1fae5; color: #059669; }
    .s-icon-wfh { background: #dbeafe; color: var(--primary-blue); }
    .s-icon-sakit { background: #fee2e2; color: #ef4444; }
    .s-icon-izin { background: #fef3c7; color: #d97706; }

    .s-name {
        font-weight: 700;
        font-size: 0.86rem;
        color: #1e293b;
    }
    .s-desc {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 2px;
    }

    /* ── Location Section ── */
    .location-panel { display: none; }
    .location-panel.show { display: block; }

    .location-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 1rem;
    }

    /* ── Camera Input Card ── */
    .camera-start-actions {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .camera-panel {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        overflow: hidden;
        background: #f8fafc;
        margin-top: 0.75rem;
        position: relative;
    }
    .camera-panel video {
        display: block;
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        background: #f8fafc;
    }
    .camera-preview {
        display: none;
        width: 100%;
        max-height: 420px;
        object-fit: contain;
        background: #f8fafc;
    }

    /* ── Submit Button ── */
    .btn-submit-absen {
        background: var(--primary-blue);
        border: 1px solid var(--primary-blue);
        color: #ffffff;
        border-radius: 10px;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        font-size: 0.92rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: background 0.2s;
    }
    .btn-submit-absen:hover {
        background: var(--primary-blue-hover);
        border-color: var(--primary-blue-hover);
        color: #ffffff;
    }

    /* ── Upload Zone ── */
    .upload-zone {
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        background: #fafbff;
    }
    .upload-zone:hover { border-color: var(--primary-blue); background: rgba(37, 99, 235, 0.02); }
    .upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .upload-preview { display: none; width: 100%; max-height: 180px; margin-top: 0.75rem; border-radius: 8px; object-fit: contain; }
    .file-name { display: none; margin-top: 0.5rem; font-size: 0.8rem; font-weight: 600; color: var(--primary-blue); }

    /* ── Monthly Stats Widget ── */
    .pct-ring-bg { stroke: #e2e8f0; }
    .pct-ring-fill { stroke: var(--primary-blue); transition: stroke-dashoffset 0.4s ease; transform: rotate(-90deg); transform-origin: 50% 50%; }

    .stat-row-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0.9rem;
        border-radius: 10px;
        margin-bottom: 0.45rem;
    }
    .stat-row-item:last-child { margin-bottom: 0; }

    .stat-row-hadir { background: #f0fdf4; }
    .stat-row-wfh { background: #eff6ff; }
    .stat-row-sakit { background: #fef2f2; }
    .stat-row-izin { background: #fffbeb; }

    .pill-badge {
        border-radius: 6px;
        padding: 0.25rem 0.65rem;
        font-weight: 700;
        font-size: 0.75rem;
    }
    .pill-badge-hadir { background: #dcfce7; color: #15803d; }
    .pill-badge-wfh { background: #dbeafe; color: #1d4ed8; }
    .pill-badge-sakit { background: #fee2e2; color: #b91c1c; }
    .pill-badge-izin { background: #fef3c7; color: #b45309; }

    /* ── History Table ── */
    .history-wrap { margin-top: 1.5rem; }
    .history-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
    .history-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.8rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    .history-table tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    .history-table tbody tr:last-child td { border-bottom: none; }
    .history-table tbody tr:hover { background: #fafbff; }

    .badge-status {
        display: inline-block;
        padding: 0.28em 0.65em;
        font-size: 0.68rem;
        font-weight: 700;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-hadir { background: #dcfce7; color: #15803d; }
    .badge-wfh { background: #dbeafe; color: #1d4ed8; }
    .badge-sakit { background: #fee2e2; color: #b91c1c; }
    .badge-izin { background: #fef3c7; color: #b45309; }
    .attachment-thumb { width: 36px; height: 36px; border-radius: 6px; object-fit: cover; border: 1px solid var(--border-color); }
</style>
@endsection

@section('content')
<div class="min-h-screen pt-8 pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Top Navigation: Kembali ke Dashboard User -->
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 hover:text-blue-600 bg-white border border-gray-200 hover:border-blue-300 rounded-xl shadow-sm hover:shadow transition-all group">
                <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1 text-blue-600">arrow_back</span>
                <span>Kembali ke Dashboard User</span>
            </a>
            @if($magang)
                <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 font-medium bg-white px-3.5 py-2 rounded-xl border border-gray-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Peserta: <strong class="text-gray-800">{{ $magang->dinas->nama ?? ($magang->rekrutmen->dinas->nama ?? 'Magang Aktif') }}</strong></span>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-xl flex items-center gap-3 shadow-sm text-sm">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-5 py-3.5 rounded-xl flex items-center gap-3 shadow-sm text-sm">
                <span class="material-symbols-outlined text-blue-600 text-[20px]">info</span>
                <p class="font-medium">{{ session('info') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-xl flex items-center gap-3 shadow-sm text-sm">
                <span class="material-symbols-outlined text-red-600 text-[20px]">error</span>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if($magang)
        <div id="absensi" class="space-y-6">
            <!-- Workspace Header -->
            <div class="workspace-header mb-1">
                <h5>Absensi dan Laporan Harian</h5>
                <span>Catat kehadiran harian dan buat laporan kegiatan magang Anda secara rutin</span>
            </div>

            <!-- Layout Grid Absensi (Left: Form, Right: Bulanan) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Form Absensi (lg:col-span-8) -->
                <div class="lg:col-span-8">
                    <div class="presensi-card h-full">
                        @if(in_array(auth()->user()->status_akun ?? 'aktif', ['diblokir', 'dibatasi']))
                            <div class="text-center py-8 flex flex-col justify-center items-center">
                                <div class="rounded-full inline-flex items-center justify-center mb-3 w-16 h-16 bg-red-50 text-red-600">
                                    <i class="fa-solid fa-user-lock text-3xl"></i>
                                </div>
                                <h5 class="font-bold text-red-600">Akun Anda {{ ucwords(auth()->user()->status_akun) }}</h5>
                                <p class="text-gray-500 text-xs">Anda tidak dapat mengisi absensi maupun mengumpulkan laporan karena akun Anda telah dibatasi atau diblokir.</p>
                            </div>
                        @elseif ($absensiHariIni && in_array(strtolower($absensiHariIni->status ?? ''), ['sakit', 'izin']))
                            <!-- Presensi Hari Ini: Sakit / Izin (Tidak ada kewajiban pulang & tidak ada jurnal) -->
                            <div class="text-center py-8 h-full flex flex-col justify-center items-center">
                                <div class="rounded-full inline-flex items-center justify-center mb-3 w-16 h-16 bg-amber-50 text-amber-600">
                                    <i class="fa-solid fa-calendar-check text-2xl"></i>
                                </div>
                                <h6 class="font-bold text-gray-900 text-base">Presensi Hari Ini: {{ ucfirst($absensiHariIni->status) }}</h6>
                                <p class="text-gray-500 text-xs mt-1.5 max-w-sm">Anda telah tercatat <strong>{{ strtoupper($absensiHariIni->status) }}</strong> hari ini. Tidak ada kewajiban absen pulang maupun pengisian jurnal kegiatan.</p>
                                <div class="flex flex-wrap justify-center gap-2 mt-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-700 text-xs font-semibold rounded-full border border-gray-200">
                                        <i class="fa-solid fa-clock text-blue-600"></i> Dicatat: {{ $absensiHariIni->waktu_masuk ?? '-' }}
                                    </span>
                                    @if(!empty($absensiHariIni->catatan))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-800 text-xs font-medium rounded-full border border-amber-200">
                                            <i class="fa-solid fa-circle-info text-amber-600"></i> Keterangan: {{ $absensiHariIni->catatan }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @elseif ($absensiHariIni && !is_null($absensiHariIni->waktu_masuk) && !is_null($absensiHariIni->waktu_pulang))
                            <!-- Completed attendance (Masuk & Pulang) -->
                            <div class="text-center py-8 h-full flex flex-col justify-center items-center">
                                <div class="rounded-full inline-flex items-center justify-center mb-3 w-16 h-16 bg-emerald-50 text-emerald-600">
                                    <i class="fa-solid fa-circle-check text-3xl"></i>
                                </div>
                                <h6 class="font-bold text-gray-900 text-base">Presensi Hari Ini Lengkap</h6>
                                <p class="text-gray-500 text-xs mt-1 max-w-sm">Anda telah mengisi absensi masuk dan absen pulang beserta jurnal kegiatan untuk hari ini.</p>
                                <div class="flex flex-wrap justify-center gap-3 mt-4">
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-gray-50 text-gray-700 text-xs font-semibold rounded-full border border-gray-200">
                                        <i class="fa-solid fa-arrow-right-to-bracket text-blue-600"></i> Masuk: {{ $absensiHariIni->waktu_masuk }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-gray-50 text-gray-700 text-xs font-semibold rounded-full border border-gray-200">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-emerald-600"></i> Pulang: {{ $absensiHariIni->waktu_pulang }}
                                    </span>
                                </div>
                            </div>
                        @elseif ($absensiHariIni && !is_null($absensiHariIni->waktu_masuk) && is_null($absensiHariIni->waktu_pulang) && in_array(strtolower($absensiHariIni->status ?? ''), ['hadir', 'wfh']))
                            <!-- Mode Mau Pulang (WFO / WFH): Absen Pulang & Jurnal Kegiatan Harian -->
                            <div class="card-heading">
                                <i class="fa-solid fa-right-from-bracket text-blue-600"></i>
                                <span>Absen Pulang & Jurnal Kegiatan Harian</span>
                            </div>
                            <p class="text-gray-500 text-xs mb-4">Wajib mengunci lokasi pulang, mengunggah bukti pengerjaan, dan menulis laporan kegiatan harian Anda.</p>

                            <form action="{{ url('peserta/check-out') }}" method="POST" enctype="multipart/form-data" id="checkoutAttendanceForm" class="space-y-4">
                                @csrf
                                <input type="hidden" name="status" value="{{ $absensiHariIni->status ?? 'hadir' }}">
                                <input type="hidden" name="lokasi_latitude" id="co_lokasi_latitude">
                                <input type="hidden" name="lokasi_longitude" id="co_lokasi_longitude">
                                <input type="hidden" name="lokasi_akurasi" id="co_lokasi_akurasi">

                                <!-- Verifikasi Lokasi Pulang -->
                                <div class="location-panel show" id="co_location_section">
                                    <label class="form-label-custom">Verifikasi Lokasi Pulang <span class="text-red-500">*</span></label>
                                    <div class="location-box flex flex-col gap-2">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-location-dot text-blue-600 text-base"></i>
                                            <span id="co_location_status" class="text-xs font-semibold text-gray-800">Klik Kunci Lokasi untuk memverifikasi posisi Anda.</span>
                                            <span id="co_location_spinner" class="inline-block animate-spin text-blue-600 text-xs hidden">⏳</span>
                                        </div>
                                        <div id="co_location_address" class="text-xs text-gray-600 hidden items-start gap-1"></div>
                                        <div class="pt-1">
                                            <button type="button" class="btn btn-outline-primary btn-sm font-semibold inline-flex items-center gap-1.5" id="co_lock_location" style="border-radius: 8px; padding: 6px 14px; font-size: 0.82rem; background: #fff;">
                                                <i class="fa-solid fa-location-dot"></i> Kunci Lokasi
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bukti Pengerjaan Pulang -->
                                <div>
                                    <label class="form-label-custom">Unggah Gambar Bukti Pengerjaan <span class="text-red-500">*</span></label>
                                    <div class="upload-zone" id="co_upload_zone">
                                        <i class="fa-solid fa-cloud-arrow-up text-gray-400 block mb-1 text-2xl"></i>
                                        <div class="text-xs font-semibold text-gray-800" id="co_upload_text">Klik untuk pilih gambar bukti kerja</div>
                                        <input type="file" name="foto" id="co_foto" accept="image/*" required>
                                        <div class="file-name" id="co_file_name">
                                            <i class="fa-solid fa-check-circle me-1"></i><span id="co_fname"></span>
                                        </div>
                                        <img src="" class="upload-preview" id="co_foto_preview" alt="Preview">
                                    </div>
                                </div>

                                <!-- Jurnal Kegiatan Harian (Muncul saat mau pulang) -->
                                <div>
                                    <label for="co_keterangan" class="form-label-custom">Jurnal Kegiatan Harian (Laporan Hasil Pekerjaan) <span class="text-red-500">*</span></label>
                                    <textarea name="keterangan" id="co_keterangan" rows="3" class="w-full text-xs border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Tuliskan uraian kegiatan, tugas, atau progres magang yang Anda kerjakan hari ini..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-submit-absen" id="btn_submit_co">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim Absen Pulang & Simpan Jurnal
                                </button>
                            </form>
                        @else
                            <!-- Absen Masuk Form (100% IDENTIK DENGAN GAMBAR) -->
                            <div class="card-heading">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                <span>Absen Masuk</span>
                            </div>

                            <form action="{{ url('peserta/check-in') }}" method="POST" enctype="multipart/form-data" id="attendanceForm">
                                @csrf
                                <input type="hidden" name="lokasi_latitude" id="lokasi_latitude" value="{{ old('lokasi_latitude') }}">
                                <input type="hidden" name="lokasi_longitude" id="lokasi_longitude" value="{{ old('lokasi_longitude') }}">
                                <input type="hidden" name="lokasi_akurasi" id="lokasi_akurasi" value="{{ old('lokasi_akurasi') }}">

                                <!-- Status Absensi Radios -->
                                <div class="mb-3">
                                    <label class="form-label-custom">Status Absensi Masuk <span class="text-red-500">*</span></label>
                                    <div class="status-grid">
                                        <!-- Hadir -->
                                        <div>
                                            <input type="radio" class="btn-check" name="status" id="status_hadir" value="hadir" {{ old('status', 'hadir') === 'hadir' ? 'checked' : '' }} autocomplete="off">
                                            <label class="status-card" for="status_hadir">
                                                <div class="s-icon s-icon-hadir"><i class="fa-solid fa-store"></i></div>
                                                <div class="s-name">Hadir</div>
                                                <div class="s-desc">Di kantor</div>
                                            </label>
                                        </div>

                                        <!-- WFH -->
                                        <div>
                                            <input type="radio" class="btn-check" name="status" id="status_wfh" value="wfh" {{ old('status') === 'wfh' ? 'checked' : '' }} autocomplete="off">
                                            <label class="status-card" for="status_wfh">
                                                <div class="s-icon s-icon-wfh"><i class="fa-solid fa-house"></i></div>
                                                <div class="s-name">WFH</div>
                                                <div class="s-desc">Kerja dari luar kantor</div>
                                            </label>
                                        </div>

                                        <!-- Sakit -->
                                        <div>
                                            <input type="radio" class="btn-check" name="status" id="status_sakit" value="sakit" {{ old('status') === 'sakit' ? 'checked' : '' }} autocomplete="off">
                                            <label class="status-card" for="status_sakit">
                                                <div class="s-icon s-icon-sakit"><i class="fa-solid fa-face-frown"></i></div>
                                                <div class="s-name">Sakit</div>
                                                <div class="s-desc">Izin medis</div>
                                            </label>
                                        </div>

                                        <!-- Izin -->
                                        <div>
                                            <input type="radio" class="btn-check" name="status" id="status_izin" value="izin" {{ old('status') === 'izin' ? 'checked' : '' }} autocomplete="off">
                                            <label class="status-card" for="status_izin">
                                                <div class="s-icon s-icon-izin"><i class="fa-regular fa-file-lines"></i></div>
                                                <div class="s-name">Izin</div>
                                                <div class="s-desc">Keperluan lain</div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Locker Section -->
                                <div class="mb-3 location-panel show" id="location_section">
                                    <label class="form-label-custom" id="location_label">Lokasi Hadir (Kantor) <span class="text-red-500">*</span></label>
                                    <div class="location-box flex flex-col gap-2">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-location-dot" style="color: #2563eb; font-size: 1.1rem;"></i>
                                            <span id="location_status" class="small fw-semibold text-dark">Klik Kunci Lokasi untuk memverifikasi posisi Anda di kantor.</span>
                                            <span id="location_spinner" class="spinner-border spinner-border-sm text-primary" style="display:none;" role="status"></span>
                                        </div>
                                        <div id="location_address" class="small text-muted align-items-start gap-1" style="display:none; color: #334155 !important;"></div>
                                        <div class="location-meta flex flex-wrap gap-1 items-center" style="display:none;">
                                            <span class="badge bg-secondary-subtle text-dark" id="location_accuracy" style="display:none;">Akurasi: -</span>
                                            <span class="badge bg-secondary-subtle text-dark" id="location_coordinates" style="display:none;">Koordinat: -</span>
                                            <a href="#" target="_blank" id="location_map_link" class="badge bg-primary-subtle text-primary text-decoration-none" style="display:none;">
                                                <i class="fa-solid fa-map-location-dot me-1"></i> Buka Google Maps
                                            </a>
                                        </div>
                                        <div class="pt-1">
                                            <button type="button" class="btn btn-outline-primary btn-sm fw-semibold inline-flex items-center gap-1.5" id="lock_location" style="border-radius: 8px; padding: 6px 14px; font-size: 0.82rem; background: #fff;">
                                                <i class="fa-solid fa-location-dot"></i> Kunci Lokasi
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Camera Capture Section -->
                                <div class="mb-3" id="camera_section">
                                    <label class="form-label-custom" id="camera_label">Foto Kamera <span class="text-red-500">*</span></label>
                                    <input type="file" name="foto_kamera" id="foto_kamera" accept="image/*" class="d-none">
                                    <div class="camera-start-actions" id="camera_start_actions">
                                        <span class="camera-message small text-muted" id="camera_start_message" style="font-size: 0.84rem;">Nyalakan kamera lalu ambil foto diri untuk bukti.</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary inline-flex items-center gap-1.5" id="start_camera" style="border-radius: 8px; padding: 6px 14px; font-size: 0.82rem; color: #475569; border-color: #cbd5e1; background: #f8fafc;">
                                            <i class="fa-solid fa-video"></i> Kamera
                                        </button>
                                    </div>
                                    <div class="camera-panel" id="camera_panel" style="display: none;">
                                        <video id="camera_video" autoplay playsinline muted></video>
                                        <img src="" class="camera-preview" id="camera_preview" alt="Pratinjau foto">
                                        <canvas id="camera_canvas" class="d-none"></canvas>
                                        <div class="camera-actions p-2 bg-light border-top flex justify-between items-center">
                                            <span class="camera-message small text-muted" id="camera_message">Kamera Aktif</span>
                                            <button type="button" class="btn btn-primary btn-sm rounded-2 px-3 fw-semibold" id="capture_photo" style="background: #2563eb; border-color: #2563eb;">
                                                <i class="fa-solid fa-camera me-1"></i> Ambil Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload File (for Sakit / Izin) -->
                                <div class="mb-3" id="photo_section" style="display: none;">
                                    <label id="foto_label" class="form-label-custom">Surat Keterangan / Lampiran</label>
                                    <div class="upload-zone p-3">
                                        <i class="fa-solid fa-cloud-arrow-up text-muted block mb-1" style="font-size: 1.5rem;"></i>
                                        <div class="small fw-semibold text-dark" id="upload_text">Klik untuk unggah berkas</div>
                                        <input type="file" name="foto" id="foto" accept="image/*,.pdf">
                                        <div class="file-name text-primary small mt-1" id="file_name" style="display: none;">
                                            <i class="fa-solid fa-check-circle me-1"></i><span id="fname"></span>
                                        </div>
                                        <img src="" class="upload-preview" id="foto_preview" style="display: none;">
                                    </div>
                                </div>

                                <!-- Laporan / Alasan (Izin / Sakit) -->
                                <div class="mb-3" id="laporan_section" style="display: none;">
                                    <label for="laporan" id="laporan_label" class="form-label-custom">Catatan/Alasan <span class="text-red-500">*</span></label>
                                    <textarea name="keterangan" id="laporan" rows="2" class="form-control w-100" style="border-radius: 10px; border-color: #cbd5e1; font-size: 0.88rem;" placeholder="Rincian alasan atau keterangan tambahan...">{{ old('keterangan') }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-submit-absen mt-3" id="btn_submit_absen">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim Absen Masuk
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Kolom Kanan: Kehadiran Bulanan (lg:col-span-4) -->
                <div class="lg:col-span-4">
                    <div class="stat-widget h-full">
                        <div class="card-heading">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Kehadiran Bulanan</span>
                        </div>
                        
                        <div class="flex items-center justify-center my-4">
                            <div style="width: 125px; height: 125px; position: relative;">
                                <svg viewBox="0 0 36 36" style="width: 100%; height: 100%;">
                                    <circle class="pct-ring-bg" cx="18" cy="18" r="16" fill="none" stroke-width="3.2" />
                                    <circle class="pct-ring-fill" cx="18" cy="18" r="16" fill="none" stroke-width="3.2" stroke-dasharray="{{ $stats['persentase'] ?? 0 }}, 100" />
                                </svg>
                                <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <span style="font-size: 1.45rem; font-weight: 800; color: #0f172a; line-height: 1;">{{ $stats['persentase'] ?? 0 }}%</span>
                                    <span class="text-muted" style="font-size: 0.72rem; margin-top: 4px;">Kehadiran</span>
                                </div>
                            </div>
                        </div>

                        <div class="stat-rows mt-3">
                            <div class="stat-row-item stat-row-hadir">
                                <div class="flex items-center gap-2">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                                    <span class="fw-semibold text-dark" style="font-size: 0.84rem;">Hadir</span>
                                </div>
                                <span class="pill-badge pill-badge-hadir">{{ $stats['hadir'] ?? 0 }} hari</span>
                            </div>
                            <div class="stat-row-item stat-row-wfh">
                                <div class="flex items-center gap-2">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; display: inline-block;"></span>
                                    <span class="fw-semibold text-dark" style="font-size: 0.84rem;">WFH</span>
                                </div>
                                <span class="pill-badge pill-badge-wfh">{{ $stats['wfh'] ?? 0 }} hari</span>
                            </div>
                            <div class="stat-row-item stat-row-sakit">
                                <div class="flex items-center gap-2">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                                    <span class="fw-semibold text-dark" style="font-size: 0.84rem;">Sakit</span>
                                </div>
                                <span class="pill-badge pill-badge-sakit">{{ $stats['sakit'] ?? 0 }} hari</span>
                            </div>
                            <div class="stat-row-item stat-row-izin">
                                <div class="flex items-center gap-2">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                                    <span class="fw-semibold text-dark" style="font-size: 0.84rem;">Izin</span>
                                </div>
                                <span class="pill-badge pill-badge-izin">{{ $stats['izin'] ?? 0 }} hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Kehadiran (Full Width Bottom) -->
            <div class="history-wrap">
                <div class="flex items-center justify-between mb-3">
                    <div class="card-heading mb-0">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Riwayat Kehadiran</span>
                    </div>
                    <span class="badge border bg-white" style="border-color: #e2e8f0 !important; color: #334155; border-radius: 6px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600;">{{ isset($riwayatAbsensi) ? $riwayatAbsensi->count() : 0 }} entri</span>
                </div>
                
                @if(!isset($riwayatAbsensi) || $riwayatAbsensi->isEmpty())
                    <div class="text-center py-5">
                        <div class="inline-flex items-center justify-center mb-3" style="width:52px; height:52px; border-radius: 12px; background:#eff6ff;">
                            <i class="fa-solid fa-calendar-xmark text-primary" style="font-size:1.35rem;"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Belum ada riwayat presensi</h6>
                        <p class="text-muted small mb-0" style="font-size: 0.82rem;">Catatan kehadiran Anda akan muncul di sini setelah melakukan absen masuk.</p>
                    </div>
                @else
                    <div class="table-responsive mt-2 overflow-x-auto">
                        <table class="history-table w-full">
                            <thead>
                                <tr>
                                    <th>Tanggal & Waktu</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Dokumentasi</th>
                                    <th>Catatan Pekerjaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayatAbsensi as $rec)
                                    <tr>
                                        <td>
                                            <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($rec->tanggal)->translatedFormat('d M Y') }}</div>
                                            <div class="text-gray-400 text-xs">{{ $rec->created_at ? $rec->created_at->format('H:i') : '-' }} WIB</div>
                                        </td>
                                        <td>
                                            <span class="badge-status badge-{{ $rec->status }}">
                                                {{ strtoupper($rec->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="font-semibold text-gray-800 text-xs">{{ $rec->jam_masuk ?? ($rec->waktu_masuk ?? '-') }}</span>
                                        </td>
                                        <td>
                                            <span class="font-semibold text-gray-800 text-xs">{{ $rec->jam_pulang ?? ($rec->waktu_pulang ?? '-') }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $hasDocs = false;
                                                $fotoMasukUrl = ($rec->foto_kamera || $rec->foto_masuk) ? route('absensi.kamera', ['absensi' => $rec->id, 'tipe' => 'masuk']) : null;
                                                $fotoPulangUrl = $rec->foto_pulang ? route('absensi.kamera', ['absensi' => $rec->id, 'tipe' => 'pulang']) : null;
                                                $suratUrl = $rec->surat_izin ? asset($rec->surat_izin) : (!empty($rec->foto) && !in_array(strtolower($rec->status), ['hadir', 'wfh']) ? route('absensi.lampiran', $rec) : null);
                                            @endphp
                                            <div class="flex items-center gap-2 flex-wrap">
                                                @if ($fotoMasukUrl)
                                                    @php $hasDocs = true; @endphp
                                                    <a href="{{ $fotoMasukUrl }}" target="_blank" title="Foto Masuk" class="relative inline-block group text-decoration-none">
                                                        <img src="{{ $fotoMasukUrl }}" class="attachment-thumb" alt="Foto Masuk" onerror="this.onerror=null; @if($rec->foto_masuk) this.src='{{ asset($rec->foto_masuk) }}'; @endif">
                                                        <span class="absolute -bottom-1 -right-1 bg-blue-600 text-white font-bold px-1 rounded text-[8px] leading-tight shadow-sm">In</span>
                                                    </a>
                                                @endif

                                                @if ($fotoPulangUrl)
                                                    @php $hasDocs = true; @endphp
                                                    <a href="{{ $fotoPulangUrl }}" target="_blank" title="Foto Pulang" class="relative inline-block group text-decoration-none">
                                                        <img src="{{ $fotoPulangUrl }}" class="attachment-thumb" alt="Foto Pulang" onerror="this.onerror=null; this.src='{{ asset($rec->foto_pulang) }}';">
                                                        <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white font-bold px-1 rounded text-[8px] leading-tight shadow-sm">Out</span>
                                                    </a>
                                                @endif

                                                @if ($suratUrl)
                                                    @php $hasDocs = true; @endphp
                                                    <a href="{{ $suratUrl }}" target="_blank" title="Surat Izin / Sakit" class="inline-flex items-center justify-center w-[36px] h-[36px] rounded bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 transition-colors">
                                                        <span class="material-symbols-outlined text-[18px]">description</span>
                                                    </a>
                                                @endif

                                                @if (!$hasDocs)
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td style="max-width: 250px;">
                                            @php
                                                $catatan = $rec->jurnal ? ($rec->jurnal->hasil_pekerjaan ?: $rec->jurnal->kegiatan) : ($rec->laporan ?: ($rec->keterangan ?: '-'));
                                                if (in_array(strtolower($rec->status), ['hadir', 'wfh']) && empty($rec->jam_pulang) && empty($rec->waktu_pulang)) {
                                                    $catatan = '-';
                                                } elseif ($catatan === 'Website absensi') {
                                                    $catatan = '-';
                                                }
                                            @endphp
                                            <div class="text-gray-500 text-xs truncate" title="{{ $catatan }}">
                                                {{ $catatan }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        @else
        <!-- Belum Terdaftar Magang -->
        <div class="glass-card p-8 text-center py-12">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">info</span>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Program Magang Belum Aktif</h3>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl font-semibold transition-colors text-sm">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Dashboard User
                </a>
                <a href="{{ route('landing.instansi') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-primary-dark transition-colors text-sm">
                    <span class="material-symbols-outlined text-[18px]">search</span> Cari Program Magang
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── 1. Check-In Elements & Logic ──
    const radios = document.querySelectorAll('input[name="status"]');
    const attendanceForm = document.getElementById('attendanceForm');
    const locationSection = document.getElementById('location_section');
    const locationLabel = document.getElementById('location_label');
    const locationStatus = document.getElementById('location_status');
    const locationAddress = document.getElementById('location_address');
    const locationSpinner = document.getElementById('location_spinner');
    const latitudeInput = document.getElementById('lokasi_latitude');
    const longitudeInput = document.getElementById('lokasi_longitude');
    const accuracyInput = document.getElementById('lokasi_akurasi');
    const lockLocationButton = document.getElementById('lock_location');

    const cameraSection = document.getElementById('camera_section');
    const cameraStartActions = document.getElementById('camera_start_actions');
    const startCameraButton = document.getElementById('start_camera');
    const cameraPanel = document.getElementById('camera_panel');
    const cameraVideo = document.getElementById('camera_video');
    const cameraCanvas = document.getElementById('camera_canvas');
    const cameraPreview = document.getElementById('camera_preview');
    const cameraMessage = document.getElementById('camera_message');
    const capturePhoto = document.getElementById('capture_photo');
    const fotoKameraInput = document.getElementById('foto_kamera');

    const photoSection = document.getElementById('photo_section');
    const fotoInput = document.getElementById('foto');
    const fileNameDiv = document.getElementById('file_name');
    const fnameSpan = document.getElementById('fname');
    const fotoPreview = document.getElementById('foto_preview');

    const laporanSection = document.getElementById('laporan_section');
    const laporanInput = document.getElementById('laporan');

    let cameraStream = null;
    let isAcquiringLocation = false;
    let activeWatchId = null;

    // ── Status Radio Toggle ──
    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            const val = this.value;
            if (val === 'hadir') {
                if (locationLabel) locationLabel.innerHTML = 'Lokasi Hadir (Kantor) <span class="text-red-500">*</span>';
                if (cameraSection) cameraSection.style.display = 'block';
                if (photoSection) photoSection.style.display = 'none';
                if (laporanSection) laporanSection.style.display = 'none';
                if (laporanInput) laporanInput.required = false;
            } else if (val === 'wfh') {
                if (locationLabel) locationLabel.innerHTML = 'Lokasi WFH <span class="text-red-500">*</span>';
                if (cameraSection) cameraSection.style.display = 'block';
                if (photoSection) photoSection.style.display = 'none';
                if (laporanSection) laporanSection.style.display = 'none';
                if (laporanInput) laporanInput.required = false;
            } else if (val === 'sakit') {
                if (locationLabel) locationLabel.innerHTML = 'Lokasi Presensi <span class="text-red-500">*</span>';
                if (cameraSection) cameraSection.style.display = 'block';
                if (photoSection) photoSection.style.display = 'block';
                if (laporanSection) laporanSection.style.display = 'block';
                if (laporanInput) laporanInput.required = false;
            } else if (val === 'izin') {
                if (locationLabel) locationLabel.innerHTML = 'Lokasi Presensi <span class="text-red-500">*</span>';
                if (cameraSection) cameraSection.style.display = 'none';
                if (photoSection) photoSection.style.display = 'block';
                if (laporanSection) laporanSection.style.display = 'block';
                if (laporanInput) {
                    laporanInput.required = true;
                    laporanInput.placeholder = 'Tuliskan rincian alasan izin Anda...';
                }
            }
        });
    });

    // ── Geolocation High Accuracy Acquisition ──
    function setLocation(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const accuracy = position.coords.accuracy;

        if (latitudeInput) latitudeInput.value = lat.toFixed(7);
        if (longitudeInput) longitudeInput.value = lng.toFixed(7);
        if (accuracyInput) accuracyInput.value = accuracy ? Math.round(accuracy) : '';

        if (locationStatus) {
            locationStatus.innerHTML = '<span class="text-green-600 font-semibold"><i class="fa-solid fa-circle-check me-1"></i> Lokasi terkunci</span>';
        }
        if (lockLocationButton) {
            lockLocationButton.innerHTML = '<i class="fa-solid fa-arrows-rotate me-1"></i> Perbarui Lokasi';
            lockLocationButton.disabled = false;
        }
        if (locationSpinner) locationSpinner.style.display = 'none';

        // Reverse Geocode
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
            headers: { 'Accept-Language': 'id,en' }
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.display_name && locationAddress) {
                locationAddress.innerHTML = `<i class="fa-solid fa-map-pin text-red-500 mt-0.5 flex-shrink-0 text-xs"></i> <span class="text-slate-600 text-xs">${data.display_name}</span>`;
                locationAddress.style.display = 'flex';
            }
        })
        .catch(() => {});
    }

    function setLocationError(msg) {
        if (locationStatus) locationStatus.innerText = msg;
        if (latitudeInput) latitudeInput.value = '';
        if (longitudeInput) longitudeInput.value = '';
        if (accuracyInput) accuracyInput.value = '';
        if (lockLocationButton) {
            lockLocationButton.innerHTML = '<i class="fa-solid fa-location-dot me-1"></i> Kunci Lokasi';
            lockLocationButton.disabled = false;
        }
        if (locationSpinner) locationSpinner.style.display = 'none';
    }

    function acquireLocation() {
        if (!navigator.geolocation) {
            setLocationError('Browser tidak mendukung pendeteksian lokasi GPS.');
            return;
        }
        if (isAcquiringLocation) return;
        isAcquiringLocation = true;

        if (lockLocationButton) {
            lockLocationButton.disabled = true;
            lockLocationButton.innerHTML = '<i class="fa-solid fa-location-dot fa-spin me-1"></i> Mengunci Lokasi...';
        }
        if (locationSpinner) locationSpinner.style.display = 'inline-block';
        if (locationStatus) locationStatus.innerText = 'Mengunci titik koordinat Anda...';

        let bestPosition = null;
        const maxTimeout = setTimeout(() => {
            if (activeWatchId !== null) {
                navigator.geolocation.clearWatch(activeWatchId);
                activeWatchId = null;
            }
            isAcquiringLocation = false;
            if (bestPosition) {
                setLocation(bestPosition);
            } else {
                setLocationError('Sinyal lokasi tidak stabil. Silakan klik tombol Kunci Lokasi lagi.');
            }
        }, 6000);

        activeWatchId = navigator.geolocation.watchPosition(
            pos => {
                const acc = pos.coords.accuracy;
                if (!bestPosition || acc < bestPosition.coords.accuracy) {
                    bestPosition = pos;
                }
                if (acc <= 20) {
                    clearTimeout(maxTimeout);
                    if (activeWatchId !== null) {
                        navigator.geolocation.clearWatch(activeWatchId);
                        activeWatchId = null;
                    }
                    isAcquiringLocation = false;
                    setLocation(bestPosition);
                }
            },
            err => {
                clearTimeout(maxTimeout);
                isAcquiringLocation = false;
                if (activeWatchId !== null) {
                    navigator.geolocation.clearWatch(activeWatchId);
                    activeWatchId = null;
                }
                let errText = 'Akses lokasi ditolak. Silakan klik ikon gembok/lokasi di address bar browser dan izinkan lokasi.';
                if (err.code === err.POSITION_UNAVAILABLE) errText = 'Sinyal lokasi tidak tersedia.';
                else if (err.code === err.TIMEOUT) errText = 'Waktu permintaan lokasi habis.';
                setLocationError(errText);
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    if (lockLocationButton) {
        lockLocationButton.addEventListener('click', acquireLocation);
        // Auto trigger detection on page load
        if (navigator.geolocation) {
            acquireLocation();
        }
    }

    // ── Camera Webcam Capture Logic ──
    let isPhotoCaptured = false;
    let flipHorizontal = true; // Balikkan secara horizontal agar orientasi tidak mirror

    async function initCamera() {
        if (!cameraPanel || !cameraVideo) return;
        cameraPanel.style.display = 'block';
        if (cameraStartActions) cameraStartActions.style.display = 'none';
        if (cameraMessage) cameraMessage.innerHTML = '<span class="text-blue-600 font-semibold"><span class="inline-block animate-spin mr-1">⏳</span> Menyiapkan kamera...</span>';

        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Kamera tidak didukung pada browser ini.');
            }

            if (cameraStream && cameraStream.active) {
                cameraVideo.srcObject = cameraStream;
                cameraVideo.style.transform = flipHorizontal ? 'scaleX(-1)' : 'scaleX(1)';
                await cameraVideo.play().catch(() => {});
                cameraVideo.style.display = 'block';
                if (cameraPreview) cameraPreview.style.display = 'none';
                if (cameraMessage) cameraMessage.innerText = 'Kamera aktif. Silakan berpose, lalu klik Ambil Foto.';
                return;
            }

            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            cameraVideo.srcObject = cameraStream;
            cameraVideo.style.transform = flipHorizontal ? 'scaleX(-1)' : 'scaleX(1)';
            await cameraVideo.play().catch(() => {});
            cameraVideo.style.display = 'block';
            if (cameraPreview) cameraPreview.style.display = 'none';
            if (cameraMessage) cameraMessage.innerText = 'Kamera aktif. Silakan berpose, lalu klik Ambil Foto.';
        } catch (err) {
            console.error('Camera error:', err);
            if (cameraStartActions) cameraStartActions.style.display = 'flex';
            if (cameraPanel) cameraPanel.style.display = 'none';
            Swal.fire({ icon: 'warning', title: 'Akses Kamera Ditolak', text: 'Tidak dapat membuka kamera. Pastikan izin kamera telah diberikan di browser.' });
        }
    }

    if (startCameraButton) {
        startCameraButton.addEventListener('click', initCamera);
    }


    if (capturePhoto) {
        capturePhoto.addEventListener('click', async function () {
            if (!isPhotoCaptured) {
                // ── AMBIL FOTO ──
                if (!cameraVideo || !cameraCanvas) return;

                const w = cameraVideo.videoWidth || 640;
                const h = cameraVideo.videoHeight || 480;
                cameraCanvas.width = w;
                cameraCanvas.height = h;

                const ctx = cameraCanvas.getContext('2d');
                ctx.clearRect(0, 0, w, h);
                ctx.save();
                if (flipHorizontal) {
                    ctx.translate(w, 0);
                    ctx.scale(-1, 1);
                }
                ctx.drawImage(cameraVideo, 0, 0, w, h);
                ctx.restore();

                cameraCanvas.toBlob(blob => {
                    if (!blob) return;
                    const file = new File([blob], `selfie_masuk_${Date.now()}.jpg`, { type: 'image/jpeg' });
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    if (fotoKameraInput) fotoKameraInput.files = dt.files;

                    if (cameraPreview) {
                        cameraPreview.src = URL.createObjectURL(blob);
                        cameraPreview.style.display = 'block';
                    }
                    if (cameraVideo) {
                        cameraVideo.style.display = 'none';
                    }

                    isPhotoCaptured = true;
                    if (cameraMessage) cameraMessage.innerHTML = '<span class="text-green-600 font-bold"><i class="fa-solid fa-circle-check me-1"></i> Foto berhasil diambil</span>';
                    capturePhoto.innerHTML = '<i class="fa-solid fa-rotate-right me-1"></i> Ambil Ulang';
                    capturePhoto.classList.remove('btn-primary');
                    capturePhoto.classList.add('btn-outline-primary');
                }, 'image/jpeg', 0.9);
            } else {
                // ── AMBIL ULANG (INSTAN, TANPA BLACKSCREEN) ──
                if (fotoKameraInput) fotoKameraInput.value = '';

                if (cameraStream && cameraStream.active) {
                    if (cameraVideo) {
                        cameraVideo.style.transform = flipHorizontal ? 'scaleX(-1)' : 'scaleX(1)';
                        cameraVideo.style.display = 'block';
                        cameraVideo.play().catch(() => {});
                    }
                    if (cameraPreview) cameraPreview.style.display = 'none';
                    isPhotoCaptured = false;
                    if (cameraMessage) cameraMessage.innerText = 'Kamera aktif. Silakan berpose, lalu klik Ambil Foto.';
                    capturePhoto.innerHTML = '<i class="fa-solid fa-camera me-1"></i> Ambil Foto';
                    capturePhoto.classList.remove('btn-outline-primary');
                    capturePhoto.classList.add('btn-primary');
                } else {
                    // Jika stream sempat terputus, hubungkan kembali tanpa langsung layar hitam
                    if (cameraMessage) cameraMessage.innerHTML = '<span class="text-blue-600 font-semibold"><span class="inline-block animate-spin mr-1">⏳</span> Menghubungkan kamera...</span>';
                    capturePhoto.disabled = true;
                    try {
                        cameraStream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                            audio: false
                        });
                        cameraVideo.srcObject = cameraStream;
                        cameraVideo.style.transform = flipHorizontal ? 'scaleX(-1)' : 'scaleX(1)';
                        await cameraVideo.play().catch(() => {});
                        if (cameraVideo) cameraVideo.style.display = 'block';
                        if (cameraPreview) cameraPreview.style.display = 'none';
                        isPhotoCaptured = false;
                        if (cameraMessage) cameraMessage.innerText = 'Kamera aktif. Silakan berpose, lalu klik Ambil Foto.';
                        capturePhoto.innerHTML = '<i class="fa-solid fa-camera me-1"></i> Ambil Foto';
                        capturePhoto.classList.remove('btn-outline-primary');
                        capturePhoto.classList.add('btn-primary');
                    } catch (e) {
                        Swal.fire({ icon: 'warning', title: 'Kamera Gagal Dibuka', text: 'Pastikan izin kamera aktif pada browser Anda.' });
                    } finally {
                        capturePhoto.disabled = false;
                    }
                }
            }
        });
    }

    // ── File Upload Preview (Sakit / Izin) ──
    if (fotoInput) {
        fotoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const f = this.files[0];
                if (fnameSpan) fnameSpan.innerText = f.name;
                if (fileNameDiv) fileNameDiv.style.display = 'block';
                if (f.type.startsWith('image/') && fotoPreview) {
                    fotoPreview.src = URL.createObjectURL(f);
                    fotoPreview.style.display = 'block';
                }
            }
        });
    }

    // ── Check-In Form Submit Validation ──
    if (attendanceForm) {
        attendanceForm.addEventListener('submit', function (e) {
            const checkedStatus = document.querySelector('input[name="status"]:checked')?.value || 'hadir';
            const lat = latitudeInput?.value;
            const lng = longitudeInput?.value;
            const hasCamPhoto = fotoKameraInput?.files && fotoKameraInput.files.length > 0;

            if (['hadir', 'wfh'].includes(checkedStatus) && !hasCamPhoto) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Foto Kamera Diperlukan', text: 'Klik tombol Kamera lalu Ambil Foto terlebih dahulu sebagai bukti kehadiran.' });
                return;
            }

            if (!lat || !lng) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Lokasi Belum Terkunci', text: 'Silakan klik tombol "Kunci Lokasi" untuk memverifikasi posisi Anda.' });
                return;
            }

            if (checkedStatus === 'izin' && (!laporanInput || !laporanInput.value.trim())) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Alasan Diperlukan', text: 'Tuliskan alasan izin pada kolom keterangan.' });
                return;
            }

            if (cameraStream) {
                cameraStream.getTracks().forEach(t => t.stop());
            }

            const btn = document.getElementById('btn_submit_absen');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Mengirim Absen Masuk...';
            }
        });

        window.addEventListener('beforeunload', function () {
            if (cameraStream) {
                cameraStream.getTracks().forEach(t => t.stop());
            }
        });
    }

    // ── 2. Check-Out Elements & Logic (Absen Pulang) ──
    const coLockLocationBtn = document.getElementById('co_lock_location');
    const coLocationStatus = document.getElementById('co_location_status');
    const coLocationAddress = document.getElementById('co_location_address');
    const coLocationSpinner = document.getElementById('co_location_spinner');
    const coLatInput = document.getElementById('co_lokasi_latitude');
    const coLngInput = document.getElementById('co_lokasi_longitude');
    const coAccInput = document.getElementById('co_lokasi_akurasi');
    const coFotoInput = document.getElementById('co_foto');
    const coFnameSpan = document.getElementById('co_fname');
    const coFileNameDiv = document.getElementById('co_file_name');
    const coFotoPreview = document.getElementById('co_foto_preview');
    const checkoutForm = document.getElementById('checkoutAttendanceForm');

    if (coLockLocationBtn) {
        coLockLocationBtn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                Swal.fire({ icon: 'error', title: 'GPS Tidak Didukung', text: 'Browser tidak mendukung pendeteksian lokasi GPS.' });
                return;
            }
            if (coLocationSpinner) coLocationSpinner.classList.remove('hidden');
            if (coLocationStatus) coLocationStatus.innerText = 'Mengunci titik lokasi pulang...';
            coLockLocationBtn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                pos => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    const acc = Math.round(pos.coords.accuracy);

                    if (coLatInput) coLatInput.value = lat.toFixed(7);
                    if (coLngInput) coLngInput.value = lng.toFixed(7);
                    if (coAccInput) coAccInput.value = acc;

                    if (coLocationSpinner) coLocationSpinner.classList.add('hidden');
                    if (coLocationStatus) coLocationStatus.innerHTML = '<span class="text-green-600 font-bold"><i class="fa-solid fa-circle-check me-1"></i> Lokasi pulang terkunci</span>';
                    coLockLocationBtn.innerHTML = '<i class="fa-solid fa-arrows-rotate me-1"></i> Perbarui Lokasi';
                    coLockLocationBtn.disabled = false;
                },
                err => {
                    if (coLocationSpinner) coLocationSpinner.classList.add('hidden');
                    if (coLocationStatus) coLocationStatus.innerText = 'Akses lokasi ditolak. Silakan izinkan lokasi di browser.';
                    coLockLocationBtn.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        });

        if (navigator.geolocation) {
            coLockLocationBtn.click();
        }
    }

    if (coFotoInput) {
        coFotoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const f = this.files[0];
                if (coFnameSpan) coFnameSpan.innerText = f.name;
                if (coFileNameDiv) coFileNameDiv.style.display = 'block';
                if (f.type.startsWith('image/') && coFotoPreview) {
                    coFotoPreview.src = URL.createObjectURL(f);
                    coFotoPreview.style.display = 'block';
                }
            }
        });
    }

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            const lat = coLatInput?.value;
            const lng = coLngInput?.value;
            const hasFoto = coFotoInput?.files && coFotoInput.files.length > 0;

            if (!hasFoto) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Lampiran Diperlukan', text: 'Pilih gambar bukti hasil pengerjaan sebelum Check Out.' });
                return;
            }

            if (!lat || !lng) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Lokasi Belum Terkunci', text: 'Silakan klik tombol "Kunci Lokasi" terlebih dahulu.' });
                return;
            }

            const btn = document.getElementById('btn_submit_co');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Mengirim Absen Pulang...';
            }
        });
    }
});
</script>
@endsection
