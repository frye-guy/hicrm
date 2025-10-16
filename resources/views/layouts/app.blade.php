<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $title ?? config('app.name') }}</title>

{{-- Alpine.js (already fine to include here) --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
  :root { --blue:#2563eb; }
  nav{display:flex;gap:12px;align-items:center;padding:12px;border-bottom:1px solid #ddd;font-family:system-ui;position:relative}
  nav a{color:#222;text-decoration:none}
  nav a:hover{color:var(--blue)}
  nav a.active{color:var(--blue);font-weight:700}
  .ml-auto{margin-left:auto}
  /* Dropdown */
  .dropdown{position:relative;display:inline-flex}
  .dropdown-btn{display:inline-flex;align-items:center;gap:6px;cursor:pointer;color:#222;background:none;border:0;padding:0;font:inherit}
  .dropdown-btn:hover{color:var(--blue)}
  .caret{display:inline-block;transition:transform .15s; width:0; height:0; border-left:4px solid transparent;border-right:4px solid transparent;border-top:5px solid currentColor;}
  .menu{position:absolute; top:38px; left:0; min-width:200px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; box-shadow:0 8px 24px rgba(0,0,0,.08); padding:6px 0; z-index:50}
  .menu a{display:block; padding:8px 12px}
  .menu a:hover{background:#f6f7f9}
  /* small screens wrap */
  @media (max-width: 640px){ nav{flex-wrap:wrap;gap:10px} .dropdown{order:10} .ml-auto{order:99} }
</style>
</head>
<body>
<nav x-data="{ leadsOpen: false }" @keydown.escape.window="leadsOpen=false">
  <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
  <a href="{{ route('contacts.index') }}" class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">Contacts</a>
  <a href="{{ route('queues.index') }}" class="{{ request()->routeIs('queues.*') ? 'active' : '' }}">Queues</a>
  <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">Appointments</a>
  <a href="{{ route('reports.calls') }}" class="{{ request()->routeIs('reports.calls') ? 'active' : '' }}">Reports</a>

  {{-- Admin-only dropdown for lead pages --}}
  @if(auth()->user()?->roles()->where('name','Admin')->exists())
    <div class="dropdown" @click.outside="leadsOpen=false">
      <button type="button"
              class="dropdown-btn {{ request()->routeIs('lead-sources.*') || request()->routeIs('lead-source-types.*') ? 'active' : '' }}"
              @click="leadsOpen=!leadsOpen"
              :aria-expanded="leadsOpen"
              aria-haspopup="true">
        Leads <span class="caret" :style="leadsOpen ? 'transform:rotate(180deg)' : ''"></span>
      </button>

      <div class="menu" x-cloak x-show="leadsOpen" x-transition>
        <a href="{{ route('lead-sources.index') }}"
           class="{{ request()->routeIs('lead-sources.*') ? 'active' : '' }}">
          Lead Sources
        </a>
        <a href="{{ route('lead-source-types.index') }}"
           class="{{ request()->routeIs('lead-source-types.*') ? 'active' : '' }}">
          Lead Source Types
        </a>
      </div>
    </div>
  @endif
<div class="flex items-center gap-3">
  @include('settings._nav')
  <button class="btn btn-ghost" @click="$dispatch('open-theme-panel')">Theme</button>
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-ghost">Logout</button>
  </form>
</div>

@yield('content')
</body>
</html>
