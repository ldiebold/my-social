<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\ExternalPodcastFolder
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $path
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalPodcastFolder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalPodcastFolder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalPodcastFolder query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalPodcastFolder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalPodcastFolder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalPodcastFolder wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalPodcastFolder whereUpdatedAt($value)
 */
	class ExternalPodcastFolder extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FacebookAccessToken
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $access_token
 * @property int|null $expires
 * @property string|null $refresh_token
 * @property string $token_type
 * @property-read mixed $expires_in
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken whereExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken whereTokenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookAccessToken whereUpdatedAt($value)
 */
	class FacebookAccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FacebookGroup
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $access_token
 * @property string $facebook_id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookGroup whereUpdatedAt($value)
 */
	class FacebookGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FacebookPage
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $access_token
 * @property string $facebook_id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FacebookPage whereUpdatedAt($value)
 */
	class FacebookPage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GoogleAccessToken
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $access_token
 * @property string $refresh_token
 * @property string $scope
 * @property string $token_type
 * @property int $created
 * @property-read mixed $expires_in
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken whereTokenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleAccessToken whereUpdatedAt($value)
 */
	class GoogleAccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LinkedInAccessToken
 *
 * @property string $other
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $access_token
 * @property int $expires_in
 * @property int $created
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedInAccessToken whereUpdatedAt($value)
 */
	class LinkedInAccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PodcastEpisode
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string $description
 * @property int $episode_number
 * @property \Illuminate\Support\Carbon|null $publish_date
 * @property string $raw_audio_file_path
 * @property string|null $clean_audio_file_path
 * @property string $local_folder_path
 * @property string|null $video_file_path
 * @property string|null $cover_image_path
 * @property string $social_post_text
 * @property string|null $video_url
 * @property string $video_provider
 * @property string $video_id
 * @property string|null $video_share_url
 * @property string $publish_provider
 * @property string|null $provider_id
 * @property string|null $audio_share_url
 * @property string|null $branded_audio_link_url
 * @property string $branded_link_provider
 * @property string|null $branded_audio_link_id
 * @property string|null $branded_video_link_id
 * @property-read null|string $video_share_link
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereAudioShareUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereBrandedAudioLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereBrandedAudioLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereBrandedLinkProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereBrandedVideoLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereCleanAudioFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereCoverImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereEpisodeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereLocalFolderPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode wherePublishProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereRawAudioFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereSocialPostText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereVideoFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereVideoProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereVideoShareUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisode whereVideoUrl($value)
 */
	class PodcastEpisode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PodcastEpisodeCampaignTemplate
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PublishPostEventTemplate[] $publish_post_event_templates
 * @property-read int|null $publish_post_event_templates_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ScheduledSocialPost[] $scheduled_social_posts
 * @property-read int|null $scheduled_social_posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisodeCampaignTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisodeCampaignTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisodeCampaignTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisodeCampaignTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisodeCampaignTemplate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisodeCampaignTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisodeCampaignTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PodcastEpisodeCampaignTemplate whereUpdatedAt($value)
 */
	class PodcastEpisodeCampaignTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PublishEvent
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $publish_at
 * @property int $social_post_template_id
 * @property int $social_publisher_id
 * @property int $social_post_id
 * @property-read \App\Models\SocialPost $social_post
 * @property-read \App\Models\SocialPostTemplate $social_post_template
 * @property-read \App\Models\SocialPublisher $social_publisher
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent whereSocialPostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent whereSocialPostTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent whereSocialPublisherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishEvent whereUpdatedAt($value)
 */
	class PublishEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PublishPodcastOrchestrator
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $external_podcast_folder_id
 * @property int|null $podcast_episode_id
 * @property bool $raw_audio_file_is_stored
 * @property bool $audio_cleaned
 * @property bool $published_on_podcast_platform
 * @property bool $has_branded_link
 * @property bool $has_cover_image
 * @property bool $has_podcast_video
 * @property bool $published_on_video_platform
 * @property bool $social_posts_scheduled
 * @property-read \App\Models\ExternalPodcastFolder $external_podcast_folder
 * @property-read mixed $complete_jobs
 * @property-read \Illuminate\Support\Collection $incomplete_jobs
 * @property-read \App\Models\PodcastEpisode|null $podcast_episode
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereAudioCleaned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereExternalPodcastFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereHasBrandedLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereHasCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereHasPodcastVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator wherePodcastEpisodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator wherePublishedOnPodcastPlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator wherePublishedOnVideoPlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereRawAudioFileIsStored($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereSocialPostsScheduled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPodcastOrchestrator whereUpdatedAt($value)
 */
	class PublishPodcastOrchestrator extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PublishPostEventTemplate
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $days_after_release
 * @property string $release_time
 * @property int $social_post_template_id
 * @property string $publish_post_event_templateable_type
 * @property int $publish_post_event_templateable_id
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $publish_post_event_templateable
 * @property-read \App\Models\SocialPostTemplate $social_post_template
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SocialPublisher[] $social_publishers
 * @property-read int|null $social_publishers_count
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate whereDaysAfterRelease($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate wherePublishPostEventTemplateableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate wherePublishPostEventTemplateableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate whereReleaseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate whereSocialPostTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplate whereUpdatedAt($value)
 */
	class PublishPostEventTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PublishPostEventTemplateSocialPublisher
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $publish_post_event_template_id
 * @property int $social_publisher_id
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplateSocialPublisher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplateSocialPublisher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplateSocialPublisher query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplateSocialPublisher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplateSocialPublisher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplateSocialPublisher wherePublishPostEventTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplateSocialPublisher whereSocialPublisherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublishPostEventTemplateSocialPublisher whereUpdatedAt($value)
 */
	class PublishPostEventTemplateSocialPublisher extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RedditAccessToken
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $access_token
 * @property string $token_type
 * @property int $expires_in
 * @property string $scope
 * @property string $refresh_token
 * @property int $date_retrieved
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereDateRetrieved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereTokenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RedditAccessToken whereUpdatedAt($value)
 */
	class RedditAccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ScheduledSocialPost
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $publish_at
 * @property bool $posted
 * @property int $social_post_id
 * @property-read \App\Models\SocialPost $social_post
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost whereSocialPostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledSocialPost whereUpdatedAt($value)
 */
	class ScheduledSocialPost extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SocialPost
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $social_publisher_id
 * @property string|null $title
 * @property string|null $body
 * @property string|null $link
 * @property string|null $image_path
 * @property string|null $image_link
 * @property string|null $video_path
 * @property-read \App\Models\SocialPublisher $social_publisher
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereImageLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereSocialPublisherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPost whereVideoPath($value)
 */
	class SocialPost extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SocialPostTemplate
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $title
 * @property string|null $body
 * @property string|null $image_path
 * @property string|null $video_path
 * @property string|null $image_link
 * @property string|null $link
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereImageLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPostTemplate whereVideoPath($value)
 */
	class SocialPostTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SocialPublisher
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $identifier
 * @property string $title
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PublishPostEventTemplate[] $publish_post_event_templates
 * @property-read int|null $publish_post_event_templates_count
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialPublisher whereUpdatedAt($value)
 */
	class SocialPublisher extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TwitterTokenCredentials
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $identifier
 * @property string $secret
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterTokenCredentials newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterTokenCredentials newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterTokenCredentials query()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterTokenCredentials whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterTokenCredentials whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterTokenCredentials whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterTokenCredentials whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterTokenCredentials whereUpdatedAt($value)
 */
	class TwitterTokenCredentials extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

