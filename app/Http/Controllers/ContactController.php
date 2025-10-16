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
    // Eager-load for the notes timeline
    $contact->load([
        'notes.user:id,name',
        'notes.disposition:id,name,slug,is_positive,is_final,row_color',
    ]);

    // Dispositions for the dropdown
    $dispositions = \App\Models\Disposition::orderBy('name')
        ->get(['id','name','row_color','is_positive','is_final']);

    // Settings (for UI colors, etc.)
    $settings = \App\Models\Setting::first();

    // Lead Sources (if your view expects it)
    // Adjust fields as needed for your project
    $leadSources = \App\Models\LeadSource::orderBy('name')->get(['id','name']);

    return view('contacts.show', [
        'contact'      => $contact,
        'dispositions' => $dispositions,
        'settings'     => $settings,
        'leadSources'  => $leadSources,
    ]);
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

