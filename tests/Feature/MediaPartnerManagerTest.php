<?php

namespace Tests\Feature;

use App\Models\MediaPartner;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MediaPartnerManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => 'admin@rsud.cb.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_media_partner_manager()
    {
        $this->get(route('admin.media-partners'))->assertRedirect(route('login'));
    }

    public function test_admin_can_create_media_partner()
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\MediaPartnerManager::class)
            ->call('create')
            ->set('name', 'Media Partner Uji')
            ->set('description', 'Deskripsi singkat media partner.')
            ->set('link', 'https://contoh.com')
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('media_partners', [
            'name' => 'Media Partner Uji',
            'link' => 'https://contoh.com',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_update_media_partner()
    {
        $admin = $this->admin();

        $partner = MediaPartner::create([
            'name' => 'Partner Lama',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\MediaPartnerManager::class)
            ->call('edit', $partner->id)
            ->assertSet('name', 'Partner Lama')
            ->set('name', 'Partner Baru')
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('media_partners', [
            'id' => $partner->id,
            'name' => 'Partner Baru',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_delete_media_partner()
    {
        $admin = $this->admin();

        $partner = MediaPartner::create([
            'name' => 'Partner Dihapus',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\MediaPartnerManager::class)
            ->call('delete', $partner->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('media_partners', ['id' => $partner->id]);
    }

    public function test_name_is_required_to_save_media_partner()
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\MediaPartnerManager::class)
            ->call('create')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_published_media_partner_appears_on_homepage()
    {
        $partner = MediaPartner::create(['name' => 'Partner Beranda', 'status' => 'published']);
        MediaPartner::create(['name' => 'Partner Draft', 'status' => 'draft']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Partner Beranda')
            ->assertSee(route('public.media-partners.show', $partner->slug), false)
            ->assertDontSee('Partner Draft');
    }

    public function test_published_media_partner_appears_on_news_detail_page()
    {
        $partner = MediaPartner::create(['name' => 'Partner Detail Berita', 'status' => 'published']);

        $news = News::create([
            'title' => 'Berita Punya Partner',
            'slug' => 'berita-punya-partner',
            'content' => '<p>Konten berita.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('public.news.show', $news->slug))
            ->assertOk()
            ->assertSee('Partner Detail Berita')
            ->assertSee(route('public.media-partners.show', $partner->slug), false);
    }

    public function test_published_media_partner_detail_page_can_be_viewed()
    {
        $partner = MediaPartner::create([
            'name' => 'Partner Detail Publik',
            'description' => 'Deskripsi detail untuk pengunjung.',
            'link' => 'https://partner.example',
            'status' => 'published',
        ]);

        $this->get(route('public.media-partners.show', $partner->slug))
            ->assertOk()
            ->assertSee('Partner Detail Publik')
            ->assertSee('Deskripsi detail untuk pengunjung.')
            ->assertSee('https://partner.example');
    }

    public function test_draft_media_partner_detail_page_is_not_public()
    {
        $partner = MediaPartner::create(['name' => 'Partner Draft Detail', 'status' => 'draft']);

        $this->get(route('public.media-partners.show', $partner->slug))
            ->assertNotFound();
    }
}
