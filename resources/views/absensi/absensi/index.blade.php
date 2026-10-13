@extends('absensi.layouts.app')

@section('title', 'Absensi dan Laporan Harian')

@section('styles')
<style>
:root {
    --radius-lg: 16px;
    --radius-md: 12px;
    --radius-sm: 8px;
    --border-color: #e2e8f0;
    --primary-blue: #2563eb;
    --primary-blue-hover: #1d4ed8;
}

/* ── Container & Header ── */
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
@media (max-width: 576px) {
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
    background: #0f172a;
    margin-top: 0.75rem;
}
.camera-panel video {
    display: block;
    width: 100%;
    max-height: 420px;
    object-fit: cover;
}
.camera-preview {
    display: none;
    width: 100%;
    max-height: 420px;
    object-fit: contain;
    background: #ffffff;
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
<div class="container py-3">
    <!-- Workspace Header -->
    <div class="workspace-header mb-4">
        <h5>Absensi dan Laporan Harian</h5>
        <span>Catat kehadiran harian dan buat laporan kegiatan magang Anda secara rutin</span>
    </div>

    <!-- Layout Grid -->
    <div class="row g-4">
        <!-- Kolom Kiri: Form Absensi -->
        <div class="col-lg-8">


            <!-- Form Presensi Kehadiran -->
            <div class="presensi-card h-100">
                @if(in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
                    <div class="text-center py-5 h-100 d-flex flex-column justify-content-center align-items-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px; height:64px; background:rgba(220, 53, 69, 0.1); color:#dc3545;">
                            <i class="fa-solid fa-user-lock" style="font-size:2rem;"></i>
                        </div>
                        <h5 class="fw-bold text-danger">Akun Anda {{ ucwords(auth()->user()->status_akun) }}</h5>
                        <p class="text-muted small">Anda tidak dapat mengisi absensi maupun mengumpulkan laporan karena akun Anda telah dibatasi atau diblokir.</p>
                    </div>
                @elseif ($todayAttendance && $todayAttendance->jam_masuk && $todayAttendance->jam_pulang)
                    <!-- Completed attendance -->
                    <div class="text-center py-4 h-100 d-flex flex-column justify-content-center align-items-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px; height:56px; background:rgba(16,185,129,0.06); color:#00b894;">
                            <i class="fa-solid fa-circle-check" style="font-size:1.5rem;"></i>
                        </div>
                        <h6 class="fw-bold">Presensi Hari Ini Lengkap</h6>
                        <p class="text-muted small">Anda telah mengisi absensi masuk dan pulang untuk hari ini.</p>
                        <div class="d-flex justify-content-center gap-3 mt-2">
                            <span class="badge bg-light text-dark py-2 px-3 rounded-pill border">Masuk: {{ $todayAttendance->jam_masuk }}</span>
                            <span class="badge bg-light text-dark py-2 px-3 rounded-pill border">Pulang: {{ $todayAttendance->jam_pulang }}</span>
                        </div>
                    </div>
                @elseif ($todayAttendance && $todayAttendance->jam_masuk && !$todayAttendance->jam_pulang)
                    <!-- Checkout form -->
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-right-from-bracket me-2 text-primary"></i>Absen Pulang</h6>
                    <p class="text-muted small">Wajib mengunggah lampiran pengerjaan dan menulis laporan pekerjaan harian.</p>
                    
                    <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="status" id="status_checkout" value="{{ $todayAttendance->status }}">
                        <input type="hidden" name="lokasi_latitude" id="lokasi_latitude" value="{{ old('lokasi_latitude') }}">
                        <input type="hidden" name="lokasi_longitude" id="lokasi_longitude" value="{{ old('lokasi_longitude') }}">
                        <input type="hidden" name="lokasi_akurasi" id="lokasi_akurasi" value="{{ old('lokasi_akurasi') }}">

                        <!-- Location panel -->
                        <div class="mb-3 location-panel show" id="location_section">
                            <label class="form-label-admin">Verifikasi Lokasi <span class="text-danger">*</span></label>
                            <div class="location-status p-3 border rounded-3 bg-light d-flex gap-3 align-items-start">
                                <i class="fa-solid fa-location-dot text-primary mt-1" style="font-size:1.3rem;"></i>
                                <div class="w-100">
                                    <div id="location_status" class="small fw-semibold text-dark">Klik tombol di bawah untuk mengunci lokasi.</div>
                                    <div id="location_address" class="small text-muted mt-1" style="display:none;"></div>
                                    <div class="location-meta mt-2 d-flex flex-wrap gap-1 align-items-center" style="display:none;">
                                        <span class="badge bg-secondary-subtle text-dark" id="location_accuracy" style="display:none;">Akurasi: -</span>
                                        <span class="badge bg-secondary-subtle text-dark" id="location_coordinates" style="display:none;">Koordinat: -</span>
                                        <a href="#" target="_blank" id="location_map_link" class="badge bg-primary-subtle text-primary text-decoration-none" style="display:none;">
                                            <i class="fa-solid fa-map-location-dot me-1"></i> Buka Google Maps
                                        </a>
                                    </div>
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-3" id="lock_location">
                                            <i class="fa-solid fa-location-dot me-1"></i> Kunci Lokasi
                                        </button>
                                        <span id="location_spinner" class="spinner-border spinner-border-sm text-primary" style="display:none;" role="status"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (in_array($todayAttendance->status, ['hadir', 'wfh'], true))
                            <!-- Required file upload for Hadir/WFH checkout -->
                            <div class="mb-3" id="photo_section" style="display: block;">
                                <label id="foto_label" class="form-label-admin">Unggah Gambar Bukti Pengerjaan <span class="text-danger">*</span></label>
                                <div class="upload-zone p-3 border text-center rounded-3 bg-light">
                                    <i class="fa-solid fa-cloud-arrow-up text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                                    <div class="small fw-semibold text-dark" id="upload_text">Klik untuk pilih gambar bukti kerja</div>
                                    <input type="file" name="foto" id="foto" accept="image/*" required>
                                    <div class="file-name text-primary small mt-1" id="file_name" style="display: none;">
                                        <i class="fa-solid fa-check-circle me-1"></i><span id="fname"></span>
                                    </div>
                                    <img src="" class="upload-preview mt-2 w-100 rounded-3 border" id="foto_preview" style="display: none; max-height: 180px; object-fit: contain;">
                                </div>
                            </div>

                            <!-- Required Keterangan/Laporan for Hadir/WFH checkout -->
                            <div class="mb-3" id="laporan_section" style="display: block;">
                                <label for="laporan" id="laporan_label" class="form-label-admin">Laporan Hasil Pekerjaan Hari Ini <span class="text-danger">*</span></label>
                                <textarea name="keterangan" id="laporan" rows="3" class="form-control form-control-admin w-100" placeholder="Jelaskan progres pekerjaan Anda hari ini..." required>{{ old('keterangan') }}</textarea>
                            </div>
                        @else
                            <!-- Optional Keterangan for Sakit/Izin checkout -->
                            <div class="mb-3" id="laporan_section" style="display: block;">
                                <label for="laporan" id="laporan_label" class="form-label-admin">Catatan/Keterangan Tambahan</label>
                                <textarea name="keterangan" id="laporan" rows="2" class="form-control form-control-admin w-100" placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3">
                            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Absen Pulang
                        </button>
                    </form>
                @else
                    <!-- Check-in form -->
                    <div class="card-heading">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Absen Masuk</span>
                    </div>
                    
                    @php
                        $isHadirWfhDisabled = false;
                    @endphp

                    <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="lokasi_latitude" id="lokasi_latitude" value="{{ old('lokasi_latitude') }}">
                        <input type="hidden" name="lokasi_longitude" id="lokasi_longitude" value="{{ old('lokasi_longitude') }}">
                        <input type="hidden" name="lokasi_akurasi" id="lokasi_akurasi" value="{{ old('lokasi_akurasi') }}">

                        <!-- Status Absensi Radios -->
                        <div class="mb-3">
                            <label class="form-label-custom">Status Absensi Masuk <span class="text-danger">*</span></label>
                            <div class="status-grid">
                                <!-- Hadir -->
                                <div>
                                    <input type="radio" class="btn-check" name="status" id="status_hadir" value="hadir" {{ old('status', $isHadirWfhDisabled ? 'sakit' : 'hadir') === 'hadir' ? 'checked' : '' }} {{ $isHadirWfhDisabled ? 'disabled' : '' }} autocomplete="off">
                                    <label class="status-card" for="status_hadir">
                                        <div class="s-icon s-icon-hadir"><i class="fa-solid fa-store"></i></div>
                                        <div class="s-name">Hadir</div>
                                        <div class="s-desc">Di kantor</div>
                                    </label>
                                </div>

                                <!-- WFH -->
                                <div>
                                    <input type="radio" class="btn-check" name="status" id="status_wfh" value="wfh" {{ old('status') === 'wfh' ? 'checked' : '' }} {{ $isHadirWfhDisabled ? 'disabled' : '' }} autocomplete="off">
                                    <label class="status-card" for="status_wfh">
                                        <div class="s-icon s-icon-wfh"><i class="fa-solid fa-house"></i></div>
                                        <div class="s-name">WFH</div>
                                        <div class="s-desc">Kerja dari luar kantor</div>
                                    </label>
                                </div>

                                <!-- Sakit -->
                                <div>
                                    <input type="radio" class="btn-check" name="status" id="status_sakit" value="sakit" {{ old('status', $isHadirWfhDisabled ? 'sakit' : '') === 'sakit' ? 'checked' : '' }} autocomplete="off">
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
                            <label class="form-label-custom" id="location_label">Lokasi Hadir (Kantor) <span class="text-danger">*</span></label>
                            <div class="location-box d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-location-dot" style="color: #2563eb; font-size: 1.1rem;"></i>
                                    <span id="location_status" class="small fw-semibold text-dark">Klik Kunci Lokasi untuk memverifikasi posisi Anda di kantor.</span>
                                    <span id="location_spinner" class="spinner-border spinner-border-sm text-primary" style="display:none;" role="status"></span>
                                </div>
                                <div id="location_address" class="small text-muted align-items-start gap-1" style="display:none; color: #334155 !important;"></div>
                                <div class="location-meta d-flex flex-wrap gap-1 align-items-center" style="display:none;">
                                    <span class="badge bg-secondary-subtle text-dark" id="location_accuracy" style="display:none;">Akurasi: -</span>
                                    <span class="badge bg-secondary-subtle text-dark" id="location_coordinates" style="display:none;">Koordinat: -</span>
                                    <a href="#" target="_blank" id="location_map_link" class="badge bg-primary-subtle text-primary text-decoration-none" style="display:none;">
                                        <i class="fa-solid fa-map-location-dot me-1"></i> Buka Google Maps
                                    </a>
                                </div>
                                <div class="pt-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm fw-semibold d-inline-flex align-items-center gap-1.5" id="lock_location" style="border-radius: 8px; padding: 6px 14px; font-size: 0.82rem; background: #fff;">
                                        <i class="fa-solid fa-location-dot"></i> Kunci Lokasi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Camera Capture Section -->
                        <div class="mb-3" id="camera_section">
                            <label class="form-label-custom" id="camera_label">Foto Kamera <span class="text-danger">*</span></label>
                            <input type="file" name="foto_kamera" id="foto_kamera" accept="image/*" class="d-none">
                            <div class="camera-start-actions" id="camera_start_actions">
                                <span class="camera-message small text-muted" id="camera_start_message" style="font-size: 0.84rem;">Nyalakan kamera lalu ambil foto diri untuk bukti.</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5" id="start_camera" style="border-radius: 8px; padding: 6px 14px; font-size: 0.82rem; color: #475569; border-color: #cbd5e1; background: #f8fafc;">
                                    <i class="fa-solid fa-video"></i> Kamera
                                </button>
                            </div>
                            <div class="camera-panel" id="camera_panel" style="display: none;">
                                <video id="camera_video" autoplay playsinline muted></video>
                                <img src="" class="camera-preview" id="camera_preview" alt="Pratinjau foto">
                                <canvas id="camera_canvas" class="d-none"></canvas>
                                <div class="camera-actions p-2 bg-light border-top d-flex justify-content-between align-items-center">
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
                                <i class="fa-solid fa-cloud-arrow-up text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
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
                            <label for="laporan" id="laporan_label" class="form-label-custom">Catatan/Alasan <span class="text-danger">*</span></label>
                            <textarea name="keterangan" id="laporan" rows="2" class="form-control w-100" style="border-radius: 10px; border-color: #cbd5e1; font-size: 0.88rem;" placeholder="Rincian alasan atau keterangan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-submit-absen mt-3">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Absen Masuk
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Kehadiran Bulanan -->
        <div class="col-lg-4">
            @if(auth()->user()->status_akun == 'diblokir')
            <div class="stat-widget h-100 d-flex flex-column align-items-center justify-content-center text-center py-5">
                <i class="fa-solid fa-lock text-danger mb-3" style="font-size:2.5rem;"></i>
                <h6 class="fw-bold text-danger">Data Terkunci</h6>
                <p class="text-muted small">Statistik kehadiran tidak dapat ditampilkan karena akun Anda diblokir.</p>
            </div>
            @else
            <div class="stat-widget h-100">
                <div class="card-heading">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Kehadiran Bulanan</span>
                </div>
                
                <div class="d-flex align-items-center justify-content-center my-4">
                    <div style="width: 125px; height: 125px; position: relative;">
                        <svg viewBox="0 0 36 36" style="width: 100%; height: 100%;">
                            <circle class="pct-ring-bg" cx="18" cy="18" r="16" fill="none" stroke-width="3.2" />
                            <circle class="pct-ring-fill" cx="18" cy="18" r="16" fill="none" stroke-width="3.2" stroke-dasharray="{{ $stats['persentase'] }}, 100" />
                        </svg>
                        <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <span style="font-size: 1.45rem; font-weight: 800; color: #0f172a; line-height: 1;">{{ $stats['persentase'] }}%</span>
                            <span class="text-muted" style="font-size: 0.72rem; margin-top: 4px;">Kehadiran</span>
                        </div>
                    </div>
                </div>

                <div class="stat-rows mt-3">
                    <div class="stat-row-item stat-row-hadir">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                            <span class="fw-semibold text-dark" style="font-size: 0.84rem;">Hadir</span>
                        </div>
                        <span class="pill-badge pill-badge-hadir">{{ $stats['hadir'] }} hari</span>
                    </div>
                    <div class="stat-row-item stat-row-wfh">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; display: inline-block;"></span>
                            <span class="fw-semibold text-dark" style="font-size: 0.84rem;">WFH</span>
                        </div>
                        <span class="pill-badge pill-badge-wfh">{{ $stats['wfh'] }} hari</span>
                    </div>
                    <div class="stat-row-item stat-row-sakit">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                            <span class="fw-semibold text-dark" style="font-size: 0.84rem;">Sakit</span>
                        </div>
                        <span class="pill-badge pill-badge-sakit">{{ $stats['sakit'] }} hari</span>
                    </div>
                    <div class="stat-row-item stat-row-izin">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                            <span class="fw-semibold text-dark" style="font-size: 0.84rem;">Izin</span>
                        </div>
                        <span class="pill-badge pill-badge-izin">{{ $stats['izin'] }} hari</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div> <!-- /row -->

    <!-- Riwayat Kehadiran (Full Width Bottom) -->
    <div class="history-wrap">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="card-heading mb-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Riwayat Kehadiran</span>
            </div>
            @if(auth()->user()->status_akun != 'diblokir')
            <span class="badge border bg-white" style="border-color: #e2e8f0 !important; color: #334155; border-radius: 6px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600;">{{ $absensi->count() }} entri</span>
            @endif
        </div>
        
        @if(auth()->user()->status_akun == 'diblokir')
            <div class="text-center py-5">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px; height:56px; background:rgba(220, 53, 69, 0.06);">
                    <i class="fa-solid fa-ban text-danger" style="font-size:1.5rem;"></i>
                </div>
                <h6 class="fw-bold text-danger">Akses Riwayat Diblokir</h6>
                <p class="text-muted small">Riwayat absen Anda tidak dapat ditampilkan karena akun diblokir.</p>
            </div>
        @elseif ($absensi->isEmpty())
            <div class="text-center py-5">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:52px; height:52px; border-radius: 12px; background:#eff6ff;">
                    <i class="fa-solid fa-calendar-xmark text-primary" style="font-size:1.35rem;"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Belum ada riwayat presensi</h6>
                <p class="text-muted small mb-0" style="font-size: 0.82rem;">Catatan kehadiran Anda akan muncul di sini setelah melakukan absen masuk.</p>
            </div>
        @else
            <div class="table-responsive mt-2">
                <table class="history-table w-100">
                    <thead>
                        <tr>
                            <th>Tanggal & Waktu</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Catatan Pekerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absensi as $rec)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($rec->tanggal)->translatedFormat('d M Y') }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">{{ $rec->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <span class="badge-status badge-{{ $rec->status }}">
                                        {{ strtoupper($rec->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-medium text-dark">{{ $rec->jam_masuk ?? '-' }}</span>
                                        @if ($rec->foto_kamera)
                                            <a href="{{ route('absensi.kamera', $rec) }}" target="_blank">
                                                <img src="{{ route('absensi.kamera', $rec) }}" class="attachment-thumb" title="Foto Kamera">
                                            </a>
                                        @elseif ($rec->foto_masuk)
                                            <a href="{{ route('absensi.kamera', $rec) }}" target="_blank">
                                                <img src="{{ asset($rec->foto_masuk) }}" class="attachment-thumb" title="Foto Masuk">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-medium text-dark">{{ $rec->jam_pulang ?? '-' }}</span>
                                        @if ($rec->foto_pulang)
                                            <a href="{{ asset($rec->foto_pulang) }}" target="_blank">
                                                <img src="{{ asset($rec->foto_pulang) }}" class="attachment-thumb" title="Foto Jam Pulang">
                                            </a>
                                        @elseif ($rec->foto)
                                            <a href="{{ asset($rec->foto) }}" target="_blank">
                                                <img src="{{ asset($rec->foto) }}" class="attachment-thumb" title="File Lampiran">
                                            </a>
                                        @elseif ($rec->jurnal && $rec->jurnal->file_lampiran)
                                            <a href="{{ asset($rec->jurnal->file_lampiran) }}" target="_blank">
                                                <img src="{{ asset($rec->jurnal->file_lampiran) }}" class="attachment-thumb" title="File Lampiran">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="max-width: 250px;">
                                    @php
                                        $catatan = $rec->jurnal ? $rec->jurnal->hasil_pekerjaan : ($rec->laporan ?: '-');
                                    @endphp
                                    <div class="text-muted small text-truncate" title="{{ $catatan }}">
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="status"]');
    const attendanceForm = document.getElementById('attendanceForm');
    const photoSection = document.getElementById('photo_section');
    const fotoLabel = document.getElementById('foto_label');
    const fotoInput = document.getElementById('foto');
    const laporanSection = document.getElementById('laporan_section');
    const laporanLabel = document.getElementById('laporan_label');
    const laporanInput = document.getElementById('laporan');
    const fileNameDiv = document.getElementById('file_name');
    const fnameSpan = document.getElementById('fname');
    const uploadText = document.getElementById('upload_text');
    const fotoPreview = document.getElementById('foto_preview');
    const fotoKameraInput = document.getElementById('foto_kamera');
    const cameraSection = document.getElementById('camera_section');
    const cameraStartActions = document.getElementById('camera_start_actions');
    const startCameraButton = document.getElementById('start_camera');
    const cameraPanel = document.getElementById('camera_panel');
    const cameraVideo = document.getElementById('camera_video');
    const cameraCanvas = document.getElementById('camera_canvas');
    const cameraLabel = document.getElementById('camera_label');
    const cameraStartMessage = document.getElementById('camera_start_message');
    const cameraMessage = document.getElementById('camera_message');
    const cameraPreview = document.getElementById('camera_preview');
    const capturePhoto = document.getElementById('capture_photo');
    const locationSection = document.getElementById('location_section');
    const locationStatus = document.getElementById('location_status');
    const locationAddress = document.getElementById('location_address');
    const locationAccuracy = document.getElementById('location_accuracy');
    const locationCoordinates = document.getElementById('location_coordinates');
    const locationMapLink = document.getElementById('location_map_link');
    const locationSpinner = document.getElementById('location_spinner');
    const latitudeInput = document.getElementById('lokasi_latitude');
    const longitudeInput = document.getElementById('lokasi_longitude');
    const accuracyInput = document.getElementById('lokasi_akurasi');
    const lockLocationButton = document.getElementById('lock_location');
    let previewUrl = null;
    let cameraPreviewUrl = null;
    let cameraStream = null;

    let isAcquiringLocation = false;
    let activeWatchId = null;

    async function fetchAddress(lat, lng) {
        if (!locationAddress) return;
        try {
            const resp = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
                headers: { 'Accept-Language': 'id,en' }
            });
            if (resp.ok) {
                const data = await resp.json();
                if (data && data.display_name) {
                    locationAddress.innerHTML = `<i class="fa-solid fa-map-pin text-danger mt-0.5 flex-shrink-0" style="font-size: 0.82rem;"></i> <span class="fw-normal text-secondary" style="font-size: 0.82rem; line-height: 1.45;">${data.display_name}</span>`;
                    locationAddress.style.display = 'flex';
                }
            }
        } catch (e) {
            // Ignore geocoding network errors silently
        }
    }

    function setLocation(position, options = {}) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const accuracy = position.coords.accuracy;

        // Store full high-precision coordinates (7 decimal places ~ 1.1 cm)
        if (latitudeInput) latitudeInput.value = lat.toFixed(7);
        if (longitudeInput) longitudeInput.value = lng.toFixed(7);
        if (accuracyInput) accuracyInput.value = accuracy ? Math.round(accuracy) : '';

        if (locationStatus) {
            locationStatus.innerHTML = options.message || '<span class="text-success fw-semibold">Lokasi terkunci</span>';
        }

        // Accuracy badge styling (hidden from UI view as requested, kept for system inputs)
        if (locationAccuracy) {
            locationAccuracy.style.display = 'none';
        }

        if (locationCoordinates) {
            locationCoordinates.style.display = 'none';
        }

        // Google Maps direct verification link
        if (locationMapLink) {
            locationMapLink.href = `https://www.google.com/maps?q=${lat.toFixed(7)},${lng.toFixed(7)}`;
            locationMapLink.style.display = 'none';
        }

        if (lockLocationButton) {
            lockLocationButton.innerHTML = '<i class="fa-solid fa-arrows-rotate me-1"></i> Perbarui Lokasi';
            lockLocationButton.disabled = false;
        }

        if (locationSpinner) locationSpinner.style.display = 'none';

        // Asynchronously fetch human-readable street address
        fetchAddress(lat, lng);
    }

    function setLocationError(message) {
        if (locationStatus) locationStatus.innerText = message;
        if (latitudeInput) latitudeInput.value = '';
        if (longitudeInput) longitudeInput.value = '';
        if (accuracyInput) accuracyInput.value = '';
        if (locationAccuracy) {
            locationAccuracy.style.display = 'none';
        }
        if (locationCoordinates) {
            locationCoordinates.style.display = 'none';
        }
        if (locationMapLink) locationMapLink.style.display = 'none';
        if (locationAddress) locationAddress.style.display = 'none';
        if (lockLocationButton) {
            lockLocationButton.innerHTML = '<i class="fa-solid fa-location-dot me-1"></i> Kunci Lokasi';
            lockLocationButton.disabled = false;
        }
        if (locationSpinner) locationSpinner.style.display = 'none';
    }

    function showLocationSection() {
        if (!locationSection) return;
        locationSection.classList.add('show');
        if (!latitudeInput?.value || !longitudeInput?.value) {
            if (locationStatus) locationStatus.innerText = 'Klik Kunci Lokasi untuk menyimpan posisi Anda.';
        }
    }

    function hideLocationSection() {
        // GPS location is required for all attendance statuses
        showLocationSection();
    }

    /**
     * High-Precision Progressive Geolocation Acquisition
     * Collects continuous GPS / Wi-Fi trilateration updates via watchPosition
     * to ensure the smallest possible error radius is locked.
     */
    function acquireHighAccuracyLocation() {
        if (!navigator.geolocation) {
            setLocationError('Browser ini tidak bisa mengambil lokasi. Gunakan Chrome, Safari, atau Edge.');
            return Promise.reject(new Error('Browser ini tidak bisa mengambil lokasi.'));
        }

        if (isAcquiringLocation) return Promise.resolve();
        isAcquiringLocation = true;

        if (lockLocationButton) {
            lockLocationButton.disabled = true;
            lockLocationButton.innerHTML = '<i class="fa-solid fa-location-dot fa-spin me-1"></i> Mengunci Lokasi...';
        }
        if (locationSpinner) locationSpinner.style.display = 'inline-block';
        if (locationStatus) {
            locationStatus.innerHTML = '<span class="text-primary fw-semibold">Sedang mengunci lokasi...</span>';
        }

        return new Promise((resolve, reject) => {
            let bestPosition = null;
            const maxDurationMs = 6000; // Allow GPS hardware up to 6 seconds to stabilize
            const desiredAccuracyMeters = 20; // 20m or lower is high accuracy

            if (activeWatchId !== null) {
                navigator.geolocation.clearWatch(activeWatchId);
                activeWatchId = null;
            }

            const timeoutId = setTimeout(() => {
                finishAcquisition();
            }, maxDurationMs);

            function finishAcquisition() {
                if (activeWatchId !== null) {
                    navigator.geolocation.clearWatch(activeWatchId);
                    activeWatchId = null;
                }
                clearTimeout(timeoutId);
                isAcquiringLocation = false;

                if (bestPosition) {
                    setLocation(bestPosition, {
                        message: '<span class="text-success fw-semibold">Lokasi terkunci</span>'
                    });
                    resolve(bestPosition);
                } else {
                    setLocationError('Gagal mendapatkan sinyal GPS. Pastikan GPS/izin lokasi aktif pada perangkat.');
                    reject(new Error('Gagal mendapatkan sinyal GPS.'));
                }
            }

            activeWatchId = navigator.geolocation.watchPosition(
                position => {
                    const acc = position.coords.accuracy;

                    if (!bestPosition || acc < bestPosition.coords.accuracy) {
                        bestPosition = position;
                    }

                    if (locationStatus) {
                        locationStatus.innerHTML = '<span class="text-primary">Mengunci titik lokasi...</span>';
                    }

                    // If excellent accuracy is reached (< 20m), lock immediately
                    if (acc <= desiredAccuracyMeters) {
                        finishAcquisition();
                    }
                },
                error => {
                    if (bestPosition) {
                        finishAcquisition();
                    } else {
                        isAcquiringLocation = false;
                        if (activeWatchId !== null) {
                            navigator.geolocation.clearWatch(activeWatchId);
                            activeWatchId = null;
                        }
                        clearTimeout(timeoutId);
                        let errorMsg = 'Lokasi belum bisa diambil. Izinkan akses lokasi pada browser/perangkat Anda.';
                        if (error.code === error.PERMISSION_DENIED) {
                            errorMsg = 'Akses lokasi ditolak. Silakan klik ikon gembok/lokasi di address bar browser dan izinkan lokasi.';
                        } else if (error.code === error.POSITION_UNAVAILABLE) {
                            errorMsg = 'Sinyal lokasi tidak tersedia. Coba aktifkan GPS perangkat atau hubungkan ke Wi-Fi.';
                        } else if (error.code === error.TIMEOUT) {
                            errorMsg = 'Waktu permintaan lokasi habis. Silakan klik tombol Kunci Lokasi lagi.';
                        }
                        setLocationError(errorMsg);
                        reject(new Error(errorMsg));
                    }
                },
                {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 10000
                }
            );
        });
    }

    // Auto-detect high accuracy position on page load
    if (navigator.geolocation) {
        acquireHighAccuracyLocation().catch(() => {
            // Keep default message if auto-detect requires user interaction
        });
    }

    async function startCamera() {
        if (!cameraPanel || !cameraVideo || cameraStream) return;

        cameraPanel.style.display = 'block';
        if (cameraStartActions) cameraStartActions.style.display = 'none';
        if (cameraMessage) cameraMessage.innerText = 'Membuka kamera...';

        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Kamera tidak didukung browser ini.');
            }

            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' },
                audio: false
            });
            cameraVideo.srcObject = cameraStream;
            cameraVideo.play().catch(e => console.error("Error playing video:", e));
            if (cameraMessage) cameraMessage.innerText = 'Kamera aktif. Silakan berpose, lalu klik Ambil Foto.';
        } catch (error) {
            if (cameraStartActions) cameraStartActions.style.display = 'flex';
            if (cameraMessage) cameraMessage.innerText = 'Kamera tidak bisa dibuka. Unggah gambar secara manual.';
        }
    }

    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }

        if (cameraVideo) {
            cameraVideo.srcObject = null;
        }

        if (cameraPanel) {
            cameraPanel.style.display = 'none';
        }

        if (cameraStartActions) {
            cameraStartActions.style.display = 'flex';
        }
    }

    function setPreviewFromFile(file) {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        if (fnameSpan) fnameSpan.innerText = file.name;
        if (fileNameDiv) fileNameDiv.style.display = 'block';
        if (uploadText) uploadText.innerText = 'Ganti berkas';

        if (fotoPreview && file.type.startsWith('image/')) {
            previewUrl = URL.createObjectURL(file);
            fotoPreview.src = previewUrl;
            fotoPreview.style.display = 'block';
        }
    }

    function setCameraPreviewFromFile(file) {
        if (cameraPreviewUrl) {
            URL.revokeObjectURL(cameraPreviewUrl);
            cameraPreviewUrl = null;
        }

        if (cameraPreview && file.type.startsWith('image/')) {
            cameraPreviewUrl = URL.createObjectURL(file);
            cameraPreview.src = cameraPreviewUrl;
            cameraPreview.style.display = 'block';
            if (cameraVideo) cameraVideo.style.display = 'none';
        }
    }

    if (fotoInput) {
        fotoInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                setPreviewFromFile(this.files[0]);
            } else {
                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }
                if (fileNameDiv) fileNameDiv.style.display = 'none';
                if (uploadText) uploadText.innerText = 'Klik untuk pilih gambar bukti kerja';
                if (fotoPreview) {
                    fotoPreview.removeAttribute('src');
                    fotoPreview.style.display = 'none';
                }
            }
        });
    }

    if (capturePhoto) {
        capturePhoto.addEventListener('click', function() {
            if (!fotoKameraInput || !cameraVideo || !cameraCanvas || !cameraStream) return;

            const width = cameraVideo.videoWidth || 1280;
            const height = cameraVideo.videoHeight || 720;
            cameraCanvas.width = width;
            cameraCanvas.height = height;
            cameraCanvas.getContext('2d').drawImage(cameraVideo, 0, 0, width, height);

            cameraCanvas.toBlob(function(blob) {
                if (!blob) return;

                const file = new File([blob], `kamera_absensi_${Date.now()}.jpg`, { type: 'image/jpeg' });
                const transfer = new DataTransfer();
                transfer.items.add(file);
                fotoKameraInput.files = transfer.files;
                setCameraPreviewFromFile(file);
                if (cameraMessage) cameraMessage.innerText = 'Foto berhasil diambil dan siap dikirim.';
            }, 'image/jpeg', 0.9);
        });
    }

    if (startCameraButton) {
        startCameraButton.addEventListener('click', startCamera);
    }

    if (lockLocationButton) {
        lockLocationButton.addEventListener('click', function() {
            acquireHighAccuracyLocation().catch(error => {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lokasi belum bisa dikunci',
                        text: error.message || 'Izinkan akses lokasi di browser agar lokasi bisa dikunci.',
                        confirmButtonColor: '#115cb9'
                    });
                } else {
                    alert(error.message || 'Izinkan akses lokasi di browser agar lokasi bisa dikunci.');
                }
            });
        });
    }

    if (attendanceForm) {
        attendanceForm.addEventListener('submit', function(e) {
            const checked = document.querySelector('input[name="status"]:checked') || document.getElementById('status_checkout');
            const statusVal = checked ? checked.value : '';
            const isCheckout = !!document.getElementById('status_checkout');
            
            const needsCamera = (!isCheckout && ['hadir', 'wfh', 'sakit'].includes(statusVal)) || (isCheckout && statusVal === 'sakit');
            const needsPhoto = isCheckout && ['hadir', 'wfh'].includes(statusVal);
            const needsLaporan = (isCheckout && ['hadir', 'wfh'].includes(statusVal)) || (!isCheckout && statusVal === 'izin');
            const needsLocation = true; // GPS location is required for all attendance statuses

            if (needsCamera && (!fotoKameraInput || !fotoKameraInput.files || fotoKameraInput.files.length === 0)) {
                e.preventDefault();
                if (window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Foto kamera belum diambil',
                        text: 'Klik Nyalakan Kamera lalu Ambil Foto terlebih dahulu sebagai bukti kehadiran.',
                        confirmButtonColor: '#115cb9'
                    });
                } else {
                    alert('Klik Nyalakan Kamera lalu Ambil Foto terlebih dahulu.');
                }
                return;
            }

            if (needsPhoto && (!fotoInput || !fotoInput.files || fotoInput.files.length === 0)) {
                e.preventDefault();
                if (window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Bukti pengerjaan belum diunggah',
                        text: 'Pilih dan unggah gambar bukti pengerjaan Anda hari ini.',
                        confirmButtonColor: '#115cb9'
                    });
                } else {
                    alert('Pilih dan unggah gambar bukti pengerjaan Anda hari ini.');
                }
                return;
            }

            if (needsLaporan && (!laporanInput || !laporanInput.value.trim())) {
                e.preventDefault();
                const msg = isCheckout ? 'Laporan hasil pekerjaan hari ini wajib diisi sebelum mengirim absen pulang.' : 'Alasan izin wajib diisi sebelum mengirim absensi.';
                if (window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Laporan/Keterangan belum diisi',
                        text: msg,
                        confirmButtonColor: '#115cb9'
                    });
                } else {
                    alert(msg);
                }
                return;
            }

            if (needsLocation) {
                if (!latitudeInput?.value || !longitudeInput?.value) {
                    e.preventDefault();
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Lokasi belum dikunci',
                            text: 'Klik Kunci Lokasi terlebih dahulu sebelum mengirim absensi.',
                            confirmButtonColor: '#115cb9'
                        });
                    } else {
                        alert('Klik Kunci Lokasi terlebih dahulu sebelum mengirim absensi.');
                    }
                    return;
                }

                // Geofencing for WFO (Hadir)
                if (statusVal === 'hadir') {
                    const OFFICE_LAT = -6.4829;
                    const OFFICE_LNG = 106.8285;
                    const MAX_RADIUS = 100; // in meters

                    function getDistanceFromLatLonInM(lat1, lon1, lat2, lon2) {
                        const R = 6371e3; // Radius of the earth in m
                        const dLat = (lat2 - lat1) * Math.PI / 180;
                        const dLon = (lon2 - lon1) * Math.PI / 180;
                        const a = 
                            Math.sin(dLat/2) * Math.sin(dLat/2) +
                            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                            Math.sin(dLon/2) * Math.sin(dLon/2);
                        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                        return R * c; 
                    }

                    const userLat = parseFloat(latitudeInput.value);
                    const userLng = parseFloat(longitudeInput.value);
                    const distance = getDistanceFromLatLonInM(OFFICE_LAT, OFFICE_LNG, userLat, userLng);

                    if (distance > MAX_RADIUS) {
                        e.preventDefault();
                        const msg = `Jarak Anda terlalu jauh dari kantor (${Math.round(distance)} meter). Jarak maksimal adalah ${MAX_RADIUS} meter. Silakan mendekat ke area kantor untuk absen Hadir (WFO).`;
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Di Luar Area Kantor',
                                text: msg,
                                confirmButtonColor: '#ef4444'
                            });
                        } else {
                            alert(msg);
                        }
                        return;
                    }
                }
            }

            // Show submit loading feedback
            const submitBtn = attendanceForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim absensi...';
            }
        });
    }

    function updateLabels() {
        const checked = document.querySelector('input[name="status"]:checked') || document.getElementById('status_checkout');
        if (!checked) return;
        const v = checked.value;
        const isCheckout = !!document.getElementById('status_checkout');

        if (isCheckout) {
            // Checkout Form Logic
            if (['hadir', 'wfh'].includes(v)) {
                if (photoSection) photoSection.style.display = 'block';
                if (fotoLabel) fotoLabel.innerHTML = 'Unggah Gambar Bukti Pengerjaan <span class="text-danger">*</span>';
                if (fotoInput) fotoInput.required = true;
                if (laporanSection) laporanSection.style.display = 'block';
                if (laporanLabel) laporanLabel.innerHTML = 'Laporan Hasil Pekerjaan Hari Ini <span class="text-danger">*</span>';
                if (laporanInput) {
                    laporanInput.placeholder = 'Jelaskan progres pekerjaan Anda hari ini...';
                    laporanInput.required = true;
                }
            } else if (v === 'sakit') {
                if (photoSection) photoSection.style.display = 'none';
                if (fotoInput) fotoInput.required = false;
                if (laporanSection) laporanSection.style.display = 'block';
                if (laporanLabel) laporanLabel.innerHTML = 'Catatan/Keterangan Tambahan';
                if (laporanInput) {
                    laporanInput.placeholder = 'Keterangan tambahan...';
                    laporanInput.required = false;
                }
            } else {
                if (photoSection) photoSection.style.display = 'none';
                if (fotoInput) fotoInput.required = false;
                if (laporanSection) laporanSection.style.display = 'block';
                if (laporanLabel) laporanLabel.innerHTML = 'Catatan/Keterangan Tambahan';
                if (laporanInput) {
                    laporanInput.placeholder = 'Keterangan tambahan...';
                    laporanInput.required = false;
                }
            }
        } else {
            // Check-in Form Logic: No daily report on Hadir / WFH!
            if (['hadir', 'wfh'].includes(v)) {
                if (photoSection) photoSection.style.display = 'none';
                if (fotoInput) {
                    fotoInput.required = false;
                    fotoInput.value = '';
                }
                if (laporanSection) laporanSection.style.display = 'none';
                if (laporanInput) {
                    laporanInput.required = false;
                    laporanInput.value = '';
                }
            } else if (v === 'sakit') {
                if (photoSection) photoSection.style.display = 'block';
                if (fotoLabel) fotoLabel.innerHTML = 'Surat Keterangan Dokter (Opsional)';
                if (fotoInput) fotoInput.required = false;
                if (laporanSection) laporanSection.style.display = 'block';
                if (laporanLabel) laporanLabel.innerHTML = 'Keterangan Sakit';
                if (laporanInput) {
                    laporanInput.placeholder = 'Rincian kondisi kesehatan...';
                    laporanInput.required = false;
                }
            } else if (v === 'izin') {
                if (photoSection) photoSection.style.display = 'block';
                if (fotoLabel) fotoLabel.innerHTML = 'Lampiran Izin (Opsional)';
                if (fotoInput) fotoInput.required = false;
                if (laporanSection) laporanSection.style.display = 'block';
                if (laporanLabel) laporanLabel.innerHTML = 'Alasan Izin <span class="text-danger">*</span>';
                if (laporanInput) {
                    laporanInput.placeholder = 'Alasan pengajuan izin...';
                    laporanInput.required = true;
                }
            }
        }

        // Update GPS Location label & description dynamically
        const locationLabel = document.getElementById('location_label');
        if (locationLabel) {
            if (v === 'wfh') {
                locationLabel.innerHTML = 'Lokasi WFH <span class="text-danger">*</span>';
            } else if (v === 'hadir') {
                locationLabel.innerHTML = 'Lokasi Hadir (Kantor) <span class="text-danger">*</span>';
            } else {
                locationLabel.innerHTML = 'Lokasi Presensi <span class="text-danger">*</span>';
            }
        }
        const locStatusText = document.getElementById('location_status');
        if (locStatusText && (!latitudeInput?.value || !longitudeInput?.value)) {
            if (v === 'wfh') {
                locStatusText.innerText = 'Klik Kunci Lokasi untuk menyimpan posisi WFH.';
            } else if (v === 'hadir') {
                locStatusText.innerText = 'Klik Kunci Lokasi untuk memverifikasi posisi Anda di kantor.';
            } else {
                locStatusText.innerText = 'Klik Kunci Lokasi untuk menyimpan posisi Anda.';
            }
        }

        // Show/hide camera section
        const showCamera = (!isCheckout && ['hadir', 'wfh', 'sakit'].includes(v)) || (isCheckout && v === 'sakit');
        if (showCamera) {
            if (cameraSection) cameraSection.style.display = 'block';
            if (!cameraStream && cameraPanel) cameraPanel.style.display = 'none';
            if (!cameraStream && cameraStartActions) cameraStartActions.style.display = 'flex';
            if (cameraLabel) {
                cameraLabel.innerHTML = (isCheckout ? 'Foto Kamera Terkini' : 'Foto Kamera') + ' <span class="text-danger">*</span>';
            }
        } else {
            if (cameraSection) cameraSection.style.display = 'none';
            if (fotoKameraInput) {
                fotoKameraInput.value = '';
            }
            if (cameraPreviewUrl) {
                URL.revokeObjectURL(cameraPreviewUrl);
                cameraPreviewUrl = null;
            }
            if (cameraPreview) {
                cameraPreview.removeAttribute('src');
                cameraPreview.style.display = 'none';
            }
            stopCamera();
        }

        // Show/hide location section
        hideLocationSection();
    }

    radios.forEach(r => r.addEventListener('change', updateLabels));
    updateLabels();
});

function confirmCancelTask(taskId, taskJudul) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Batalkan Pilihan Tugas?',
            html: `Apakah Anda yakin ingin membatalkan pengerjaan <strong>${taskJudul}</strong>?<br><small class="text-muted">Tugas atau modul ini akan kembali tersedia sehingga Anda bisa memilih pekerjaan lain.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Batalkan Pilihan',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-cancel-' + taskId).submit();
            }
        });
    } else {
        if (confirm(`Apakah Anda yakin ingin membatalkan pengerjaan tugas "${taskJudul}"?`)) {
            document.getElementById('form-cancel-' + taskId).submit();
        }
    }
}
</script>
@endsection
