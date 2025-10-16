{{-- Settings dropdown used in top nav --}}
<div x-data="{open:false}" class="relative">
  <button type="button" class="btn btn-ghost" @click="open=!open">Settings ?</button>
  <div x-cloak x-show="open" @click.outside="open=false"
       class="absolute right-0 mt-1 w-64 card p-2 border border-slate-700/40 rounded">
    <a class="block px-3 py-2 hover:bg-slate-800 rounded" href="{{ route('settings.dispositions.index') }}">Dispositions</a>
    <a class="block px-3 py-2 hover:bg-slate-800 rounded" href="{{ route('settings.result-rans.index') }}">Result Ran</a>
    <a class="block px-3 py-2 hover:bg-slate-800 rounded" href="{{ route('settings.results.index') }}">Results (Confirmation)</a>
    <a class="block px-3 py-2 hover:bg-slate-800 rounded" href="{{ route('settings.apis.edit') }}">APIs (Connections)</a>
    <a class="block px-3 py-2 hover:bg-slate-800 rounded" href="{{ route('users.index') }}">User Management</a>
    {{-- Theme lives in your head/panel, but we show an entry here --}}
    <button class="block w-full text-left px-3 py-2 hover:bg-slate-800 rounded"
            @click="$dispatch('open-theme-panel'); open=false">Theme</button>
  </div>
</div>
