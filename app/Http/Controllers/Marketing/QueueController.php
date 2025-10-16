<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index()
    {
        $queues = Queue::orderBy('created_at','desc')->paginate(20);
        return view('queues.index', compact('queues'));
    }

    public function create()
    {
        return view('queues.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'    => ['required','string','max:100'],
            'filters' => ['nullable','array'],
        ]);
        $q = Queue::create([
            'name' => $data['name'],
            'filters' => $data['filters'] ?? [],
            'created_by' => auth()->id(),
        ]);
        return redirect()->route('queues.show',$q)->with('status','Queue saved');
    }

    public function show(Queue $queue)
    {
        return view('queues.show', compact('queue'));
    }

    public function edit(Queue $queue)
    {
        return view('queues.edit', compact('queue'));
    }

    public function update(Request $r, Queue $queue)
    {
        $data = $r->validate([
            'name'    => ['required','string','max:100'],
            'filters' => ['nullable','array'],
        ]);
        $queue->update($data);
        return redirect()->route('queues.show',$queue)->with('status','Queue updated');
    }

    public function destroy(Queue $queue)
    {
        $queue->delete();
        return redirect()->route('queues.index')->with('status','Queue deleted');
    }
}
