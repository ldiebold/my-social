<?php

namespace App\Jobs;

use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ExternalPodcastFolder;
use App\Models\PodcastEpisode;
use App\Models\PublishPodcastOrchestrator;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DownloadAndStoreAudioFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public FilesystemAdapter $dropboxDisk;
    public FilesystemAdapter $podcastDiskLocal;
    public PublishPodcastOrchestrator $orchestrator;
    public ExternalPodcastFolder $podcastFolder;

    public array $info;

    public string $podcastFolderPath;
    public string $rawAudioFilePathDropbox;
    public string $infoFilePath;
    public string $errorsFilePath;
    public string $podcastEpisodeNumber;

    public static array $requiredFiles = [
        'raw.wav',
        // 'noise.wav',
        'info.json'
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PublishPodcastOrchestrator $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }

    public function setup()
    {
        $this->dropboxDisk = Storage::disk('dropbox');
        $this->podcastDiskLocal = Storage::disk('local-podcasts');

        $this->podcastFolder = $this->orchestrator->external_podcast_folder;
        $this->podcastFolderPath = $this->podcastFolder->path;
        $this->rawAudioFilePathDropbox = $this->podcastFolder->path . '/raw.wav';
        $this->noiseAudioFilePathDropbox = $this->podcastFolder->path . '/noise.wav';

        $this->infoFilePath = $this->podcastFolder->path . '/info.json';
        $this->errorsFilePath = $this->podcastFolder->path . '/errors.txt';

        $this->podcastEpisodeNumber = Str::of('podcasts/1')->explode('/')->last();

        $this->rawAudioFileName = $this->podcastEpisodeNumber . '/raw.wav';
        $this->noiseAudioFileName = $this->podcastEpisodeNumber . '/noise.wav';

        $this->removeErrorsFile();
    }

    /**
     * Execute the job.
     *
     * @return ExternalPodcastFolder
     */
    public function handle()
    {
        $this->setup();

        if ($this->validateExternalPodcastFolder($this->podcastFolder)) {
            $this->deleteRawAudioFile()
                ->storePodcastOnLocalDisk()
                // ->storePodcastNoiseOnLocalDisk()
                ->createPodcastEpisodeModel()
                ->markJobCompleteOnOrchestrator();
        };
    }

    public function deleteRawAudioFile()
    {
        if ($this->podcastDiskLocal->exists($this->rawAudioFileName)) {
            $this->podcastDiskLocal->delete($this->rawAudioFileName);
        }

        return $this;
    }

    public function storePodcastOnLocalDisk()
    {
        $this->podcastDiskLocal->writeStream(
            $this->rawAudioFileName,
            $this->dropboxDisk->readStream($this->rawAudioFilePathDropbox)
        );

        return $this;
    }

    public function storePodcastNoiseOnLocalDisk()
    {
        $this->podcastDiskLocal->writeStream(
            $this->noiseAudioFileName,
            $this->dropboxDisk->readStream($this->noiseAudioFilePathDropbox)
        );

        return $this;
    }

    public function createPodcastEpisodeModel()
    {
        $podcastEpisode = PodcastEpisode::create([
            'title' => $this->info['title'],
            'description' => $this->info['description'],
            'social_post_text' => $this->info['social_post_text'],
            'raw_audio_file_path' => $this->rawAudioFileName,
            'episode_number' => $this->podcastEpisodeNumber,
            'local_folder_path' => $this->podcastEpisodeNumber
        ]);

        $this->orchestrator->podcast_episode()
            ->associate($podcastEpisode)
            ->save();

        return $this;
    }

    public function markJobCompleteOnOrchestrator()
    {
        $this->orchestrator->update([
            'raw_audio_file_is_stored' => true
        ]);

        return $this;
    }


    /**
     * Remove error feedback file on the dropbox disk
     *
     * @return DownloadAndStoreAudioFileJob
     */
    public function removeErrorsFile()
    {
        $this->dropboxDisk->delete($this->errorsFilePath);
        return $this;
    }

    /**
     * Ensure we have all the required data to begin publishing
     * the podcast. Also, validate that data.
     *
     * @return bool
     */
    public function validateExternalPodcastFolder()
    {
        $missingFilesCollection = $this->podcastFolderMissingFiles();
        if ($missingFilesCollection->count()) {
            return $this->dropboxDisk->put(
                $this->errorsFilePath,
                $this->getMissingFilesError($missingFilesCollection)
            );
            return false;
        }

        $infoFile = $this->dropboxDisk->get($this->infoFilePath);
        $this->info = json_decode($infoFile, true);

        $validator = $this->validate($this->info);

        if ($validator->fails()) {
            $this->handleFailedValidation($validator);
            return false;
        }

        return true;
    }

    public function getMissingFilesError(Collection $missingFilesCollection)
    {
        return "The following files are missing: "
            . "\n"
            . $missingFilesCollection->join("\n");
    }

    /**
     * Check the podcast folder isn't missing any required files
     *
     * @return Collection
     */
    public function podcastFolderMissingFiles()
    {
        return collect(static::$requiredFiles)
            ->filter(function (string $file) {
                return $this->dropboxDisk
                    ->missing($this->podcastFolderPath . '/' . $file);
            });
    }

    /**
     * @param string $info
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate($info)
    {
        return Validator::make($info, [
            'title' => 'required',
            'description' => 'required|min:20',
            'social_post_text' => 'required|min:10|max:140',
        ]);
    }

    /**
     * If validation fails, save an "errors.txt" file.
     * This gives quick feedback to the content
     * creator!
     *
     * @param [type] $validator
     * @return bool
     */
    public function handleFailedValidation($validator)
    {
        return $this->dropboxDisk->put(
            $this->errorsFilePath,
            $this->formatErrorMessages($validator->errors())
        );
    }

    /**
     * Format MessageBag so that's it's nice to view
     * inside a file
     *
     * @param MessageBag $messages
     * @return string
     */
    public function formatErrorMessages(MessageBag $messages)
    {
        return collect($messages)->map(function ($value, $key) {
            return collect($value)->map(function ($value) use ($key) {
                return "$key: $value";
            });
        })->flatten()
            ->join("\n");
    }
}
