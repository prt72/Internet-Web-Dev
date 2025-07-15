<?php

namespace App\Console\Commands\Log;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the Laravel log file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if the log file exists
        $logFilePath = storage_path('logs/laravel.log');
        
        if (File::exists($logFilePath)) {
            // Clear the log file
            File::put($logFilePath, '');
            $this->info('Logs have been cleared successfully.');
        } else {
            $this->error('Log file does not exist.');
        }
    }
}
