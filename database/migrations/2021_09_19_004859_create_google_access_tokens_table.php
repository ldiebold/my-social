<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('access_token');
            $table->string('refresh_token');
            $table->string('scope');
            $table->string('token_type');
            $table->unsignedInteger('created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_access_tokens');
    }
}
