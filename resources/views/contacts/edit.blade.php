@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Edit Contact</h1>

    <form method="POST" action="{{ route('contacts.update', $contact) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="first_name">First Name</label>
                <input id="first_name" name="first_name" type="text" class="w-full rounded border px-3 py-2"
                       value="{{ old('first_name', $contact->first_name) }}">
                @error('first_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="last_name">Last Name</label>
                <input id="last_name" name="last_name" type="text" class="w-full rounded border px-3 py-2"
                       value="{{ old('last_name', $contact->last_name) }}">
                @error('last_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="phone">Phone</label>
                <input id="phone" name="phone" type="text" class="w-full rounded border px-3 py-2"
                       value="{{ old('phone', $contact->phone) }}">
                @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="email">Email</label>
                <input id="email" name="email" type="email" class="w-full rounded border px-3 py-2"
                       value="{{ old('email', $contact->email) }}">
                @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1" for="address">Address</label>
                <input id="address" name="address" type="text" class="w-full rounded border px-3 py-2"
                       value="{{ old('address', $contact->address) }}">
                @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="city">City</label>
                <input id="city" name="city" type="text" class="w-full rounded border px-3 py-2"
                       value="{{ old('city', $contact->city) }}">
                @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="state">State</label>
                <input id="state" name="state" type="text" class="w-full rounded border px-3 py-2"
                       value="{{ old('state', $contact->state) }}">
                @error('state') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="zip">ZIP</label>
                <input id="zip" name="zip" type="text" class="w-full rounded border px-3 py-2"
                       value="{{ old('zip', $contact->zip) }}">
                @error('zip') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Source dropdown: uses $leadSources --}}
            <div>
                <label class="block text-sm font-medium mb-1" for="lead_source_id">Source</label>
                <select id="lead_source_id" name="lead_source_id" class="w-full rounded border px-3 py-2">
                    <option value="">Select a source…</option>
                    @foreach($leadSources as $id => $name)
                        <option value="{{ $id }}" @selected(old('lead_source_id', $contact->lead_source_id) == $id)>{{ $name }}</option>
                    @endforeach
                </select>
                @error('lead_source_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">
                    Need to add or edit sources?
                    <a href="{{ route('lead-sources.index') }}" class="text-blue-600 hover:underline" target="_blank">Manage Lead Sources</a>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('contacts.show', $contact) }}" class="text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
