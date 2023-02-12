<?php
use App\Http\Controllers\Api\BtcController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::match(['get', 'post'], '/v1', [BTCController::class,'callAPI']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
