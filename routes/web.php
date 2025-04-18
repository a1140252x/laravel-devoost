<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\{
    Products,
    Customers,
    Orders,
    OrderItems
};


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::group([
    'middleware' => [
        'web',
        'auth',
        'verified',
    ],
], function () {

    Route::resource( 'products', Products::class );
    Route::resource( 'customers', Customers::class );
    Route::resource( 'orders', Orders::class );
    Route::resource( 'order-items', OrderItems::class );

    Route::group([
        'as' => 'dt.',
        'prefix' => 'dt',
        'middleware' => [],
    ], function () {
        Route::get( 'products', [Products::class, 'datatable'] )->name('products');
        Route::get( 'customers', [Customers::class, 'datatable'] )->name('customers');
        Route::get( 'orders', [Orders::class, 'datatable'] )->name('orders');
    });

    Route::group([
        'as' => 'data.',
        'prefix' => 'data',
        'middleware' => [],
    ], function () {
        Route::get( 'products', [Products::class, 'data'] )->name('products');
        Route::get( 'customers', [Customers::class, 'data'] )->name('customers');
        Route::get( 'orders', [Orders::class, 'data'] )->name('orders');
    });

});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
