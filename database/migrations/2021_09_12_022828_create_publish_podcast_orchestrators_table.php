<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishPodcastOrchestratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publish_podcast_orchestrators', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignIdFor(\App\Models\ExternalPodcastFolder::class)
                ->constrained();
            $table->foreignIdFor(\App\Models\PodcastEpisode::class)
                ->nullable()
                ->constrained();

            $table->boolean('raw_audio_file_is_stored')->default('false');
            $table->boolean('audio_cleaned')->default('false');
            $table->boolean('published_on_podcast_platform')->default('false');
            $table->boolean('has_branded_link')->default('false');
            $table->boolean('has_cover_image')->default('false');
            $table->boolean('has_podcast_video')->default('false');
            $table->boolean('published_on_video_platform')->default('false');
            $table->boolean('social_posts_scheduled')->default('false');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publish_podcast_orchestrators');
    }
}
