<x-layout>
<h1 class="text-xl font-semibold mb-3">Live Presence</h1>
<div x-data="{ rows: [] }" x-init="setInterval(async ()=>{ const r = await fetch('/current-calls'); this.rows = await r.json(); }, 5000)">
<template x-for="r in rows" :key="r.id">
<div class="border rounded p-3 mb-2">
<div class="font-semibold" x-text="r.user.name"></div>
<div>calling <span x-text="r.contact.first_name + ' ' + r.contact.last_name"></span> since <span x-text="r.since"></span></div>
</div>
</template>
</div>
</x-layout>