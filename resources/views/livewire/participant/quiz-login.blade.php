<div class="py-16 max-w-md mx-auto px-4">
    <div class="bg-white border border-slate-200 rounded-3xl shadow-xl p-6 sm:p-8 space-y-6">
        <div class="text-center">
            <span class="text-xs font-bold text-red-600 bg-red-50 border border-red-100 rounded-full px-3 py-1 uppercase tracking-widest">Portal Peserta</span>
            <h1 class="text-xl sm:text-2xl font-black mt-3 text-slate-800">Masuk Lomba</h1>
            <p class="text-slate-500 text-xs mt-1">Gunakan akun email dan password yang diberikan oleh panitia lomba.</p>
        </div>

        @if(session()->has('error'))
            <div class="bg-red-50 border border-red-100 text-red-800 text-xs font-bold rounded-xl p-4.5 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="login" class="space-y-4">
            <div>
                <label for="p-email" class="form-label">Email Terdaftar</label>
                <input type="email" id="p-email" wire:model.defer="email" class="form-input" placeholder="peserta@gmail.com" autocomplete="email" required>
            </div>
            <div>
                <label for="p-pass" class="form-label">Password</label>
                <input type="password" id="p-pass" wire:model.defer="password" class="form-input" placeholder="Masukkan password" autocomplete="current-password" required>
            </div>

            <button type="submit" class="btn btn-primary w-full py-2.5 mt-2 flex items-center justify-center gap-2" wire:loading.attr="disabled">
                <span wire:loading.remove>Masuk &amp; Kerjakan Kuis</span>
                <span wire:loading>Memeriksa...</span>
            </button>
        </form>

        <div class="text-center border-t border-slate-100 pt-4">
            <p class="text-xs text-slate-400">Belum mendaftarkan diri? <a href="{{ route('public.quiz.register') }}" class="text-red-600 hover:text-red-700 font-bold">Daftar disini</a></p>
        </div>
    </div>
</div>
