<?php

use App\Models\SocialPost;
use App\Models\SocialPostTemplate;
use App\Models\SocialPublisher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publish_events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->timestampTz('publish_at');

            $table->foreignIdFor(SocialPostTemplate::class)
                ->constrained();
            $table->foreignIdFor(SocialPublisher::class)
                ->constrained();
            $table->foreignIdFor(SocialPost::class)
                ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publish_events');
    }
}
