
<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuideController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin dashboard pages
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
        Route::get('/repairs', [AdminController::class, 'repairs'])->name('admin.repairs');
        Route::get('/repair-logs', [AdminController::class, 'repairLogs'])->name('admin.repairLogs');
        Route::get('/sales', [AdminController::class, 'sales'])->name('admin.sales');

        // Guides CRUD
        Route::prefix('guides')->name('guides.')->group(function () {

            Route::get('/', [GuideController::class, 'index'])->name('admin.guides.index');
            Route::get('/create', [GuideController::class, 'create'])->name('admin.guides.create');
            Route::post('/store', [GuideController::class, 'store'])->name('admin.guides.store');

            Route::get('/{guide}/edit', [GuideController::class, 'edit'])->name('admin.guides.edit');
            Route::put('/{guide}', [GuideController::class, 'update'])->name('admin.guides.update');

            Route::get('/{guide}', [GuideController::class, 'show'])->name('admin.guides.show');
        });
    });



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');


Route::get('/track', [RepairController::class, 'trackForm'])->name('track.form');
Route::post('/track', [RepairController::class, 'trackSubmit'])->name('track.submit');
Route::get('/test-cookie', function () {
    cookie()->queue('hello', 'world', 60);
    return 'cookie set';
});


