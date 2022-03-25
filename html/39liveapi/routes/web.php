<?php

use App\Models\User;
use App\Models\UserGiftEntry;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
  Route::post('/register', 'UserController@register');
  Route::post('/login', 'UserController@login')->name('login');
  Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', 'UserController@logout');
    Route::get('/me/profile', 'UserController@getUserProfile');
    Route::post('/me/identification-paper', 'UserController@postIdentificationPaper');
    Route::get('/live/search', 'LiveController@getLive');
    Route::get('/live/detail', 'LiveController@getLiveById');
    Route::post('/live/start', 'LiveController@startLive');
    Route::get('/wallet/coin', 'WalletController@getCoinWalletByUserId');
    Route::get('/wallet/point', 'WalletController@getPointWalletByUserId');
    Route::get('/wallet/gift', 'WalletController@getUserGiftEntriesByUserId');
    Route::get('/wallet/gift/{gift_id}', 'WalletController@getUserGiftEntriesByUserIdAndGiftId');
    Route::post('/wallet/gift/{gift_id}/{to_uid}', 'WalletController@presentOneGift');
    Route::post('/wallet/point/request', 'WalletController@createPointToYenRequest');
  });
});
Route::get('loginAnonymous', 'FirebaseTestController@loginAnonymous');
