@extends('layouts.app', ['title'=>'Edit Queue'])
@section('content')
<div style="max-width:800px;margin:2rem auto;font-family:system-ui">
  <h1>Edit Queue</h1>
  <form method="post" action="{{ route('queues.update',$queue) }}">
    @csrf @method('PUT')
    <label>Name <input name="name" value="{{ old('name',$queue->name) }}" required></label>
    <label style="display:block;margin-top:8px">Filters (JSON)
      <textarea name="filters" rows="6">{{ old('filters', json_encode($queue->filters ?? [], JSON_PRETTY_PRINT)) }}</textarea>
    </label>
    <button type="submit" style="margin-top:10px">Save</button>
  </form>
</div>
@endsection
