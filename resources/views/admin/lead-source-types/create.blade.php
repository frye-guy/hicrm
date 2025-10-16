@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">Add Lead Source Type</h1>

    <form action="{{ route('lead-source-types.store') }}" method="POST"
          class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-5">
        @csrf

        @if ($errors->any())
            <div class="p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-sm font-semibold mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white" />
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('lead-source-types.index') }}"
               class="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
               Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                Save Type
            </button>
        </div>
    </form>
</div>
@endsection
