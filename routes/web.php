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
    return view("frontend");
})->name("frontend");

Route::get("/download", function(){
    $attachment = Attachment::all()->where("is_active", "=", true)->first();
    if($attachment){
        return response()->download(public_path("app-debug.apk"), "PBQMS");
    }
    return redirect()->route("frontend");
})->name("app.download");

Route::post("/feedback", function(Request $request){
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

Route::prefix("/backend")->middleware(["auth"])->group(function(){
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
    Route::get("/notification/daily", [NotificationController::class, "daily"])->name("notifications.index.daily")->middleware("checkreport"); 
    Route::get("/notification/monthly", [NotificationController::class, "monthly"])->name("notifications.index.monthly")->middleware("checkreport");
    Route::get("/notification/yearly", [NotificationController::class, "yearly"])->name("notifications.index.yearly")->middleware("checkreport");
    Route::get("/notification/export_pdf_daily/{date}", [NotificationController::class, "export_pdf_daily"])->name("notifications.export.pdf.daily")->middleware("checkreport");

    Route::resource("transactions", TransactionController::class)->middleware("checkreport"); 
    Route::post("transactions/generate_demo_data", [TransactionController::class, "generate_demo_data"])->name("transaction.generate_demo_data"); 
    Route::post("transactions/export", [TransactionController::class, "export"])->name("transaction.export"); 
    Route::post("accounts/import", [AccountController::class, "import"])->name("account.import");
    Route::get("attachments/{attachment}/download", [AttachmentController::class, "download"])->name("attachments.download");
    Route::get("attachments/{attachment}/active", [AttachmentController::class, "active"])->name("attachments.active");
});
