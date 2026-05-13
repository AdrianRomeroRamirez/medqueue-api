<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Http\Requests\StoreAppointmentRequest;
use App\Jobs\SendAppointmentConfirmation;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->isDoctor()
            ? Appointment::where('doctor_id', $user->id)->with('patient')
            : Appointment::where('patient_id', $user->id)->with('doctor');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('date')->orderBy('time')->get());
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = Appointment::create([
            ...$request->validated(),
            'patient_id' => $request->user()->id,
        ]);

        $appointment->load('patient', 'doctor');

        SendAppointmentConfirmation::dispatch($appointment);

        return response()->json($appointment, 201);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment->load('patient', 'doctor'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes'  => 'nullable|string',
        ]);

        $appointment->update($request->only('status', 'notes'));

        return response()->json($appointment->fresh()->load('patient', 'doctor'));
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted']);
    }

    public function doctors()
    {
        $doctors = User::where('role', 'doctor')->select('id', 'name', 'email')->get();

        return response()->json($doctors);
    }
}