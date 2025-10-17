{{-- resources/views/settings/_nav.blade.php --}}
<div x-data="{ open: false }" class="relative">
  <button type="button"
          class="btn btn-ghost"
          @click="open = !open"
          :aria-expanded="open"
          aria-haspopup="true">
    Settings
    <svg class="w-4 h-4 ml-1 inline-block" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
    </svg>
  </button>

  <div x-cloak x-show="open" @click.outside="open=false"
       class="absolute right-0 mt-2 w-64 bg-[#0f172a] border border-slate-700/40 rounded-lg shadow-xl p-2 z-50">
    @if (Route::has('settings.dispositions.index'))
      <a class="block px-3 py-2 hover:bg-slate-800 rounded"
         href="{{ route('settings.dispositions.index') }}">Dispositions</a>
    @endif

    @if (Route::has('settings.result-rans.index'))
      <a class="block px-3 py-2 hover:bg-slate-800 rounded"
         href="{{ route('settings.result-rans.index') }}">Result Ran</a>
    @endif

    @if (Route::has('settings.results.index'))
      <a class="block px-3 py-2 hover:bg-slate-800 rounded"
         href="{{ route('settings.results.index') }}">Results (Confirmation)</a>
    @endif

    @if (Route::has('settings.apis.edit'))
      <a class="block px-3 py-2 hover:bg-slate-800 rounded"
         href="{{ route('settings.apis.edit') }}">APIs (Connections)</a>
    @endif

    {{-- User Management: prefer the settings.* name if that’s how you registered it --}}
    @if (Route::has('settings.users.index'))
      <a class="block px-3 py-2 hover:bg-slate-800 rounded"
         href="{{ route('settings.users.index') }}">User Management</a>
    @elseif (Route::has('users.index'))
      <a class="block px-3 py-2 hover:bg-slate-800 rounded"
         href="{{ route('users.index') }}">User Management</a>
    @endif

    <button class="block w-full text-left px-3 py-2 hover:bg-slate-800 rounded"
            @click="$dispatch('open-theme-panel'); open=false">
      Theme
    </button>
  </div>
</div>
