<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($request->username === 'sitcadmin' && $request->password === 'password') {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['username' => 'Invalid credentials']);
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    public function index()
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $appointments = Appointment::latest()->paginate(25);
        return view('admin.dashboard', compact('appointments'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Prevent updating already approved appointments
        if ($appointment->status === 'approved') {
            return back()->with('error', 'This appointment has already been scheduled and cannot be modified.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
        ]);

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'time_slot' => $request->time_slot,
            'status' => 'approved',
        ]);

        // Send SMS notification to student
        if ($appointment->phone_number) {
            $this->smsService->sendAppointmentConfirmation(
                $appointment->phone_number,
                $appointment->full_name,
                $request->appointment_date,
                $request->time_slot,
                $appointment->branch
            );
        }

        return back()->with('success', 'Appointment date and time assigned successfully! SMS notification sent.');
    }

    public function destroy(Appointment $appointment)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $appointment->delete();

        return back()->with('success', 'Appointment deleted successfully!');
    }
}
