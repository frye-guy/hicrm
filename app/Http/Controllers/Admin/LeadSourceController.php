<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadSourceController extends Controller
{
    public function index()
    {
        $sources = DB::table('lead_sources as ls')
            ->leftJoin('lead_source_types as lst','lst.id','=','ls.type_id')
            ->select('ls.*','lst.name as type_name')
            ->orderBy('ls.name')
            ->get();
        return view('admin.lead-sources.index', compact('sources'));
    }

    public function create()
    {
        $types = DB::table('lead_source_types')->orderBy('name')->get();
        return view('admin.lead-sources.create', compact('types'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'          => 'required|string|max:255|unique:lead_sources,name',
            'short_code'    => 'nullable|string|max:50|unique:lead_sources,short_code',
            'description'   => 'nullable|string',
            'contact_name'  => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'type_id'       => 'nullable|exists:lead_source_types,id',
        ]);
        DB::table('lead_sources')->insert($data + ['created_at'=>now(),'updated_at'=>now()]);
        return redirect()->route('lead-sources.index');
    }

    public function edit($id)
    {
        $source = DB::table('lead_sources')->find($id);
        abort_if(!$source, 404);
        $types = DB::table('lead_source_types')->orderBy('name')->get();
        return view('admin.lead-sources.edit', compact('source','types'));
    }

    public function update(Request $r, $id)
    {
        $data = $r->validate([
            'name'          => 'required|string|max:255|unique:lead_sources,name,'.$id,
            'short_code'    => 'nullable|string|max:50|unique:lead_sources,short_code,'.$id,
            'description'   => 'nullable|string',
            'contact_name'  => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'type_id'       => 'nullable|exists:lead_source_types,id',
        ]);
        DB::table('lead_sources')->where('id',$id)->update($data + ['updated_at'=>now()]);
        return redirect()->route('lead-sources.index');
    }

    public function destroy($id)
    {
        DB::table('lead_sources')->where('id',$id)->delete();
        return back();
    }
}
