<?php

namespace App\Providers;

use App\Events\AddFieldEvent;
use App\Events\ChangeStatusEvent;
use App\Events\RemoveFieldEvent;
use App\Events\StudentRegisterCourseEvent;
use App\Events\StudentUnRegisterCourseEvent;
use App\Listeners\CalcGpaAndProgressListener;
use App\Listeners\RecommendationListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ChangeStatusEvent::class => [
            CalcGpaAndProgressListener::class,
        ],
        StudentRegisterCourseEvent::class => [
            CalcGpaAndProgressListener::class,
        ],
        StudentUnRegisterCourseEvent::class => [
            CalcGpaAndProgressListener::class,
        ],
        AddFieldEvent::class => [
            CalcGpaAndProgressListener::class,
            RecommendationListener::class,
        ],
        RemoveFieldEvent::class =>[
            RecommendationListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
