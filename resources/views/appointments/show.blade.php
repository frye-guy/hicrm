@extends('layouts.app', ['title'=>'Appointment'])
@section('content')
<div style="max-width:800px;margin:2rem auto;font-family:system-ui">
  <h1>Appointment</h1>
  <p><strong>When:</strong> {{ optional($appointment->scheduled_for)->toDayDateTimeString() }}</p>
  <p><strong>Duration:</strong> {{ $appointment->duration_min }} min</p>
  <p><strong>Contact:</strong> {{ $appointment->contact->first_name ?? '' }} {{ $appointment->contact->last_name ?? '' }} ({{ $appointment->contact->phone ?? '' }})</p>
  <p><strong>Set By:</strong> {{ $appointment->setter->name ?? '—' }}</p>
</div>
@endsection
