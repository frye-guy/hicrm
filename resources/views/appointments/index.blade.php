@extends('layouts.app', ['title'=>'Appointments'])

@section('content')
<div x-data="appts()" x-init="init()" class="max-w-[1800px] mx-auto p-4">
  <h1 class="text-2xl font-semibold mb-4">Appointments</h1>

  <!-- Filters -->
  <div class="flex flex-wrap gap-2 items-end mb-3">
    <div>
      <label class="text-xs block text-gray-600">Search</label>
      <input x-model="search" @keyup.enter="reload" type="text"
             placeholder="Last name / phone / city / state"
             class="border rounded px-3 py-2 w-64">
    </div>
    <div>
      <label class="text-xs block text-gray-600">From</label>
      <input x-model="from" type="date" class="border rounded px-3 py-2">
    </div>
    <div>
      <label class="text-xs block text-gray-600">To</label>
      <input x-model="to" type="date" class="border rounded px-3 py-2">
    </div>
    <div>
      <label class="text-xs block text-gray-600">Sort</label>
      <select x-model="sort" class="border rounded px-3 py-2">
        <option value="scheduled_for">Appt Date</option>
        <option value="last_name">Last Name</option>
        <option value="city">City</option>
        <option value="state">State</option>
        <option value="credit">Credit</option>
        <option value="amount_sold">Amount Sold</option>
      </select>
    </div>
    <div>
      <label class="text-xs block text-gray-600">Dir</label>
      <select x-model="dir" class="border rounded px-3 py-2">
        <option value="desc">Desc</option>
        <option value="asc">Asc</option>
      </select>
    </div>
    <button @click="reload" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
  </div>

  <!-- Table -->
  <div class="overflow-x-auto border rounded-lg shadow-sm">
    <table class="min-w-[1400px] w-full text-sm">
<thead class="bg-gray-50 border-b sticky top-0 z-10">
  <tr class="text-left text-gray-700">
    <th class="px-3 py-2">Phone</th>
    <th class="px-3 py-2">Last Name</th>
    <th class="px-3 py-2">City</th>
    <th class="px-3 py-2">State</th>
    <th class="px-3 py-2">Result</th>
    <th class="px-3 py-2">Set/Reset By</th>
    <th class="px-3 py-2">Credit</th>
    <th class="px-3 py-2">Appt Date</th>
    <th class="px-3 py-2">Issued To</th>
    <th class="px-3 py-2">Lead Source</th>
    <th class="px-3 py-2">Last Contact</th>
  </tr>
</thead>
      <tbody>
        <template x-for="row in rows" :key="row.id">
          <tr class="border-b hover:bg-gray-50">
            <td class="px-3 py-2" x-text="row.phone ?? row.contact?.phone ?? '—'"></td>
            <td class="px-3 py-2">
              <a class="text-blue-600 hover:underline" :href="`/contacts/${row.contact_id}`"
                 x-text="row.last_name ?? row.contact?.last_name ?? '—'"></a>
            </td>
            <td class="px-3 py-2" x-text="row.city ?? row.contact?.city ?? '—'"></td>
            <td class="px-3 py-2" x-text="row.state ?? row.contact?.state ?? '—'"></td>
            <td class="px-3 py-2">
              <span :class="badgeClass(row.appointment_disposition_id)"
                    x-text="resultLabel(row.appointment_disposition_id)"></span>
            </td>
            <td class="px-3 py-2">
  <div>
    <div><span class="text-gray-600">Set:</span> <span x-text="row.set_by_name ?? '—'"></span></div>
    <template x-if="row.reset_by_name">
      <div class="text-xs text-gray-600">
        <span>Reset:</span>
        <span x-text="row.reset_by_name"></span>
        <span x-show="row.reset_at">&middot; <span x-text="formatDate(row.reset_at)"></span></span>
        <span x-show="row.reset_reason">&middot; <span x-text="row.reset_reason"></span></span>
      </div>
    </template>
  </div>
</td>

<td class="px-3 py-2">
  <div x-text="row.lead_source_name ? (row.lead_source_code ? row.lead_source_name + ' (' + row.lead_source_code + ')' : row.lead_source_name) : '—'"></div>
  <div class="text-xs text-gray-600" x-text="row.lead_source_type ?? ''"></div>
</td>            <td class="px-3 py-2" x-text="row.credit ?? '—'"></td>
            <td class="px-3 py-2" x-text="formatDate(row.scheduled_for)"></td>
            <td class="px-3 py-2" x-text="row.issued_to_name ?? '—'"></td>
            <td class="px-3 py-2 text-right" x-text="money(row.amount_sold)"></td>
            <td class="px-3 py-2" x-text="formatDate(row.last_contact_at) || '—'"></td>
          </tr>
        </template>
      </tbody>
    </table>

    <div class="p-3 text-center text-gray-500" x-show="loading">Loading…</div>
    <div class="p-3 text-center text-gray-400 text-xs" x-show="!nextPage && !loading && rows.length">End of results</div>
    <div x-ref="sentinel"></div>
  </div>
</div>

<script>
function appts(){
  return {
    rows: [], nextPage: 1, loading: false,
    search:'', from:'', to:'', sort:'scheduled_for', dir:'desc',
    observer: null,
    init(){
      this.observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) this.load(); });
      }, { rootMargin: '600px' });
      this.observer.observe(this.$refs.sentinel);
      this.load();
    },
    async load(){
      if(this.loading || !this.nextPage) return;
      this.loading = true;
      const params = new URLSearchParams({
        page: this.nextPage, search: this.search, from: this.from, to: this.to, sort: this.sort, dir: this.dir
      });
      const res = await fetch(`/appointments/table?`+params.toString());
      const data = await res.json();
      this.rows.push(...data.data);
      this.nextPage = data.next_page;
      this.loading = false;
    },
    async reload(){ this.rows=[]; this.nextPage=1; await this.load(); },
    formatDate(d){ if(!d) return ''; const x = new Date(d); return isNaN(x)?'':x.toLocaleString(); },
    money(v){ if(v===null || v===undefined || v==='') return '$0'; const num = Number(v); return isNaN(num) ? '$0' : '$'+num.toLocaleString(); },
    resultLabel(id){
      // TODO: map real disposition IDs to labels. Placeholder:
      return id ?? '—';
    },
    badgeClass(id){
      const base='inline-block px-2 py-1 rounded text-xs ';
      if(!id) return base+'bg-gray-100 text-gray-600';
      const s = String(id);
      if (/SOLD|COMPLETED|CONFIRMED/i.test(s)) return base+'bg-green-100 text-green-700';
      if (/RESCHEDULED|CALLBACK/i.test(s))     return base+'bg-yellow-100 text-yellow-700';
      return base+'bg-red-100 text-red-700';
    }
  }
}
</script>
@endsection
