@extends('layouts.app', ['title'=>'Appointments Map'])
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<div id="map" style="height:480px;margin:16px auto;max-width:1200px;"></div>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  const map = L.map('map').setView([39.8,-86.1], 7);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
  @foreach(\App\Models\Appointment::with('contact')->latest()->take(100)->get() as $a)
    @if(($a->contact->lat ?? null) && ($a->contact->lng ?? null))
      L.marker([{{ $a->contact->lat }}, {{ $a->contact->lng }}])
        .addTo(map)
        .bindPopup(`{{ $a->contact->first_name }} {{ $a->contact->last_name }}<br>{{ \Illuminate\Support\Carbon::parse($a->scheduled_for)->toDayDateTimeString() }}`);
    @endif
  @endforeach
</script>
@endsection
