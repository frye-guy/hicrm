<?php
namespace App\Services;


use App\Models\Contact;
use App\Models\Queue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


class QueueService
{
public function contactsForQueue(Queue $q): Builder
{
$f = $q->filters ?? [];
$contacts = Contact::query();


if (!empty($f['lead_sources'])) {
$contacts->whereIn('lead_source_id', $f['lead_sources']);
}
if (!empty($f['office_ids'])) {
$contacts->whereIn('office_id', $f['office_ids']);
}
if (!empty($f['product_ids'])) {
foreach ($f['product_ids'] as $pid) {
$contacts->whereJsonContains('interests', $pid);
}
}
// Join calls/appointments based on filters
if (!empty($f['call_dispositions'])) {
$contacts->whereHas('calls', function($qq) use ($f){
$qq->whereHas('disposition', function($dq) use ($f){
$dq->where('type','call')->whereIn('code', $f['call_dispositions']);
});
});
}
if (!empty($f['appointment_results'])) {
$contacts->whereHas('appointments', function($qq) use ($f){
$qq->whereHas('disposition', function($dq) use ($f){
$dq->where('type','appointment')->whereIn('code', $f['appointment_results']);
});
});
}
if (!empty($f['date'])) {
$contacts = $this->applyDate($contacts, $f['date']);
}
return $contacts->distinct();
}


private function applyDate(Builder $q, array $date): Builder
{
// Example: {field:"calls.started_at", mode:"relative", unit:"days", value:7}
$field = $date['field'] ?? 'contacts.created_at';
if (($date['mode'] ?? null) === 'relative') {
$end = now();
$start = match($date['unit'] ?? 'days'){
'days' => now()->subDays((int)($date['value'] ?? 7)),
'months' => now()->subMonths((int)($date['value'] ?? 1)),
'hours' => now()->subHours((int)($date['value'] ?? 24)),
default => now()->subDays(7)
};
$q->whereBetween($field, [$start, $end]);
}
if (($date['mode'] ?? null) === 'between') {
$q->whereBetween($field, [Carbon::parse($date['start']), Carbon::parse($date['end'])]);
}
return $q;
}
}