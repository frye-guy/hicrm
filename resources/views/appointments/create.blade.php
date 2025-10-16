@extends('layouts.app', ['title'=>'New Appointment'])
@section('content')
<div style="max-width:800px;margin:2rem auto;font-family:system-ui">
  <h1>New Appointment</h1>
  <form method="post" action="{{ route('appointments.store') }}">
    @csrf
    <label>Contact
      <select name="contact_id" required>
        <option value="">-- choose --</option>
        @foreach($contacts as $c)
          <option value="{{ $c->id }}">{{ $c->last_name }}, {{ $c->first_name }} — {{ $c->phone }}</option>
        @endforeach
      </select>
    </label>
    <label style="display:block;margin-top:8px">Scheduled For
      <input type="datetime-local" name="scheduled_for" required>
    </label>
    <label style="display:block;margin-top:8px">Duration (min)
      <input type="number" name="duration_min" value="60" min="30" max="240" required>
    </label>
    <button type="submit" style="margin-top:12px">Save</button>
  </form>
  @if ($errors->any())
    <div style="color:#b00;margin-top:8px">
      <strong>Errors:</strong>
      <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif
</div>
@endsection
