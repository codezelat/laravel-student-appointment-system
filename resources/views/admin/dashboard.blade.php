@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
        
        <!-- Status Filter Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('admin.dashboard', ['status' => 'all']) }}" 
               class="px-4 py-2 text-sm font-medium rounded-md {{ request('status', 'all') === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                All Appointments
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" 
               class="px-4 py-2 text-sm font-medium rounded-md {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Pending
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'approved']) }}" 
               class="px-4 py-2 text-sm font-medium rounded-md {{ request('status') === 'approved' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Approved
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment Details</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($appointments as $index => $appointment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $appointment->full_name }}</div>
                        <div class="text-sm text-gray-500">{{ $appointment->student_id }}</div>
                        <div class="text-sm text-gray-500">{{ $appointment->phone_number }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <ul class="list-disc list-inside">
                            @foreach($appointment->purpose as $purpose)
                                <li>{{ $purpose }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($appointment->status === 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Approved
                            </span>
                            <div class="mt-1 text-xs text-gray-600">
                                <div>{{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : 'N/A' }}</div>
                                <div>{{ $appointment->time_slot }}</div>
                            </div>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        @if($appointment->status === 'approved')
                            <!-- Show assigned appointment details as read-only -->
                            <div class="text-xs text-gray-600">
                                <div class="font-medium text-gray-900">{{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : 'N/A' }}</div>
                                <div class="text-gray-500">{{ $appointment->time_slot }}</div>
                                <div class="mt-1 text-green-600 font-medium">✓ Assigned</div>
                            </div>
                        @else
                            <!-- Show scheduling form for pending appointments -->
                            <form action="{{ route('admin.update', $appointment) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Date</label>
                                    <input type="date" name="appointment_date" value="{{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : '' }}" min="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs p-1.5 border" required>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Time Range</label>
                                    <div class="flex items-center space-x-1">
                                        <input type="time" name="start_time" id="start_time_{{ $appointment->id }}" value="{{ $appointment->time_slot ? explode(' - ', $appointment->time_slot)[0] ?? '' : '' }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs p-1.5 border" required>
                                        <span class="text-gray-500 text-xs">to</span>
                                        <input type="time" name="end_time" id="end_time_{{ $appointment->id }}" value="{{ $appointment->time_slot && count(explode(' - ', $appointment->time_slot)) > 1 ? explode(' - ', $appointment->time_slot)[1] : '' }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs p-1.5 border" required>
                                    </div>
                                </div>
                                <input type="hidden" name="time_slot" id="time_slot_{{ $appointment->id }}">
                                <button type="submit" onclick="combineTimeSlot({{ $appointment->id }})" class="w-full bg-indigo-600 text-white px-2 py-1 rounded text-xs hover:bg-indigo-700">Assign</button>
                            </form>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form action="{{ route('admin.delete', $appointment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No appointments to manage.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($appointments->hasPages())
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
    @endif
</div>

<script>
function combineTimeSlot(appointmentId) {
    const startTime = document.getElementById('start_time_' + appointmentId).value;
    const endTime = document.getElementById('end_time_' + appointmentId).value;
    
    if (startTime && endTime) {
        // Convert 24-hour format to 12-hour format with AM/PM
        const startFormatted = formatTime(startTime);
        const endFormatted = formatTime(endTime);
        
        // Combine into time slot format
        const timeSlot = startFormatted + ' - ' + endFormatted;
        document.getElementById('time_slot_' + appointmentId).value = timeSlot;
    }
}

function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return hour12 + ':' + minutes + ' ' + ampm;
}

// Auto-set end time to 30 minutes after start time when start time changes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="time"][name="start_time"]').forEach(function(startInput) {
        startInput.addEventListener('change', function() {
            const appointmentId = this.id.replace('start_time_', '');
            const endInput = document.getElementById('end_time_' + appointmentId);
            
            if (this.value && !endInput.value) {
                const [hours, minutes] = this.value.split(':');
                const date = new Date();
                date.setHours(parseInt(hours));
                date.setMinutes(parseInt(minutes) + 30);
                
                const endHours = String(date.getHours()).padStart(2, '0');
                const endMinutes = String(date.getMinutes()).padStart(2, '0');
                endInput.value = endHours + ':' + endMinutes;
            }
        });
    });
});
</script>
@endsection
