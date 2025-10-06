<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $manager;

    public function __construct($employee, $manager)
    {
        $this->employee = $employee;
        $this->manager = $manager;
    }

    public function build()
    {
        return $this->subject('Welcome to the Team!')
                    ->markdown('emails.employee.created')
                    ->with([
                        'employee' => $this->employee,
                        'manager' => $this->manager,
                    ]);
    }
}
