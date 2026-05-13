<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isPatient();
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:users,id',
            'date'      => 'required|date|after:today',
            'time'      => 'required|date_format:H:i',
            'reason'    => 'required|string|max:500',
            'notes'     => 'nullable|string',
        ];
    }
}
