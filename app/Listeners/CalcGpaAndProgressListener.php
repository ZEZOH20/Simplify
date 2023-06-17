<?php

namespace App\Listeners;

use App\Events\ChangeStatusEvent;
use App\Events\StudentRegisterCourseEvent;
use App\Events\StudentUnRegisterCourseEvent;
use App\Classes\GpaCalculator;
use App\Classes\StudentFieldProgress;
use App\Events\AddFieldEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalcGpaAndProgressListener
{
    use GpaCalculator,StudentFieldProgress;
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
    public function handle($event): void
    {
        if (
            $event instanceof StudentRegisterCourseEvent ||
            $event instanceof ChangeStatusEvent ||
            $event instanceof StudentUnRegisterCourseEvent|| 
            $event instanceof AddFieldEvent
        ) {
            //calc GPA
            GpaCalculator::automatedCalcGPA();
            //measure progress   
            StudentFieldProgress::automatedCheckProgress();
        }
    }
  
}
