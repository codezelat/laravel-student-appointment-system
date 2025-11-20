@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($appointments->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($appointments as $appointment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $appointment->full_name }}</div>
                        <div class="text-sm text-gray-500">{{ $appointment->student_id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $appointment->branch }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <ul class="list-disc list-inside">
                            @foreach($appointment->purpose as $purpose)
                                <li>{{ $purpose }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->requested_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form action="{{ route('admin.update', $appointment) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="admin_time_slot" value="{{ $appointment->admin_time_slot }}" placeholder="e.g. 10:00 AM - 10:30 AM" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-1 border" required>
                            <button type="submit" class="text-indigo-600 hover:text-indigo-900">Assign</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="p-6 text-center text-gray-500">
                No appointments to manage.
            </div>
        @endif
    </div>
</div>
@endsection
