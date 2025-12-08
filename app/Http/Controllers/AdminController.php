<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        // Get credentials from config (which reads from .env)
        $adminUsername = config('admin.username');
        $adminPassword = config('admin.password');

        // Verify username and password
        if ($request->username === $adminUsername && $request->password === $adminPassword) {
            Session::put('admin_logged_in', true);
            return redirect()->route('sitc-admin.dashboard');
        }

        return back()->withErrors(['username' => 'Invalid credentials']);
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('sitc-admin.login');
    }

    public function index(Request $request)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('sitc-admin.login');
        }

        $status = $request->get('status', 'all');
        
        $query = Appointment::query();
        
        // Filter by status
        if ($status === 'pending') {
            $query->where('status', 'pending')->latest();
        } elseif ($status === 'approved') {
            $query->where('status', 'approved')->orderBy('appointment_date', 'desc')->orderBy('time_slot', 'asc');
        } else {
            // Show all, with pending first, then approved ordered by date and time
            $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
                  ->orderBy('appointment_date', 'desc')
                  ->orderBy('time_slot', 'asc')
                  ->orderBy('created_at', 'desc');
        }
        
        $appointments = $query->paginate(25)->appends(['status' => $status]);
        
        return view('admin.dashboard', compact('appointments', 'status'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('sitc-admin.login');
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
                $appointment->address
            );
        }

        return back()->with('success', 'Appointment date and time assigned successfully! SMS notification sent.');
    }

    public function destroy(Appointment $appointment)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('sitc-admin.login');
        }

        $appointment->delete();

        return back()->with('success', 'Appointment deleted successfully!');
    }
}
