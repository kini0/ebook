<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Customer;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\Public;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/', Public\HomeController::class)->name('home');

Route::get('/ebooks', [Public\EbookController::class, 'index'])->name('ebooks.index');
Route::get('/ebooks/{ebook:slug}', [Public\EbookController::class, 'show'])->name('ebooks.show');

Route::get('/a-propos', [Public\StaticPagesController::class, 'about'])->name('about');
Route::get('/faq', [Public\StaticPagesController::class, 'faq'])->name('faq');
Route::get('/contact', [Public\StaticPagesController::class, 'contact'])->name('contact');
Route::post('/contact', [Public\StaticPagesController::class, 'sendContact'])
    ->middleware('throttle:5,1')->name('contact.send');

/*
|--------------------------------------------------------------------------
| Cart (session-based, no auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('panier')->name('cart.')->group(function () {
    Route::get('/', [Public\CartController::class, 'index'])->name('index');
    Route::post('/ajouter/{ebook:slug}', [Public\CartController::class, 'add'])->name('add');
    Route::delete('/retirer/{ebookId}', [Public\CartController::class, 'remove'])->name('remove');
    Route::post('/vider', [Public\CartController::class, 'clear'])->name('clear');
});

/*
|--------------------------------------------------------------------------
| Webhook (no auth, must NOT be in web group typically — but Laravel 11
| keeps CSRF off for these by excluding in bootstrap if needed)
|--------------------------------------------------------------------------
*/
Route::post('/webhooks/payments/{gateway}', [PaymentWebhookController::class, 'handle'])
    ->name('webhooks.payment');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [Public\CheckoutController::class, 'index'])->name('index');
        Route::post('/', [Public\CheckoutController::class, 'process'])->name('process');
        Route::get('/{order:reference}/succes', [Public\CheckoutController::class, 'success'])->name('success');
        Route::get('/{order:reference}/en-attente', [Public\CheckoutController::class, 'pending'])->name('pending');
        Route::get('/echec/{order:reference?}', [Public\CheckoutController::class, 'failed'])->name('failed');
    });

    // Customer area
    Route::middleware('verified')->prefix('mon-compte')->name('customer.')->group(function () {
        Route::get('/', Customer\DashboardController::class)->name('dashboard');

        Route::get('/commandes', [Customer\OrderController::class, 'index'])->name('orders.index');
        Route::get('/commandes/{order:reference}', [Customer\OrderController::class, 'show'])->name('orders.show');

        Route::get('/telechargements', [Customer\DownloadController::class, 'index'])->name('downloads.index');
        Route::get('/telechargements/{order:reference}/{ebook:slug}', [Customer\DownloadController::class, 'request'])
            ->name('downloads.request');

        Route::get('/factures/{invoice:number}', [Customer\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/factures/{invoice:number}/pdf', [Customer\InvoiceController::class, 'download'])->name('invoices.download');

        Route::get('/profil', [Customer\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil', [Customer\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profil/mot-de-passe', [Customer\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

// Signed download serve route (must be outside the verified group, validated by signature)
Route::get('/d/{order:reference}/{ebook:slug}', [Customer\DownloadController::class, 'serve'])
    ->middleware(['auth', 'signed'])
    ->name('download.serve');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('ebooks', Admin\EbookController::class);

        Route::get('commandes', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('commandes/{order:reference}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('commandes/{order:reference}/marquer-payee', [Admin\OrderController::class, 'markPaid'])->name('orders.markPaid');
        Route::post('commandes/{order:reference}/annuler', [Admin\OrderController::class, 'cancel'])->name('orders.cancel');

        Route::get('transactions', [Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{transaction}', [Admin\TransactionController::class, 'show'])->name('transactions.show');
        Route::post('transactions/{transaction}/reconcilier', [Admin\TransactionController::class, 'reconcile'])->name('transactions.reconcile');

        Route::get('utilisateurs', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('utilisateurs/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::post('utilisateurs/{user}/toggle', [Admin\UserController::class, 'toggleActive'])->name('users.toggle');

        Route::get('export/commandes', [Admin\ExportController::class, 'orders'])->name('export.orders');
        Route::get('export/transactions', [Admin\ExportController::class, 'transactions'])->name('export.transactions');

        Route::get('parametres', [Admin\SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('parametres', [Admin\SettingsController::class, 'update'])->name('settings.update');
    });
