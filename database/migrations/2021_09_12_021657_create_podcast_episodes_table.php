<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodcastEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Details
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('episode_number');
            $table->dateTimeTz('publish_date')->nullable();

            // Storage
            $table->string('raw_audio_file_path');
            $table->string('clean_audio_file_path')->nullable();
            $table->string('local_folder_path');
            $table->string('video_file_path')->nullable();

            // Cover Image
            $table->string('cover_image_path')->nullable();

            // Social Details
            $table->text('social_post_text');

            // Video Publishing Provider
            $table->string('video_url')->nullable();
            $table->string('video_provider')->default('youtube');
            $table->string('video_id')->default('youtube');
            $table->string('video_share_url')->nullable();

            // Audio Publishing Provider
            $table->string('publish_provider')->default('transistor');
            $table->string('provider_id')->nullable();
            $table->string('audio_share_url')->nullable();

            // Branded Link
            $table->string('branded_audio_link_url')->nullable();
            $table->string('branded_link_provider')->default('rebrandly');
            $table->string('branded_audio_link_id')->nullable();
            $table->string('branded_video_link_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('podcast_episodes');
    }
}
