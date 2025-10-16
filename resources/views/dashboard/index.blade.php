<x-layout>
<h1 class="text-xl font-semibold mb-4">Executive Dashboard</h1>
<div class="grid grid-cols-2 gap-4">
<x-kpi label="New Leads" :value="$kpis['leads']" />
<x-kpi label="Dials" :value="$kpis['dials']" />
<x-kpi label="Appointments Set" :value="$kpis['appts_set']" />
<x-kpi label="Close Rate" :value="$kpis['close_rate'].'%'" />
</div>
<div class="mt-6">
@include('reports.calls')
</div>
</x-layout>