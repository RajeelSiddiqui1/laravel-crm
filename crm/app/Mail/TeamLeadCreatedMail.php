<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamLeadCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $teamLead;
    public $manager;

    public function __construct($teamLead, $manager)
    {
        $this->teamLead = $teamLead;
        $this->manager = $manager;
    }

    public function build()
    {
        return $this->subject('Welcome to the Project Team!')
                    ->markdown('emails.teamlead.created')
                    ->with([
                        'teamLead' => $this->teamLead,
                        'manager' => $this->manager,
                    ]);
    }
}
