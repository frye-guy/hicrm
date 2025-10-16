<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function edit(Appointment $appointment)
    {
        // show a simple edit form (or redirect back if you want to inline edit)
        return view('appointments.edit', [
            'appointment' => $appointment,
            'title' => 'Edit Appointment'
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'scheduled_for' => ['nullable','date'],
            'set_by'        => ['nullable','string','max:120'],
            'set_with'      => ['nullable','string','max:120'],
            'interested_in' => ['nullable','string','max:120'],
            'result'        => ['nullable','string','max:120'],
            'notes'         => ['nullable','string'],
        ]);

        $appointment->update($data);

        return redirect()
            ->route('contacts.show', $appointment->contact_id)
            ->with('status', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $contactId = $appointment->contact_id;
        $appointment->delete();

        return redirect()
            ->route('contacts.show', $contactId)
            ->with('status', 'Appointment deleted.');
    }

public function store(Request $request)
{
    $data = $request->validate([
        'contact_id'    => ['required','exists:contacts,id'],
        'scheduled_for' => ['nullable','date'],
        'set_by'        => ['nullable','string','max:120'],
        'set_with'      => ['nullable','string','max:120'],
        'interested_in' => ['nullable','string','max:120'],
        'result'        => ['nullable','string','max:120'],
        'notes'         => ['nullable','string'],
    ]);

    $appointment = \App\Models\Appointment::create($data);

    return redirect()
        ->route('contacts.show', $data['contact_id'])
        ->with('status', 'Appointment created.');
}




}
