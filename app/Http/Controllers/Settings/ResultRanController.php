<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ResultRan;
use Illuminate\Http\Request;

class ResultRanController extends Controller
{
    public function index()
    {
        $items = ResultRan::orderBy('name')->paginate(25);
        return view('settings.resultrans.index', compact('items'));
    }

    public function create()
    {
        return view('settings.resultrans.form', ['item'=>new ResultRan()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required','string','max:255','unique:result_rans,name'],
            'active' => ['nullable','boolean'],
        ]);
        ResultRan::create($data);
        return redirect()->route('settings.result-rans.index')->with('status','Result Ran created.');
    }

    public function edit(ResultRan $resultRan)
    {
        return view('settings.resultrans.form', ['item'=>$resultRan]);
    }

    public function update(Request $request, ResultRan $resultRan)
    {
        $data = $request->validate([
            'name'   => ['required','string','max:255','unique:result_rans,name,'.$resultRan->id],
            'active' => ['nullable','boolean'],
        ]);
        $resultRan->update($data);
        return redirect()->route('settings.result-rans.index')->with('status','Result Ran updated.');
    }

    public function destroy(ResultRan $resultRan)
    {
        $resultRan->delete();
        return back()->with('status','Result Ran deleted.');
    }
}
