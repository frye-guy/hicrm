@extends('layouts.app', ['title'=>'New Appointment'])
@section('content')
<div style="max-width:800px;margin:2rem auto;font-family:system-ui">
  <h1>New Appointment</h1>
<form method="POST" action="{{ route('appointments.store') }}">
  @include('appointments._form', [
    'contact' => $contact,
    'resultRans' => $resultRans ?? ($resultRans ?? []),
    'results' => $results ?? ($results ?? []),
    'marketingUsers' => $marketingUsers ?? ($marketingUsers ?? []),
    'salesUsers' => $salesUsers ?? ($salesUsers ?? []),
  ])
  <div class="mt-5 flex items-center gap-3">
    <button type="submit" class="btn btn-brand">Save Appointment</button>
    <button type="button" class="btn btn-ghost" @click="newAppt=false">Cancel</button>
  </div>
</form>  @if ($errors->any())
    <div style="color:#b00;margin-top:8px">
      <strong>Errors:</strong>
      <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif
</div>
@endsection
