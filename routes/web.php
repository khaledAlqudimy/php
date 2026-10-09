<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\PersonalShopperController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\Admin;

/*
|─────────────────────────────────────────────────────────────────────────────
| الصفحات العامة
|─────────────────────────────────────────────────────────────────────────────
*/

Route::get('/',          [HomeController::class, 'index'])->name('home');
Route::get('/shop',      [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/auction',          [AuctionController::class, 'index'])->name('auction.index');
Route::post('/auction/interest',[AuctionController::class, 'storeInterest'])->name('auction.interest')->middleware('throttle:10,10');

Route::get('/personal-shopper',  [PersonalShopperController::class, 'index'])->name('personal-shopper.index');
Route::post('/personal-shopper', [PersonalShopperController::class, 'store'])->name('personal-shopper.store')->middleware('throttle:10,10');

Route::get('/blog',        [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/about',       fn() => view('pages.about'))->name('about');
Route::get('/contact',     [ContactController::class, 'show'])->name('contact');
Route::post('/contact',    [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,10');
Route::get('/services',    fn() => view('pages.services'))->name('services');
Route::get('/shipping',    fn() => view('pages.shipping'))->name('shipping');
Route::get('/returns',     fn() => view('pages.returns'))->name('returns');
Route::get('/faq',         fn() => view('pages.faq'))->name('faq');
Route::get('/collections', fn() => view('pages.collections'))->name('collections');
Route::get('/privacy',     fn() => view('pages.privacy'))->name('privacy');
Route::get('/terms',       fn() => view('pages.terms'))->name('terms');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt',  [SeoController::class, 'robots'])->name('robots');

/*
|─────────────────────────────────────────────────────────────────────────────
| المصادقة
|─────────────────────────────────────────────────────────────────────────────
*/

Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);

    // ─── استعادة كلمة المرور ───
    Route::get('/forgot-password',        [PasswordResetController::class, 'showRequest'])->name('password.request');
    Route::post('/forgot-password',       [PasswordResetController::class, 'sendLink'])->name('password.email')->middleware('throttle:5,10');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password',        [PasswordResetController::class, 'reset'])->name('password.update')->middleware('throttle:5,10');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|─────────────────────────────────────────────────────────────────────────────
| حساب العميل (يتطلب تسجيل دخول)
|─────────────────────────────────────────────────────────────────────────────
*/

Route::middleware(['auth', 'customer'])->prefix('account')->name('account.')->group(function () {
    Route::get('/profile',   [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile',   [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders',    [AccountController::class, 'orders'])->name('orders');
    Route::get('/wishlist',  [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses',[AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])->name('addresses.destroy');
});

/*
|─────────────────────────────────────────────────────────────────────────────
| السلة والطلبات
|─────────────────────────────────────────────────────────────────────────────
*/

Route::middleware('auth')->group(function () {
    Route::get('/cart',             [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart',            [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{id}',      [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}',     [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('/wishlist/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::get('/checkout',         [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout',        [OrderController::class, 'placeOrder'])->name('order.place');
    Route::post('/coupon/validate', [OrderController::class, 'validateCoupon'])->name('coupon.validate');
});

/*
|─────────────────────────────────────────────────────────────────────────────
| لوحة الإدارة (يتطلب دور ADMIN أو أعلى)
|─────────────────────────────────────────────────────────────────────────────
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // تسجيل دخول الإدارة (بدون Middleware)
    Route::get('/login',  [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [Admin\AuthController::class, 'login']);

    // باقي صفحات الإدارة (محمية)
    Route::middleware('admin')->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');

        Route::get('/',                    [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products',        Admin\ProductController::class)->except(['show', 'destroy']);
        Route::delete('products/images/{id}', [Admin\ProductController::class, 'deleteImage'])->name('products.images.destroy');

        Route::resource('categories',      Admin\CategoryController::class)->only(['index', 'store', 'update']);
        Route::resource('orders',          Admin\OrderController::class)->only(['index','show','update']);
        Route::resource('customers',       Admin\CustomerController::class)->only(['index','show']);

        Route::get('auction-interest',     [Admin\AuctionInterestController::class, 'index'])->name('auction-interest.index');
        Route::get('personal-shopper',     [Admin\PersonalShopperController::class, 'index'])->name('personal-shopper.index');
        Route::patch('personal-shopper/{id}', [Admin\PersonalShopperController::class, 'updateStatus'])->name('personal-shopper.status');

        Route::get('messages',             [Admin\ContactMessageController::class, 'index'])->name('messages.index');

        // ─── للمدير فقط (SUPER_ADMIN / MANAGER) ───
        Route::middleware('manager')->group(function () {
            Route::delete('products/{product}',   [Admin\ProductController::class, 'destroy'])->name('products.destroy');
            Route::delete('categories/{category}',[Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
            Route::resource('coupons',            Admin\CouponController::class)->only(['index', 'store', 'update', 'destroy']);

            Route::get('reports',              [Admin\ReportController::class, 'index'])->name('reports.index');
            Route::get('settings',             [Admin\SettingController::class, 'index'])->name('settings.index');
            Route::put('settings',             [Admin\SettingController::class, 'update'])->name('settings.update');
        });
    });
});
