@extends('layouts.app', ['title'=>'Settings'])
@section('content')
<div style="max-width:720px;margin:2rem auto;font-family:system-ui">
  <h1>Settings</h1>
  <form method="post" action="{{ route('settings.update') }}">
    @csrf
    <label>Brand Name <input name="brand_name" value="{{ $settings['brand_name'] ?? '' }}"></label>
    <label style="display:block;margin-top:8px">3CX Link Template
      <input name="dial_template" value="{{ $settings['dial_template'] ?? 'callto:{E164}' }}" placeholder="https://pbx.example.com/webclient/#/call/{E164}">
    </label>
    <button type="submit" style="margin-top:10px">Save</button>
  </form>
  @if (session('status')) <p style="color:green">{{ session('status') }}</p> @endif
</div>
@endsection
