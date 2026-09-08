@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-blue-900 p-6 text-white">
            <h2 class="text-2xl font-semibold">Pilih Dinas / OPD & Daftar Slot Magang</h2>
            <p class="text-blue-100 mt-2">Pastikan memilih instansi yang tepat. Pendaftaran awal akan kedaluwarsa dalam 1 jam jika pengajuan surat tidak diselesaikan.</p>
        </div>
        
        <div class="p-6">
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <ul class="list-disc list-inside text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('booking.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Pilih Dinas / OPD</label>
                    <select name="instansi_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">-- Pilih Instansi --</option>
                        @foreach(\App\Models\Instansi::all() as $instansi)
                            <option value="{{ $instansi->id }}">{{ $instansi->nama }} (Kuota: {{ $instansi->kuota }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Tambahkan Anggota Tim (Maksimal {{ config('magang.maksimal_anggota_tambahan', 2) }})</label>
                    <p class="text-sm text-gray-500 mb-2">Masukkan nama atau email teman yang sudah memiliki akun pada sistem (LENTERA Kab Bogor).</p>
                    <input type="text" id="search-participant" class="w-full border-gray-300 rounded-lg shadow-sm mb-2" placeholder="Cari teman...">
                    <div id="search-results" class="bg-white border border-gray-200 rounded-lg shadow-sm hidden"></div>
                    
                    <div id="participant-list" class="space-y-2 mt-4">
                        <!-- JS akan menambahkan input hidden participant_ids[] -->
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition shadow">Daftar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Contoh implementasi pencarian teman (JavaScript dasar)
    const searchInput = document.getElementById('search-participant');
    const searchResults = document.getElementById('search-results');
    const participantList = document.getElementById('participant-list');
    let addedParticipants = [];

    searchInput.addEventListener('input', function() {
        let query = this.value;
        if(query.length >= 3) {
            fetch(`{{ route('booking.search_users') }}?q=${query}`)
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    searchResults.classList.remove('hidden');
                    data.forEach(user => {
                        let div = document.createElement('div');
                        div.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0';
                        div.innerHTML = `<strong>${user.nama}</strong> <br><span class="text-sm text-gray-500">${user.email}</span>`;
                        div.onclick = function() {
                            if(addedParticipants.length >= {{ config('magang.maksimal_anggota_tambahan', 2) }}) {
                                alert('Maksimal {{ config('magang.maksimal_anggota_tambahan', 2) }} anggota tambahan!');
                                return;
                            }
                            if(addedParticipants.includes(user.id)) {
                                alert('Peserta sudah ditambahkan!');
                                return;
                            }
                            
                            addedParticipants.push(user.id);
                            
                            let participantDiv = document.createElement('div');
                            participantDiv.className = 'flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-200';
                            participantDiv.innerHTML = `
                                <span>${user.nama}</span>
                                <input type="hidden" name="participant_ids[]" value="${user.id}">
                                <button type="button" class="text-red-500 hover:text-red-700 text-sm" onclick="this.parentElement.remove(); addedParticipants = addedParticipants.filter(id => id !== ${user.id})">Hapus</button>
                            `;
                            participantList.appendChild(participantDiv);
                            
                            searchInput.value = '';
                            searchResults.classList.add('hidden');
                        };
                        searchResults.appendChild(div);
                    });
                });
        } else {
            searchResults.classList.add('hidden');
        }
    });
</script>
@endsection

