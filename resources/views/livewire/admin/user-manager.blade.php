<div>
    @if($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Buat Akun Admin Baru' : 'Edit Akun Admin' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5 max-w-lg">
                <div>
                    <label for="u-name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="u-name" wire:model.defer="name" class="form-input @error('name') is-error @enderror" autocomplete="name">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="u-email" class="form-label">Alamat Email</label>
                    <input type="email" id="u-email" wire:model.defer="email" class="form-input @error('email') is-error @enderror" autocomplete="email">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="u-role" class="form-label">Peran (Role)</label>
                    <select id="u-role" wire:model.defer="role" class="form-select @error('role') is-error @enderror">
                        <option value="admin">Admin</option>
                        <option value="superadmin">Superadmin</option>
                    </select>
                    <p class="form-hint">Superadmin dapat mengakses manajemen pengguna, log, dan pengaturan sistem.</p>
                    @error('role') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="u-pass" class="form-label">{{ $isCreating ? 'Kata Sandi' : 'Kata Sandi Baru (Kosongkan jika tidak diganti)' }}</label>
                    <input type="password" id="u-pass" wire:model.defer="password" class="form-input @error('password') is-error @enderror" autocomplete="new-password">
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="u-active" wire:model.defer="is_active" class="rounded border-slate-300">
                    <label for="u-active" class="text-sm text-slate-700">Akun aktif (dapat login)</label>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Akun</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari nama atau email...">
                </div>
                <button type="button" wire:click="create" class="btn btn-primary btn-sm w-full sm:w-auto"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Buat Akun Admin</button>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="table-wrap">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th scope="col">Nama / Email</th>
                                <th scope="col">Peran</th>
                                <th scope="col">Status</th>
                                <th scope="col">Login Terakhir</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($users as $u)
                                <tr>
                                    <td>
                                        <div class="font-bold text-slate-800 text-sm">{{ $u->name }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $u->email }}</div>
                                    </td>
                                    <td>
                                        <span class="text-xs font-bold uppercase px-2 py-0.5 rounded {{ $u->role === 'superadmin' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                            {{ $u->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $u->is_active ? 'badge-published' : 'badge-archived' }}">
                                            {{ $u->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-slate-500">
                                        {{ $u->last_login_at ? $u->last_login_at->diffForHumans() : '-' }}
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" wire:click="edit({{ $u->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                            @if($u->id !== auth()->id())
                                                <button type="button" wire:click="toggleActive({{ $u->id }})" class="btn btn-sm {{ $u->is_active ? 'btn-danger' : 'btn-success' }}">
                                                    {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-12 text-center text-slate-400">Tidak ada akun admin ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $users->links() }}</div>
        </div>
    @endif
</div>
