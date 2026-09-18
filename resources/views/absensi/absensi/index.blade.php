@extends('absensi.layouts.app')

@section('title', 'Ruang Kerja Magang')

@section('styles')
<style>
:root {
    --radius: 12px;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 8px rgba(0,0,0,0.03);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.06);
}

/* ── Page Layout ── */
.workspace-header { padding: 1.25rem 0 0.5rem; }
.workspace-header h5 { font-size: 1.1rem; font-weight: 800; color: var(--dark); margin: 0; }
.workspace-header span { font-size: 0.78rem; color: var(--text-muted); }

/* ── Card Base ── */
.ws-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
}
.ws-card-header {
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid var(--border);
    background: #fafbff;
    border-radius: var(--radius) var(--radius) 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}
.ws-card-header h6 {
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}
.ws-card-body { padding: 1.25rem; }

/* ── Project Overview Card ── */
.project-overview-card {
    background: linear-gradient(135deg, #f5f3ff 0%, #eef2ff 100%);
    border: 1px solid rgba(17, 92, 185,0.15);
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow-sm);
}
.project-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.status-on-track { background: rgba(16,185,129,0.12); color: #047857; }
.status-late { background: rgba(239,68,68,0.12); color: #dc2626; }
.status-ahead { background: rgba(17, 92, 185,0.12); color: #1f477b; }
.progress-bar-slim { height: 6px; border-radius: 999px; background: rgba(0,0,0,0.07); overflow: hidden; }
.progress-fill { height: 100%; border-radius: inherit; transition: width 0.4s ease; }

/* ── Active Task Card ── */
.task-active-card {
    border: 1px solid var(--border);
    border-left: 4px solid var(--primary) !important;
    border-radius: var(--radius);
    background: var(--white);
    box-shadow: var(--shadow-sm);
    margin-bottom: 1rem;
}

/* ── Status Badges ── */
.task-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.3rem 0.65rem;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.task-badge-working { background: rgba(17, 92, 185,0.1); color: #1f477b; }
.task-badge-review { background: rgba(245,158,11,0.12); color: #b45309; }
.task-badge-revision { background: rgba(239,68,68,0.1); color: #dc2626; }

/* ── Available Task Module Header ── */
.module-header {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border);
}
.module-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: rgba(17, 92, 185,0.08);
    color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}
.module-title { font-size: 0.88rem; font-weight: 700; color: var(--dark); }
.module-bobot {
    font-size: 0.68rem; font-weight: 700;
    background: rgba(17, 92, 185,0.08);
    color: var(--primary);
    padding: 0.2rem 0.5rem;
    border-radius: 999px;
}

/* ── Task Item Card ── */
.task-item {
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--white);
    padding: 1rem;
    height: 100%;
    display: flex; flex-direction: column; justify-content: space-between;
    transition: box-shadow 0.2s, transform 0.2s;
}
.task-item:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
.task-item-title { font-size: 0.86rem; font-weight: 700; color: var(--dark); margin-bottom: 0.35rem; }
.task-item-meta { font-size: 0.72rem; color: var(--text-muted); margin-bottom: 0.75rem; }

/* ── Form Presensi ── */
.presensi-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 1.5rem;
    margin-bottom: 1rem;
}
.status-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.65rem; }
@media (max-width: 480px) { .status-grid { grid-template-columns: 1fr 1fr; } }
.status-card {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    background: var(--white);
    padding: 1rem 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex; flex-direction: column;
    align-items: flex-start;
    height: 100%;
}
.status-card:hover { border-color: #c7d2fe; background: #f5f3ff; }
.btn-check:checked + .status-card { border-color: var(--primary); background: rgba(17, 92, 185,0.04); box-shadow: 0 0 0 3px rgba(17, 92, 185,0.1); }
.btn-check:disabled + .status-card { opacity: 0.5; cursor: not-allowed; background: #f8fafc; border-color: #e2e8f0; pointer-events: none; }
.s-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; margin-bottom: 0.6rem; }
.s-icon-hadir { background: rgba(0,184,148,0.1); color: #00b894; }
.s-icon-wfh { background: rgba(17, 92, 185,0.1); color: #115cb9; }
.s-icon-sakit { background: rgba(225,112,85,0.1); color: #e17055; }
.s-icon-izin { background: rgba(253,203,110,0.15); color: #d97706; }
.s-name { font-weight: 700; font-size: 0.85rem; color: var(--dark); }
.s-desc { font-size: 0.7rem; color: var(--text-muted); margin-top: 1px; }

/* ── Upload & Camera ── */
.upload-zone {
    border: 2px dashed var(--border);
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    background: #fafbff;
}
.upload-zone:hover { border-color: var(--primary); background: rgba(17, 92, 185,0.02); }
.upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.upload-preview { display: none; width: 100%; max-height: 200px; margin-top: 0.85rem; border-radius: 10px; object-fit: contain; }
.file-name { display: none; margin-top: 0.5rem; font-size: 0.8rem; font-weight: 600; color: var(--primary); }
.camera-panel { display: none; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; background: #0f172a; margin-top: 0.85rem; }
.camera-panel video { display: block; width: 100%; max-height: 480px; height: 100%; object-fit: cover; }
.camera-actions { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.75rem; background: #fff; border-top: 1px solid var(--border); }
.camera-preview { display: none; width: 100%; max-height: 480px; height: 100%; object-fit: contain; background: #fff; }
.camera-start-actions { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.85rem 1rem; border: 1px solid var(--border); border-radius: 10px; background: #fff; margin-top: 0.85rem; }
.location-panel { display: none; }
.location-panel.show { display: block; }
.location-meta { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.6rem; }
.location-pill { border-radius: 999px; background: rgba(17, 92, 185,0.08); color: var(--primary); font-size: 0.72rem; font-weight: 700; padding: 0.28rem 0.6rem; }

/* ── Stats Widget ── */
.stat-widget { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm); padding: 1.25rem; }
.stat-row { display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: 10px; margin-bottom: 0.4rem; }
.stat-dot { width: 8px; height: 8px; border-radius: 999px; display: inline-block; margin-right: 0.45rem; }

/* ── History Table ── */
.history-wrap { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 2rem; }
.history-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
.history-table thead th { background: #f8fafc; color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 0.8rem 1rem; text-align: left; border-bottom: 1px solid var(--border); }
.history-table tbody td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
.history-table tbody tr:last-child td { border-bottom: none; }
.history-table tbody tr:hover { background: #fafbff; }
.badge-status { display: inline-block; padding: 0.28em 0.65em; font-size: 0.68rem; font-weight: 700; border-radius: 7px; text-transform: uppercase; letter-spacing: 0.3px; }
.badge-hadir { background: rgba(0,184,148,0.1); color: #00b894; }
.badge-wfh { background: rgba(17, 92, 185,0.1); color: var(--primary); }
.badge-sakit { background: rgba(225,112,85,0.1); color: #e17055; }
.badge-izin { background: rgba(253,203,110,0.15); color: #d97706; }
.attachment-link { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.78rem; font-weight: 600; color: var(--primary); text-decoration: none; }
.attachment-thumb { width: 36px; height: 36px; border-radius: 7px; object-fit: cover; border: 1px solid var(--border); }

/* ── Filter Bar ── */
.filter-bar-wrap { padding: 1.25rem; background: #fafbff; border-bottom: 1px solid var(--border); }
.filter-extra { display: none; }
.filter-extra.show { display: block; }

/* ── Focus Banner ── */
.focus-banner { border: 1px solid #c7d2fe; border-left: 4px solid var(--primary) !important; background: #f5f3ff; border-radius: var(--radius); padding: 1rem 1.25rem; margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 0.75rem; }
.focus-banner-icon { width: 36px; height: 36px; border-radius: 10px; background: rgba(17, 92, 185,0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

/* ── Empty States ── */
.empty-state-ws { text-align: center; padding: 2.5rem 1rem; }
.empty-state-ws .empty-icon { width: 52px; height: 52px; border-radius: 14px; background: rgba(17, 92, 185,0.06); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 0.75rem; }
.empty-state-ws h6 { font-weight: 700; color: var(--dark); margin-bottom: 0.3rem; font-size: 0.9rem; }
.empty-state-ws p { font-size: 0.78rem; color: var(--text-muted); margin: 0; }

@media (min-width: 992px) { .border-start-lg { border-left: 1px solid var(--border) !important; } }

/* SVG progress ring styling */
.pct-ring-bg { stroke: #eef2f6; }
.pct-ring-fill { stroke: var(--primary); transition: stroke-dashoffset 0.35s; transform: rotate(-90deg); transform-origin: 50% 50%; }
</style>
@endsection

@section('content')
<div class="container py-3">
    <!-- Workspace Header -->
    <div class="workspace-header mb-3">
        <h5>Absensi dan Laporan Harian</h5>
        <span>Catat kehadiran harian dan buat laporan kegiatan magang Anda secara rutin</span>
    </div>

    <!-- 1. Project Overview or Project Selector (Top - Full Width) -->
    @if ($selectedProject)
        @php $project = $selectedProject; @endphp
        <div class="project-overview-card">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="project-status-badge status-on-track">
                            <i class="fa-solid fa-circle-play me-1"></i> Proyek Aktif
                        </span>
                        @if (!$hasActiveTask)
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2.5 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:0.75rem;">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i> Ganti Proyek
                                </button>
                                <ul class="dropdown-menu shadow-sm border-0">
                                    @if (isset($allActiveProjects) && $allActiveProjects->count() > 1)
                                        <li class="dropdown-header small">Pilih Proyek Lain</li>
                                        @foreach ($allActiveProjects as $pOption)
                                            @if ($pOption->id !== $project->id)
                                                <li>
                                                    <a class="dropdown-item small" href="{{ route('absensi.index', ['project_id' => $pOption->id]) }}">
                                                        <i class="fa-solid fa-folder me-1 text-primary"></i> {{ $pOption->nama }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item small text-danger" href="{{ route('absensi.index', ['reset_project' => 1]) }}">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Batalkan Pilihan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <span class="badge bg-light text-muted border rounded-pill py-1 px-2.5" style="font-size:0.75rem;" title="Batalkan tugas aktif pada 'Tugas Saya Hari Ini' untuk mengganti proyek">
                                <i class="fa-solid fa-lock me-1"></i> Terkunci karena ada tugas aktif
                            </span>
                        @endif
                    </div>
                    <h4 class="fw-bold mt-2 mb-1 text-dark">{{ $project->nama }}</h4>
                    <p class="text-muted small mb-0">
                        <i class="fa-regular fa-calendar me-1"></i> Durasi: {{ $project->tanggal_mulai->translatedFormat('d M Y') }} — {{ $project->tanggal_selesai->translatedFormat('d M Y') }}
                    </p>
                </div>
                <!-- Progress Ring SVG -->
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 60px; height: 60px; position: relative;">
                        <svg viewBox="0 0 36 36" style="width: 100%; height: 100%;">
                            <circle class="pct-ring-bg" cx="18" cy="18" r="16" fill="none" stroke-width="3" />
                            <circle class="pct-ring-fill" cx="18" cy="18" r="16" fill="none" stroke-width="3" stroke-dasharray="{{ $project->actual_progress }}, 100" />
                        </svg>
                        <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; color: var(--dark);">
                            {{ $project->actual_progress }}%
                        </div>
                    </div>
                    <div>
                        <div class="small fw-bold text-dark">Progres Aktual</div>
                        <div class="text-muted small">Berdasarkan modul selesai</div>
                    </div>
                </div>
            </div>
            @if ($project->kebutuhan)
                <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit($project->kebutuhan, 200) }}</p>
            @endif
            <div class="row g-3 align-items-center mt-2">
                <div class="col-sm-6">
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Target Jadwal</span>
                        <span class="fw-bold text-dark">{{ $project->planned_progress }}%</span>
                    </div>
                    <div class="progress-bar-slim">
                        <div class="progress-fill" style="width: {{ $project->planned_progress }}%; background: #94a3b8;"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Progres Pengerjaan</span>
                        <span class="fw-bold text-primary">{{ $project->actual_progress }}%</span>
                    </div>
                    <div class="progress-bar-slim">
                        <div class="progress-fill" style="width: {{ $project->actual_progress }}%; background: var(--primary);"></div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (isset($allActiveProjects) && $allActiveProjects->isNotEmpty())
        <!-- Project Selector Card when no project has been selected yet -->
        <div class="project-overview-card p-4" style="background: linear-gradient(135deg, rgba(17, 92, 185,0.06) 0%, rgba(255,255,255,1) 100%); border: 1.5px dashed rgba(17, 92, 185,0.35);">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fw-semibold mb-2">
                        <i class="fa-solid fa-hand-pointer me-1"></i> Pilih Proyek Magang
                    </span>
                    <h5 class="fw-bold text-dark mb-1">Silakan Pilih Proyek Anda Terlebih Dahulu</h5>
                    <p class="text-muted small mb-0">Pilih salah satu proyek aktif berikut untuk menampilkan modul pengerjaan dan tugas yang tersedia.</p>
                </div>
                <div class="w-100 w-md-auto" style="min-width: 280px; max-width: 400px;">
                    <form action="{{ route('absensi.index') }}" method="GET" class="d-flex gap-2">
                        <select name="project_id" class="form-select form-control-admin" required>
                            <option value="" disabled selected>-- Pilih Proyek Aktif --</option>
                            @foreach ($allActiveProjects as $pOption)
                                <option value="{{ $pOption->id }}">
                                    {{ $pOption->nama }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary rounded-3 px-3.5 d-flex align-items-center gap-1.5" title="Terapkan Pilihan Proyek">
                            <i class="fa-solid fa-check"></i>
                            <span class="d-none d-sm-inline small fw-semibold">Pilih</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Workspace Grid (Middle - Two Column Layout) -->
    <div class="row g-4">
        <!-- Kolom Kiri -->
        <div class="col-lg-8">


            <!-- Form Presensi Kehadiran -->
            <div class="presensi-card h-100">
                @if ($todayAttendance && $todayAttendance->jam_masuk && $todayAttendance->jam_pulang)
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
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-right-to-bracket me-2 text-primary"></i>Absen Masuk</h6>
                    
                    @php
                        $isHadirWfhDisabled = false;
                    @endphp

                    <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="lokasi_latitude" id="lokasi_latitude" value="{{ old('lokasi_latitude') }}">
                        <input type="hidden" name="lokasi_longitude" id="lokasi_longitude" value="{{ old('lokasi_longitude') }}">
                        <input type="hidden" name="lokasi_akurasi" id="lokasi_akurasi" value="{{ old('lokasi_akurasi') }}">

                        @if ($hasActiveTask)
                            @php $activeTaskId = $myTodayTasks->first() ? $myTodayTasks->first()->id : null; @endphp
                            <input type="hidden" name="task_id" value="{{ $activeTaskId }}">
                        @endif

                        <!-- Status Absensi Radios -->
                        <div class="mb-3">
                            <label class="form-label-admin">Status Absensi Masuk <span class="text-danger">*</span></label>
                            <div class="status-grid">
                                
                                <!-- Hadir -->
                                <div>
                                    <input type="radio" class="btn-check" name="status" id="status_hadir" value="hadir" {{ old('status', $isHadirWfhDisabled ? 'sakit' : 'hadir') === 'hadir' ? 'checked' : '' }} {{ $isHadirWfhDisabled ? 'disabled' : '' }} autocomplete="off">
                                    <label class="status-card" for="status_hadir">
                                        <div class="s-icon s-icon-hadir"><i class="fa-solid fa-building"></i></div>
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
                                        <div class="s-icon s-icon-sakit"><i class="fa-solid fa-face-tired"></i></div>
                                        <div class="s-name">Sakit</div>
                                        <div class="s-desc">Izin medis</div>
                                    </label>
                                </div>

                                <!-- Izin -->
                                <div>
                                    <input type="radio" class="btn-check" name="status" id="status_izin" value="izin" {{ old('status') === 'izin' ? 'checked' : '' }} autocomplete="off">
                                    <label class="status-card" for="status_izin">
                                        <div class="s-icon s-icon-izin"><i class="fa-solid fa-file-lines"></i></div>
                                        <div class="s-name">Izin</div>
                                        <div class="s-desc">Keperluan lain</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Location Locker Section -->
                        <div class="mb-3 location-panel show" id="location_section">
                            <label class="form-label-admin" id="location_label">Lokasi WFH / Kantor <span class="text-danger">*</span></label>
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

                        <!-- Camera Capture Section -->
                        <div class="mb-3" id="camera_section">
                            <label class="form-label-admin" id="camera_label">Foto Kamera <span class="text-danger">*</span></label>
                            <input type="file" name="foto_kamera" id="foto_kamera" accept="image/*" class="d-none">
                            <div class="camera-start-actions" id="camera_start_actions">
                                <span class="camera-message small">Nyalakan kamera lalu ambil foto diri untuk bukti.</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="start_camera">
                                    <i class="fa-solid fa-video me-1"></i> Kamera
                                </button>
                            </div>
                            <div class="camera-panel" id="camera_panel" style="display: none;">
                                <video id="camera_video" autoplay playsinline muted></video>
                                <img src="" class="camera-preview" id="camera_preview" alt="Pratinjau foto">
                                <canvas id="camera_canvas" class="d-none"></canvas>
                                <div class="camera-actions p-2 bg-light border-top d-flex justify-content-between align-items-center">
                                    <span class="camera-message small text-muted" id="camera_message">Kamera Aktif</span>
                                    <button type="button" class="btn btn-primary btn-sm rounded-3" id="capture_photo">
                                        <i class="fa-solid fa-camera me-1"></i> Ambil Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Upload File (for Sakit / Izin) -->
                        <div class="mb-3" id="photo_section" style="display: none;">
                            <label id="foto_label" class="form-label-admin">Surat Keterangan / Lampiran</label>
                            <div class="upload-zone p-3 border text-center rounded-3 bg-light">
                                <i class="fa-solid fa-cloud-arrow-up text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                                <div class="small fw-semibold text-dark" id="upload_text">Klik untuk unggah berkas</div>
                                <input type="file" name="foto" id="foto" accept="image/*,.pdf">
                                <div class="file-name text-primary small mt-1" id="file_name" style="display: none;">
                                    <i class="fa-solid fa-check-circle me-1"></i><span id="fname"></span>
                                </div>
                                <img src="" class="upload-preview mt-2 w-100 rounded-3 border" id="foto_preview" style="display: none; max-height: 180px; object-fit: contain;">
                            </div>
                        </div>

                        <!-- Laporan / Alasan (Izin / Sakit) -->
                        <div class="mb-3" id="laporan_section" style="display: none;">
                            <label for="laporan" id="laporan_label" class="form-label-admin">Catatan/Alasan <span class="text-danger">*</span></label>
                            <textarea name="keterangan" id="laporan" rows="2" class="form-control form-control-admin w-100" placeholder="Rincian alasan atau keterangan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3">
                            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Absen Masuk
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-4">
            <!-- Ringkasan Kehadiran -->
            <div class="stat-widget h-100">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Kehadiran Bulanan</h6>
                
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <div style="width: 120px; height: 120px; position: relative;">
                        <svg viewBox="0 0 36 36" style="width: 100%; height: 100%;">
                            <circle class="pct-ring-bg" cx="18" cy="18" r="16" fill="none" stroke-width="3" />
                            <circle class="pct-ring-fill" cx="18" cy="18" r="16" fill="none" stroke-width="3" stroke-dasharray="{{ $stats['persentase'] }}, 100" />
                        </svg>
                        <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <span style="font-size: 1.3rem; font-weight: 800; color: var(--dark); line-height: 1;">{{ $stats['persentase'] }}%</span>
                            <span class="text-muted" style="font-size: 0.65rem;">Kehadiran</span>
                        </div>
                    </div>
                </div>

                <div class="stat-rows mt-2">
                    <div class="stat-row" style="background: rgba(0, 184, 148, 0.05);">
                        <span class="small fw-semibold"><span class="stat-dot" style="background:#00b894;"></span>Hadir</span>
                        <span class="badge bg-success-subtle text-success">{{ $stats['hadir'] }} hari</span>
                    </div>
                    <div class="stat-row" style="background: rgba(17, 92, 185, 0.05);">
                        <span class="small fw-semibold"><span class="stat-dot" style="background:var(--primary);"></span>WFH</span>
                        <span class="badge bg-primary-subtle text-primary">{{ $stats['wfh'] }} hari</span>
                    </div>
                    <div class="stat-row" style="background: rgba(225, 112, 85, 0.05);">
                        <span class="small fw-semibold"><span class="stat-dot" style="background:#e17055;"></span>Sakit</span>
                        <span class="badge bg-danger-subtle text-danger">{{ $stats['sakit'] }} hari</span>
                    </div>
                    <div class="stat-row" style="background: rgba(253, 203, 110, 0.08);">
                        <span class="small fw-semibold"><span class="stat-dot" style="background:#fdcb6e;"></span>Izin</span>
                        <span class="badge bg-warning-subtle text-warning">{{ $stats['izin'] }} hari</span>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /row -->

    <!-- Riwayat Presensi (Bottom - Full Width) -->
    <div class="history-wrap mt-4">
        <div class="ws-card-header">
            <h6><i class="fa-solid fa-clock-rotate-left me-2" style="color:var(--primary);"></i>Riwayat Kehadiran</h6>
            <span class="badge bg-light text-dark border">{{ $absensi->count() }} entri</span>
        </div>
        
        @if ($absensi->isEmpty())
            <div class="text-center py-5">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px; height:56px; background:rgba(17, 92, 185,0.06);">
                    <i class="fa-solid fa-calendar-xmark text-primary" style="font-size:1.3rem;"></i>
                </div>
                <h6 class="fw-bold">Belum ada riwayat presensi</h6>
                <p class="text-muted small">Catatan kehadiran Anda akan muncul di sini setelah melakukan absen masuk.</p>
            </div>
        @else
            <div class="table-responsive">
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
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($rec->tanggal)->translatedFormat('d M Y') }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">
                                        Masuk: {{ $rec->jam_masuk ?? '-' }} | Pulang: {{ $rec->jam_pulang ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-status badge-{{ $rec->status }}">
                                        {{ strtoupper($rec->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
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
                                    <div class="d-flex align-items-center gap-1">
                                        @if ($rec->foto_pulang)
                                            <a href="{{ asset($rec->foto_pulang) }}" target="_blank">
                                                <img src="{{ asset($rec->foto_pulang) }}" class="attachment-thumb" title="Foto Jam Pulang">
                                            </a>
                                        @endif
                                        @if ($rec->foto)
                                            <a href="{{ asset($rec->foto) }}" target="_blank">
                                                <img src="{{ asset($rec->foto) }}" class="attachment-thumb" title="File Lampiran">
                                            </a>
                                        @elseif ($rec->jurnal && $rec->jurnal->file_lampiran)
                                            <a href="{{ asset($rec->jurnal->file_lampiran) }}" target="_blank">
                                                <img src="{{ asset($rec->jurnal->file_lampiran) }}" class="attachment-thumb" title="File Lampiran">
                                            </a>
                                        @endif
                                        @if (!$rec->foto_pulang && !$rec->foto && !($rec->jurnal && $rec->jurnal->file_lampiran))
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
                    locationAddress.innerHTML = `<i class="fa-solid fa-map-pin text-danger me-1"></i> <span class="fw-medium text-dark">${data.display_name}</span>`;
                    locationAddress.style.display = 'block';
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
