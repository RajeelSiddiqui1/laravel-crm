<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SharedTask;
use App\Models\TeamLead;

class OperationTeamLeadAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sharedTask;
    public $teamLead;

    public function __construct(SharedTask $sharedTask, TeamLead $teamLead)
    {
        $this->sharedTask = $sharedTask;
        $this->teamLead = $teamLead;
    }

    public function build()
    {
        return $this->subject('You Have Been Assigned a New Operation Task')
                    ->markdown('emails.operation_teamlead_assigned');
    }
}
