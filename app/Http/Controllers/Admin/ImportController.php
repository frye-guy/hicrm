<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function form(){ return view('admin.import'); }

    public function store(Request $r)
    {
        $r->validate(['csv'=>['required','file','mimes:csv,txt']]);
        $path = $r->file('csv')->getRealPath();
        $rows = array_map('str_getcsv', preg_split("/\r\n|\n|\r/", file_get_contents($path)));
        $header = array_map('trim', array_shift($rows));

        DB::transaction(function() use ($rows,$header){
            foreach ($rows as $row) {
                if (!$row || count($row) < 1) continue;
                $data = array_combine($header, $row);
                $phone = isset($data['phone']) ? preg_replace('/\D+/','', $data['phone']) : null;
                $email = $data['email'] ?? null;
                if (!$phone && !$email) continue;

                $exists = Contact::query()
                    ->when($phone, fn($q)=>$q->where('phone',$phone))
                    ->when($email, fn($q)=>$q->orWhere('email',$email))
                    ->first();
                if ($exists) continue;

                Contact::create([
                    'first_name'=>$data['first_name'] ?? '—',
                    'last_name'=>$data['last_name'] ?? '—',
                    'phone'=>$phone,
                    'email'=>$email,
                    'address'=>$data['address'] ?? null,
                    'city'=>$data['city'] ?? null,
                    'state'=>$data['state'] ?? null,
                    'zip'=>$data['zip'] ?? null,
                ]);
            }
        });

        return back()->with('status','Import complete');
    }
}
