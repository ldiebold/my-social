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

            $table->string('title');
            $table->text('description');
            $table->text('social_post_text');
            $table->string('local_folder_path');
            $table->unsignedInteger('episode_number');
            $table->string('raw_audio_file_path');
            $table->string('clean_audio_file_path')->nullable();
            $table->string('video_file_path')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('direct_link')->nullable();
            $table->string('branded_link')->nullable();
            $table->string('youtube_url')->nullable();
            $table->dateTimeTz('publish_date')->nullable();
            $table->string('cover_image_path')->nullable();
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
