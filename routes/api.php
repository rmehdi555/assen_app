<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\AmazonProductController;
use App\Http\Controllers\Api\V1\ArticlesCatergoryController;
use App\Http\Controllers\Api\V1\ArticlesController;
use App\Http\Controllers\Api\V1\CalculatorController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CartFrontController;
use App\Http\Controllers\Api\V1\CheckUrlController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\DashbboardController;
use App\Http\Controllers\Api\V1\EbayProdouctController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\LandingController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\SitemapController;
use App\Http\Controllers\Api\V1\SlidersController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\UserLoginController;
use App\Http\Controllers\Api\V1\UserRegisterController;
use App\Http\Controllers\Api\V1\WalletController;
use App\Http\Middleware\UserNameComplete;
use App\Http\Middleware\UserRegisterComplete;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1/')->namespace('api/v1/')->group(function () {
    Route::post('register-set-number', [UserRegisterController::class, 'setNumber']);
    Route::post('register-validate-otp', [UserRegisterController::class, 'validateOtp']);
    Route::post('send-otp', [UserLoginController::class, 'getNumber'])->middleware('throttle:login')->name('login');
    Route::post('validate-otp', [UserLoginController::class, 'validateOTP'])->middleware('throttle:login');

    Route::middleware(['auth:api'])->group(function () {
        Route::post('register-form', [UserRegisterController::class, 'registerForm']);
        Route::post('logout', [UserLoginController::class, 'logout']);

    });

    Route::get('articles', [ArticlesController::class, 'index']);
    Route::get('article-category', [ArticlesCatergoryController::class, 'index']);
    Route::post('category-show', [ArticlesCatergoryController::class, 'show']);
    Route::get('article-show/{slug}', [ArticlesController::class, 'show']);
    Route::get('sitemap.xml', [SitemapController::class, 'index']);
    Route::get('robot', [SitemapController::class, 'robot']);
    Route::get('sliders', [SlidersController::class, 'index']);

    Route::get('category/{slug}', [ProductController::class, 'category']);
    Route::get('factory/{slug}', [ProductController::class, 'factory']);
    Route::get('size/{slug}', [ProductController::class, 'size']);
    Route::get('standard/{slug}', [ProductController::class, 'standard']);
    Route::get('product-show/{slug}', [ProductController::class, 'show']);
    Route::get('product-chart/{slug}', [ProductController::class, 'chart']);


    Route::get('home-index', [HomeController::class, 'index']);
    Route::post('counseling', [HomeController::class, 'counseling']);
    Route::post('contact', [ContactController::class, 'index']);

});
