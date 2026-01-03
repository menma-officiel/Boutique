<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::resource('products', ProductController::class)->only(['index', 'show']);

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('/orders/{order}/send-whatsapp', [OrderController::class, 'sendWhatsapp'])->name('orders.send_whatsapp');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function() {
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('comments', App\Http\Controllers\Admin\CommentController::class)->only(['index','destroy','update']);
    Route::post('comments/bulk', [App\Http\Controllers\Admin\CommentController::class, 'bulk'])->name('comments.bulk');
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update_status');
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
