@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Lead Sources</h1>
        <a href="{{ route('lead-sources.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
           + Add New
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <table class="min-w-full border-collapse text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left p-2">Name</th>
                    <th class="text-left p-2">Short Code</th>
                    <th class="text-left p-2">Description</th>
                    <th class="text-left p-2">Type</th>
                    <th class="text-left p-2">Contact</th>
                    <th class="text-left p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sources as $src)
                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="p-2">{{ $src->name }}</td>
                        <td class="p-2">{{ $src->short_code ?? '-' }}</td>
                        <td class="p-2">{{ $src->description ?? '-' }}</td>
                        <td class="p-2">{{ $src->type_name ?? '-' }}</td>
                        <td class="p-2">{{ $src->contact ?? '-' }}</td>
                        <td class="p-2">
                            <div class="flex space-x-2">
                                <a href="{{ route('lead-sources.edit', $src->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('lead-sources.destroy', $src->id) }}" method="POST" onsubmit="return confirm('Delete this source?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 p-4">No lead sources found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
