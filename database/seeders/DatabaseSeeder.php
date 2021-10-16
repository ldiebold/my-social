<?php

namespace Database\Seeders;

use App\Models\PodcastEpisodeCampaignTemplate;
use App\Models\PublishPostEventTemplate;
use App\Models\SocialPostTemplate;
use App\Models\SocialPublisher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        SocialPublisher::insert([
            ['identifier' => 'facebook.page.image', 'title' => 'Facebook Page Image'],
            ['identifier' => 'facebook.page.link', 'title' => 'Facebook Page Link'],
            ['identifier' => 'facebook.page.text', 'title' => 'Facebook Page Text'],
            ['identifier' => 'reddit.subreddit.link', 'title' => 'Reddit Link'],
            ['identifier' => 'reddit.subreddit.text', 'title' => 'Reddit Text'],
            ['identifier' => 'twitter.image', 'title' => 'Twitter Image'],
            ['identifier' => 'twitter.link', 'title' => 'Twitter Link'],
        ]);

        $podcastEpisodeCampaignTemplate = PodcastEpisodeCampaignTemplate::create([
            'name' => 'default template',
            'description' => 'default podcast publishing template'
        ]);

        $socialPostTemplate = SocialPostTemplate::create([
            'title' => 'Example Social Post',
            'body' => 'A social post for the interwebs!',
            'image_path' => 'cover.jpeg',
            'video_path' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'image_link' => 'https://nerdist.com/wp-content/uploads/2020/07/maxresdefault.jpg',
            'link' => 'https://quasarcast.com',
        ]);

        $publishPodcastEventTemplate = PublishPostEventTemplate::make([
            'days_after_release' => 3,
            'release_time' => Carbon::now(),
        ]);

        $publishPodcastEventTemplate->publish_post_event_templateable()
            ->associate($podcastEpisodeCampaignTemplate);
        $publishPodcastEventTemplate->social_post_template()
            ->associate($socialPostTemplate);

        $publishPodcastEventTemplate->save();

        $publishPodcastEventTemplate->social_publishers()->attach([1, 4, 7]);
    }
}
