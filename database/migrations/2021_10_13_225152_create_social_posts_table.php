<?php

use App\Models\SocialPublisher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignIdFor(SocialPublisher::class);
            $table->string('title')->nullable();
            $table->longText('body')->nullable();
            $table->string('link')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_link')->nullable();
            $table->string('video_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_posts');
    }
}
