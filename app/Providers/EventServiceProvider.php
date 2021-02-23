<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\UserNameChanged' => [
            'App\Listeners\RemoveUserPetition',
            'App\Listeners\RemoveUserVerification',
        ],
        'App\Events\PetitionSupportersChanged' => [
            'App\Listeners\UpdatePetitionStatus',
            'App\Listeners\ChangeUserType',
        ],
        'App\Events\SearchNameChanged' => [
            'App\Listeners\RemoveFollowers',
        ],
        'App\Events\IdeaSupportHasChanged' => [
            'App\Listeners\RemoveIdea',
        ],
        'App\Events\BeforeUserNationChanged' => [
            'App\Listeners\RemoveToggleLikedIdeas',
            'App\Listeners\RemoveOfficerPetitionSupport',
        ],
        'App\Events\UserLeftIlp' => [
            'App\Listeners\RemoveOfficerPetition',
            'App\Listeners\RemoveOfficerPetitionSupport',
        ],
        'App\Events\UserLostVerification' => [
            'App\Listeners\RemoveOfficerPetitionSupport',            
        ],
        'App\Events\UserInvitedToIdea' => [
            'App\Listeners\SendUserNotificationEmail',
        ],
        'App\Events\UserRespondedToInvitation' => [
            'App\Listeners\SendResponseNotificationEmail',
        ],
        'App\Events\UserLikedIdeaFromNotification' => [
            'App\Listeners\SendLikedIdeaNotificationEmail',
        ],
        'App\Events\UserLeftComminity' => [
            'App\Listeners\DetachComminityIdeasFromUser',
        ],
        'App\Events\UserLeftingMunicipality' => [
            'App\Listeners\DetachMunicipalityIdeasFromUser',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
