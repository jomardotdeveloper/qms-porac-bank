<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWindows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('windows', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("order");
            $table->boolean("is_priority")->default(false);
            $table->string("current")->default("NONE");
            $table->foreignId("profile_id")->nullable()->constrained("profiles")->onDelete("set null");
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
        Schema::dropIfExists('windows');
    }
}
