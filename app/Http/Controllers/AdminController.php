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

        $appointments = Appointment::latest()->get();
        return view('admin.dashboard', compact('appointments'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'admin_time_slot' => 'required|string',
        ]);

        $appointment->update([
            'admin_time_slot' => $request->admin_time_slot,
            'status' => 'approved',
        ]);

        // Send SMS notification to student
        if ($appointment->phone_number) {
            $this->smsService->sendAppointmentConfirmation(
                $appointment->phone_number,
                $appointment->full_name,
                $appointment->requested_date->format('Y-m-d'),
                $request->admin_time_slot,
                $appointment->branch
            );
        }

        return back()->with('success', 'Time slot assigned successfully and SMS notification sent!');
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
