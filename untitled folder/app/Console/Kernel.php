<?php

namespace App\Console;

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
        //
        Commands\CampaignScheduler::class,
        Commands\ABTestScheduler::class,
        Commands\CheckForActiveSequence::class,
        Commands\CheckForPendingEmail::class,
        Commands\ImmediateCampaign::class,
        Commands\FeedToCampaign::class,
        Commands\SingleFeedToCampaign::class,
        Commands\DigestFeedToCampaign::class,
        Commands\PushLater::class,
        Commands\SmsCampaignScheduler::class,
        Commands\ImmediateSmsCampaign::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('command:pushlater')->everyMinute();
        $schedule->command('campaign:schedule')->everyMinute();
        $schedule->command('abtest:subject')->everyMinute();
        $schedule->command('active:sequence')->everyMinute();
        $schedule->command('pending:email')->everyMinute();
        $schedule->command('campaign:immediately')->everyMinute();
        $schedule->command('feed:campaign')->everyMinute();
        $schedule->command('feedsingle:campaign')->everyMinute();
        $schedule->command('feeddigest:campaign')->hourly();
        $schedule->command('smscampaign:schedule')->everyMinute();
        $schedule->command('smscampaign:immediately')->everyMinute();
        
        // $schedule->command('feeddigest:campaign')->everyMinute();
        // $schedule->command('feed:campaign')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
