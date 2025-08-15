<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapIndex;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AdminController;

Route::get('/clear', function () {
    Artisan::call('optimize');
    dd('optimized!');
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "All caches have been cleared!";
});

Route::get('/delete-db', function () {
    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    $tables = DB::select('SHOW TABLES');
    $dbName = 'Tables_in_' . DB::getDatabaseName();
    foreach ($tables as $table) {
        Schema::drop($table->$dbName);
    }

    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    return 'All tables dropped successfully!';
});

Route::get('/migrations', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations executed successfully!';
});

Route::get('/seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return 'Database seeded successfully!';
});


Route::fallback(function () {
    return redirect('/');
});

Route::get('/env-test', function () {
    return [
        'APP_URL' => env('APP_URL', 'default'),
        'APP_LICENSE_KEY' => env('APP_LICENSE_KEY', 'default'),
    ];
});

Route::get('/', [Controller::class, 'index'])->name('home');
Route::get('/project', [Controller::class, 'project'])->name('project');
Route::get('/blog_details', [Controller::class, 'blog_details'])->name('blog_details');
Route::get('/services', [Controller::class, 'services'])->name('services');
Route::get('/about', [Controller::class, 'about'])->name('about');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'admin'])->name('admin');
    Route::get('/blog', [AdminController::class, 'blog'])->name('admin.blog.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.blog.create');
    Route::post('/', [AdminController::class, 'store'])->name('admin.blog.store');
    // Route::get('/{id}', [AdminController::class, 'show'])->name('admin.blog.show');
    Route::get('/{blog}/edit', [AdminController::class, 'edit'])->name('admin.blog.edit');
    Route::put('/{blog}', [AdminController::class, 'update'])->name('admin.blog.update');
    Route::delete('/{id}', [AdminController::class, 'destroy'])->name('admin.blog.destroy');

    Route::get('/projects', [AdminController::class, 'projects'])->name('admin.projects');
});


// Settings 
Route::post('/settings/store', [SettingController::class, 'store'])->name('settings.store');
Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');


Route::get('/contact', [ContactMessageController::class, 'contact'])->name('contact');
Route::post('/store', [ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact.messages.index');
Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact.messages.destroy');


Route::get('/blog/category/ajax/{slug}', [BlogController::class, 'categoryAjax'])->name('blog.category.ajax');
Route::get('/blog/category/{slug}', [BlogController::class, 'categoryFilter'])->name('blog.category');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/{slug}', [BlogController::class, 'blogpostview'])->name('blog.details');
Route::get('/blog', [BlogController::class, 'blog'])->name('blog');


// Route::prefix('blogs')->group(function () {
//     Route::get('/', [BlogController::class, 'index'])->name('blog.index');
//     Route::get('/create', [BlogController::class, 'create'])->name('blog.create');
//     Route::post('/', [BlogController::class, 'store'])->name('blog.store');
//     Route::get('/{id}', [BlogController::class, 'show'])->name('blog.show');
//     Route::get('/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
//     Route::put('/{blog}', [BlogController::class, 'update'])->name('blog.update');
//     Route::delete('/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');
// });

Route::get('/backup', [Controller::class, 'backup'])->name('backup');
Route::get('/backupdb', [Controller::class, 'backupDatabase'])->name('backupDatabase');

Route::get('backup-form', function() {
    return view('backup');
});

// Route::get('/sitemap.xml', function () {
//     $sitemapIndex = SitemapIndex::create();
//     $sitemapIndex->add(
//         \Spatie\Sitemap\Tags\Sitemap::create(url('/'))
//             ->setLastModificationDate(now())
//     );
//     $totalMovies = Movie::where('status', 1)->count();
//     $pages = ceil($totalMovies / 500);
//     foreach (range(1, $pages) as $page) {
//         $sitemapIndex->add(
//             \Spatie\Sitemap\Tags\Sitemap::create(url("/post-sitemap{$page}.xml"))
//                 ->setLastModificationDate(now())
//         );
//     }
//     return response($sitemapIndex->render(), 200, ['Content-Type' => 'application/xml']);
// });

// Route::get('/post-sitemap{page}.xml', function ($page) {
//     $sitemap = Sitemap::create();
//     $movies = Movie::where('status', 1)
//         ->skip(($page - 1) * 500)
//         ->take(500)
//         ->get();
//     foreach ($movies as $movie) {
//         $sitemap->add(
//             Url::create("https://videosroom.com/movie/{$movie->slug}")
//                 ->setLastModificationDate($movie->updated_at ?? now())
//                 ->setChangeFrequency('weekly')
//                 ->setPriority(0.8)
//         );
//     }
//     return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
// });
