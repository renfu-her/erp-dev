<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Run monthly on the 10th at 02:00 server time
        $schedule->command('payroll:settle')->monthlyOn(10, '2:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}


