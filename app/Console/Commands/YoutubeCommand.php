<?php

namespace App\Console\Commands;

use Google\Http\MediaFileUpload;
use Google\Service\YouTube;
use Google\Service\YouTube\Video;
use Google\Service\YouTube\VideoCategory;
use Google\Service\YouTube\VideoSnippet;
use Google\Service\YouTube\VideoStatus;
use Illuminate\Console\Command;

class YoutubeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Youtube test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $youtube = new YouTube([]);
        $video = new Video();
        $snippet = new VideoSnippet([
            'title' => 'First Sample Video Podcast',
            'description' => 'this is a sample to see if I can upload videos through the youtube API!'
        ]);

        $video->setSnippet($snippet);

        $status = new VideoStatus();
        $status->privacyStatus = 'public';

        $snippet->setTags(['tag1', 'tag2']);
        $snippet->setCategoryId('22');

        // MediaFileUpload

        $youtube->videos->insert(
            [
                'uploadType' => 'resumable',
                'part' => "snippet, status"
            ],
            $video
        );
        $youtube->getClient()->isAccessTokenExpired();
        return 0;
    }
}
