<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function create()
    {
        return view('student.appointment');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'full_name' => 'required|string',
            'phone_number' => 'required|string|regex:/^[0-9]{10}$/',
            'address' => 'required|string',
            'purpose' => 'required|array|min:1',
        ]);

        Appointment::create([
            'student_id' => $request->student_id,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'branch' => $request->address, // Store in branch column for backward compatibility
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->route('appointment.create')->with('success', 'Appointment submitted successfully! The admin will assign a date and time slot shortly.');
    }
}
