{{-- resources/views/contacts/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div x-data="{ newAppt:false, noteOpen:false }">
  {{-- Header --}}
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">{{ $contact->first_name ?? '' }} {{ $contact->last_name ?? '' }}</h1>
      <p class="text-sm text-gray-400">
        ID: <span class="font-mono">{{ $contact->id }}</span>
        &nbsp;&nbsp;
        <span class="text-gray-500">Source:</span>
        <span class="text-gray-100 font-semibold">{{ optional($contact->leadSource)->name ?? '' }}</span>
      </p>
    </div>
    @isset($contact->needs_reset)
      <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold"
            style="background:#f59e0b; color:#111827">
        {{ $contact->needs_reset ? 'Needs Reset' : 'OK' }}
      </span>
    @endisset
  </div>

  {{-- Contact form --}}
  <form method="POST" action="{{ route('contacts.update',$contact) }}"
        class="card border border-slate-700/40 p-5 rounded-xl mb-8">
    @csrf @method('PUT')

    <div class="grid grid-cols-12 gap-3">
      <div class="col-span-12 md:col-span-3">
        <label class="block text-sm text-gray-400 mb-1">Phone *</label>
        <input name="phone" value="{{ old('phone',$contact->phone ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>

      {{-- Dispo button opens modal --}}
      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">Dispo</label>
        <button type="button" class="inline-flex items-center justify-center w-full px-3 py-2 rounded font-semibold"
                style="background:#38bdf8; color:#041018"
                @click="noteOpen = true">
          Add / Update Disposition
        </button>
      </div>

      <div class="col-span-12 md:col-span-3">
        <label class="block text-sm text-gray-400 mb-1">Source</label>
        <select name="lead_source_id"
                class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
          <option value="">Select</option>
          @foreach(($leadSources ?? []) as $source)
            <option value="{{ $source->id }}" @selected(old('lead_source_id',$contact->lead_source_id) == $source->id)>
              {{ $source->name }}
            </option>
          @endforeach
        </select>
        @error('lead_source_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
      </div>

      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">Record ID</label>
        <input value="{{ $contact->id }}" disabled
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>

      <div class="col-span-12 md:col-span-2 flex items-end">
        <label class="sr-only">Needs Reset</label>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="needs_reset" value="1"
                 @checked(old('needs_reset',$contact->needs_reset ?? false))>
          <span class="text-gray-400 text-sm">Needs Reset</span>
        </div>
      </div>
    </div>

    {{-- names --}}
    <div class="grid grid-cols-12 gap-3 mt-4">
      <div class="col-span-12 md:col-span-3">
        <label class="block text-sm text-gray-400 mb-1">First Name</label>
        <input name="first_name" value="{{ old('first_name',$contact->first_name ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-3">
        <label class="block text-sm text-gray-400 mb-1">Spouse</label>
        <input name="spouse" value="{{ old('spouse',$contact->spouse ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-3">
        <label class="block text-sm text-gray-400 mb-1">Last Name</label>
        <input name="last_name" value="{{ old('last_name',$contact->last_name ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-3">
        <label class="block text-sm text-gray-400 mb-1">Email</label>
        <input name="email" type="email" value="{{ old('email',$contact->email ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
    </div>

    {{-- address --}}
    <div class="grid grid-cols-12 gap-3 mt-4">
      <div class="col-span-12 md:col-span-6">
        <label class="block text-sm text-gray-400 mb-1">Address</label>
        <input name="address" value="{{ old('address',$contact->address ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-3">
        <label class="block text-sm text-gray-400 mb-1">City</label>
        <input name="city" value="{{ old('city',$contact->city ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">State</label>
        <select name="state" class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
          @php($st = old('state',$contact->state ?? ''))
          @foreach(($states ?? ['AL','AK','AZ','AR','CA','CO','CT','DC','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY']) as $state)
            <option value="{{ $state }}" @selected($st==$state)>{{ $state }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-span-12 md:col-span-1">
        <label class="block text-sm text-gray-400 mb-1">Zip</label>
        <input name="zip" value="{{ old('zip',$contact->zip ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
    </div>

    {{-- misc --}}
    <div class="grid grid-cols-12 gap-3 mt-4">
      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">Mr Works</label>
        <input name="mr_works" value="{{ old('mr_works',$contact->mr_works ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">Mrs Works</label>
        <input name="mrs_works" value="{{ old('mrs_works',$contact->mrs_works ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">Alt Phone</label>
        <input name="alt_phone" value="{{ old('alt_phone',$contact->alt_phone ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">Alt Phone 2</label>
        <input name="alt_phone2" value="{{ old('alt_phone2',$contact->alt_phone2 ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">Alt Phone 3</label>
        <input name="alt_phone3" value="{{ old('alt_phone3',$contact->alt_phone3 ?? '') }}"
               class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
      </div>
      <div class="col-span-12 md:col-span-2">
        <label class="block text-sm text-gray-400 mb-1">Search Tool</label>
        <select name="search_tool"
                class="w-full bg-[#0b1220] border border-slate-600/40 rounded p-2">
          @php($tool = old('search_tool',$contact->search_tool ?? ''))
          @foreach(($searchTools ?? ['Select Tool','Google','Bing','Maps','County GIS']) as $t)
            <option value="{{ $t }}" @selected($tool==$t)>{{ $t }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
      <button type="submit" class="inline-flex items-center px-3 py-2 rounded font-semibold"
              style="background:#38bdf8; color:#041018">Save</button>

      @if (session('status'))
        <span class="text-sm text-emerald-400">{{ session('status') }}</span>
      @endif
      @if ($errors->any())
        <span class="text-sm text-red-400">Please fix the highlighted errors.</span>
      @endif
    </div>
  </form>

  {{-- Appointments --}}
  <section class="card border border-slate-700/40 p-5 rounded-xl">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-semibold">Appointments</h2>
      <button class="inline-flex items-center px-3 py-2 rounded font-semibold"
              style="background:#38bdf8; color:#041018"
              @click="newAppt = true">
        New Appointment
      </button>
    </div>

    <div class="overflow-x-auto mt-4">
      <table class="table w-full text-sm">
        <thead>
          <tr>
            <th>Action</th>
            <th>Appt Date</th>
            <th>Set By</th>
            <th>Set With</th>
            <th>Date Set</th>
            <th>Interested In</th>
            <th>Result</th>
          </tr>
        </thead>
        <tbody>
          @forelse(($appointments ?? []) as $appt)
            <tr class="align-top">
              <td class="whitespace-nowrap">
                <div class="flex gap-2">
                  <a href="{{ route('appointments.edit',$appt) }}" class="btn btn-xs btn-success">View/Edit</a>
                  <form method="POST" action="{{ route('appointments.destroy',$appt) }}"
                        onsubmit="return confirm('Delete this appointment?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                  </form>
                </div>
              </td>
              <td class="whitespace-nowrap">{{ optional($appt->scheduled_for)->format('m/d/Y h:i a') }}</td>
              <td>{{ $appt->set_by_name ?? $appt->createdBy->name ?? '' }}</td>
              <td>{{ $appt->set_with_name ?? '' }}</td>
              <td class="whitespace-nowrap">{{ optional($appt->created_at)->format('m/d/Y h:i:s A') }}</td>
              <td>{{ $appt->interested_in ?? '' }}</td>
              <td>
                {{-- show both Result (Ran) and Confirmation Result if present --}}
                @if(!empty($appt->resultReason?->name))
                  <div><span class="text-gray-400 text-xs">Result (Ran):</span> {{ $appt->resultReason->name }}</div>
                @endif
                @if(!empty($appt->confirmResult?->name))
                  <div><span class="text-gray-400 text-xs">Confirmation:</span> {{ $appt->confirmResult->name }}</div>
                @endif
                @if(empty($appt->resultReason) && empty($appt->confirmResult))
                  {{ $appt->result ?? '' }}
                @endif
              </td>
            </tr>
            @if(!empty($appt->notes))
              <tr>
                <td colspan="7" class="text-sm">
                  <div class="text-gray-400">Notes</div>
                  <div class="mt-1 whitespace-pre-line">{{ $appt->notes }}</div>
                </td>
              </tr>
            @endif
          @empty
            <tr><td colspan="7" class="text-center py-10 text-gray-400">No appointments yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  {{-- Notes & Activity --}}
  <section class="card border border-slate-700/40 p-5 rounded-xl mt-8">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-semibold">Notes & Activity</h2>
      <button class="inline-flex items-center px-3 py-2 rounded font-semibold"
              style="background:#38bdf8; color:#041018"
              @click="noteOpen=true">
        Add Note
      </button>
    </div>

    <div class="mt-4 space-y-3">
      @forelse($contact->notes as $note)
        <div class="p-3 border border-slate-700/40 rounded-lg">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              @if($note->disposition)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs text-white"
                      style="background: {{ $note->disposition->row_color ?? '#334155' }}">
                  {{ $note->disposition->label ?? $note->disposition->name ?? 'Disposition' }}
                </span>
              @endif
              @if($note->follow_up_at)
                <span class="text-xs text-amber-300">
                  Follow-up: {{ $note->follow_up_at->format('M j, Y g:i a') }}
                </span>
              @endif
            </div>
            <div class="text-xs text-gray-400">
              by {{ $note->user->name ?? 'System' }} • {{ $note->created_at->diffForHumans() }}
            </div>
          </div>
          @if($note->body)
            <div class="text-sm mt-2 whitespace-pre-line">{{ $note->body }}</div>
          @endif
        </div>
      @empty
        <p class="text-sm text-gray-400">No notes yet.</p>
      @endforelse
    </div>
  </section>

  {{-- New Appointment Modal --}}
<div x-show="newAppt" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 p-4"
     @keydown.escape.window="newAppt=false">
  <div class="card max-w-4xl mx-auto rounded-xl p-6 border border-slate-700/40">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">New Appointment</h3>
      <button class="btn btn-ghost" @click="newAppt=false">Close</button>
    </div>

    <form method="POST" action="{{ route('appointments.store') }}">
      @csrf
      <input type="hidden" name="contact_id" value="{{ $contact->id }}">

      {{-- Core --}}
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="field md:col-span-2">
          <label>Scheduled For</label>
          <input type="datetime-local" name="scheduled_for" class="ring-brand w-full">
        </div>
        <div class="field">
          <label>Interested In</label>
          <input name="interested_in" class="ring-brand w-full">
        </div>
        <div class="field">
          <label>Confirmed At</label>
          <input type="datetime-local" name="confirmed_at" class="ring-brand w-full">
        </div>
      </div>

      {{-- Who set / sales rep --}}
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
        <div class="field md:col-span-2">
          <label>Set By (Marketing)</label>
          <select name="set_by_user_id" class="ring-brand w-full">
            <option value="">— Select —</option>
            @foreach($marketingUsers as $u)
              <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="field md:col-span-2">
          <label>Sales Rep (Sales)</label>
          <select name="sales_rep_id" class="ring-brand w-full">
            <option value="">— Select —</option>
            @foreach($salesUsers as $u)
              <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Results --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div class="field">
          <label>Result (Ran)</label>
          <select name="result_reason_id" class="ring-brand w-full">
            <option value="">— Select —</option>
            @foreach($resultRans as $r)
              <option value="{{ $r->id }}">{{ $r->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label>Confirmation Result</label>
          <select name="confirm_result_id" class="ring-brand w-full">
            <option value="">— Select —</option>
            @foreach($confirmResults as $r)
              <option value="{{ $r->id }}">{{ $r->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label>Price Quoted</label>
          <input type="number" step="0.01" name="price_quoted" class="ring-brand w-full">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div class="field">
          <label>Price Sold</label>
          <input type="number" step="0.01" name="price_sold" class="ring-brand w-full">
        </div>
        <div class="field md:col-span-2">
          <label>Notes</label>
          <textarea name="notes" rows="4" class="ring-brand w-full"></textarea>
        </div>
      </div>

      <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="btn btn-brand">Save Appointment</button>
        <button type="button" class="btn btn-ghost" @click="newAppt=false">Cancel</button>
      </div>
    </form>
  </div>
</div>
{{-- Auto-geocode when lat/lng missing --}}
<script>
document.addEventListener('DOMContentLoaded', async () => {
  try {
    const lat = @json($contact->lat);
    const lng = @json($contact->lng);

    if (!lat || !lng) {
      await fetch(@json(route('contacts.geocode', $contact)), {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': @json(csrf_token())}
      });
      // Optionally: location.reload();
    }
  } catch (e) {
    console.warn('Geocode failed:', e);
  }
});
</script>
@endsection
