<?php
use App\Services\SchedulingService;
use Illuminate\Support\Carbon;


it('returns slot proposals', function(){
$svc = app(SchedulingService::class);
$start = Carbon::now()->startOfWeek();
$end = $start->copy()->addDays(5);
$slots = $svc->recommendSlots($start,$end,null,null,null,60);
expect($slots)->toBeArray();
});