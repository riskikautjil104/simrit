<div class="py-14 max-w-5xl mx-auto px-4 sm:px-6">

    {{-- Page Header --}}
    <div class="text-center mb-14">
        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-full px-4 py-1.5 mb-4">
            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <span class="text-blue-700 text-xs font-bold uppercase tracking-widest">Identitas Visual</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">Filosofi Logo SIMRIT</h1>
        <p class="text-slate-500 text-base mt-3 max-w-xl mx-auto">
            Makna dan arti dari setiap elemen visual yang membentuk identitas
            Sistem Informasi Manajemen Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate.
        </p>
    </div>

    {{-- Logo Display --}}
    <div class="flex justify-center mb-14">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 sm:p-12 max-w-xl w-full">
            <img src="{{ asset('logo/logoruangit.png') }}"
                 alt="Logo SIMRIT RSUD Dr. H. Chasan Boesoirie Ternate"
                 class="w-full h-auto object-contain max-h-72 drop-shadow-md">
        </div>
    </div>

    {{-- Meaning Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10">

        {{-- Huruf T / IT --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 3h6M12 3v18M9 21h6"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base mb-1">Huruf "T" / Simbol IT</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Huruf T besar berwarna biru tua yang berdiri kokoh melambangkan fondasi teknologi informasi (IT) sebagai tulang punggung sistem manajemen rumah sakit yang kuat dan andal.
                </p>
            </div>
        </div>

        {{-- Palang Merah --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v6m-3-3h6m-3-9a9 9 0 110 18A9 9 0 0112 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base mb-1">Palang Merah (Plus)</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Simbol palang merah pada lingkaran putih merepresentasikan identitas lembaga kesehatan — RSUD Dr. H. Chasan Boesoirie — dengan semangat pelayanan kesehatan yang tulus dan profesional.
                </p>
            </div>
        </div>

        {{-- Gedung Rumah Sakit --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base mb-1">Gedung Rumah Sakit</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Ilustrasi gedung rumah sakit di bagian atas logo mencerminkan fisik RSUD Dr. H. Chasan Boesoirie sebagai institusi pelayanan kesehatan utama di Kota Ternate, Maluku Utara.
                </p>
            </div>
        </div>

        {{-- Gunung Gamalama --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 3l7 14 7-14"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base mb-1">Gunung Gamalama</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Siluet gunung berwarna hijau di belakang gedung melambangkan Gunung Gamalama, ikon alam Pulau Ternate, sebagai simbol kekuatan, keteguhan, dan kebanggaan daerah.
                </p>
            </div>
        </div>

        {{-- Sirkuit Digital --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-cyan-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base mb-1">Motif Sirkuit Digital</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Pola sirkuit elektronik pada bagian batang huruf T melambangkan transformasi digital dan konektivitas sistem informasi yang terintegrasi di seluruh unit layanan rumah sakit.
                </p>
            </div>
        </div>

        {{-- Kotak-kotak Hijau --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base mb-1">Kotak-Kotak Gradasi</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Susunan kotak biru dan hijau bergradasi melambangkan data yang terstruktur, modul sistem yang saling terhubung, serta pertumbuhan dan perkembangan sistem informasi yang berkelanjutan.
                </p>
            </div>
        </div>

        {{-- Gelombang Biru --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 15c1.5-2 3-3 4.5-3s3 2 4.5 2 3-1 4.5-3"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base mb-1">Gelombang Biru &amp; Hijau</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Garis melengkung dan gelombang di sekeliling lingkaran melambangkan laut Ternate yang mengitari Pulau Ternate, sekaligus menggambarkan aliran informasi yang dinamis, cepat, dan berkesinambungan.
                </p>
            </div>
        </div>

        {{-- Warna --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-slate-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base mb-1">Makna Warna</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    <strong class="text-blue-700">Biru tua</strong> — kepercayaan, profesionalisme, dan teknologi.
                    <strong class="text-green-600">Hijau</strong> — kesehatan, pertumbuhan, dan alam.
                    <strong class="text-red-600">Merah</strong> — keberanian, semangat pelayanan, dan identitas medis.
                    <strong class="text-slate-700">Putih</strong> — kebersihan, kejujuran, dan transparansi.
                </p>
            </div>
        </div>
    </div>

    {{-- Tagline --}}
    <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl p-8 text-center text-white shadow-lg">
        <div class="flex justify-center mb-3">
            <svg class="w-8 h-8 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
        </div>
        <h2 class="text-xl font-extrabold mb-2">Inovatif &bull; Terintegrasi &bull; Profesional</h2>
        <p class="text-white/80 text-sm max-w-lg mx-auto">
            Tiga nilai utama SIMRIT yang tercermin dalam setiap elemen logo — komitmen untuk terus berinovasi,
            membangun sistem yang terintegrasi, dan memberikan layanan IT yang profesional bagi RSUD Dr. H. Chasan Boesoirie Ternate.
        </p>
    </div>
</div>
