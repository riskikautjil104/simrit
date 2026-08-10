<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsManager extends Component
{
    use WithFileUploads;

    public $site_name;
    public $site_tagline;
    public $site_description;
    public $address;
    public $phone;
    public $email;
    public $footer_text;

    public $logo;
    public $existingLogo;
    public $favicon;
    public $existingFavicon;

    public function mount()
    {
        $this->site_name        = Setting::get('site_name');
        $this->site_tagline     = Setting::get('site_tagline');
        $this->site_description = Setting::get('site_description');
        $this->address          = Setting::get('address');
        $this->phone            = Setting::get('phone');
        $this->email            = Setting::get('email');
        $this->footer_text      = Setting::get('footer_text');
        $this->existingLogo     = Setting::get('logo');
        $this->existingFavicon  = Setting::get('favicon');
    }

    public function save()
    {
        $this->validate([
            'site_name'        => 'required|string|max:255',
            'site_tagline'     => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'address'          => 'nullable|string',
            'phone'            => 'nullable|string|max:30',
            'email'            => 'nullable|email|max:255',
            'footer_text'      => 'nullable|string|max:500',
            'logo'             => 'nullable|image|max:2048',
            'favicon'          => 'nullable|image|max:512',
        ]);

        $fields = ['site_name', 'site_tagline', 'site_description', 'address', 'phone', 'email', 'footer_text'];
        foreach ($fields as $field) {
            Setting::set($field, $this->$field);
        }

        if ($this->logo) {
            if ($this->existingLogo) {
                Storage::disk('public')->delete($this->existingLogo);
            }
            $filename = 'settings/logo-' . Str::random(10) . '.' . $this->logo->getClientOriginalExtension();
            $this->logo->storeAs('', $filename, 'public');
            Setting::set('logo', $filename);
            $this->existingLogo = $filename;
            $this->logo = null;
        }

        if ($this->favicon) {
            if ($this->existingFavicon) {
                Storage::disk('public')->delete($this->existingFavicon);
            }
            $filename = 'settings/favicon-' . Str::random(10) . '.' . $this->favicon->getClientOriginalExtension();
            $this->favicon->storeAs('', $filename, 'public');
            Setting::set('favicon', $filename);
            $this->existingFavicon = $filename;
            $this->favicon = null;
        }

        ActivityLogger::log('update', 'Memperbarui pengaturan situs SIMRIT');
        session()->flash('success', 'Pengaturan situs berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.settings-manager')
            ->layout('layouts.admin', ['title' => 'Pengaturan Situs']);
    }
}
