@extends('layouts.app')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    body { background-color: #f8fafc; }
    h1, h2, h3, h4, h5, h6 { margin-bottom: 0; }
    
    .glass-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #f1f5f9;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen pb-20">
    <!-- Header -->
    <div class="pt-28 pb-8 relative overflow-hidden">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-gray-800">Daftar Instansi Penyedia Magang</h1>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto mb-8">Temukan instansi, badan, dinas, kecamatan, hingga kelurahan di Kabupaten Bogor yang sesuai untuk kegiatan magang, PKL, atau penelitian Anda.</p>
            
            <!-- Search Bar -->
            <div class="max-w-3xl mx-auto relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-lg">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" id="agencySearch" placeholder="Cari nama instansi... (misal: diskominfo, cibinong)" class="w-full py-4 pl-14 pr-6 rounded-full bg-white text-gray-800 shadow-sm border border-gray-200 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-300 transition text-lg">
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 mt-6">
        
        <!-- Filter Buttons (Optional, tapi bagus jika ada) -->
        <div class="flex flex-nowrap overflow-x-auto custom-scrollbar gap-3 mb-10 pb-4 w-full justify-start md:justify-center">
            <button class="agency-filter-btn whitespace-nowrap px-6 py-2.5 text-xs uppercase tracking-wider bg-blue-600 text-white font-bold rounded-full shadow-sm transition" data-filter="all">Semua</button>
            <button class="agency-filter-btn whitespace-nowrap px-6 py-2.5 text-xs uppercase tracking-wider bg-white text-gray-600 font-bold rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition" data-filter="Badan">Badan</button>
            <button class="agency-filter-btn whitespace-nowrap px-6 py-2.5 text-xs uppercase tracking-wider bg-white text-gray-600 font-bold rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition" data-filter="Dinas">Dinas</button>
            <button class="agency-filter-btn whitespace-nowrap px-6 py-2.5 text-xs uppercase tracking-wider bg-white text-gray-600 font-bold rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition" data-filter="Pengawasan">Instansi</button>
            <button class="agency-filter-btn whitespace-nowrap px-6 py-2.5 text-xs uppercase tracking-wider bg-white text-gray-600 font-bold rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition" data-filter="Rumah Sakit">Rumah Sakit</button>
            <button class="agency-filter-btn whitespace-nowrap px-6 py-2.5 text-xs uppercase tracking-wider bg-white text-gray-600 font-bold rounded-full shadow-md border border-gray-200 hover:bg-gray-50 transition" data-filter="Sekretariat">Sekretariat</button>
            <button class="agency-filter-btn whitespace-nowrap px-6 py-2.5 text-xs uppercase tracking-wider bg-white text-gray-600 font-bold rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition" data-filter="Kecamatan">Kecamatan</button>
            <button class="agency-filter-btn whitespace-nowrap px-6 py-2.5 text-xs uppercase tracking-wider bg-white text-gray-600 font-bold rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition" data-filter="Kelurahan">Kelurahan</button>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="agency-cards-container">
            @php
                $agencies = config('agencies');
                $totalCards = 0;
            @endphp

            @if($agencies)
                @foreach($agencies as $category => $items)
                    @foreach($items as $item)
                        @php $totalCards++; @endphp
                        <!-- Card -->
                        <div class="agency-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full hover:shadow-md transition relative" data-category="{{ $category }}" data-name="{{ strtolower($item) }}">
                            <!-- Cover Image -->
                            <div class="w-full h-32 bg-gray-100 relative">
                                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=600&auto=format&fit=crop" alt="Foto {{ $item }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-5 flex-grow flex flex-col">
                                <div class="flex items-start gap-2 mb-3">
                                    <div class="w-1 h-3 bg-blue-600 rounded-full mt-1"></div>
                                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider leading-relaxed">{{ $category === 'Pengawasan' ? 'Instansi Pengawasan' : $category }}</h4>
                                </div>
                                <h3 class="text-base font-bold text-gray-800 mb-3 leading-tight flex-grow">{{ $item }}</h3>
                                
                                <div class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[11px] font-semibold mb-4 border border-blue-100 self-start">
                                    <i class="fa-solid fa-location-dot"></i> Kab. Bogor
                                </div>
                                
                                <div class="space-y-2 text-xs">
                                    <div class="grid grid-cols-[80px_1fr] text-gray-400">
                                        <span>Status</span>
                                        <span class="font-semibold text-emerald-500 flex items-center gap-1"><i class="fa-solid fa-circle-check text-[10px]"></i> Tersedia</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border-t border-gray-50 mt-auto">
                                <a href="#" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-600 py-2.5 rounded-xl font-bold text-[12px] flex items-center justify-center gap-2 transition text-decoration-none">
                                    Lihat Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>
        
        <div id="noResult" class="hidden text-center py-20">
            <div class="text-gray-300 text-6xl mb-4"><i class="fa-solid fa-building-circle-xmark"></i></div>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Instansi tidak ditemukan</h3>
            <p class="text-gray-500">Coba gunakan kata kunci lain atau periksa ejaan Anda.</p>
        </div>

        <!-- Pagination Controls -->
        <div id="paginationControls"></div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('agencySearch');
        const agencyCards = document.querySelectorAll('.agency-card');
        const filterButtons = document.querySelectorAll('.agency-filter-btn');
        const noResult = document.getElementById('noResult');
        
        let activeFilter = 'all';
        let matchedCards = [];
        let currentPage = 1;
        const itemsPerPage = 12; // 3 rows * 4 cols max

        function renderPagination() {
            const totalPages = Math.ceil(matchedCards.length / itemsPerPage);
            const paginationContainer = document.getElementById('paginationControls');
            
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let html = '<div class="flex justify-center gap-2 mt-12 mb-8">';
            
            // Prev button
            html += `<button class="px-4 py-2 rounded-xl font-semibold transition flex items-center justify-center shadow-sm ${currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'}" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fa-solid fa-chevron-left text-sm"></i></button>`;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    html += `<button class="w-10 h-10 rounded-xl font-bold bg-blue-600 text-white shadow-md transition">${i}</button>`;
                } else {
                    html += `<button class="w-10 h-10 rounded-xl font-semibold bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 shadow-sm transition" onclick="changePage(${i})">${i}</button>`;
                }
            }
            
            // Next button
            html += `<button class="px-4 py-2 rounded-xl font-semibold transition flex items-center justify-center shadow-sm ${currentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'}" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fa-solid fa-chevron-right text-sm"></i></button>`;
            
            html += '</div>';
            paginationContainer.innerHTML = html;
        }

        window.changePage = function(page) {
            const totalPages = Math.ceil(matchedCards.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            showCurrentPage();
            renderPagination();
            document.getElementById('agency-cards-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function showCurrentPage() {
            agencyCards.forEach(card => card.style.display = 'none');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const pageCards = matchedCards.slice(startIndex, startIndex + itemsPerPage);
            pageCards.forEach(card => card.style.display = 'flex');
        }

        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            matchedCards = [];

            agencyCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const category = card.getAttribute('data-category');
                
                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = (activeFilter === 'all' || category === activeFilter);

                if (matchesSearch && matchesCategory) {
                    matchedCards.push(card);
                }
                card.style.display = 'none';
            });

            currentPage = 1;

            if (matchedCards.length === 0) {
                noResult.classList.remove('hidden');
                document.getElementById('paginationControls').innerHTML = '';
            } else {
                noResult.classList.add('hidden');
                showCurrentPage();
                renderPagination();
            }
        }

        searchInput.addEventListener('input', applyFilters);

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                activeFilter = button.getAttribute('data-filter');

                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('bg-white', 'text-gray-600');
                });

                button.classList.remove('bg-white', 'text-gray-600');
                button.classList.add('bg-blue-600', 'text-white');

                applyFilters();
            });
        });
        
        applyFilters();
    });
</script>
@endsection
