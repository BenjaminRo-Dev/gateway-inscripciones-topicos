<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::any('/{service}/{path?}', [App\Http\Controllers\ApiGatewayController::class, 'handle'])
    ->where('path', '.*');

Route::get('/', function () {
    return response()->json(['message' => 'API Gateway is running']);
});
