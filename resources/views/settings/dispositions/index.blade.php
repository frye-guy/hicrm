@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Dispositions</h1>
    <a href="{{ route('settings.dispositions.create') }}" class="btn btn-brand">Add</a>
  </div>

  @if(session('status'))
    <div class="text-emerald-400 mb-3">{{ session('status') }}</div>
  @endif

  <div class="card p-4 border border-slate-700/40 rounded">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-slate-300">
          <th class="py-2">Name</th>
          <th>Color</th>
          <th>Positive</th>
          <th>Final</th>
          <th>Active</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $d)
          <tr class="border-t border-slate-700/40">
            <td class="py-2">{{ $d->name }}</td>
            <td><span class="inline-block w-5 h-5 rounded" style="background: {{ $d->row_color }}"></span></td>
            <td>{{ $d->is_positive ? 'Yes' : 'No' }}</td>
            <td>{{ $d->is_final ? 'Yes' : 'No' }}</td>
            <td>{{ $d->active ? 'Yes' : 'No' }}</td>
            <td class="text-right">
              <a href="{{ route('settings.dispositions.edit', $d) }}" class="text-blue-400 hover:underline">Edit</a>
              <form method="POST" action="{{ route('settings.dispositions.destroy',$d) }}" class="inline"
                    onsubmit="return confirm('Delete this disposition?')">
                @csrf @method('DELETE')
                <button class="text-red-400 hover:underline ml-2">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="py-8 text-center text-slate-400">No dispositions yet.</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-3">{{ $items->links() }}</div>
  </div>
</div>
@endsection
