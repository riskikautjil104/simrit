<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SIMRIT Chasan Boesoirie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg,#0f172a 0%,#1e3a8a 60%,#059669 100%);">

<div class="w-full max-w-sm mx-4">
    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 mb-4 text-white text-2xl font-black">IT</div>
        <h1 class="text-white text-2xl font-bold tracking-tight">SIMRIT</h1>
        <p class="text-white/60 text-sm mt-1">Ruang IT RSUD Dr. H. Chasan Boesoirie</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-xl font-bold text-slate-800 mb-1">Masuk ke Sistem</h2>
        <p class="text-slate-500 text-sm mb-6">Gunakan akun admin Anda untuk melanjutkan</p>

        {{-- Errors --}}
        @if($errors->any())
        <div class="alert alert-error mb-5" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Alamat Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                    class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                    placeholder="admin@rsud-cb.go.id"
                    aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                >
                @error('email')
                <p id="email-error" class="form-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="form-input {{ $errors->has('password') ? 'is-error' : '' }} pr-10"
                        placeholder="••••••••"
                    >
                    <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" aria-label="Tampilkan/sembunyikan kata sandi">
                        <svg class="w-4 h-4" id="eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('password')
                <p class="form-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center mb-6">
                <input type="checkbox" id="remember" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="remember" class="ml-2 text-sm text-slate-600">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center py-2.5 text-base" id="login-submit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Masuk
            </button>
        </form>
    </div>

    <p class="text-center text-white/40 text-xs mt-6">
        <a href="{{ route('home') }}" class="hover:text-white/70 transition-colors">← Kembali ke situs publik</a>
    </p>
</div>

<script>
    const toggleBtn = document.getElementById('toggle-password');
    const pwInput  = document.getElementById('password');
    if (toggleBtn && pwInput) {
        toggleBtn.addEventListener('click', () => {
            pwInput.type = pwInput.type === 'password' ? 'text' : 'password';
        });
    }
</script>

</body>
</html>
