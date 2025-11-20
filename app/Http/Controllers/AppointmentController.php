<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Auth::user()->appointments()->latest()->get();
        return view('student.dashboard', compact('appointments'));
    }

    public function create()
    {
        return view('student.appointment');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'full_name' => 'required|string',
            'branch' => 'required|string',
            'purpose' => 'required|array',
            'requested_date' => 'required|date',
        ]);

        Auth::user()->appointments()->create([
            'student_id' => $request->student_id,
            'full_name' => $request->full_name,
            'branch' => $request->branch,
            'purpose' => $request->purpose,
            'requested_date' => $request->requested_date,
            'status' => 'pending',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Appointment submitted successfully!');
    }
}
