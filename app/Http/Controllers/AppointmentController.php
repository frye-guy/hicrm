<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
public function edit(Appointment $appointment)
{
    $marketingUsers = \App\Models\User::role('marketing')->select('id','name')->orderBy('name')->get();
    $salesUsers     = \App\Models\User::role('sales')->select('id','name')->orderBy('name')->get();
    $resultRans     = \App\Models\ResultRan::select('id','name')->orderBy('name')->get();
    $confirmResults = \App\Models\ConfirmResult::select('id','name')->orderBy('name')->get();
    $dispositions   = \App\Models\Disposition::select('id','name')->orderBy('name')->get();

    return view('appointments.edit', [
        'appointment'    => $appointment,
        'marketingUsers' => $marketingUsers,
        'salesUsers'     => $salesUsers,
        'resultRans'     => $resultRans,
        'confirmResults' => $confirmResults,
        'dispositions'   => $dispositions,
        'title'          => 'Edit Appointment',
    ]);
}
    public function update(Request $request, Appointment $appointment)
    {
    $data = $request->validate([
        'scheduled_for'     => ['nullable','date'],
        'interested_in'     => ['nullable','string','max:120'],
        'confirmed_at'      => ['nullable','date'],

        'set_by_user_id'    => ['nullable','exists:users,id'],
        'sales_rep_id'      => ['nullable','exists:users,id'],

        'result_reason_id'  => ['nullable','exists:result_rans,id'],
        'confirm_result_id' => ['nullable','exists:confirm_results,id'],

        'price_quoted'      => ['nullable','numeric'],
        'price_sold'        => ['nullable','numeric'],

        'notes'             => ['nullable','string'],
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
        'contact_id'        => ['required','exists:contacts,id'],
        'scheduled_for'     => ['nullable','date'],
        'interested_in'     => ['nullable','string','max:120'],
        'confirmed_at'      => ['nullable','date'],

        'set_by_user_id'    => ['nullable','exists:users,id'],
        'sales_rep_id'      => ['nullable','exists:users,id'],

        'result_reason_id'  => ['nullable','exists:result_rans,id'],     // “Result (Ran)”
        'confirm_result_id' => ['nullable','exists:confirm_results,id'], // Confirmation result

        'price_quoted'      => ['nullable','numeric'],
        'price_sold'        => ['nullable','numeric'],

        'notes'             => ['nullable','string'],
    ]);

    $appointment = \App\Models\Appointment::create($data);

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
