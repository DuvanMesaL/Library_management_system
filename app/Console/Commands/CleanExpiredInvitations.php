<?php

namespace App\Console\Commands;

use App\Models\Invitation;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanExpiredInvitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invitations:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark expired invitations and clean old ones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning expired invitations...');

        // Mark expired invitations
        $expiredCount = Invitation::where('status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->update(['status' => 'expired']);

        $this->info("Marked {$expiredCount} invitations as expired.");

        // Delete very old expired invitations (older than 30 days)
        $deletedCount = Invitation::where('status', 'expired')
            ->where('expires_at', '<', Carbon::now()->subDays(30))
            ->delete();

        $this->info("Deleted {$deletedCount} old expired invitations.");

        $this->info('Invitation cleanup completed successfully.');

        return 0;
    }
}
