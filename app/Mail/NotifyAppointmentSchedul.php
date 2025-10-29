<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyAppointmentSchedul extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $appointment;
    protected $patient;
    protected $doctor;
    protected $appointment_slot;
    public function __construct($appointment)
    {
        $this->appointment=$appointment;
        $this->patient = $this->appointment->patient;
        $this->doctor = $this->appointment->doctor;
        $this->appointment_slot = $this->appointment->appointmentSlot;

    }



    public function build()
    {
        return $this->view('email-templates.notify-patient-appointment', 
        ['appointment' => $this->appointment, 'patient' => $this->patient, 
        'doctor' => $this->doctor, 'appointment_slot'=>$this->appointment_slot])->subject('Appointment Schedule Notification'); ; ;
    }
  
}
