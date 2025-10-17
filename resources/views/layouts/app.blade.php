{{-- resources/views/layouts/app.blade.php --}}
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
  <title>@yield('title', config('app.name'))</title>

  {{-- Alpine --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { fontFamily: { ui: "var(--font)" } } } }
  </script>

  <style>
    :root{
      --bg:#0b1220; --card:#0f172a; --muted:#0b1220;
      --text:#e5e7eb; --text-muted:#a3a3a3;
      --brand:#38bdf8; --brand-600:#0891b2; --ring:#22d3ee;
      --success:#22c55e; --danger:#ef4444; --warning:#f59e0b;
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
    .badge{padding:.15rem .45rem; border-radius:.35rem; font-size:.72rem}
    .table thead th{font-size:.75rem; color:var(--text-muted); font-weight:600}
    .table td,.table th{padding:.9rem .8rem; border-bottom:1px solid rgba(148,163,184,.15)}
    .btn-xs{padding:.35rem .55rem; font-size:.8rem; border-radius:.45rem}
  </style>

  @stack('head')
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
          @include('settings._nav')
          <button class="btn btn-ghost" @click="$dispatch('open-theme-panel')">Theme</button>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </nav>

  {{-- Page content --}}
  <main class="max-w-7xl mx-auto px-4 py-8">
    @yield('content')
  </main>

  {{-- Theme panel --}}
  <div x-cloak x-show="panelOpen" @keydown.escape.window="panelOpen=false"
       class="fixed right-4 bottom-4 z-40 card border border-slate-700/40 p-4 rounded-xl w-[20rem]">
    {{-- … theme panel … --}}
  </div>

  <script>
    function theme(){
      const get = k => getComputedStyle(document.documentElement).getPropertyValue(k).trim()
      const defaults = {
        bg:get('--bg'), card:get('--card'), muted:get('--muted'),
        text:get('--text'), textMuted:get('--text-muted'),
        brand:get('--brand'), brand600:get('--brand-600'), ring:get('--ring'),
        success:get('--success'), danger:get('--danger'), warning:get('--warning'),
      };
      const saved = JSON.parse(localStorage.getItem('crm.theme')||'{}');
      const savedFont = localStorage.getItem('crm.theme.font') || get('--font');
      return {
        panelOpen:false,
        newAppt:false,
        noteOpen:false,
        colors: Object.assign({}, defaults, saved),
        font:savedFont,
        setColor(k,v){ this.colors[k]=v; this.save(); },
        save(){ localStorage.setItem('crm.theme', JSON.stringify(this.colors));
                localStorage.setItem('crm.theme.font', this.font); },
        reset(){ this.colors = Object.assign({}, defaults);
                 this.font = get('--font'); this.save(); }
      }
    }
    document.addEventListener('open-theme-panel', () => {
      const el = document.querySelector('[x-data="theme()"]');
      if (el && el.__x) el.__x.$data.panelOpen = true;
    });
  </script>

  @stack('body')
</body>
</html>
