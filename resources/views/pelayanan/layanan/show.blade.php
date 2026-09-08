@extends('layouts.app')

@section('title', 'Detail Layanan Publik')

@section('content')
<div class="container py-5">
    <div class="glass-card p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-file-lines text-secondary me-2"></i> Detail Permohonan Layanan</h4>
            <span class="badge rounded-pill fs-6" style="background-color: {{ $layanan->statusMaster->warna ?? '#ccc' }}">
                {{ $layanan->statusMaster->nama ?? 'Unknown' }}
            </span>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <h6 class="text-muted fw-bold mb-2">Informasi Umum</h6>
                <table class="table table-sm table-borderless">
                    <tr><td width="40%" class="text-muted">Jenis Layanan</td><td>: <span class="fw-medium text-capitalize">{{ $layanan->jenisLayanan->nama ?? '-' }}</span></td></tr>
                    <tr><td class="text-muted">Atas Nama</td><td>: {{ $layanan->atas_nama }}</td></tr>
                    <tr><td class="text-muted">Asal Instansi</td><td>: {{ $layanan->asal_instansi }}</td></tr>
                    <tr><td class="text-muted">Tanggal Mulai</td><td>: {{ $layanan->tanggal_mulai }}</td></tr>
                    <tr><td class="text-muted">Tanggal Selesai</td><td>: {{ $layanan->tanggal_selesai }}</td></tr>
                </table>
            </div>
            
            <div class="col-md-6 mb-4">
                <h6 class="text-muted fw-bold mb-2">Dokumen Terlampir</h6>
                <div class="d-flex flex-column gap-2">
                    @if($layanan->file_ktp)
                        <a href="{{ Storage::url($layanan->file_ktp) }}" target="_blank" class="btn btn-sm btn-outline-secondary text-start"><i class="fa-solid fa-id-card me-2"></i> KTP</a>
                    @endif
                    @if($layanan->file_surat_pengantar)
                        <a href="{{ Storage::url($layanan->file_surat_pengantar) }}" target="_blank" class="btn btn-sm btn-outline-secondary text-start"><i class="fa-solid fa-file-pdf me-2"></i> Surat Pengantar</a>
                    @endif
                    @if($layanan->file_proposal)
                        <a href="{{ Storage::url($layanan->file_proposal) }}" target="_blank" class="btn btn-sm btn-outline-secondary text-start"><i class="fa-solid fa-file-pdf me-2"></i> Proposal / Rencana Kegiatan</a>
                    @endif
                </div>
            </div>
        </div>
        
        @if($layanan->keterangan)
        <div class="alert alert-warning mb-4">
            <h6 class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Catatan Kesbangpol:</h6>
            <p class="mb-0">{{ $layanan->keterangan }}</p>
        </div>
        @endif
        
        <div class="d-flex gap-2 mt-4 pt-4 border-top">
            <a href="{{ route('layanan.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
            
            @if($layanan->statusMaster && $layanan->statusMaster->kode === 'perlu_revisi')
                <button type="button" class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#revisiModal">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Revisi Permohonan
                </button>
            @endif
            
            @if(isset($layanan->jenisLayanan) && $layanan->jenisLayanan->is_magang && $layanan->statusMaster && $layanan->statusMaster->kode === 'selesai')
                <a href="{{ route('landing.instansi') }}" class="btn btn-primary"><i class="fa-solid fa-building-user me-1"></i> Lanjutkan Pendaftaran Magang</a>
            @endif
        </div>
    </div>
</div>

@if($layanan->statusMaster && $layanan->statusMaster->kode === 'perlu_revisi')
<!-- Modal Revisi (Simplified) -->
<div class="modal fade" id="revisiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('layanan.update', $layanan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Revisi Permohonan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Upload ulang dokumen yang perlu direvisi sesuai catatan Kesbangpol.</p>
                    <div class="mb-3">
                        <label class="form-label">KTP (Opsional jika tidak diganti)</label>
                        <input type="file" name="file_ktp" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Surat Pengantar (Opsional jika tidak diganti)</label>
                        <input type="file" name="file_surat_pengantar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proposal (Opsional jika tidak diganti)</label>
                        <input type="file" name="file_proposal" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
