<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutoffs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutoffs', function (Blueprint $table) {
            $table->id();
            $table->string("m")->nullable();
            $table->string("t")->nullable();
            $table->string("w")->nullable();
            $table->string("th")->nullable();
            $table->string("f")->nullable();
            $table->string("s")->nullable();
            $table->string("sd")->nullable();
            $table->boolean("is_cutoff")->default(false);
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
        Schema::dropIfExists('cutoffs');
    }
}
