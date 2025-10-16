@extends('layouts.app', ['title'=>'Call Report'])
@section('content')
  <div style="max-width:1200px;margin:2rem auto;font-family:system-ui">
    <h1>Call Report</h1>
    <table border="1" cellpadding="6" cellspacing="0">
      <thead><tr><th>User</th><th>Day</th><th>Calls</th><th>Disposed</th><th>Talk Sec</th></tr></thead>
      <tbody>
        @forelse($rows as $r)
        <tr>
          <td>{{ $r->name }}</td>
          <td>{{ $r->day }}</td>
          <td>{{ $r->calls }}</td>
          <td>{{ $r->disposed }}</td>
          <td>{{ $r->talk_seconds }}</td>
        </tr>
        @empty
        <tr><td colspan="5">No data yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
