<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Contact $contact, Request $request)
    {
        $data = $request->validate([
            'body'           => ['nullable','string'],
            'disposition_id' => ['nullable','exists:dispositions,id'],
            'follow_up_at'   => ['nullable','date'],
        ]);

        $data['contact_id'] = $contact->id;
        $data['user_id']    = auth()->id();

        Note::create($data);

        return back()->with('success', 'Note added.');
    }

    public function destroy(Contact $contact, Note $note)
    {
        // (Optional) authorize that this note belongs to contact
        abort_unless($note->contact_id === $contact->id, 404);
        $note->delete();

        return back()->with('success', 'Note deleted.');
    }
}
