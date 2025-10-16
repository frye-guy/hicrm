@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">APIs (Connections)</h1>

  @if(session('status'))
    <div class="text-emerald-400 mb-3">{{ session('status') }}</div>
  @endif

  <form method="POST" action="{{ route('settings.apis.update') }}" class="card p-4 border border-slate-700/40 rounded space-y-4">
    @csrf

    <div class="field">
      <label>Google Maps API Key</label>
      <input name="google_maps_key" class="ring-brand" value="{{ old('google_maps_key',$googleMapsKey) }}">
      <p class="text-xs text-slate-400 mt-1">Used for server-side geocoding of contacts.</p>
    </div>

    <button class="btn btn-brand">Save</button>
  </form>
</div>
@endsection
