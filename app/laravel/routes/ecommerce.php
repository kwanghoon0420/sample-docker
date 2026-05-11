<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PointController as AdminPointController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PointController;

// 관리자 페이지 라우트
Route::prefix('admin2')->middleware(['auth', 'admin2'])->as('admin.')->group(function () {

    Route::get('product_list', [AdminProductController::class, 'productList'])->name('product_list');
    Route::get('product_store', [AdminProductController::class, 'productEditPage'])->name('product_store');
    Route::post('product_store', [AdminProductController::class, 'productStore'])->name('product_store');
 
    Route::prefix('point')->as('point.')->group(function () {
        Route::get('logs', [AdminPointController::class, 'logs'])->name('logs');
        Route::get('add', [AdminPointController::class, 'add'])->name('add');
    });

    Route::prefix('user')->as('user.')->group(function () {
        Route::get('list', [UserController::class, 'list'])->name('list');
        Route::get('earn_point', [UserController::class, 'earnPointPage'])->name('earn_point');
        Route::post('earn_point', [UserController::class, 'earnPoint'])->name('earn_point');
    });

    Route::get('order_list', [AdminOrderController::class, 'orderList'])->name('order_list');
    Route::post('orders/{order}/cancel', [AdminOrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('orders/{order}/confirm', [AdminOrderController::class, 'confirmOrder'])->name('orders.confirm');

    Route::redirect('/', '/admin2/product_list');
});

// 사용자 페이지 라우트

// 상품 라우터
Route::prefix('product')->group(function () {
    Route::get('/list', [ProductController::class, 'productList'])->name('product.list');
    Route::get('/{id}', [ProductController::class, 'productView'])->middleware('auth')->name('product.view');
});

//주문 라우터
Route::prefix('order')->as('order.')->middleware('auth')->group(function () {
    Route::post('/order_product', [OrderController::class, 'orderProduct'])->name('order_product');
    Route::post('{order}/cancel', [OrderController::class, 'cancelOrder'])->name('cancel');
    Route::post('{order}/confirm', [OrderController::class, 'confirmOrder'])->name('confirm');
});

// 마이 페이지 라우터
Route::prefix('mypage')->as('mypage.')->middleware('auth')->group(function () {
    Route::get('/order_list', [OrderController::class, 'orderList'])->name('order.list');
    Route::get('/point_list', [PointController::class, 'pointList'])->name('point.list');
    Route::get('/point_charge', [PointController::class, 'pointCharge'])->name('point.charge');
    Route::post('/point_charge', [PointController::class, 'pointCharge'])->name('point.charge');
    Route::get('/point_history', [PointController::class, 'pointHistory'])->name('point.history');
    Route::get('/point_history', [PointController::class, 'pointHistory'])->name('point.history');
});