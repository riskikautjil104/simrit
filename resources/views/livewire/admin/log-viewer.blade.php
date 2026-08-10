<div>
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari deskripsi log...">
            </div>
            <select wire:model.live="actionFilter" class="form-select py-1.5 text-sm w-44">
                <option value="">Semua Aksi</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}">{{ strtoupper($action) }}</option>
                @endforeach
            </select>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="table-wrap">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr>
                            <th scope="col">Aksi</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Pengguna</th>
                            <th scope="col">IP Address</th>
                            <th scope="col">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <span class="badge bg-blue-50 text-blue-700 uppercase text-[9px] font-black tracking-widest px-2 py-0.5 rounded">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="text-xs text-slate-700 max-w-xs truncate">{{ $log->description }}</td>
                                <td class="text-xs text-slate-600">
                                    {{ $log->user ? $log->user->name : 'Sistem' }}
                                </td>
                                <td class="text-xs text-slate-400 font-mono">{{ $log->ip_address ?? '-' }}</td>
                                <td class="text-xs text-slate-400">{{ $log->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center text-slate-400">Belum ada rekaman log aktivitas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $logs->links() }}</div>
    </div>
</div>
