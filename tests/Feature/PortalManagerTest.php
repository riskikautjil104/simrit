<?php

namespace Tests\Feature;

use App\Models\Portal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PortalManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => 'admin-portal@rsud.cb.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_portal_manager()
    {
        $this->get(route('admin.portals'))->assertRedirect(route('login'));
    }

    public function test_admin_can_create_portal()
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\PortalManager::class)
            ->call('create')
            ->set('name', 'SIMRS')
            ->set('description', 'Portal Sistem Informasi Manajemen Rumah Sakit.')
            ->set('link', 'https://simrs.example.test')
            ->set('icon', 'SIMRS')
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('portals', [
            'name' => 'SIMRS',
            'slug' => 'simrs',
            'link' => 'https://simrs.example.test',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_update_portal()
    {
        $admin = $this->admin();
        $portal = Portal::create([
            'name' => 'Portal Lama',
            'link' => 'https://lama.example.test',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\PortalManager::class)
            ->call('edit', $portal->id)
            ->assertSet('name', 'Portal Lama')
            ->set('name', 'Portal Baru')
            ->set('link', 'https://baru.example.test')
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('portals', [
            'id' => $portal->id,
            'name' => 'Portal Baru',
            'slug' => 'portal-baru',
            'link' => 'https://baru.example.test',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_delete_portal()
    {
        $admin = $this->admin();
        $portal = Portal::create([
            'name' => 'Portal Dihapus',
            'link' => 'https://hapus.example.test',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\PortalManager::class)
            ->call('delete', $portal->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('portals', ['id' => $portal->id]);
    }

    public function test_link_is_required_to_save_portal()
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\PortalManager::class)
            ->call('create')
            ->set('name', 'Portal Tanpa Link')
            ->set('link', '')
            ->call('save')
            ->assertHasErrors(['link' => 'required']);
    }

    public function test_published_portal_appears_on_homepage_and_navbar()
    {
        Portal::create([
            'name' => 'Portal SIMRS',
            'description' => 'Akses cepat SIMRS.',
            'link' => 'https://simrs.example.test',
            'status' => 'published',
        ]);
        Portal::create([
            'name' => 'Portal Draft',
            'link' => 'https://draft.example.test',
            'status' => 'draft',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Portal Aplikasi')
            ->assertSee('Portal SIMRS')
            ->assertSee('https://simrs.example.test', false)
            ->assertDontSee('Portal Draft');
    }
}
