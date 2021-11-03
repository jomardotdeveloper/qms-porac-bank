<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string("token");
            $table->integer("order")->default(1);
            $table->foreignId("account_id")->nullable()->constrained("accounts")->onDelete("set null");
            $table->enum("state",  ["waiting", "serving", "out", "drop"])->default("waiting");
            $table->timestamp("in")->default(DB::raw("CURRENT_TIMESTAMP"));
            $table->timestamp("out")->nullable();
            $table->timestamp("drop")->nullable();
            $table->timestamp("serve")->nullable();
            $table->string("amount")->nullable();
            $table->string("mobile_number")->nullable();
            $table->integer("is_notifiable")->default(false);
	        $table->foreignId("window_id")->nullable()->constrained("windows")->onDelete("set null");
            $table->foreignId("service_id")->nullable()->constrained("services")->onDelete("set null");
            $table->foreignId("branch_id")->nullable()->constrained("branches")->onDelete("cascade");
            $table->foreignId("profile_id")->nullable()->constrained("profiles")->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
