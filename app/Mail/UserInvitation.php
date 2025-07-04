<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        $registerUrl = route('register', ['token' => $this->invitation->token]);

        return $this->subject('Invitación al Sistema de Biblioteca')
            ->view('emails.invitation')
            ->with([
                'invitation' => $this->invitation,
                'registerUrl' => $registerUrl,
            ]);
    }
}
