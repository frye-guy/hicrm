@extends('layouts.app', ['title'=>'Import Contacts'])
@section('content')
<div style="max-width:640px;margin:2rem auto;font-family:system-ui">
  <h1>Import Contacts (CSV)</h1>
  <p>Columns supported: first_name,last_name,phone,email,address,city,state,zip</p>
  <form method="post" action="{{ route('admin.import.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv" accept=".csv,.txt" required>
    <button type="submit">Upload</button>
  </form>
  @if (session('status')) <p style="color:green">{{ session('status') }}</p> @endif
  @if ($errors->any())
    <div style="color:#b00;margin-top:8px">
      <strong>Errors:</strong>
      <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif
</div>
@endsection
