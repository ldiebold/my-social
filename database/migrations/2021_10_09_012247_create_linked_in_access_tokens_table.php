<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkedInAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linked_in_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('access_token', 500);
            $table->unsignedBigInteger('expires_in');
            $table->unsignedBigInteger('created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linked_in_access_tokens');
    }
}
