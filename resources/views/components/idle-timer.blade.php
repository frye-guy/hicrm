<div x-data="idleTimer()" x-init="init()">
<template x-if="showModal">
<div class="fixed inset-0 bg-black/50 flex items-center justify-center">
<div class="bg-white p-6 rounded shadow w-full max-w-md">
<h2 class="text-lg font-semibold mb-2">Stay Logged In?</h2>
<p class="mb-4">You've been idle. You'll be logged out soon.</p>
<div class="flex gap-2 justify-end">
<button class="px-3 py-2 bg-gray-200 rounded" @click="logout()">Log out</button>
<button class="px-3 py-2 bg-blue-600 text-white rounded" @click="extend()">Stay Logged In</button>
</div>
</div>
</div>
</template>
</div>
<script>
function idleTimer(){
return {
idleMinutes: 0, showModal: false, tick: null,
init(){
const reset = () => { this.idleMinutes = 0; };
['mousemove','keydown','click','scroll','touchstart'].forEach(e=>window.addEventListener(e, reset));
this.tick = setInterval(async ()=>{
this.idleMinutes++;
await fetch('/heartbeat', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
if(this.idleMinutes === 9){ this.showModal = true; }
if(this.idleMinutes >= 10){ window.location = '/logout'; }
}, 60000);
},
extend(){ this.showModal=false; this.idleMinutes=0; },
logout(){ window.location = '/logout'; }
}
}
</script>