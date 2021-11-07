<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WindowController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CutoffController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SMSController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource("transactions", TransactionController::class);
Route::get("/sms/send_message/{to}/{message}", [SMSController::class, "message"]);
Route::post("/transactions/make", [TransactionController::class, "make"]);
Route::post("/transactions/update_state", [TransactionController::class, "update_state"]);
Route::post("/transactions/update_holder", [TransactionController::class, "update_holder"]);
Route::get("/branches/all/", [BranchController::class, "all"]);
Route::get("/transactions/get/{branch_id}/{window_id}", [TransactionController::class, "get_transactions_now"]);
Route::get("/transactions/get_live/{branch_id}", [TransactionController::class, "get_active_current"]);
Route::get("/windows/available/{branch_id}/{service_id}/{is_priority}", [WindowController::class, "get_available_window"]);
Route::get("/branches/get_branch_data/{product_key}", [BranchController::class, "get_branch_data"]);
Route::get("/cutoffs/get_cutoff_data/{branch_id}", [CutoffController::class, "get_cutoff_data"]);
Route::get("/account/account_exists/{account_number}/{branch_id}", [AccountController::class, "account_exists"]);
