<?php

namespace Database\Seeders;

use App\Models\Disposition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DispositionSeeder extends Seeder
{
    public function run(): void
    {
    $rows = [
        ['name'=>'Lead Set','slug'=>'lead-set','is_positive'=>true,'is_final'=>false,'row_color'=>'#2563eb'],
        ['name'=>'Confirmed','slug'=>'confirmed','is_positive'=>true,'is_final'=>false,'row_color'=>'#16a34a'],
        ['name'=>'Sold','slug'=>'sold','is_positive'=>true,'is_final'=>true,'row_color'=>'#0ea5e9'],
        ['name'=>'Not Home','slug'=>'not-home','is_positive'=>false,'is_final'=>false,'row_color'=>'#9ca3af'],
        ['name'=>'Not Interested','slug'=>'not-interested','is_positive'=>false,'is_final'=>true,'row_color'=>'#ef4444'],
    ];

    foreach ($rows as $r) {
        \App\Models\Disposition::firstOrCreate(['slug'=>$r['slug']], $r);
    }

    }


}

