<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
public function edit(Appointment $appointment)
{
    $dispositions   = \App\Models\ResultRan::orderBy('name')->get(['id','name']);
    $results        = \App\Models\ConfirmationResult::orderBy('name')->get(['id','name']);
    $marketingUsers = \App\Models\User::whereHas('role', fn($q)=>$q->where('name','marketing'))->orderBy('name')->get(['id','name']);
    $salesUsers     = \App\Models\User::whereHas('role', fn($q)=>$q->where('name','sales'))->orderBy('name')->get(['id','name']);

    return view('appointments.edit', [
        'appointment'   => $appointment,
        'resultRans'    => $dispositions,
        'results'       => $results,
        'marketingUsers'=> $marketingUsers,
        'salesUsers'    => $salesUsers,
        'title'         => 'Edit Appointment',
    ]);
}
    public function update(Request $request, Appointment $appointment)
    {
        $data = $this->validatedData($request);

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
    $data = $this->validatedData($request);

    $appointment = Appointment::create($data);

    return redirect()
        ->route('contacts.show', $data['contact_id'])
        ->with('status', 'Appointment created.');
}
    /**
     * Centralized validation to keep store/update in sync.
     */
    protected function validatedData(Request $request): array
    {
        return $request->validate([
            // core
            'contact_id'       => ['required','exists:contacts,id'],
            'scheduled_for'    => ['nullable','date'],
            'interested_in'    => ['nullable','string','max:255'],
            'location'         => ['nullable','string','max:255'],
            'duration_minutes' => ['nullable','integer','min:0'],

            // who set / who with (either id or name)
            'set_by_user_id'   => ['nullable','integer','exists:users,id'],
            'set_by_name'      => ['nullable','string','max:255'],
            'set_with_id'      => ['nullable','integer','exists:users,id'],
            'set_with_name'    => ['nullable','string','max:255'],

            // status / result
            'result'            => ['nullable','string','max:255'],
            'result_reason'     => ['nullable','string','max:255'],
            'confirmed_at'      => ['nullable','date'],
            'canceled_at'       => ['nullable','date'],
            'cancellation_reason'=> ['nullable','string','max:255'],
            'follow_up_at'      => ['nullable','date'],
            'window_start'      => ['nullable','date'],
            'window_end'        => ['nullable','date'],
            'result_ran_id'   => ['nullable','exists:result_rans,id'],   // dropdown
	    'result_id'       => ['nullable','exists:confirmation_results,id'], // dropdown

            // sales / pricing
            'sales_rep_id'   => ['nullable','integer','exists:users,id'],
            'product'        => ['nullable','string','max:255'],
            'price_quoted'   => ['nullable','numeric'],
            'price_sold'     => ['nullable','numeric'],

            // disposition + notes
            'disposition_id' => ['nullable','integer', Rule::exists('dispositions','id')],
            'notes'          => ['nullable','string'],

            // optional extended sales fields (only if you migrated these)
            'install_at'     => ['nullable','date'],
            'measured_at'    => ['nullable','date'],
            'result_48hr'    => ['nullable','boolean'],
            'result_onspot'  => ['nullable','boolean'],
            'below_par'      => ['nullable','boolean'],
            'got_docs'       => ['nullable','boolean'],
            'amount_sold'    => ['nullable','numeric'],
            'net'            => ['nullable','numeric'],
            'bonus_ovr'      => ['nullable','numeric'],
            'bonus_net'      => ['nullable','numeric'],
            'par'            => ['nullable','numeric'],
            'commission_pct' => ['nullable','numeric'],
            'le_win'         => ['nullable','numeric'],
            'tf_win'         => ['nullable','numeric'],
        ]);
    }
}
