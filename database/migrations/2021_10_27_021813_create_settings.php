<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean("is_automatic_sms")->default(false);
            $table->integer("sms_interval")->default(5);
            $table->boolean("is_qrcode_automatic")->default(false);
            $table->integer("qrcode_interval")->default(5);
            $table->foreignId("branch_id")->nullable()->constrained("branches")->onDelete("cascade");
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
        Schema::dropIfExists('settings');
    }
}
