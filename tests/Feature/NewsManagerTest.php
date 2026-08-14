<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NewsManagerTest extends TestCase
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

    public function test_guest_cannot_access_news_manager()
    {
        $this->get(route('admin.news'))->assertRedirect(route('login'));
    }

    public function test_admin_can_create_news_with_rich_text_content()
    {
        $admin = $this->admin();

        $richContent = '<h2>Judul Bagian</h2><p>Ini adalah <strong>paragraf tebal</strong> dan <em>miring</em> dengan <a href="https://example.com" target="_blank" rel="noopener">tautan</a>.</p><ul><li>Poin satu</li><li>Poin dua</li></ul>';

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\NewsManager::class)
            ->call('create')
            ->set('title', 'Berita Uji Coba Tiptap')
            ->set('excerpt', 'Ringkasan singkat berita uji coba.')
            ->set('content', $richContent)
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('news', [
            'title' => 'Berita Uji Coba Tiptap',
            'content' => $richContent,
            'status' => 'published',
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_update_existing_news_content()
    {
        $admin = $this->admin();

        $news = News::create([
            'title' => 'Berita Lama',
            'slug' => 'berita-lama',
            'excerpt' => 'Ringkasan lama',
            'content' => '<p>Konten lama.</p>',
            'status' => 'draft',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $updatedContent = '<p>Konten yang sudah <strong>diperbarui</strong> melalui editor Tiptap.</p>';

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\NewsManager::class)
            ->call('edit', $news->id)
            ->assertSet('title', 'Berita Lama')
            ->set('content', $updatedContent)
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'content' => $updatedContent,
            'status' => 'published',
        ]);
    }

    public function test_content_is_required_to_save_news()
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\NewsManager::class)
            ->call('create')
            ->set('title', 'Berita Tanpa Konten')
            ->set('content', '')
            ->call('save')
            ->assertHasErrors(['content' => 'required']);
    }

    public function test_news_detail_shows_related_news_from_same_category()
    {
        $category = Category::create(['name' => 'SIMRS', 'slug' => 'simrs']);
        $mainNews = News::create([
            'category_id' => $category->id,
            'title' => 'Berita Utama SIMRS',
            'slug' => 'berita-utama-simrs',
            'content' => '<p>Konten utama.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);
        News::create([
            'category_id' => $category->id,
            'title' => 'Berita Terkait SIMRS',
            'slug' => 'berita-terkait-simrs',
            'content' => '<p>Konten terkait.</p>',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);

        $this->get(route('public.news.show', $mainNews->slug))
            ->assertOk()
            ->assertSee('Berita Terkait SIMRS');
    }

    public function test_news_detail_falls_back_to_latest_news_when_same_category_is_empty()
    {
        $mainCategory = Category::create(['name' => 'Utama', 'slug' => 'utama']);
        $otherCategory = Category::create(['name' => 'Lainnya', 'slug' => 'lainnya']);
        $mainNews = News::create([
            'category_id' => $mainCategory->id,
            'title' => 'Berita Utama Tanpa Teman',
            'slug' => 'berita-utama-tanpa-teman',
            'content' => '<p>Konten utama.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);
        News::create([
            'category_id' => $otherCategory->id,
            'title' => 'Berita Fallback',
            'slug' => 'berita-fallback',
            'content' => '<p>Konten fallback.</p>',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);

        $this->get(route('public.news.show', $mainNews->slug))
            ->assertOk()
            ->assertSee('Berita Fallback');
    }
}
