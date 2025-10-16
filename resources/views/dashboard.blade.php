@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
<div style="max-width:1280px;margin:24px auto;padding:0 12px;font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial">

  <h1 style="font-size:1.75rem;margin-bottom:1rem;">Welcome, {{ auth()->user()->name }}!</h1>

  {{-- KPI cards --}}
  <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px; margin-bottom:16px;">
    <div style="border:1px solid #eee;border-radius:8px;padding:12px">
      <div style="font-size:.9rem;color:#666">New Leads (7d)</div>
      <div style="font-size:1.6rem;font-weight:600">{{ $kpis['newLeads7d'] ?? 0 }}</div>
    </div>
    <div style="border:1px solid #eee;border-radius:8px;padding:12px">
      <div style="font-size:.9rem;color:#666">Dials Today</div>
      <div style="font-size:1.6rem;font-weight:600">{{ $kpis['dialsToday'] ?? 0 }}</div>
    </div>
    <div style="border:1px solid #eee;border-radius:8px;padding:12px">
      <div style="font-size:.9rem;color:#666">Appts Next 7d</div>
      <div style="font-size:1.6rem;font-weight:600">{{ $kpis['apptsNext7d'] ?? 0 }}</div>
    </div>
    <div style="border:1px solid #eee;border-radius:8px;padding:12px">
      <div style="font-size:.9rem;color:#666">Close Rate (7d)</div>
      <div style="font-size:1.6rem;font-weight:600">
        @if(!is_null($kpis['closeRate7d'])) {{ $kpis['closeRate7d'] }}% @else — @endif
      </div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;">
    {{-- Calls last 7 days chart --}}
    <div style="border:1px solid #eee;border-radius:8px;padding:12px">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
        <h2 style="font-size:1.1rem;margin:0">Calls — last 7 days</h2>
      </div>
      <canvas id="calls7"></canvas>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        (function(){
          const labels = @json($calls7['labels'] ?? []);
          const values = @json($calls7['values'] ?? []);
          const ctx = document.getElementById('calls7').getContext('2d');
          new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Calls', data: values }] },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
          });
          // Make the canvas a bit taller
          document.getElementById('calls7').style.height = '260px';
        })();
      </script>
    </div>

    {{-- Upcoming appointments --}}
    <div style="border:1px solid #eee;border-radius:8px;padding:12px">
      <h2 style="font-size:1.1rem;margin:0 0 8px">Upcoming Appointments</h2>
      <table border="1" cellpadding="6" cellspacing="0" width="100%" style="font-size:.95rem">
        <thead><tr><th>When</th><th>Contact</th><th>Phone</th><th>Set By</th></tr></thead>
        <tbody>
          @forelse($upcomingAppts as $a)
          <tr>
            <td>{{ \Illuminate\Support\Carbon::parse($a->scheduled_for)->toDayDateTimeString() }}</td>
            <td>{{ $a->last_name }}, {{ $a->first_name }}</td>
            <td>{{ $a->phone }}</td>
            <td>{{ $a->set_by }}</td>
          </tr>
          @empty
          <tr><td colspan="4">No upcoming appointments.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Recent contacts --}}
  <div style="border:1px solid #eee;border-radius:8px;padding:12px;margin-top:16px">
    <h2 style="font-size:1.1rem;margin:0 0 8px">Recent Contacts</h2>
    <table border="1" cellpadding="6" cellspacing="0" width="100%" style="font-size:.95rem">
      <thead><tr><th>Name</th><th>Phone</th><th>City/State</th><th>Created</th></tr></thead>
      <tbody>
        @forelse($recentContacts as $c)
        <tr>
          <td><a href="{{ route('contacts.show', $c->id) }}">{{ $c->first_name }} {{ $c->last_name }}</a></td>
          <td>{{ $c->phone }}</td>
          <td>{{ $c->city }}, {{ $c->state }}</td>
          <td>{{ \Illuminate\Support\Carbon::parse($c->created_at)->diffForHumans() }}</td>
        </tr>
        @empty
        <tr><td colspan="4">No contacts yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection