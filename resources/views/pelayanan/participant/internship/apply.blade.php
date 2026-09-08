@extends('layouts.app')

@section('title', 'Pendaftaran Magang')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-card p-4 p-md-5">
                <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                    <div class="nav-brand-icon bg-primary text-white" style="width: 48px; height: 48px; font-size: 1.5rem;">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">Formulir Pendaftaran Magang</h4>
                        <p class="text-muted mb-0 small">Lengkapi data diri dan unggah dokumen persyaratan.</p>
                    </div>
                </div>

                <form action="{{ route('internship.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-building text-secondary me-2"></i>Instansi Tujuan</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Dinas / Instansi <span class="text-danger">*</span></label>
                            <select name="dinas_id" id="dinas_id" class="form-select form-control-premium" required>
                                <option value="">Pilih Dinas...</option>
                                @foreach($dinas as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                            @error('dinas_id') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Bidang Penempatan <span class="text-danger">*</span></label>
                            <select name="bidang_id" id="bidang_id" class="form-select form-control-premium" required disabled>
                                <option value="">Pilih Bidang...</option>
                            </select>
                            @error('bidang_id') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-user-graduate text-secondary me-2"></i>Data Akademik</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Instansi Asal (Kampus/Sekolah) <span class="text-danger">*</span></label>
                            <input type="text" name="instansi_asal" class="form-control form-control-premium" value="{{ old('instansi_asal', $layananMagang->instansi_asal) }}" readonly>
                            @error('instansi_asal') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Jurusan / Program Studi <span class="text-danger">*</span></label>
                            <input type="text" name="jurusan" class="form-control form-control-premium" value="{{ old('jurusan', $layananMagang->program_studi) }}" readonly>
                            @error('jurusan') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">NIM / NIS <span class="text-danger">*</span></label>
                            <input type="text" name="nim_nis" class="form-control form-control-premium" value="{{ old('nim_nis', $layananMagang->nik) }}" readonly>
                            @error('nim_nis') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Alamat Domisili <span class="text-danger">*</span></label>
                            <input type="text" name="alamat" class="form-control form-control-premium" value="{{ old('alamat') }}" required>
                            @error('alamat') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 text-primary"><i class="fa-regular fa-calendar-days text-secondary me-2"></i>Rencana Pelaksanaan</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control form-control-premium" value="{{ old('tanggal_mulai', $layananMagang->tanggal_mulai) }}" readonly>
                            @error('tanggal_mulai') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-premium">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" class="form-control form-control-premium" value="{{ old('tanggal_selesai', $layananMagang->tanggal_selesai) }}" readonly>
                            @error('tanggal_selesai') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <input type="hidden" name="layanan_publik_id" value="{{ $layananMagang->id }}">
                    
                    <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-file-arrow-up text-secondary me-2"></i>Dokumen Persyaratan (Diambil dari Layanan)</h5>
                    <div class="mb-4">
                        <div class="alert alert-info">
                            Dokumen KTP, Pengantar Kampus, dan Rencana Kegiatan Anda telah diverifikasi oleh Kesbangpol. Anda tidak perlu mengunggah ulang.
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5 pt-3 border-top">
                        <a href="{{ route('participant.dashboard') }}" class="btn btn-premium-secondary flex-grow-1 text-center text-decoration-none">Batal</a>
                        <button type="submit" class="btn btn-premium-primary flex-grow-1">
                            <i class="fa-solid fa-paper-plane me-2"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('dinas_id').addEventListener('change', function() {
    let dinasId = this.value;
    let bidangSelect = document.getElementById('bidang_id');
    
    bidangSelect.innerHTML = '<option value="">Pilih Bidang...</option>';
    bidangSelect.disabled = true;

    if (dinasId) {
        fetch(`/api/bidang/${dinasId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(bidang => {
                    bidangSelect.innerHTML += `<option value="${bidang.id}">${bidang.nama_bidang}</option>`;
                });
                bidangSelect.disabled = false;
            });
    }
});
</script>
@endsection
