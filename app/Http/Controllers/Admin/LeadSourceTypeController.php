<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadSourceTypeController extends Controller
{
    // Show all types
    public function index()
    {
        $types = DB::table('lead_source_types')->orderBy('name')->get();
        return view('admin.lead-source-types.index', compact('types'));
    }

    // Show create form
    public function create()
    {
        return view('admin.lead-source-types.create');
    }

    // Handle create form
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        DB::table('lead_source_types')->insert($data);
        return redirect()->route('lead-source-types.index')->with('status', 'Lead Source Type added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $type = DB::table('lead_source_types')->find($id);
        return view('admin.lead-source-types.edit', compact('type'));
    }

    // Handle edit form
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        DB::table('lead_source_types')->where('id', $id)->update($data);
        return redirect()->route('lead-source-types.index')->with('status', 'Lead Source Type updated successfully.');
    }

    // Delete
    public function destroy($id)
    {
        DB::table('lead_source_types')->where('id', $id)->delete();
        return redirect()->route('lead-source-types.index')->with('status', 'Lead Source Type deleted.');
    }
}
