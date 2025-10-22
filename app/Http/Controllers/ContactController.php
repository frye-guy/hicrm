<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Display the specified contact.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('status','Contact deleted');
    }

public function show(\App\Models\Contact $contact)
{
    // appointments for the table under the contact form
    $appointments = \App\Models\Appointment::where('contact_id', $contact->id)
        ->latest('scheduled_for')->get();

    // lead sources for the contact form dropdown
    $leadSources = \App\Models\LeadSource::select('id','name')->orderBy('name')->get();

    // settings for theme colors (if you use this)
    $settings = \App\Models\Setting::first();

    // NEW: users by role for the appointment modal
    // If you use spatie/laravel-permission:
    $marketingUsers = \App\Models\User::role('marketing')
        ->select('id','name')->orderBy('name')->get();
    $salesUsers = \App\Models\User::role('sales')
        ->select('id','name')->orderBy('name')->get();

    // If you don't use spatie, replace the two calls above with:
    // $marketingUsers = \App\Models\User::where('role', 'marketing')->select('id','name')->orderBy('name')->get();
    // $salesUsers     = \App\Models\User::where('role', 'sales')->select('id','name')->orderBy('name')->get();

    // NEW: options managed in Settings
    $resultRans      = \App\Models\ResultRan::select('id','name')->orderBy('name')->get();         // “Result (Ran)”
    $confirmResults  = \App\Models\ConfirmResult::select('id','name')->orderBy('name')->get();     // “Confirmation Result”
    $dispositions    = \App\Models\Disposition::select('id','name','row_color','is_positive','is_final')
        ->orderBy('name')->get();

    return view('contacts.show', compact(
        'contact',
        'appointments',
        'leadSources',
        'marketingUsers',
        'salesUsers',
        'resultRans',
        'confirmResults',
        'dispositions',
        'settings'
    ));
}

    /**
     * Update the specified contact in storage.
     */
public function update(Request $request, Contact $contact)
{
    // validate every field that is editable on the Contact Show form
    $data = $request->validate([
        'phone'          => ['required', 'string', 'max:25'],
        'dispo'          => ['nullable', 'string', 'max:255'],             // or 'disposition' if that's your column
        'lead_source_id' => ['nullable', 'integer', 'exists:lead_sources,id'],

        'first_name'     => ['nullable', 'string', 'max:255'],
        'spouse'         => ['nullable', 'string', 'max:255'],             // or 'spouse_name' if that’s your column
        'last_name'      => ['nullable', 'string', 'max:255'],
        'email'          => ['nullable', 'email', 'max:255'],

        'address'        => ['nullable', 'string', 'max:255'],
        'city'           => ['nullable', 'string', 'max:255'],
        'state'          => ['nullable', 'string', 'max:2'],
        'zip'            => ['nullable', 'string', 'max:10'],

        'mr_works'       => ['nullable', 'string', 'max:255'],
        'mrs_works'      => ['nullable', 'string', 'max:255'],

        'alt_phone'      => ['nullable', 'string', 'max:25'],
        'alt_phone2'     => ['nullable', 'string', 'max:25'],
        'alt_phone3'     => ['nullable', 'string', 'max:25'],

        'search_tool'    => ['nullable', 'string', 'max:255'],             // matches “Search Tool” select
        'age_of_home'    => ['nullable', 'string', 'max:255'],
        'home_type'      => ['nullable', 'string', 'max:255'],             // “Type of Home”
        'color_of_home'  => ['nullable', 'string', 'max:255'],
        'years_owned'    => ['nullable', 'string', 'max:255'],

        'lat'            => ['nullable', 'numeric'],
        'lng'            => ['nullable', 'numeric'],
        'zone'           => ['nullable', 'string', 'max:255'],

        // checkbox for Needs Reset — if column name is needs_reset (TINYINT/BOOL)
        'needs_reset'    => ['nullable', 'boolean'],
    ]);

    // Handle boolean checkbox explicitly (unchecked boxes are not posted)
    $data['needs_reset'] = $request->boolean('needs_reset');

    // Save once
    $contact->fill($data);
    $contact->save();

    return redirect()
        ->route('contacts.show', $contact)
        ->with('status', 'Contact updated');
}
}

