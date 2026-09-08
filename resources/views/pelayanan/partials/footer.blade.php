<footer class="bg-[#0f172a] text-white py-12 mt-auto" id="kontak">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10 border-b border-gray-800 pb-10">
            <!-- Col 1: Brand -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('assets/logo_diskominfo_bogorkab.png') }}" alt="Logo Diskominfo Kabupaten Bogor" style="height: 52px; width: auto; object-fit: contain;">
                </div>
                <p class="text-sm text-gray-400 leading-relaxed mb-4 text-left">
                    Dinas Komunikasi dan Informatika Kabupaten Bogor. Fasilitas pelayanan online untuk mempermudah permohonan surat izin magang, penelitian, dan kegiatan lainnya.
                </p>
                <div class="flex gap-3">
                    <a href="https://www.instagram.com/diskominfokabbogor/?hl=en" target="_blank" class="w-8 h-8 bg-gray-800 text-gray-300 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="w-8 h-8 bg-gray-800 text-gray-300 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/@DISKOMINFOKABBOGOR" target="_blank" class="w-8 h-8 bg-gray-800 text-gray-300 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <!-- Col 2: Hubungi Kami -->
            <div>
                <h4 class="text-lg font-semibold mb-4 text-gray-200 text-left">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="fa-brands fa-whatsapp text-green-400 mt-1"></i>
                        <div class="text-left">
                            <p class="text-sm text-gray-300 font-medium">WhatsApp Admin</p>
                            <a href="https://wa.me/62895422603123" target="_blank" class="text-sm text-gray-400 hover:text-white transition">0895-4226-03123</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-regular fa-clock text-blue-400 mt-1"></i>
                        <div class="text-left">
                            <p class="text-sm text-gray-300 font-medium mb-1">Jam Layanan</p>
                            <div class="flex flex-col gap-1">
                                <div class="grid grid-cols-[100px_1fr] gap-2">
                                    <span class="text-sm text-gray-400">Senin - Kamis</span>
                                    <span class="text-sm text-gray-400">: 08.00 - 15.00</span>
                                </div>
                                <div class="grid grid-cols-[100px_1fr] gap-2">
                                    <span class="text-sm text-gray-400">Jumat</span>
                                    <span class="text-sm text-gray-400">: 08.00 - 11.30</span>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Alamat -->
            <div class="text-left">
                <h4 class="text-lg font-semibold mb-4 text-gray-200 text-left">Alamat Kantor</h4>
                <div class="flex items-start gap-3 mb-4">
                    <i class="fa-solid fa-location-dot text-red-500 mt-1"></i>
                    <p class="text-sm text-gray-400 leading-relaxed text-left">
                        Jl. KSR Dadi Kusmayadi No.41, Kel. Tengah, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16914
                    </p>
                </div>
                <div class="text-left">
                    <a href="https://www.google.com/maps/place/Dinas+Komunikasi+dan+Informatika+Kabupaten+Bogor/@-6.4856438,106.8355556,724m/data=!3m2!1e3!4b1!4m6!3m5!1s0x2e69c1ed6c72faf9:0x94533a62933ec7c2!8m2!3d-6.4856491!4d106.8381305!16s%2Fg%2F1ptxmrdry?entry=ttu&g_ep=EgoyMDI2MDgxOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="inline-flex items-center gap-2 bg-gray-800 text-gray-300 font-medium px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                        <i class="fa-solid fa-map-location-dot"></i> Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <p class="text-xs text-gray-500 tracking-widest uppercase">
                COPYRIGHT &copy; {{ date('Y') }} DISKOMINFO KABUPATEN BOGOR 
            </p>
        </div>
    </div>
</footer>
