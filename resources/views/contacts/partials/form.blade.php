@php $c = $contact ?? null; @endphp
<div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px">
  <label>First Name <input name="first_name" value="{{ old('first_name', $c->first_name ?? '') }}" required></label>
  <label>Last Name  <input name="last_name"  value="{{ old('last_name',  $c->last_name ?? '') }}" required></label>
  <label>Phone      <input name="phone"      value="{{ old('phone',      $c->phone ?? '') }}" required></label>
  <label>Email      <input name="email"      value="{{ old('email',      $c->email ?? '') }}"></label>
  <label>Address    <input name="address"    value="{{ old('address',    $c->address ?? '') }}"></label>
  <label>City       <input name="city"       value="{{ old('city',       $c->city ?? '') }}"></label>
  <label>State      <input name="state"      value="{{ old('state',      $c->state ?? '') }}"></label>
  <label>Zip        <input name="zip"        value="{{ old('zip',        $c->zip ?? '') }}"></label>
</div>
@if ($errors->any())
  <div style="color:#b00;margin-top:8px">
    <strong>Errors:</strong>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif
