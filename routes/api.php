<?php
date_default_timezone_set('Asia/Manila');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WindowController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CutoffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\CodeController;

use App\Http\Controllers\SinkerLoc;
use App\Http\Controllers\SinkerClod;
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
Route::post("notifications/store", [NotificationController::class, "store"]);   
Route::get("/server/all", [ServerController::class, "all"]);
Route::get("/server/get_branch_id", [ServerController::class, "getBranchID"]);
Route::get("/sms/send_message/{to}/{message}", [SMSController::class, "message"]);
Route::post("/transactions/make", [TransactionController::class, "make"]);
Route::post("/transactions/update_state", [TransactionController::class, "update_state"]);
Route::post("/transactions/update_holder", [TransactionController::class, "update_holder"]);
Route::get("/branches/all/", [BranchController::class, "all"]);
Route::get("/transactions/send_sms/{id}/{is_transfer}", [TransactionController::class, "sendSms"]);
Route::get("/transactions/set_notifiable/{is_notifiable}/{delimeter}", [TransactionController::class, "set_notifiable"]);
Route::get("/transactions/get_mobile_transactions/{delimeter}", [TransactionController::class, "get_mobile_transactions"]);
Route::get("/transactions/get/{branch_id}/{window_id}", [TransactionController::class, "get_transactions_now"]);
Route::get("/transactions/get_live/{branch_id}", [TransactionController::class, "get_active_current"]);
Route::get("/settings/get/{branch_id}", [SettingController::class, "get_setting"]);
Route::get("/windows/available/{branch_id}/{service_id}/{is_priority}", [WindowController::class, "get_available_window"]);
Route::get("/windows/update_tour/{window_id}", [WindowController::class, "updateIsTour"]);
Route::get("/branches/get_branch_data/{product_key}", [BranchController::class, "get_branch_data"]);
Route::get("/cutoffs/get_cutoff_data/{branch_id}", [CutoffController::class, "get_cutoff_data"]);
Route::get("/account/account_exists/{account_number}/{branch_id}", [AccountController::class, "account_exists"]);

//APEX CHART
Route::get("/dashboards/get_monthly/{year}/{branch_id}/{window_id}", [DashboardController::class, "get_monthly"]);
Route::get("/dashboards/get_quarterly/{year}/{branch_id}/{window_id}", [DashboardController::class, "get_quarterly"]);
Route::get("/dashboards/get_half/{year}/{branch_id}/{window_id}", [DashboardController::class, "get_half"]);


// WITHOUT BRANCH
Route::get("/dashboards/get_monthly/{year}/{branch_id}/{window_id}", [DashboardController::class, "get_monthly"]);
Route::get("/dashboards/get_quarterly/{year}/{branch_id}/{window_id}", [DashboardController::class, "get_quarterly"]);
Route::get("/dashboards/get_half/{year}/{branch_id}/{window_id}", [DashboardController::class, "get_half"]);


//GETTING THE MESSAGES NOTIFICATIONS
Route::get("/transactions/get_inform/{id}", [DashboardController::class, "getInformingMessage"]);
Route::get("/transactions/get_first/{id}", [DashboardController::class, "getMessageFirst"]);
Route::get("/transactions/get_transfer/{id}", [DashboardController::class, "getMessageTransferFirst"]);
Route::get("/transactions/get_transfer_first/{id}", [DashboardController::class, "getMessageTransfer"]);
Route::get("/transactions/get_sms/{id}/{is_transfer}", [TransactionController::class, "getSms"]);


// FOR TESTING PURPOSES
Route::get("/transactions/test/{branch_id}", [TransactionController::class, "isCutoff"]);



// CONTROLS
Route::get("/transactions/start_queue/{branch_id}/{window_id}", [TransactionController::class, "startQueue"]);
Route::get("/transactions/stop_queue/{window_id}", [TransactionController::class, "stopQueue"]);
Route::get("/transactions/next_queue/{window_id}/{time}", [TransactionController::class, "nextQueue"]);
Route::get("/transactions/drop_queue/{window_id}", [TransactionController::class, "dropQueue"]);



//OTP
Route::get("/codes/create_code/{number}", [CodeController::class, "createCode"]);
Route::get("/codes/check_code/{number}/{code}", [CodeController::class, "checker"]);









//LOCAL ENDPOINTS
Route::get("/sinker_local/sink_all", [SinkerLoc::class, "sinkAll"]);

//CLOUD ENDPOINTS
Route::post("/sinker_cloud/sink_transactions", [SinkerClod::class, "sinkTransactions"]);