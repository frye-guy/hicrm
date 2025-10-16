<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ConfirmationResult;
use Illuminate\Http\Request;

class ConfirmationResultController extends Controller
{
    public function index()
    {
        $items = ConfirmationResult::orderBy('name')->paginate(25);
        return view('settings.results.index', compact('items'));
    }

    public function create()
    {
        return view('settings.results.form', ['item'=>new ConfirmationResult()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required','string','max:255','unique:confirmation_results,name'],
            'active' => ['nullable','boolean'],
        ]);
        ConfirmationResult::create($data);
        return redirect()->route('settings.results.index')->with('status','Result created.');
    }

    public function edit(ConfirmationResult $result)
    {
        return view('settings.results.form', ['item'=>$result]);
    }

    public function update(Request $request, ConfirmationResult $result)
    {
        $data = $request->validate([
            'name'   => ['required','string','max:255','unique:confirmation_results,name,'.$result->id],
            'active' => ['nullable','boolean'],
        ]);
        $result->update($data);
        return redirect()->route('settings.results.index')->with('status','Result updated.');
    }

    public function destroy(ConfirmationResult $result)
    {
        $result->delete();
        return back()->with('status','Result deleted.');
    }
}
