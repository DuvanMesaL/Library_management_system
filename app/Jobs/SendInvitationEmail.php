<?php

namespace App\Jobs;

use App\Models\Invitation;
use App\Mail\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendInvitationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invitation;

    /**
     * Create a new job instance.
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->invitation->email)->send(new UserInvitation($this->invitation));

            Log::info('Invitation email sent successfully', [
                'invitation_id' => $this->invitation->id,
                'email' => $this->invitation->email,
                'role' => $this->invitation->role->name
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send invitation email', [
                'invitation_id' => $this->invitation->id,
                'email' => $this->invitation->email,
                'error' => $e->getMessage()
            ]);

            // Re-throw the exception to mark the job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Invitation email job failed permanently', [
            'invitation_id' => $this->invitation->id,
            'email' => $this->invitation->email,
            'error' => $exception->getMessage()
        ]);
    }
}
