<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VideoManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => 'admin-video@rsud.cb.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_video()
    {
        $admin = $this->admin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\VideoManager::class)
            ->call('create')
            ->set('title', 'Video Kegiatan Uji')
            ->set('description', 'Dokumentasi kegiatan.')
            ->set('embed_url', 'https://youtu.be/k4cmTDJmF3g')
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('videos', [
            'title' => 'Video Kegiatan Uji',
            'slug' => 'video-kegiatan-uji',
            'embed_url' => 'https://youtu.be/k4cmTDJmF3g',
            'status' => 'published',
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_update_video()
    {
        $admin = $this->admin();
        $video = Video::create([
            'title' => 'Video Lama',
            'embed_url' => 'https://youtu.be/k4cmTDJmF3g',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\VideoManager::class)
            ->call('edit', $video->id)
            ->assertSet('title', 'Video Lama')
            ->set('title', 'Video Baru')
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'title' => 'Video Baru',
            'slug' => 'video-baru',
            'status' => 'published',
        ]);
    }
}
