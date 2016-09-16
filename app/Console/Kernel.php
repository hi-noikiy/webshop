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
        Commands\createUser::class,
        Commands\setAdmin::class,
        Commands\checkRelatedProducts::class,
        Commands\checkProductImages::class,
        Commands\importProducts::class,
        Commands\importDiscounts::class,
        Commands\resendOrder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('import:products')->everyTenMinutes();
        $schedule->command('import:discounts')->everyTenMinutes();
    }
}
