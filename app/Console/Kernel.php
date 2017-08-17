<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\RunQueue::class
    ];

    protected function schedule(Schedule $schedule)
    {
    }

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
