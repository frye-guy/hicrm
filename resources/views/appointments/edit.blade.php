@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Edit Appointment</h1>

    <form method="POST" action="{{ route('appointments.update', $appointment) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Appt Date/Time</label>
            <input type="datetime-local" name="scheduled_for"
                   value="{{ old('scheduled_for', optional($appointment->scheduled_for)->format('Y-m-d\TH:i')) }}"
                   class="border rounded px-3 py-2 w-full">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Set By</label>
                <input name="set_by" value="{{ old('set_by', $appointment->set_by) }}" class="border rounded px-3 py-2 w-full">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Set With</label>
                <input name="set_with" value="{{ old('set_with', $appointment->set_with) }}" class="border rounded px-3 py-2 w-full">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Interested In</label>
                <input name="interested_in" value="{{ old('interested_in', $appointment->interested_in) }}" class="border rounded px-3 py-2 w-full">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Result</label>
                <input name="result" value="{{ old('result', $appointment->result) }}" class="border rounded px-3 py-2 w-full">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Notes</label>
            <textarea name="notes" rows="6" class="border rounded px-3 py-2 w-full">{{ old('notes', $appointment->notes) }}</textarea>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('contacts.show', $appointment->contact_id) }}" class="px-4 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
    </form>
</div>
@endsection
