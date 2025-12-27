
<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminRepairController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ApiCallController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\AccountingController;
use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin dashboard pages
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/repair-logs', [AdminController::class, 'repairLogs'])->name('repairLogs');
        Route::get('/sales', [AdminController::class, 'sales'])->name('sales');
        Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
        Route::post('/warehouse', [WarehouseController::class, 'store'])->name('warehouse.store');
        Route::get('/warehouse', [WarehouseController::class, 'create'])->name('warehouse.create');
        Route::post('/warehouse', [WarehouseController::class, 'storeAgain'])->name('warehouse.storeAgain');
        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');



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

            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            Route::delete('/images/{image}', [ProductController::class, 'deleteImage'])->name('images.delete');

        Route::get('/{product}/reviews', [ProductController::class, 'reviews'])->name('reviews');
        });
        Route::delete('reviews/{review}', [ProductController::class, 'destroyReview'])->name('reviews.destroy');

        //Accounting Route
        Route::prefix('accounting')->name('accounting.')->group(function () {
            Route::get('/', [AccountingController::class, 'index'])->name('index');
            Route::get('/apicalls', [ApiCallController::class, 'index'])->name('apicalls.index');
            Route::post('/provider/{provider}', [AccountingController::class, 'updateProvider'])->name('provider.update');
            Route::post('/record-api-call', [AccountingController::class, 'recordApiCall'])->name('api.record');
            Route::post('/expense', [AccountingController::class, 'addExpense'])->name('expense.add');
            Route::get('/chart-data', [AccountingController::class, 'chartData'])->name('chart.data');
        });

        Route::prefix('repairs')->name('repairs.')->group(function () {
            // List all repairs
            Route::get('/', [AdminRepairController::class, 'index'])->name('index');
            // Show details for a specific repair
            Route::get('/{repair}', [AdminRepairController::class, 'show'])->name('show');
            // Update the repair status
            Route::put('/{repair}/status', [AdminRepairController::class, 'updateStatus'])->name('updateStatus');
            // Add a new step/update to the repair timeline
            Route::post('/{repair}/steps', [AdminRepairController::class, 'addStep'])->name('addStep');
        });

    });


Route::get('/dashboard', function () {
    return redirect()->route('home');
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
// In routes/api.php or web.php (without 'web' middleware for CSRF)
Route::post('/webhooks/shipbubble', [WebhookController::class, 'handleShipbubbleNotification']);

Route::get('/track', [RepairController::class, 'trackForm'])->name('track.form');
Route::post('/track', [RepairController::class, 'trackSubmit'])->name('track.submit');

Route::prefix('guides')->group(function () {

    Route::get('/', [GuideController::class, 'devices'])->name('guides.devices');

    Route::get('/{device}', [GuideController::class, 'categories'])->name('guides.categories');

    Route::get('/{device}/{category}', [GuideController::class, 'issues'])->name('guides.issues');

    Route::get('/{device}/{category}/{issue}', [GuideController::class, 'showU'])->name('guides.show');

});

Route::prefix('store')->group(function(){
    // User Store Homepage
    Route::get('/', [StoreController::class, 'index'])->name('store.index');

    // Category page
    Route::get('/category/{category}', [StoreController::class, 'category'])->name('store.category');

    // Search
    Route::get('/search', [StoreController::class, 'search'])->name('store.search');
    Route::get('/product/{id}', [StoreController::class, 'show'])->name('store.product');

});

// routes/web.php
Route::middleware('auth')->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/{id}/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('cart.checkout');
    Route::post('/address/save', [CheckoutController::class, 'saveAddress'])->name('checkout.saveAddress'); // Save address
    Route::get('/checkout/summary', [CheckoutController::class, 'summary'])->name('checkout.summary');
    Route::post('/checkout/shipping-rate', [ShippingController::class, 'getRate'])->name('checkout.shippingRate');
    Route::post('/checkout/initiate-payment', [CheckoutController::class, 'initiatePayment'])->name('checkout.pay');
    Route::get('/checkout/payment/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');

    Route::get('/order/{order}/track', [CheckoutController::class, 'track'])->name('orders.track');
    
    Route::post('/guides/{guide}/review',[GuideController::class,'storeReview'])
        ->name('guides.review.store');

   
});

Route::middleware('auth')->group(function(){
    Route::get('/orders', [OrderController::class, 'index'])->name('user.orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('user.orders.show');
    Route::get('/account', [AccountController::class, 'index'])->name('user.account');
    Route::put('/account/update', [AccountController::class, 'updateAddress'])->name('account.updateAddress');

    
    // 1. Repair Listing (Index) and Search Handler
    Route::get('/repairs', [RepairController::class, 'index'])->name('repair.index');
    Route::get('/repair/book', [RepairController::class, 'create'])->name('repair.create');
    Route::post('/repair/book', [RepairController::class, 'store'])->name('repair.store');

    // Step 2: Show shipping rates
    Route::get('/repair/rates/{repair}', [RepairController::class, 'showRates'])->name('repair.rates');
    Route::post('/repair/rates/{repair}/select', [RepairController::class, 'selectRate'])->name('repair.selectRate');

    // Step 3: Payment / Confirm booking
    Route::get('/repair/confirm/{repair}', [RepairController::class, 'confirm'])->name('repair.confirm');
    Route::post('/repair/pay/{repair}', [RepairController::class, 'pay'])->name('repair.pay');

    // Tracking
    Route::get('/repair/track/{tracking_code}', [RepairController::class, 'track'])->name('repair.track');

    //Review
    Route::post('/product/{product}/review', [StoreController::class, 'storeReview'])->name('product.review.store');
});


// Show the upload form

// Show upload form
Route::get('/uploadcare-test', function () {
    return view('uploadcare-test');
});

// Handle upload
Route::post('/uploadcare-test', function (Request $request) {

    $request->validate([
        'file' => 'required|file|max:51200', // 50MB (supports images & videos)
    ]);

    try {
        $file = $request->file('file');

        $response = Http::withBasicAuth(
            config('services.uploadcare.secret'),
            ''
        )->attach(
            'file',
            fopen($file->getRealPath(), 'r'),
            $file->getClientOriginalName()
        )->post('https://upload.uploadcare.com/base/', [
            'UPLOADCARE_PUB_KEY' => config('services.uploadcare.public'),
            'UPLOADCARE_STORE'  => '0',
        ]);

        if (!$response->successful()) {
            throw new Exception('Uploadcare upload failed');
        }

        $uuid = $response->json('file');
        Http::withBasicAuth(
            config('services.uploadcare.public'),
            config('services.uploadcare.secret')
        )->put("https://api.uploadcare.com/files/{$uuid}/storage/");
        
        $url  = "https://ucarecdn.com/{$uuid}/";

        return back()
            ->with('success', 'Upload successful!')
            ->with('url', $url)
            ->with('uuid', $uuid);

    } catch (\Exception $e) {
        return back()->with('error', 'Upload failed: ' . $e->getMessage());
    }

})->name('uploadcare.test.store');


// Debug Uploadcare config
Route::get('/debug-uploadcare', function () {
    dd(config('services.uploadcare'));
});
