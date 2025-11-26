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
            'branch' => 'required|string',
            'purpose' => 'required|array',
            'requested_date' => 'required|date',
        ]);

        Appointment::create([
            'student_id' => $request->student_id,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'branch' => $request->branch,
            'purpose' => $request->purpose,
            'requested_date' => $request->requested_date,
            'status' => 'pending',
        ]);

        return redirect()->route('appointment.create')->with('success', 'Appointment submitted successfully!');
    }
}
