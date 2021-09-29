<?php

use App\Models\SocialPost;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledSocialPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_social_posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->dateTimeTz('publish_at');
            $table->boolean('posted')->default(false);

            $table->foreignIdFor(SocialPost::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_social_posts');
    }
}
