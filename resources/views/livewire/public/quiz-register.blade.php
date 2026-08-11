<div class="py-12 max-w-4xl mx-auto px-4"
    style="background-image:radial-gradient(rgba(193,39,45,0.05) 1px, transparent 1px); background-size:16px 16px;">

    {{-- Bunting / umbul-umbul strip --}}
    <div class="h-4 w-full mb-2 rounded-full overflow-hidden shadow-inner"
        style="background-image: repeating-linear-gradient(60deg, #C1272D 0 10px, #ffffff 10px 20px);"></div>

    {{-- Header Banner --}}
    <div
        class="relative bg-gradient-to-br from-[#8B1E1E] via-[#B32424] to-[#C1272D] text-white rounded-b-3xl rounded-t-lg pt-8 px-6 sm:px-10 shadow-xl mb-1 overflow-hidden">

        {{-- Watermark angka 81 --}}
        <div class="absolute -right-4 -bottom-6 text-[10rem] sm:text-[13rem] font-black leading-none select-none pointer-events-none text-white/10"
            style="font-family: Georgia, 'Times New Roman', serif; -webkit-text-stroke: 1px rgba(255,255,255,0.15);">81
        </div>

        {{-- Stempel lencana --}}
        <div
            class="absolute top-5 right-5 sm:top-7 sm:right-8 w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-dashed border-[#F2CB6B]/70 flex flex-col items-center justify-center text-center rotate-6 bg-[#8B1E1E]/40 backdrop-blur-sm">
            <span
                class="text-[#F2CB6B] text-[9px] sm:text-[10px] font-bold tracking-wider leading-tight">HUT<br>KE-81</span>
        </div>

        <div class="relative z-10 space-y-3 pb-10 max-w-2xl">
            <span
                class="inline-flex items-center gap-1.5 bg-[#F2CB6B] text-[#5A1414] text-[11px] font-bold tracking-widest uppercase px-3 py-1 rounded-full shadow-sm">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                </svg>
                Dirgahayu Republik Indonesia · 17 Agustus 2026
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight"
                style="font-family: Georgia, 'Times New Roman', serif;">Lomba Cerdas Cermat Online</h1>
            <p class="text-red-100 text-sm max-w-xl leading-relaxed">Semarakkan HUT Kemerdekaan RI Ke-81 dengan menguji
                wawasan kebangsaan, sejarah perjuangan bangsa, dan wawasan teknologi Anda.</p>
            <p class="font-mono text-[11px] tracking-widest text-[#F2CB6B]/90 uppercase pt-1">"Merdeka Berprestasi,
                Cerdas Berbangsa"</p>
        </div>

        {{-- Perforasi bawah ala perangko --}}
        <div class="relative h-4 -mx-6 sm:-mx-10 mt-4"
            style="background-image: radial-gradient(circle, rgba(255,255,255,0.9) 3px, transparent 3.5px); background-size: 16px 16px; background-position: center top;">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mt-6">
        {{-- Info Card --}}
        <div class="md:col-span-5 space-y-6">
            <div
                class="bg-gradient-to-br from-[#FFF7E0] to-[#FDF0C6] border border-[#D9B36C] rounded-2xl p-6 text-center space-y-3 shadow-sm">

                <h3 class="text-sm font-bold text-[#145a1a]">Support by</h3>

                <div
                    class="mx-auto w-30 h-30 rounded-full bg-[#ffffff] flex items-center justify-center shadow-inner overflow-hidden">
                    <img src="{{ asset('logo/logo404.png') }}" alt="Logo" class="w-full h-full object-contain p-2">
                </div>

                <p class="text-xs text-[#5b8a2f]">
                    404 coffee & workspace, Ternate. <br>
                    Jl. Jati Lampu Merah Kampus III Unkhair INFORMATIKA, Ternate, Maluku Utara <br>
                    Instagram: <a href="https://www.instagram.com/404kofi/" target="_blank"
                        class="text-[#119b48] hover:text-[#1e8b3b] font-bold">@404kofi</a>
                </p>

                <a href="https://www.google.com/maps/search/?api=1&query=0.7759051214307087,127.37461984907569"
                    target="_blank" rel="noopener noreferrer"
                    class="inline-block bg-[#099041] hover:bg-[#47c37a] text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                    📍 Kunjungi 404 Kofi →
                </a>
            </div>
            <div
                class="relative bg-[#FDF8ED] border-2 border-dashed border-[#D9B36C] rounded-2xl p-6 shadow-sm space-y-4">
                <div class="absolute -top-3 -left-3 w-6 h-6 rounded-full bg-[#C1272D] border-2 border-white shadow">
                </div>
                <div class="absolute -top-3 -right-3 w-6 h-6 rounded-full bg-[#C1272D] border-2 border-white shadow">
                </div>

                <h2 class="text-md font-bold text-[#5A1414] flex items-center gap-2"
                    style="font-family: Georgia, 'Times New Roman', serif;">
                    <svg class="w-5 h-5 text-[#C1272D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 13H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-8z" />
                    </svg>
                    Ketentuan Lomba
                </h2>
                <ul class="space-y-3 text-xs text-[#5A4632]">
                    <li class="flex gap-2">
                        <span class="text-[#C1272D] font-bold">✓</span>
                        <span>Terbuka untuk seluruh staf dan mitra RSUD Dr. H. Chasan Boesoirie.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-[#C1272D] font-bold">✓</span>
                        <span>Soal berbentuk Pilihan Ganda (PG) dikerjakan secara online.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-[#C1272D] font-bold">✓</span>
                        <span>Pemenang ditentukan berdasarkan jumlah skor tertinggi dan waktu penyelesaian
                            tercepat.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-[#C1272D] font-bold">✓</span>
                        <span>Kredensial login dikirimkan setelah pendaftaran disetujui panitia.</span>
                    </li>
                </ul>
            </div>

            <div
                class="bg-gradient-to-br from-[#FFF7E0] to-[#FDF0C6] border border-[#D9B36C] rounded-2xl p-6 text-center space-y-3 shadow-sm">
                <div class="mx-auto w-10 h-10 rounded-full bg-[#F2CB6B] flex items-center justify-center shadow-inner">
                    <svg class="w-5 h-5 text-[#5A1414]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17V7a2 2 0 012-2h2a2 2 0 012 2v10m-6 0h6m-6 0H5a2 2 0 01-2-2v-1a2 2 0 012-2h.5m13.5 5h2a2 2 0 002-2v-1a2 2 0 00-2-2h-.5" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#5A1414]">Sudah punya akun lomba?</h3>
                <p class="text-xs text-[#8A6A2F]">Silakan login ke portal peserta untuk mengerjakan soal kuis secara
                    langsung.</p>
                <a href="{{ route('participant.login') }}"
                    class="inline-block bg-[#C1272D] hover:bg-[#8B1E1E] text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">Masuk
                    Portal Peserta →</a>
            </div>
            
        </div>

        {{-- Registration Form --}}
        <div class="md:col-span-7">
            <div class="bg-[#FDF8ED] border border-[#E3D3AC] rounded-2xl p-6 sm:p-8 shadow-sm relative">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-[#5A1414]"
                        style="font-family: Georgia, 'Times New Roman', serif;">Formulir Registrasi Peserta</h2>
                    <span
                        class="font-mono text-[10px] tracking-widest text-[#8A6A2F] uppercase border border-[#D9B36C] rounded px-2 py-1">Edisi
                        17-08-2026</span>
                </div>

                @if ($successMessage)
                    <div
                        class="bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-xl p-4 text-sm mb-6 animate-fade-in flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <div class="font-bold mb-1">Pendaftaran Berhasil!</div>
                            {{ $successMessage }}
                        </div>
                    </div>
                @else
                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label for="reg-name" class="form-label">Nama Lengkap</label>
                            <input type="text" id="reg-name" wire:model.defer="name"
                                class="form-input @error('name') is-error @enderror" placeholder="e.g. Budi Susanto">
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reg-pos" class="form-label">Jabatan / Instansi</label>
                            <input type="text" id="reg-pos" wire:model.defer="position"
                                class="form-input @error('position') is-error @enderror"
                                placeholder="e.g. Staf Keperawatan, Programmer IT">
                            @error('position')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reg-email" class="form-label">Alamat Email</label>
                            <input type="email" id="reg-email" wire:model.defer="email"
                                class="form-input @error('email') is-error @enderror"
                                placeholder="e.g. budi@gmail.com">
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reg-phone" class="form-label">Nomor WhatsApp / HP</label>
                            <input type="text" id="reg-phone" wire:model.defer="phone"
                                class="form-input @error('phone') is-error @enderror" placeholder="e.g. 081234567890">
                            @error('phone')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="reg-pass" class="form-label">Password Pilihan Anda</label>
                                <input type="password" id="reg-pass" wire:model.defer="password"
                                    class="form-input @error('password') is-error @enderror"
                                    placeholder="Minimal 6 karakter">
                                @error('password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="reg-pass-conf" class="form-label">Konfirmasi Password</label>
                                <input type="password" id="reg-pass-conf" wire:model.defer="password_confirmation"
                                    class="form-input" placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-dashed border-[#D9B36C] flex justify-end">
                            <button type="submit"
                                class="btn btn-primary w-full sm:w-auto flex items-center justify-center gap-2 !bg-[#C1272D] hover:!bg-[#8B1E1E]"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Daftar Lomba 17-an
                                </span>
                                <span wire:loading>Mendaftarkan...</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Bunting / umbul-umbul strip penutup --}}
    <div class="h-4 w-full mt-8 rounded-full overflow-hidden shadow-inner"
        style="background-image: repeating-linear-gradient(60deg, #ffffff 0 10px, #C1272D 10px 20px);"></div>
</div>
