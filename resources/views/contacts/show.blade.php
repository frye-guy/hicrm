{{-- resources/views/contacts/show.blade.php --}}
<!doctype html>
<html lang="en" x-data="theme()"
      :style="{
        '--bg': colors.bg,
        '--card': colors.card,
        '--muted': colors.muted,
        '--text': colors.text,
        '--text-muted': colors.textMuted,
        '--brand': colors.brand,
        '--brand-600': colors.brand600,
        '--ring': colors.ring,
        '--success': colors.success,
        '--danger': colors.danger,
        '--warning': colors.warning,
        '--font': font
      }">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? ($contact->full_name ?? 'Contact') }}  {{ config('app.name') }}</title>

  {{-- Alpine for theme + modals --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- TailwindCDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { ui: "var(--font)" }
        }
      }
    }
  </script>

  <style>
    :root{
      --bg:#0b1220;
      --card:#0f172a;
      --muted:#0b1220;
      --text:#e5e7eb;
      --text-muted:#a3a3a3;
      --brand:#38bdf8;
      --brand-600:#0891b2;
      --ring:#22d3ee;
      --success:#22c55e;
      --danger:#ef4444;
      --warning:#f59e0b;
      --font: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Inter, "Helvetica Neue", Arial, "Apple Color Emoji","Segoe UI Emoji";
    }
    html,body{background:var(--bg); color:var(--text); font-family:var(--font)}
    .card{background:var(--card)}
    .muted{color:var(--text-muted)}
    .ring-brand:focus{outline:none; box-shadow:0 0 0 3px var(--ring)}
    .btn{display:inline-flex; align-items:center; gap:.5rem; font-weight:600; border-radius:.6rem; padding:.6rem .9rem}
    .btn-brand{background:var(--brand); color:#041018}
    .btn-brand:hover{background:var(--brand-600)}
    .btn-ghost{background:transparent; color:var(--text)}
    .btn-danger{background:var(--danger)}
    .btn-success{background:var(--success)}
    .field label{font-size:.78rem; color:var(--text-muted)}
    .field input,.field select,.field textarea{
      width:100%; background:#0b1220; color:var(--text);
      border:1px solid rgba(148,163,184,.25); border-radius:.6rem; padding:.55rem .7rem
    }
    .grid-col{display:grid; grid-template-columns:repeat(12,minmax(0,1fr)); gap:.9rem}
    .badge{padding:.15rem .45rem; border-radius:.35rem; font-size:.72rem}
    .table thead th{font-size:.75rem; color:var(--text-muted); font-weight:600}
    .table td,.table th{padding:.9rem .8rem; border-bottom:1px solid rgba(148,163,184,.15)}
    .btn-xs{padding:.35rem .55rem; font-size:.8rem; border-radius:.45rem}
  </style>
</head>
<body class="min-h-screen">

  {{-- Top Nav --}}
  <nav class="card border border-slate-700/40 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex items-center justify-between h-14">
        <div class="flex items-center gap-2">
          <a href="{{ route('dashboard') }}" class="btn btn-ghost">Dashboard</a>
          <a href="{{ route('contacts.index') }}" class="btn btn-ghost">Contacts</a>
          <a href="{{ route('queues.index') }}" class="btn btn-ghost">Queues</a>
          <a href="{{ route('appointments.index') }}" class="btn btn-ghost">Appointments</a>
          <a href="{{ route('reports.calls') }}" class="btn btn-ghost">Reports</a>
          <a href="{{ route('lead-sources.index') }}" class="btn btn-ghost">Lead Sources</a>
          <a href="{{ route('lead-source-types.index') }}" class="btn btn-ghost">Lead Source Types</a>
        </div>

        <div class="flex items-center gap-3">
          <button class="btn btn-ghost" @click="panelOpen = !panelOpen">Theme</button>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost">Logout</button>
          </form>
<script>
  document.addEventListener('open-theme-panel', () => {
    const el = document.querySelector('[x-data="theme()"]');
    if (el && el.__x) {
      el.__x.$data.panelOpen = true;
    }
  });
</script>
        </div>
      </div>
    </div>
  </nav>

  {{-- Content --}}
  <main class="max-w-7xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">{{ $contact->first_name ?? '' }} {{ $contact->last_name ?? '' }}</h1>
        <p class="muted">
          ID: <span class="font-mono">{{ $contact->id }}</span>
          &nbsp; &nbsp;
          <span class="text-gray-400">Source:</span>
          <span class="text-gray-100 font-semibold">{{ optional($contact->leadSource)->name ?? '' }}</span>
        </p>
      </div>
      @isset($contact->needs_reset)
        <span class="badge" :style="{background: colors.warning, color:'#111827'}">
          {{ $contact->needs_reset ? 'Needs Reset' : 'OK' }}
        </span>
      @endisset
    </div>

    {{-- Contact form --}}
    <form method="POST" action="{{ route('contacts.update',$contact) }}" class="card border border-slate-700/40 p-5 rounded-xl mb-8">
      @csrf @method('PUT')

      {{-- row 1 --}}
      <div class="grid-col">
        <div class="col-span-3 field">
          <label>Phone *</label>
          <input name="phone" value="{{ old('phone',$contact->phone ?? '') }}" class="ring-brand">
        </div>

        {{-- Dispo button opens modal --}}
        <div class="col-span-2 field">
          <label>Dispo</label>
          <button type="button" class="btn btn-brand w-full" @click="noteOpen = true">
            Add / Update Disposition
          </button>
        </div>

        <div class="field">
          <label class="block text-sm font-medium">Source</label>
          <select name="lead_source_id" class="form-select w-full">
            <option value="">Select</option>
            @foreach($leadSources as $source)
              <option value="{{ $source->id }}" @selected(old('lead_source_id',$contact->lead_source_id) == $source->id)>
                {{ $source->name }}
              </option>
            @endforeach
          </select>
          @error('lead_source_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="col-span-2 field">
          <label>Record ID</label>
          <input value="{{ $contact->id }}" disabled>
        </div>

        <div class="col-span-2 field flex items-end">
          <label class="sr-only">Needs Reset</label>
          <div class="flex items-center gap-2">
            <input type="checkbox" name="needs_reset" value="1" @checked(old('needs_reset',$contact->needs_reset ?? false))>
            <span class="muted">Needs Reset</span>
          </div>
        </div>
      </div>

      {{-- row 2 --}}
      <div class="grid-col mt-4">
        <div class="col-span-3 field">
          <label>First Name</label>
          <input name="first_name" value="{{ old('first_name',$contact->first_name ?? '') }}">
        </div>
        <div class="col-span-3 field">
          <label>Spouse</label>
          <input name="spouse" value="{{ old('spouse',$contact->spouse ?? '') }}">
        </div>
        <div class="col-span-3 field">
          <label>Last Name</label>
          <input name="last_name" value="{{ old('last_name',$contact->last_name ?? '') }}">
        </div>
        <div class="col-span-3 field">
          <label>Email</label>
          <input name="email" type="email" value="{{ old('email',$contact->email ?? '') }}">
        </div>
      </div>

      {{-- row 3 --}}
      <div class="grid-col mt-4">
        <div class="col-span-6 field">
          <label>Address</label>
          <input name="address" value="{{ old('address',$contact->address ?? '') }}">
        </div>
        <div class="col-span-3 field">
          <label>City</label>
          <input name="city" value="{{ old('city',$contact->city ?? '') }}">
        </div>
        <div class="col-span-2 field">
          <label>State</label>
          <select name="state">
            @php($st = old('state',$contact->state ?? ''))
            @foreach(($states ?? ['AL','AK','AZ','AR','CA','CO','CT','DC','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY']) as $state)
              <option value="{{ $state }}" @selected($st==$state)>{{ $state }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-span-1 field">
          <label>Zip</label>
          <input name="zip" value="{{ old('zip',$contact->zip ?? '') }}">
        </div>
      </div>

      {{-- row 4 --}}
      <div class="grid-col mt-4">
        <div class="col-span-2 field">
          <label>Mr Works</label>
          <input name="mr_works" value="{{ old('mr_works',$contact->mr_works ?? '') }}">
        </div>
        <div class="col-span-2 field">
          <label>Mrs Works</label>
          <input name="mrs_works" value="{{ old('mrs_works',$contact->mrs_works ?? '') }}">
        </div>
        <div class="col-span-2 field">
          <label>Alt Phone</label>
          <input name="alt_phone" value="{{ old('alt_phone',$contact->alt_phone ?? '') }}">
        </div>
        <div class="col-span-2 field">
          <label>Alt Phone 2</label>
          <input name="alt_phone2" value="{{ old('alt_phone2',$contact->alt_phone2 ?? '') }}">
        </div>
        <div class="col-span-2 field">
          <label>Alt Phone 3</label>
          <input name="alt_phone3" value="{{ old('alt_phone3',$contact->alt_phone3 ?? '') }}">
        </div>
        <div class="col-span-2 field">
          <label>Search Tool</label>
          <select name="search_tool">
            @php($tool = old('search_tool',$contact->search_tool ?? ''))
            @foreach(($searchTools ?? ['Select Tool','Google','Bing','Maps','County GIS']) as $t)
              <option value="{{ $t }}" @selected($tool==$t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="grid-col mt-4">
        <div class="col-span-2 field">
          <label>Age of Home</label>
          <input name="age_of_home" value="{{ old('age_of_home',$contact->age_of_home ?? '') }}">
        </div>
        <div class="col-span-2 field">
          <label>Type of Home</label>
          <input name="home_type" value="{{ old('home_type',$contact->home_type ?? '') }}">
        </div>
        <div class="col-span-2 field">
          <label>Color of Home</label>
          <input name="color_of_home" value="{{ old('color_of_home',$contact->color_of_home ?? '') }}">
        </div>
        <div class="col-span-3 field">
          <label>Years Owned</label>
          <input name="years_owned" value="{{ old('years_owned',$contact->years_owned ?? '') }}">
        </div>
        <div class="col-span-3 grid grid-cols-3 gap-3">
          <div class="field">
            <label>Lat</label>
            <input name="lat" value="{{ old('lat',$contact->lat ?? '') }}">
          </div>
          <div class="field">
            <label>Long</label>
            <input name="lng" value="{{ old('lng',$contact->lng ?? '') }}">
          </div>
          <div class="field">
            <label>Zone</label>
            <input name="zone" value="{{ old('zone',$contact->zone ?? '') }}">
          </div>
        </div>
      </div>

      <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="btn btn-brand">Save</button>
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
        <button class="btn btn-brand" @click="newAppt = true">New Appointment</button>
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
              <td>{{ $appt->set_with_name ?? $appt->set_with ?? '' }}</td>
              <td class="whitespace-nowrap">{{ optional($appt->created_at)->format('m/d/Y h:i:s A') }}</td>
              <td>{{ $appt->interested_in ?? '' }}</td>
              <td>{{ $appt->result ?? '' }}</td>
            </tr>
            @if(!empty($appt->notes))
              <tr>
                <td colspan="7" class="text-sm">
                  <div class="muted">Notes</div>
                  <div class="mt-1 whitespace-pre-line">{{ $appt->notes }}</div>
                </td>
              </tr>
            @endif
          @empty
            <tr><td colspan="7" class="text-center py-10 muted">No appointments yet.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </section>

    {{-- Notes & Activity (below appointments) --}}
    <section class="card border border-slate-700/40 p-5 rounded-xl mt-8">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Notes & Activity</h2>
        <button class="btn btn-brand" @click="noteOpen=true">Add Note</button>
      </div>

      <div class="mt-4 space-y-3">
        @forelse($contact->notes as $note)
          <div class="p-3 border border-slate-700/40 rounded-lg">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                @if($note->disposition)
                  <span class="badge"
                        style="background: {{ $note->disposition->row_color ?? '#334155' }}; color:#fff">
                    {{ $note->disposition->label ?? $note->disposition->name ?? 'Disposition' }}
                  </span>
                @endif
                @if($note->follow_up_at)
                  <span class="text-xs text-amber-300">
                    Follow-up: {{ $note->follow_up_at->format('M j, Y g:i a') }}
                  </span>
                @endif
              </div>
              <div class="text-xs muted">
                by {{ $note->user->name ?? 'System' }} • {{ $note->created_at->diffForHumans() }}
              </div>
            </div>
            @if($note->body)
              <div class="text-sm mt-2 whitespace-pre-line">{{ $note->body }}</div>
            @endif
          </div>
        @empty
          <p class="text-sm muted">No notes yet.</p>
        @endforelse
      </div>
    </section>
  </main>

{{-- New Appointment Modal (expanded) --}}
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
          <input type="datetime-local" name="scheduled_for" class="ring-brand">
        </div>

        <div class="field">
          <label>Interested In</label>
          <input name="interested_in" class="ring-brand">
        </div>

        <div class="field">
          <label>Location</label>
          <input name="location" class="ring-brand">
        </div>

        <div class="field">
          <label>Duration (minutes)</label>
          <input type="number" min="0" name="duration_minutes" class="ring-brand">
        </div>
      </div>

      {{-- Who set / who with --}}
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
        <div class="field">
          <label>Set By (User ID)</label>
          <input type="number" name="set_by_user_id" class="ring-brand">
        </div>
        <div class="field">
          <label>Set By (Name)</label>
          <input name="set_by_name" class="ring-brand">
        </div>
        <div class="field">
          <label>Set With (User ID)</label>
          <input type="number" name="set_with_id" class="ring-brand">
        </div>
        <div class="field">
          <label>Set With (Name)</label>
          <input name="set_with_name" class="ring-brand">
        </div>
      </div>

      {{-- Status / Result --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div class="field">
          <label>Result</label>
          <input name="result" class="ring-brand">
        </div>
        <div class="field md:col-span-2">
          <label>Result Reason</label>
          <input name="result_reason" class="ring-brand">
        </div>

        <div class="field">
          <label>Confirmed At</label>
          <input type="datetime-local" name="confirmed_at" class="ring-brand">
        </div>
        <div class="field">
          <label>Canceled At</label>
          <input type="datetime-local" name="canceled_at" class="ring-brand">
        </div>
        <div class="field">
          <label>Cancellation Reason</label>
          <input name="cancellation_reason" class="ring-brand">
        </div>

        <div class="field">
          <label>Follow Up At</label>
          <input type="datetime-local" name="follow_up_at" class="ring-brand">
        </div>
        <div class="field">
          <label>Window Start</label>
          <input type="datetime-local" name="window_start" class="ring-brand">
        </div>
        <div class="field">
          <label>Window End</label>
          <input type="datetime-local" name="window_end" class="ring-brand">
        </div>
      </div>

      {{-- Sales / Pricing --}}
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
        <div class="field">
          <label>Sales Rep (User ID)</label>
          <input type="number" name="sales_rep_id" class="ring-brand">
        </div>
        <div class="field">
          <label>Product</label>
          <input name="product" class="ring-brand">
        </div>
        <div class="field">
          <label>Price Quoted</label>
          <input type="number" step="0.01" name="price_quoted" class="ring-brand">
        </div>
        <div class="field">
          <label>Price Sold</label>
          <input type="number" step="0.01" name="price_sold" class="ring-brand">
        </div>
      </div>

      {{-- Disposition + Notes --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div class="field">
          <label>Disposition</label>
          <select name="disposition_id" class="ring-brand">
            <option value="">— optional —</option>
            @foreach(($dispositions ?? []) as $d)
              <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="field md:col-span-2">
          <label>Notes</label>
          <textarea name="notes" rows="4" class="ring-brand"></textarea>
        </div>
      </div>

      {{-- Optional extended sales fields (include only if you migrated these) --}}
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
        <div class="field">
          <label>Install At</label>
          <input type="datetime-local" name="install_at" class="ring-brand">
        </div>
        <div class="field">
          <label>Measured At</label>
          <input type="datetime-local" name="measured_at" class="ring-brand">
        </div>

        <div class="flex items-center gap-2 mt-7">
          <input type="checkbox" name="result_48hr" value="1">
          <label class="text-sm">Result 48hr</label>
        </div>
        <div class="flex items-center gap-2 mt-7">
          <input type="checkbox" name="result_onspot" value="1">
          <label class="text-sm">Result On-Spot</label>
        </div>

        <div class="flex items-center gap-2">
          <input type="checkbox" name="below_par" value="1">
          <label class="text-sm">Below PAR</label>
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="got_docs" value="1">
          <label class="text-sm">Got Docs</label>
        </div>

        <div class="field">
          <label>Amount Sold</label>
          <input type="number" step="0.01" name="amount_sold" class="ring-brand">
        </div>
        <div class="field">
          <label>NET</label>
          <input type="number" step="0.01" name="net" class="ring-brand">
        </div>
        <div class="field">
          <label>Bonus OVR</label>
          <input type="number" step="0.01" name="bonus_ovr" class="ring-brand">
        </div>
        <div class="field">
          <label>Bonus NET</label>
          <input type="number" step="0.01" name="bonus_net" class="ring-brand">
        </div>
        <div class="field">
          <label>PAR</label>
          <input type="number" step="0.01" name="par" class="ring-brand">
        </div>
        <div class="field">
          <label>Commission %</label>
          <input type="number" step="0.01" name="commission_pct" class="ring-brand">
        </div>
        <div class="field">
          <label>LE Win</label>
          <input type="number" step="0.01" name="le_win" class="ring-brand">
        </div>
        <div class="field">
          <label>TF Win</label>
          <input type="number" step="0.01" name="tf_win" class="ring-brand">
        </div>
      </div>

      <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="btn btn-brand">Save Appointment</button>
        <button type="button" class="btn btn-ghost" @click="newAppt=false">Cancel</button>
      </div>
    </form>
  </div>
</div>

  {{-- Theme Panel --}}
  <div x-cloak x-show="panelOpen" @keydown.escape.window="panelOpen=false"
       class="fixed right-4 bottom-4 z-40 card border border-slate-700/40 p-4 rounded-xl w-[20rem]">
    <h4 class="font-semibold mb-3">Theme</h4>
    <div class="space-y-3">
      <div>
        <label class="text-sm muted">Font (stack)</label>
        <input class="w-full mt-1 rounded-md bg-[#0b1220] border border-slate-600/50 p-2"
               x-model="font" @change="save()">
      </div>
      <div class="grid grid-cols-2 gap-3">
        <template x-for="(label,key) in {brand:'Brand',card:'Card',bg:'Background',text:'Text',textMuted:'Muted'}" :key="key">
          <div>
            <label class="text-sm muted" x-text="label"></label>
            <input type="color" class="w-full h-9 rounded-md mt-1"
                   :value="colors[key]" @input="setColor(key,$event.target.value)">
          </div>
        </template>
      </div>
      <div class="flex items-center gap-2">
        <button class="btn btn-ghost" @click="reset()">Reset</button>
        <button class="btn btn-brand" @click="save()">Save</button>
      </div>
    </div>
  </div>

  <script>
    function theme(){
      const defaultColors = {
        bg:getComputedStyle(document.documentElement).getPropertyValue('--bg').trim(),
        card:getComputedStyle(document.documentElement).getPropertyValue('--card').trim(),
        muted:getComputedStyle(document.documentElement).getPropertyValue('--muted').trim(),
        text:getComputedStyle(document.documentElement).getPropertyValue('--text').trim(),
        textMuted:getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim(),
        brand:getComputedStyle(document.documentElement).getPropertyValue('--brand').trim(),
        brand600:getComputedStyle(document.documentElement).getPropertyValue('--brand-600').trim(),
        ring:getComputedStyle(document.documentElement).getPropertyValue('--ring').trim(),
        success:getComputedStyle(document.documentElement).getPropertyValue('--success').trim(),
        danger:getComputedStyle(document.documentElement).getPropertyValue('--danger').trim(),
        warning:getComputedStyle(document.documentElement).getPropertyValue('--warning').trim(),
      };
      const saved = JSON.parse(localStorage.getItem('crm.theme')||'{}');
      const savedFont = localStorage.getItem('crm.theme.font') || getComputedStyle(document.documentElement).getPropertyValue('--font');

      const state = {
        panelOpen:false,
        newAppt:false,
        noteOpen:false, // <— added
        colors: Object.assign({}, defaultColors, saved),
        font:savedFont,
        setColor(k,v){ this.colors[k]=v; this.save(); },
        save(){
          localStorage.setItem('crm.theme', JSON.stringify(this.colors));
          localStorage.setItem('crm.theme.font', this.font);
        },
        reset(){
          this.colors = Object.assign({}, defaultColors);
          this.font = getComputedStyle(document.documentElement).getPropertyValue('--font');
          this.save();
        }
      };
      return state;
    }
  </script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
  try {
    const lat = @json($contact->lat);
    const lng = @json($contact->lng);

    if (!lat || !lng) {
      const res = await fetch(@json(route('contacts.geocode', $contact)), {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': @json(csrf_token())}
      });
      // Optional: You can refresh the page or update Lat/Lng inputs if needed.
      // const json = await res.json(); console.log('Geocode', json);
    }
  } catch (e) {
    console.warn('Geocode failed:', e);
  }
});
</script>

</body>
</html>
