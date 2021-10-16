<?php

use App\Models\PublishPostEventTemplate;
use App\Models\SocialPublisher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishPostEventTemplateSocialPublishersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publish_post_event_template_social_publishers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignIdFor(PublishPostEventTemplate::class)
                ->constrained();
            $table->foreignIdFor(SocialPublisher::class)
                ->constrained();

            $table->unique([
                'publish_post_event_template_id',
                'social_publisher_id'
            ], 'unique_publish_post_event_template_social_publishers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publish_post_event_template_social_publishers');
    }
}
