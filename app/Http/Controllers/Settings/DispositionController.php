<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Disposition;
use Illuminate\Http\Request;

class DispositionController extends Controller
{
    public function index()
    {
        $items = Disposition::orderBy('name')->paginate(25);
        return view('settings.dispositions.index', compact('items'));
    }

    public function create()
    {
        return view('settings.dispositions.form', ['item'=>new Disposition()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required','string','max:255','unique:dispositions,name'],
            'active' => ['nullable','boolean'],
            'row_color' => ['nullable','string','max:20'],
            'is_positive' => ['nullable','boolean'],
            'is_final'    => ['nullable','boolean'],
        ]);

        Disposition::create($data);
        return redirect()->route('settings.dispositions.index')->with('status','Disposition created.');
    }

    public function edit(Disposition $disposition)
    {
        return view('settings.dispositions.form', ['item'=>$disposition]);
    }

    public function update(Request $request, Disposition $disposition)
    {
        $data = $request->validate([
            'name'   => ['required','string','max:255','unique:dispositions,name,'.$disposition->id],
            'active' => ['nullable','boolean'],
            'row_color' => ['nullable','string','max:20'],
            'is_positive' => ['nullable','boolean'],
            'is_final'    => ['nullable','boolean'],
        ]);

        $disposition->update($data);
        return redirect()->route('settings.dispositions.index')->with('status','Disposition updated.');
    }

    public function destroy(Disposition $disposition)
    {
        $disposition->delete();
        return back()->with('status','Disposition deleted.');
    }
}
