@extends('layouts.app', ['title'=>'Queues'])
@section('content')
<div style="max-width:960px;margin:2rem auto;font-family:system-ui">
  <h1>Queues</h1>
  <p><a href="{{ route('queues.create') }}">New Queue</a></p>
  <table border="1" cellpadding="6" cellspacing="0" width="100%">
    <thead><tr><th>Name</th><th>Created</th><th></th></tr></thead>
    <tbody>
      @forelse($queues as $q)
      <tr>
        <td><a href="{{ route('queues.show',$q) }}">{{ $q->name }}</a></td>
        <td>{{ $q->created_at->toDayDateTimeString() }}</td>
        <td><a href="{{ route('queues.edit',$q) }}">Edit</a></td>
      </tr>
      @empty
      <tr><td colspan="3">No queues yet.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div style="margin-top:1rem">{{ $queues->links() }}</div>
</div>
@endsection
