<?php
use App\Models\{Queue, Contact, Disposition, Call};


it('filters contacts by call disposition', function(){
$contact = Contact::factory()->create();
$disp = Disposition::firstOrCreate(['type'=>'call','code'=>'NO_ANSWER'], ['label'=>'No Answer']);
Call::factory()->create(['contact_id'=>$contact->id,'disposition_id'=>$disp->id]);


$q = Queue::create(['name'=>'No Answers','created_by'=>\App\Models\User::factory()->create()->id,'filters'=>['call_dispositions'=>['NO_ANSWER']]]);
$service = app(\App\Services\QueueService::class);
$ids = $service->contactsForQueue($q)->pluck('contacts.id')->toArray();


expect($ids)->toContain($contact->id);
});