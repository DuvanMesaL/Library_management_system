<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProcessEmailQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:process {--timeout=60}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the email queue for sending invitations and notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeout = $this->option('timeout');

        $this->info('Starting email queue processing...');
        $this->info("Timeout set to: {$timeout} seconds");

        try {
            Artisan::call('queue:work', [
                '--queue' => 'default',
                '--timeout' => $timeout,
                '--tries' => 3,
                '--delay' => 3,
                '--stop-when-empty' => true,
            ]);

            $this->info('Email queue processing completed successfully.');
            $this->line(Artisan::output());
        } catch (\Exception $e) {
            $this->error('Error processing email queue: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
