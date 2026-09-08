@extends('pelayanan.layouts.app')

@section('title', 'Formulir Pendaftaran Magang - LENTERA')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                
                {{-- Header --}}
                <div class="mb-4 pb-3 border-bottom">
                    <h3 class="fw-bold mb-1 text-dark">Formulir Pendaftaran Magang</h3>
                    <p class="text-muted mb-0 small">
                        Mendaftar untuk posisi <span class="fw-bold text-primary">{{ $rekrutmen->judul }}</span> 
                        di <span class="fw-bold text-dark">{{ $rekrutmen->dinas->name ?? $rekrutmen->dinas->nama ?? 'Dinas Terkait' }}</span>.
                    </p>
                </div>

                {{-- Alert Error --}}
                @if(session('error'))
                    <div class="alert alert-danger rounded-3 border-0 shadow-sm d-flex align-items-center gap-2 mb-4">
                        <i class="fa-solid fa-circle-exclamation fs-5"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                {{-- Alert Info Rekomendasi Kesbangpol --}}
                <div class="alert alert-primary rounded-3 border-0 p-3 mb-4 d-flex align-items-start gap-3" 
                     style="background: rgba(17, 92, 185, 0.08); border-left: 4px solid #115cb9 !important; color: #1f477b;">
                    <i class="fa-solid fa-circle-info fs-5 text-primary mt-1 flex-shrink-0"></i>
                    <div class="small">
                        Pengajuan pendaftaran ini akan otomatis melampirkan izin rekomendasi Kesbangpol Anda yang terakhir disetujui.
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('magang.submit', $rekrutmen->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="permohonan_layanan_id" value="{{ $permohonanLayanan->id ?? '' }}">

                    {{-- Pesan Lamaran --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small mb-2">
                            Pesan Lamaran / Motivasi <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <textarea name="pesan_lamaran" rows="4" 
                            class="form-control rounded-3 p-3 text-sm" 
                            style="border: 1px solid #cbd5e1; resize: vertical;" 
                            placeholder="Tuliskan motivasi, keahlian, atau pesan tambahan Anda..."></textarea>
                    </div>

                    {{-- Tanggal Mulai & Selesai --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-2">Tanggal Mulai Magang <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" required 
                                class="form-control rounded-3 p-2.5 text-sm" 
                                style="border: 1px solid #cbd5e1;" 
                                value="{{ !empty($permohonanLayanan->tanggal_mulai) ? (is_string($permohonanLayanan->tanggal_mulai) ? \Carbon\Carbon::parse($permohonanLayanan->tanggal_mulai)->format('Y-m-d') : $permohonanLayanan->tanggal_mulai->format('Y-m-d')) : date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-2">Tanggal Selesai Magang <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" required 
                                class="form-control rounded-3 p-2.5 text-sm" 
                                style="border: 1px solid #cbd5e1;" 
                                value="{{ !empty($permohonanLayanan->tanggal_selesai) ? (is_string($permohonanLayanan->tanggal_selesai) ? \Carbon\Carbon::parse($permohonanLayanan->tanggal_selesai)->format('Y-m-d') : $permohonanLayanan->tanggal_selesai->format('Y-m-d')) : '' }}">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <button type="submit" class="btn text-white fw-bold px-4 py-2.5 rounded-3 shadow-sm d-inline-flex align-items-center gap-2" 
                                style="background: linear-gradient(135deg, #115cb9 0%, #3b82f6 100%); transition: all 0.3s ease;">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Pendaftaran
                        </button>
                        <a href="{{ route('landing.instansi_detail', $rekrutmen->dinas_id) }}" 
                           class="btn btn-light text-secondary fw-semibold px-4 py-2.5 rounded-3 text-decoration-none">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tglMulai = document.querySelector('input[name="tanggal_mulai"]');
    const tglSelesai = document.querySelector('input[name="tanggal_selesai"]');
    
    function syncDates() {
        if (tglMulai && tglSelesai && tglMulai.value) {
            tglSelesai.min = tglMulai.value;
            if (tglSelesai.value && tglSelesai.value < tglMulai.value) {
                tglSelesai.value = tglMulai.value;
            }
        }
    }
    if (tglMulai) {
        tglMulai.addEventListener('change', syncDates);
        tglMulai.addEventListener('input', syncDates);
        syncDates();
    }

    const form = document.querySelector('form[action*="magang.submit"]');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (tglMulai && tglSelesai && tglMulai.value && tglSelesai.value) {
                if (tglSelesai.value < tglMulai.value) {
                    e.preventDefault();
                    alert('Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.');
                    tglSelesai.focus();
                    return;
                }
            }
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Mengirim...`;
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Mengirim Pendaftaran...',
                    text: 'Mohon tunggu sebentar, data pendaftaran Anda sedang diproses.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    }
});
</script>
@endsection
