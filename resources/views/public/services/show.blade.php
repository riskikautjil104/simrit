<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Breadcrumbs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <a href="{{ route('public.services') }}" class="hover:text-white transition-colors">Layanan IT</a>
                <span>/</span>
                <span class="text-white font-semibold truncate max-w-[200px]">{{ $service->title }}</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight leading-tight">{{ $service->title }}</h1>
        </div>
    </section>

    {{-- Main Service detail content --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Service Details --}}
            <article class="lg:col-span-8 bg-white border border-slate-100 rounded-2xl p-6 sm:p-8 shadow-sm">
                @if($service->image)
                    <div class="mb-6 rounded-xl overflow-hidden aspect-video max-h-96 w-full shadow-sm bg-slate-50">
                        <img src="{{ asset('storage/'.$service->image) }}" alt="Visualisasi {{ $service->title }}" class="object-cover w-full h-full">
                    </div>
                @endif

                <div class="prose max-w-none text-slate-600">
                    {!! $service->content !!}
                </div>
            </article>

            {{-- Support Info Card --}}
            <aside class="lg:col-span-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-6" aria-label="Informasi bantuan layanan">
                <div>
                    <h3 class="text-[#1e3a8a] font-bold text-xs uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Butuh Bantuan IT?</h3>
                    <p class="text-slate-500 text-xs leading-relaxed mb-4">Apabila ada kebutuhan pengerjaan, instalasi, atau perbaikan terkait layanan ini, silakan hubungi Ruang IT melalui narahubung resmi.</p>
                    <div class="space-y-3 text-xs text-slate-600">
                        @if(\App\Models\Setting::get('phone'))
                        <div class="flex items-center gap-2">
                            <span>{{ \App\Models\Setting::get('phone') }}</span>
                        </div>
                        @endif
                        @if(\App\Models\Setting::get('email'))
                        <div class="flex items-center gap-2">
                            <span>✉️</span>
                            <span>{{ \App\Models\Setting::get('email') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-public-layout>
