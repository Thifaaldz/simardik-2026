<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\PruneExpiredDocuments;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        PruneExpiredDocuments::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('documents:prune-expired')->daily();
    }
}
