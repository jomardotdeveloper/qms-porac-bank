<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId("account_id")->nullable()->constrained("accounts")->onDelete("set null");
            $table->foreignId("branch_id")->constrained("branches")->onDelete("cascade");
            $table->timestamp("datetime")->default(DB::raw("CURRENT_TIMESTAMP"));
            $table->string("message");
            $table->foreignId("transaction_id")->nullable()->constrained("transactions")->onDelete("cascade");
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
        Schema::dropIfExists('notifications');
    }
}
