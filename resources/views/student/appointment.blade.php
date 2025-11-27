@extends('layouts.app')

@section('content')
<!-- <div class="text-center mb-8">
    <h1 class="text-4xl font-bold text-indigo-600 mb-2">SITC Appointment Portal</h1>
    <p class="text-gray-600">Book your appointment with SITC Campus</p>
</div> -->

<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Book Appointment</h2>
    <form method="POST" action="{{ route('appointment.store') }}">
        @csrf
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                <input type="text" name="student_id" id="student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border" required>
            </div>

            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border" required>
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="tel" name="phone_number" id="phone_number" placeholder="Phone Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border" required pattern="[0-9]{10}">
                <p class="mt-1 text-xs text-gray-500">Enter 10 digit phone number (e.g., 07xxxxxxxx)</p>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 p-2 border text-sm text-gray-700">
                    World Trade Center, Level 26, East Tower, Colombo 01
                </div>
                <input type="hidden" name="address" value="World Trade Center, Level 26, East Tower, Colombo 01">
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-700 mb-2">Purpose</span>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input id="purpose_copy" name="purpose[]" value="Collect True Copy" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="purpose_copy" class="ml-2 block text-sm text-gray-900">Collect True Copy</label>
                    </div>
                    <div class="flex items-center">
                        <input id="purpose_cert" name="purpose[]" value="Collect Certificate" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="purpose_cert" class="ml-2 block text-sm text-gray-900">Collect Certificate</label>
                    </div>
                    <div class="flex items-center">
                        <input id="purpose_photos" name="purpose[]" value="Collect Photos" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="purpose_photos" class="ml-2 block text-sm text-gray-900">Collect Photos</label>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                <p class="text-sm text-blue-700">
                    <span class="font-semibold">Note:</span> The admin will assign a date and time slot for your appointment after review and after approval you will get a SMS.
                </p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection
