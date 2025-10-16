@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">Add New Lead Source</h1>

    <form action="{{ route('lead-sources.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white" />
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Short Code</label>
            <input type="text" name="short_code" value="{{ old('short_code') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white" />
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Description</label>
            <textarea name="description" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Type</label>
            <select name="type_id" required
                    class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white">
                <option value="">Select Type</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Contact Person / Vendor</label>
            <input type="text" name="contact" value="{{ old('contact') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm p-2 dark:bg-gray-900 dark:text-white" />
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('lead-sources.index') }}"
               class="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
               Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                Save Lead Source
            </button>
        </div>
    </form>
</div>
@endsection
