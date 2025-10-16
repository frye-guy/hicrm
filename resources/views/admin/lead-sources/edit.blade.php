@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">Edit Lead Source</h1>

    <form action="{{ route('lead-sources.update', $source->id) }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $source->name) }}" required
                   class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white" />
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Short Code</label>
            <input type="text" name="short_code" value="{{ old('short_code', $source->short_code) }}"
                   class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white" />
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Description</label>
            <textarea name="description" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white">{{ old('description', $source->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Type</label>
            <select name="type_id" required
                    class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white">
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ $source->type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Contact Person / Vendor</label>
            <input type="text" name="contact" value="{{ old('contact', $source->contact) }}"
                   class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white" />
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('lead-sources.index') }}"
               class="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
               Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                Update Lead Source
            </button>
        </div>
    </form>
</div>
@endsection
