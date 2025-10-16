{{-- resources/views/appointments/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">
            {{ $title ?? 'Edit Appointment' }}
        </h1>

        <a href="{{ route('contacts.show', $appointment->contact_id) }}"
           class="inline-flex items-center px-3 py-2 rounded border bg-white hover:bg-gray-50 text-sm">
           Back to Contact
        </a>
    </div>

<form method="POST" action="{{ route('appointments.update', $appointment) }}" class="bg-white border rounded-lg p-5 space-y-6">
  @include('appointments._form', [
    'appointment' => $appointment,
    'method' => 'PUT',
    'resultRans' => $resultRans ?? [],
    'results' => $results ?? [],
    'marketingUsers' => $marketingUsers ?? [],
    'salesUsers' => $salesUsers ?? [],
  ])

  <div class="flex items-center gap-3 pt-2">
    <button class="inline-flex items-center px-4 py-2 rounded text-white" style="background:#111827">Save</button>
    <a href="{{ route('contacts.show', $appointment->contact_id) }}" class="text-sm underline">Cancel</a>
  </div>
</form>
</div>
@endsection
