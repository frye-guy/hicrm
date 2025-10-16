@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">{{ $item->exists ? 'Edit Result Ran' : 'Add Result Ran' }}</h1>

  <form method="POST" action="{{ $item->exists ? route('settings.result-rans.update',$item) : route('settings.result-rans.store') }}"
        class="card p-4 border border-slate-700/40 rounded space-y-4">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="field">
      <label>Name</label>
      <input name="name" class="ring-brand" value="{{ old('name',$item->name) }}">
      @error('name') <div class="text-red-400 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="active" value="1" @checked(old('active',$item->active ?? true))> Active
    </label>

    <div>
      <button class="btn btn-brand">Save</button>
      <a href="{{ route('settings.result-rans.index') }}" class="btn btn-ghost">Cancel</a>
    </div>
  </form>
</div>
@endsection
