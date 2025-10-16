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

    public function show(Contact $contact)
    {
        // Pass all lead sources for the dropdown and the contact to the view
        $leadSources = LeadSource::orderBy('name')->get(['id', 'name']);

        return view('contacts.show', [
            'contact'      => $contact,
            'leadSources'  => $leadSources,
        ]);
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        // Validate everything you have on the form
        $validated = $request->validate([
            'phone'          => ['nullable', 'string', 'max:25'],
            'dispo'          => ['nullable', 'string', 'max:191'], // view uses "Dispo"
            'lead_source_id' => ['nullable', 'integer', 'exists:lead_sources,id'],
            'first_name'     => ['nullable', 'string', 'max:191'],
            'spouse'         => ['nullable', 'string', 'max:191'], // maps to spouse_name
            'last_name'      => ['nullable', 'string', 'max:191'],
            'email'          => [
                'nullable', 'email', 'max:255',
                Rule::unique('contacts', 'email')->ignore($contact->id)
            ],
            'address'        => ['nullable', 'string', 'max:255'],
            'city'           => ['nullable', 'string', 'max:191'],
            'state'          => ['nullable', 'string', 'max:2'],
            'zip'            => ['nullable', 'string', 'max:10'],

            'mr_works'       => ['nullable', 'string', 'max:191'],
            'mrs_works'      => ['nullable', 'string', 'max:191'],
            'alt_phone'      => ['nullable', 'string', 'max:25'],
            'alt_phone2'     => ['nullable', 'string', 'max:25'],
            'alt_phone3'     => ['nullable', 'string', 'max:25'],

            // UI label is "Search Tool" (singular); DB column is search_tools (plural)
            'search_tool'    => ['nullable', 'string', 'max:191'],

            'age_of_home'    => ['nullable', 'string', 'max:50'],
            // UI says "Type of Home"; some DBs use type_of_home or home_type
            'home_type'      => ['nullable', 'string', 'max:191'],
            'color_of_home'  => ['nullable', 'string', 'max:191'],
            'years_owned'    => ['nullable', 'string', 'max:50'],

            // UI shows Lat / Long; DB uses latitude / longitude
            'lat'            => ['nullable', 'numeric'],
            'lng'            => ['nullable', 'numeric'],
            'zone'           => ['nullable', 'string', 'max:50'],

            // UI shows Record ID; most schemas call this external_id
            'record_id'      => ['nullable', 'string', 'max:191'],

            // checkbox; normalize below
            'needs_reset'    => ['sometimes', 'boolean'],
        ]);

        /**
         * Map incoming request keys (as used in the Blade form) to the actual
         * Contact model/database column names.
         */
        $map = [
            'phone'          => 'phone',
            'dispo'          => 'disposition',
            'lead_source_id' => 'lead_source_id',
            'first_name'     => 'first_name',
            'spouse'         => 'spouse_name',
            'last_name'      => 'last_name',
            'email'          => 'email',
            'address'        => 'address',
            'city'           => 'city',
            'state'          => 'state',
            'zip'            => 'zip',

            'mr_works'       => 'mr_works',
            'mrs_works'      => 'mrs_works',
            'alt_phone'      => 'alt_phone',
            'alt_phone2'     => 'alt_phone2',
            'alt_phone3'     => 'alt_phone3',

            'search_tool'    => 'search_tools',

            'age_of_home'    => 'age_of_home',
            'home_type'      => 'type_of_home',
            'color_of_home'  => 'color_of_home',
            'years_owned'    => 'years_owned',

            'lat'            => 'latitude',
            'lng'            => 'longitude',
            'zone'           => 'zone',

            'record_id'      => 'external_id',
            // needs_reset handled below so unchecked => false is saved properly
        ];

        // Build the array we actually fill() with (using the mapping above)
        $toSave = [];
        foreach ($map as $inputKey => $column) {
            if (array_key_exists($inputKey, $validated)) {
                $toSave[$column] = $validated[$inputKey];
            }
        }

        // Normalize the checkbox; if it’s missing in the request, it's unchecked (false).
        $toSave['needs_reset'] = $request->boolean('needs_reset');

        // Fill and save
        $contact->fill($toSave);
        $contact->save();

        return redirect()
            ->route('contacts.show', $contact)
            ->with('status', 'Contact updated');
    }
}

