<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RepairController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');


Route::get('/track', [RepairController::class, 'trackForm'])->name('track.form');
Route::post('/track', [RepairController::class, 'trackSubmit'])->name('track.submit');
Route::get('/admin/repairs', [RepairController::class, 'adminView'])->name('admin.repairs');

