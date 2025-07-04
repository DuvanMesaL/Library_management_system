<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process email queue every minute
        $schedule->command('email:process --timeout=30')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Clean expired invitations daily at 2 AM
        $schedule->command('invitations:clean')
                 ->dailyAt('02:00');

        // Process failed jobs every 5 minutes
        $schedule->command('queue:retry all')
                 ->everyFiveMinutes()
                 ->when(function () {
                     return DB::table('failed_jobs')->count() > 0;
                 });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
