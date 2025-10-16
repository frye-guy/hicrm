<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\{Call, Contact, Disposition};
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function start(Contact $contact)
    {
        $call = Call::create([
            'contact_id'=>$contact->id,
            'user_id'=>auth()->id(),
            'started_at'=>now(),
        ]);
        return back()->with('status',"Call started #{$call->id}");
    }

    public function end(Call $call, Request $r)
    {
        $data = $r->validate([
            'disposition_id'=>['nullable','exists:dispositions,id'],
            'notes'=>['nullable','string'],
        ]);
        $call->update([
            'ended_at'=>now(),
            'duration_sec'=> now()->diffInSeconds($call->started_at),
            'disposition_id'=>$data['disposition_id'] ?? null,
            'notes'=>$data['notes'] ?? null,
        ]);
        return back()->with('status',"Call ended #{$call->id}");
    }
}
