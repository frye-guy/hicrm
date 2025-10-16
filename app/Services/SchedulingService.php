<?php
'sales_rep_id' => $rep->id,
'scheduled_for' => $slot->copy(),
'duration_min' => $durationMin,
'score' => $this->scoreSlot($rep, $slot, $geo)
];
}
}
}
usort($proposals, fn($a,$b)=> $a['score'] <=> $b['score']);
return array_slice($proposals, 0, 20);
}


private function expandWorkingHours($rep, Carbon $start, Carbon $end): array
{
$out = [];
$cursor = $start->copy()->startOfDay();
$hours = $rep->working_hours ?? [];
while ($cursor->lte($end)) {
$dow = $cursor->format('D'); // Mon, Tue, ...
foreach (($hours[$dow] ?? ["09:00-17:00"]) as $range) {
[$s,$e] = explode('-', $range);
$out[] = [
$cursor->copy()->setTimeFromTimeString($s),
$cursor->copy()->setTimeFromTimeString($e),
];
}
$cursor->addDay();
}
return $out;
}


private function computeGaps(array $working, $busy): array
{
$gaps = [];
foreach ($working as [$ws,$we]) {
$cursor = $ws->copy();
$appointments = $busy->filter(fn($a)=> $a->scheduled_for->between($ws, $we))->sortBy('scheduled_for');
foreach ($appointments as $appt) {
$end = $appt->scheduled_for->copy()->addMinutes($appt->duration_min);
if ($cursor->lt($appt->scheduled_for)) { $gaps[] = [$cursor->copy(), $appt->scheduled_for->copy()]; }
$cursor = $end->copy();
}
if ($cursor->lt($we)) { $gaps[] = [$cursor->copy(), $we->copy()]; }
}
return $gaps;
}


private function scoreSlot($rep, Carbon $slot, ?array $geo): int
{
// Simple scoring: earlier is better; if geo provided with ['lat'=>..,'lng'=>..], prefer closer reps
$score = $slot->timestamp;
if ($geo && $rep->lat && $rep->lng) {
$dist = hypot($rep->lat - $geo['lat'], $rep->lng - $geo['lng']);
$score += (int)($dist * 100000);
}
return $score;
}
}