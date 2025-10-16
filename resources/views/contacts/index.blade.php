@extends('layouts.app', ['title'=>'Contacts'])
@section('content')
<div style="max-width:1200px;margin:2rem auto;font-family:system-ui">
  <h1>Contacts</h1>
  <form method="get" class="mb-2">
    <input type="text" name="s" value="{{ $s ?? '' }}" placeholder="Search name/phone/email">
    <button type="submit">Search</button>
    <a href="{{ route('contacts.create') }}">New Contact</a>
  </form>
  <table border="1" cellpadding="6" cellspacing="0" width="100%">
    <thead><tr><th>Name</th><th>Phone</th><th>Email</th><th>City/State</th><th></th></tr></thead>
    <tbody>
      @forelse($contacts as $c)
      <tr>
        <td><a href="{{ route('contacts.show',$c) }}">{{ $c->first_name }} {{ $c->last_name }}</a></td>
        <td>{{ $c->phone }}</td>
        <td>{{ $c->email }}</td>
        <td>{{ $c->city }}, {{ $c->state }}</td>
        <td><a href="{{ route('contacts.edit',$c) }}">Edit</a></td>
      </tr>
      @empty
      <tr><td colspan="5">No contacts yet.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div style="margin-top:1rem">{{ $contacts->links() }}</div>
</div>
@endsection
