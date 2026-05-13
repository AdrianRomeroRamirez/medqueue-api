<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendAppointmentConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function handle(): void
    {
        Log::info('Appointment confirmation sent', [
            'appointment_id' => $this->appointment->id,
            'patient'        => $this->appointment->patient->name,
            'doctor'         => $this->appointment->doctor->name,
            'date'           => $this->appointment->date,
            'time'           => $this->appointment->time,
        ]);
    }
}