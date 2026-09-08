@extends('layouts.app')

@section('title', 'Riwayat Layanan Publik')

@section('content')
<div class="container py-5">
    <div class="glass-card p-4 p-md-5">
        <h4 class="fw-bold mb-4 text-primary"><i class="fa-solid fa-list text-secondary me-2"></i> Riwayat Permohonan Layanan</h4>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Jenis Layanan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($layanans as $layanan)
                    <tr>
                        <td class="fw-medium text-capitalize">{{ $layanan->jenisLayanan->nama ?? '-' }}</td>
                        <td>{{ $layanan->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge rounded-pill" style="background-color: {{ $layanan->statusMaster->warna ?? '#ccc' }}">
                                {{ $layanan->statusMaster->nama ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('layanan.show', $layanan->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat permohonan layanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $layanans->links() }}
        </div>
    </div>
</div>
@endsection
