
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/guides', [AdminController::class, 'guides'])->name('admin.guides');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/repairs', [AdminController::class, 'repairs'])->name('admin.repairs');
    Route::get('/repair-logs', [AdminController::class, 'repairLogs'])->name('admin.repairLogs');
    Route::get('/sales', [AdminController::class, 'sales'])->name('admin.sales');
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


