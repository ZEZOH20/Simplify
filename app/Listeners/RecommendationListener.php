<?php

namespace App\Listeners;

use App\Events\AddFieldEvent;
use App\Events\RemoveFieldEvent;
use App\Http\Controllers\RecommendationController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use PHPUnit\Framework\Constraint\IsInstanceOf;

class RecommendationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        if($event instanceof AddFieldEvent || $event instanceof RemoveFieldEvent)
        {
           (new RecommendationController)->start();
        }
    }
}
