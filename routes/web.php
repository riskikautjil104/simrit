<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\DocumentController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\VideoController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\TeamController;
use App\Models\News;
use App\Models\Page;
use Illuminate\Support\Facades\Response;

// ── Public Routes ──────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil/{slug}', [ProfileController::class, 'show'])->name('public.profile');
Route::get('/layanan', [ServiceController::class, 'index'])->name('public.services');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('public.services.show');
Route::get('/berita', [NewsController::class, 'index'])->name('public.news');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('public.news.show');
Route::get('/kegiatan', [EventController::class, 'index'])->name('public.events');
Route::get('/kegiatan/{slug}', [EventController::class, 'show'])->name('public.events.show');
Route::get('/dokumen', [DocumentController::class, 'index'])->name('public.documents');
Route::get('/dokumen/download/{id}', [DocumentController::class, 'download'])->name('public.documents.download');
Route::get('/galeri', [GalleryController::class, 'index'])->name('public.galleries');
Route::get('/galeri/{slug}', [GalleryController::class, 'show'])->name('public.galleries.show');
Route::get('/video', [VideoController::class, 'index'])->name('public.videos');
Route::get('/tim', [TeamController::class, 'index'])->name('public.team');
Route::get('/logo', fn() => view('public.logo'))->name('public.logo');
Route::get('/sitemap.xml', function () {
    $urls = collect([
        ['loc' => url('/'), 'lastmod' => now()->toDateString()],
        ['loc' => route('public.news'), 'lastmod' => now()->toDateString()],
        ['loc' => route('public.services'), 'lastmod' => now()->toDateString()],
        ['loc' => route('public.events'), 'lastmod' => now()->toDateString()],
        ['loc' => route('public.documents'), 'lastmod' => now()->toDateString()],
        ['loc' => route('public.galleries'), 'lastmod' => now()->toDateString()],
        ['loc' => route('public.videos'), 'lastmod' => now()->toDateString()],
        ['loc' => route('public.team'), 'lastmod' => now()->toDateString()],
    ]);

    Page::where('status', 'published')->get(['slug', 'updated_at'])->each(fn($page) => $urls->push([
        'loc' => route('public.profile', $page->slug),
        'lastmod' => $page->updated_at->toDateString(),
    ]));
    News::published()->get(['slug', 'updated_at'])->each(fn($news) => $urls->push([
        'loc' => route('public.news.show', $news->slug),
        'lastmod' => $news->updated_at->toDateString(),
    ]));

    return Response::make(view('public.sitemap', compact('urls'))->render(), 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

// ── Auth Routes ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin Routes ───────────────────────────────────────────
Route::middleware(['auth', 'active', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    // We point these routes to their respective Livewire components
    Route::get('/', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/pages', \App\Livewire\Admin\PagesManager::class)->name('pages');
    Route::get('/news', \App\Livewire\Admin\NewsManager::class)->name('news');
    Route::get('/events', \App\Livewire\Admin\EventManager::class)->name('events');
    Route::get('/announcements', \App\Livewire\Admin\AnnouncementManager::class)->name('announcements');
    Route::get('/documents', \App\Livewire\Admin\DocumentManager::class)->name('documents');
    Route::get('/galleries', \App\Livewire\Admin\GalleryManager::class)->name('galleries');
    Route::get('/videos', \App\Livewire\Admin\VideoManager::class)->name('videos');
    Route::get('/media', \App\Livewire\Admin\MediaManager::class)->name('media');
    Route::get('/services', \App\Livewire\Admin\ServiceManager::class)->name('services');
    Route::get('/team', \App\Livewire\Admin\TeamManager::class)->name('team');

    // Superadmin-only routes
    Route::middleware('can:superadmin-only')->group(function () {
        Route::get('/users', \App\Livewire\Admin\UserManager::class)->name('users');
        Route::get('/logs', \App\Livewire\Admin\LogViewer::class)->name('logs');
        Route::get('/settings', \App\Livewire\Admin\SettingsManager::class)->name('settings');
    });

    // Quiz Admin Routes
    Route::get('/quiz', \App\Livewire\Admin\QuizManager::class)->name('quiz.sessions');
    Route::get('/quiz/registrations', \App\Livewire\Admin\QuizRegistrationManager::class)->name('quiz.registrations');
    Route::get('/quiz/questions', \App\Livewire\Admin\QuizQuestionManager::class)->name('quiz.questions');
});

// ── Quiz / Lomba 17 Agustus Routes ───────────────────────────
Route::get('/lomba', \App\Livewire\Public\QuizRegister::class)->name('public.quiz.register');
Route::get('/lomba/leaderboard', \App\Livewire\Public\QuizLeaderboard::class)->name('public.quiz.leaderboard');
Route::get('/lomba/login', \App\Livewire\Participant\QuizLogin::class)->name('participant.login');
Route::get('/lomba/dashboard', \App\Livewire\Participant\QuizDashboard::class)->name('participant.dashboard')->middleware(['auth', 'active']);
