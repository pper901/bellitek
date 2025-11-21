
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
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/repairs', [AdminController::class, 'repairs'])->name('repairs');
        Route::get('/repair-logs', [AdminController::class, 'repairLogs'])->name('repairLogs');
        Route::get('/sales', [AdminController::class, 'sales'])->name('sales');

        // Guides CRUD
        Route::prefix('guides')->name('guides.')->group(function () {

            Route::get('/', [GuideController::class, 'index'])->name('index');
            Route::get('/create', [GuideController::class, 'create'])->name('create');
            Route::post('/store', [GuideController::class, 'store'])->name('store');

            Route::get('/{guide}/edit', [GuideController::class, 'edit'])->name('edit');
            Route::put('/{guide}', [GuideController::class, 'update'])->name('update');

            Route::get('/{guide}', [GuideController::class, 'show'])->name('show');
        });

        //Products
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');

            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/store', [ProductController::class, 'store'])->name('store');

            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');

            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('delete');
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

Route::prefix('guides')->group(function () {

    Route::get('/', [GuideController::class, 'devices'])->name('guides.devices');

    Route::get('/{device}', [GuideController::class, 'categories'])->name('guides.categories');

    Route::get('/{device}/{category}', [GuideController::class, 'issues'])->name('guides.issues');

    Route::get('/{device}/{category}/{issue}', [GuideController::class, 'showU'])->name('guides.show');

});


