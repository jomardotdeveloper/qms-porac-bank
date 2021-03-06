<?php
date_default_timezone_set('Asia/Manila');

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\CutoffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WindowController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\CashDepositController;
use App\Http\Controllers\CashWithController;
use App\Http\Controllers\CashEncashmentController;
use App\Http\Controllers\NewAccountController;
use App\Http\Controllers\BillsPaymentController;
use App\Http\Controllers\LoanTransactionController;
use App\Http\Controllers\AllTransactionController;
use App\Models\Attachment;
use App\Models\Feedback;
use App\Models\Log;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    // dd(intval("05"));
    return view("frontend");
})->name("frontend");

Route::get('send-mail', function () {
    dd(date("Y-m-d"));
    $details = [
        'title' => 'Porac Bank Daily Reports',
        'body' => 'Here are your daily reports'
    ];

    \Mail::to('remdoro.28@gmail.com')->send(new \App\Mail\MyTestMail("PUTAKA"));

    dd("Email is Sent.");
});

Route::get("/download", function () {
    $attachment = Attachment::all()->where("is_active", "=", true)->first();
    if ($attachment) {
        return response()->download(public_path("app-debug.apk"), "PBQMS");
    }
    return redirect()->route("frontend");
})->name("app.download");

Route::post("/feedback", function (Request $request) {
    $feedback = Feedback::create($request->all());
    $feedback->save();
    return Redirect::back()->withErrors(['msg' => 'Your feedback has been sent']);
})->name("submit.feedback");

Route::get("/login",  [LoginController::class, "index"])->middleware("checkauth")->name("login.index");
Route::get("/backend/",  [LoginController::class, "index"])->middleware("checkauth")->name("login.index");
Route::post("/login",  [LoginController::class, "store"])->name("login.store");
Route::post("/logout", function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();
    $log = Log::find(1);
    $log->branch_id = null;
    $log->save();
    return redirect()->route("login.index");
})->name("logout");

Route::prefix("/backend")->middleware(["auth"])->group(function () {
    Route::resource("branches", BranchController::class)->middleware("checkbranch");
    Route::resource("roles", RoleController::class)->middleware("checkrole");
    Route::resource("profiles", ProfileController::class)->middleware("checkuser");
    Route::resource("attachments", AttachmentController::class)->middleware("checkmobile");;
    Route::resource("accounts", AccountController::class)->middleware("checkaccount");
    Route::resource("settings", SettingController::class)->middleware("checksetting");
    Route::resource("cutoffs", CutoffController::class);
    Route::resource("servers", ServerController::class);
    Route::resource("windows", WindowController::class);
    Route::resource("feedbacks", FeedbackController::class)->middleware("checkfeedback");
    Route::resource("my_profile", MyProfileController::class);
    Route::resource("dashboards", DashboardController::class);
    Route::resource("controls", ControlController::class)->middleware("checkcontrol");

    // NOTIFICATION REPORTS
    Route::resource("notifications", NotificationController::class)->middleware("checkreport");
    Route::post("notifications/export", [NotificationController::class, "export"])->name("notifications.export")->middleware("checkreport");

    // PERFORMANCE REPORTS
    Route::resource("performances", PerformanceController::class)->middleware("checkreport");
    Route::get("/performances/tellers/{branch_id}", [PerformanceController::class, "getTellerByBranch"])->middleware("checkreport");
    Route::post("performances/export", [PerformanceController::class, "export"])->name("performances.export")->middleware("checkreport");

    // CASH DEPOSIT REPORTS
    Route::resource("deposits", CashDepositController::class)->middleware("checkreport");
    Route::post("deposits/export", [CashDepositController::class, "export"])->name("deposits.export")->middleware("checkreport");

    // CASH WITHDRAWAL REPORTS
    Route::resource("withs", CashWithController::class)->middleware("checkreport");
    Route::post("withs/export", [CashWithController::class, "export"])->name("withs.export")->middleware("checkreport");


    // CASH ENCASHMENT REPORTS
    Route::resource("encashments", CashEncashmentController::class)->middleware("checkreport");
    Route::post("encashments/export", [CashEncashmentController::class, "export"])->name("encashments.export")->middleware("checkreport");


    // NEW ACCOUNT REPORTS
    Route::resource("news", NewAccountController::class)->middleware("checkreport");
    Route::post("news/export", [NewAccountController::class, "export"])->name("news.export")->middleware("checkreport");

    // BILLS PAYMENT REPORTS
    Route::resource("bills", BillsPaymentController::class)->middleware("checkreport");
    Route::post("bills/export", [BillsPaymentController::class, "export"])->name("bills.export")->middleware("checkreport");

    // LOAN TRANSACTION REPORTS
    Route::resource("loans", LoanTransactionController::class)->middleware("checkreport");
    Route::post("loans/export", [LoanTransactionController::class, "export"])->name("loans.export")->middleware("checkreport");

    // ALL TRANSACTION REPORTS
    Route::resource("alls", AllTransactionController::class)->middleware("checkreport");
    Route::post("alls/export", [AllTransactionController::class, "export"])->name("alls.export")->middleware("checkreport");


    Route::resource("transactions", TransactionController::class)->middleware("checkreport");
    Route::post("transactions/generate_demo_data", [TransactionController::class, "generate_demo_data"])->name("transaction.generate_demo_data");
    Route::post("transactions/export", [TransactionController::class, "export"])->name("transaction.export");
    Route::post("accounts/import", [AccountController::class, "import"])->name("account.import");
    Route::get("attachments/{attachment}/download", [AttachmentController::class, "download"])->name("attachments.download");
    Route::get("attachments/{attachment}/active", [AttachmentController::class, "active"])->name("attachments.active");
});
