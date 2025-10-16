@extends('layouts.app', ['title'=>'New Queue'])
@section('content')
<div style="max-width:800px;margin:2rem auto;font-family:system-ui">
  <h1>New Queue</h1>
  <form method="post" action="{{ route('queues.store') }}">
    @csrf
    <label>Name <input name="name" required></label>
    <label style="display:block;margin-top:8px">Filters (JSON)
      <textarea name="filters" rows="6" placeholder='{"lead_sources":[1,2], "office_ids":[1]}'></textarea>
    </label>
    <button type="submit" style="margin-top:10px">Save</button>
  </form>
</div>
@endsection
