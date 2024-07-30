<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->group( function () {
    //用户详情
    Route::get('user', [\App\Http\Controllers\UserController::class, 'show']);
    //订单列表
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index']);
    //行程订单
    Route::get('user-routes',[\App\Http\Controllers\UserRouteController::class, 'index']);
    Route::middleware('authentication')->group(function () {
        Route::post('order', [\App\Http\Controllers\OrderController::class, 'store']);
        Route::post('withdraw', [\App\Http\Controllers\WithdrawController::class, 'store']);
    });
    //加入自费套餐
    //提现记录
    Route::get('withdraws', [\App\Http\Controllers\WithdrawController::class, 'index']);
    //收益记录
    Route::get('rewards', [\App\Http\Controllers\RewardController::class, 'index']);
    //我的团队
    Route::get('team', [\App\Http\Controllers\UserController::class, 'team']);
    //退出登陆
    Route::post('logout', [\App\Http\Controllers\LoginController::class, 'logout']);
    //用户实名认证
    Route::post('user/authentication', [\App\Http\Controllers\UserController::class, 'authentication']);

});
//行程列表
Route::get('routes', [\App\Http\Controllers\RouteController::class, 'index']);
//注册
Route::post('register', [\App\Http\Controllers\LoginController::class, 'register']);
//忘记密码
Route::post('reset-password', [\App\Http\Controllers\LoginController::class, 'resetPassword']);
//登陆
Route::post('login', [\App\Http\Controllers\LoginController::class, 'login']);

Route::get('city/tag', [\App\Http\Controllers\RouteController::class, 'city']);

Route::get('api/captcha/{config?}', [\App\Http\Controllers\CaptchaController::class, 'getCaptchaApi']);
