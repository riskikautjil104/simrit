<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'admin';
    public $is_active = true;

    public $isEditing  = false;
    public $isCreating = false;

    public function updatingSearch() { $this->resetPage(); }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $u = User::findOrFail($id);
        $this->selectedId = $u->id;
        $this->name       = $u->name;
        $this->email      = $u->email;
        $this->role       = $u->role;
        $this->is_active  = $u->is_active;
        $this->password   = '';
        $this->password_confirmation = '';
        $this->isEditing  = true;
    }

    public function save()
    {
        $emailRule = $this->isCreating
            ? 'required|email|unique:users,email'
            : 'required|email|unique:users,email,'.$this->selectedId;

        $this->validate([
            'name'     => 'required|string|max:255',
            'email'    => $emailRule,
            'role'     => 'required|in:superadmin,admin',
            'is_active'=> 'boolean',
            'password' => $this->isCreating
                ? ['required', Password::min(8)]
                : ['nullable', Password::min(8)],
        ]);

        if ($this->isCreating) {
            $u = User::create([
                'name'      => $this->name,
                'email'     => $this->email,
                'password'  => Hash::make($this->password),
                'role'      => $this->role,
                'is_active' => $this->is_active,
            ]);
            ActivityLogger::log('create', "Membuat akun admin: {$u->name} ({$u->role})", $u);
            session()->flash('success', "Akun admin \"{$u->name}\" berhasil dibuat.");
        } else {
            $u = User::findOrFail($this->selectedId);
            $data = [
                'name'      => $this->name,
                'email'     => $this->email,
                'role'      => $this->role,
                'is_active' => $this->is_active,
            ];
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }
            $u->update($data);
            ActivityLogger::log('update', "Memperbarui akun admin: {$u->name}", $u);
            session()->flash('success', "Akun \"{$u->name}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function toggleActive($id)
    {
        $u = User::findOrFail($id);
        // Prevent deactivating yourself
        if ($u->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            return;
        }
        $u->update(['is_active' => !$u->is_active]);
        $status = $u->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLogger::log('update', "Akun {$u->name} {$status}", $u);
        session()->flash('success', "Akun \"{$u->name}\" berhasil {$status}.");
    }

    public function resetForm()
    {
        $this->isEditing = $this->isCreating = false;
        $this->selectedId = null;
        $this->name = $this->email = $this->password = $this->password_confirmation = '';
        $this->role = 'admin';
        $this->is_active = true;
    }

    public function render()
    {
        $query = User::latest();
        if ($this->search) {
            $query->where(fn($q) => $q->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%'));
        }
        $users = $query->paginate(10);

        return view('livewire.admin.user-manager', compact('users'))
            ->layout('layouts.admin', ['title' => 'Manajemen Admin']);
    }
}
