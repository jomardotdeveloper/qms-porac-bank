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
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\CashWithController;
use App\Http\Controllers\CashDepositController;
use App\Http\Controllers\CashEncashmentController;
use App\Http\Controllers\BillsPaymentController;
use App\Http\Controllers\LoanTransactionController;
use App\Http\Controllers\NewAccountController;
use App\Http\Controllers\AllTransactionController;
use App\Http\Controllers\MailManController;
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
//LOCAL SERVER CHECKER
Route::get("/servers/check/{branch_id}", [ServerController::class, "check"]);
//LOCAL ENDPOINTS
// Route::get("/sinker_local/get_validated", [SinkerLoc::class, "getValidated"]);
Route::get("/sinker_local/get_all/{branch_id}", [SinkerLoc::class, "getAllTransactions"]);
Route::post("/sinker_local/sink_transactions", [SinkerLoc::class, "sinkTransactions"]);
//CLOUD ENDPOINTS
Route::post("/sinker_cloud/sink_transactions", [SinkerClod::class, "sinkTransactions"]);
Route::get("/sinker_cloud/get_all/{branch_id}", [SinkerClod::class, "getAllTransactions"]);


// NEW MESSAGING PROCESS
Route::get("/messaging/send_message/{id}/{is_prio_message}", [MessagingController::class, "sendMessage"]);
Route::get("/messaging/get_notification/{id}/{is_prio_message}", [MessagingController::class, "getNotification"]);
Route::get("/messaging/get_waiting_time/{id}", [MessagingController::class, "getEstimateWaitingTime"]);


// PUBLIC DAILY EMAILS
// NOTIFICATION
Route::get("/notifications/daily/{branch_id}/{date}", [NotificationController::class, "publicDaily"]);
// PERFORMANCE
Route::get("/performances/daily/{branch_id}/{date}", [PerformanceController::class, "publicDaily"]);
// ALL
Route::get("/alls/daily/{branch_id}/{date}", [AllTransactionController::class, "publicDaily"]);
// DEPOSITS
Route::get("/deposits/daily/{branch_id}/{date}", [CashDepositController::class, "publicDaily"]);
// WITHDRAWAL
Route::get("/withs/daily/{branch_id}/{date}", [CashWithController::class, "publicDaily"]);
// ENCASHMENT
Route::get("/encashments/daily/{branch_id}/{date}", [CashEncashmentController::class, "publicDaily"]);
// BILLS PAYMENT
Route::get("/bills/daily/{branch_id}/{date}", [BillsPaymentController::class, "publicDaily"]);
// LOAN
Route::get("/loans/daily/{branch_id}/{date}", [LoanTransactionController::class, "publicDaily"]);
// NEW ACCOUNTS
Route::get("/news/daily/{branch_id}/{date}", [NewAccountController::class, "publicDaily"]);

// MAILER
Route::get("/mailer/send/{branch_id}", [MailManController::class, "sendDailyReports"]);

// USER SINKER
Route::get("/sinker_local/get_all_users/{branch_id}", [SinkerLoc::class, "getAllUsers"]);
Route::post("/sinker_cloud/sink_users", [SinkerClod::class, "sinkUser"]);

//ACCOUNT SINKER
Route::get("/sinker_local/get_all_accounts/{branch_id}", [SinkerLoc::class, "getAllAccounts"]);
Route::post("/sinker_cloud/sink_accounts", [SinkerClod::class, "sinkAccounts"]);


// NOTIFICATION SINKER
// FROM LOCAL TO CLOUD
Route::get("/sinker_local/get_all_notifs/{branch_id}", [SinkerLoc::class, "getAllNotifications"]);
Route::post("/sinker_cloud/sink_notifs", [SinkerClod::class, "sinkNotification"]);

// SAMPLE SMS / DROP SMS AND PUSH
Route::get("/samplelangnaman/{to}/{message}", [MessagingController::class, "itextMoSend"]);
Route::get("/messaging/drop/sms/{id}", [MessagingController::class, "messageDrop"]);
Route::get("/messaging/drop/push/{id}", [MessagingController::class, "pushNotifDrop"]);
