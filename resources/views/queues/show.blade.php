@extends('layouts.app', ['title'=>'Queue'])
@section('content')
<div style="max-width:800px;margin:2rem auto;font-family:system-ui">
  <h1>{{ $queue->name }}</h1>
  <pre style="background:#f7f7f7;padding:10px">{{ json_encode($queue->filters ?? [], JSON_PRETTY_PRINT) }}</pre>
</div>
@endsection
