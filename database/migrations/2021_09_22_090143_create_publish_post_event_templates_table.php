<?php

use App\Models\SocialPostTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishPostEventTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publish_post_event_templates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedInteger('days_after_release');
            $table->time('release_time');

            $table->morphs('publish_post_event_templateable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publish_post_event_templates');
    }
}
