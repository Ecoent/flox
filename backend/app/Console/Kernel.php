<?php

namespace App\Console;

use App\Console\Commands\Daily;
use App\Console\Commands\Refresh;
use App\Setting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      Commands\Init::class,
      Commands\DB::class,
      Refresh::class,
      Daily::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      if(app()->runningUnitTests()) {
        return null;
      }
      
      $settings = Setting::first();
      
      if ($settings->refresh_automatically) {
        $schedule->command(Refresh::class)->dailyAt('06:00');
      }
      
      if ($settings->daily_reminder) {
        $schedule->command(Daily::class)->dailyAt('07:00');
      }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
