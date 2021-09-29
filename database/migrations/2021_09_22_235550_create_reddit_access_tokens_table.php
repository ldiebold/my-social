<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedditAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reddit_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('access_token');
            $table->string('token_type');
            $table->unsignedInteger('expires_in');
            $table->string('scope');
            $table->string('refresh_token');
            $table->unsignedInteger('date_retrieved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reddit_access_tokens');
    }
}
