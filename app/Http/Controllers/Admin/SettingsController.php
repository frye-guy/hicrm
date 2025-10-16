<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(){ return view('settings.index', ['settings'=>$this->map()]); }

    public function update(Request $r)
    {
        $data = $r->validate([
            'brand_name'=>['nullable','string','max:120'],
            'dial_template'=>['nullable','string','max:255'],
        ]);
        $this->put('brand.name', $data['brand_name'] ?? '');
        $this->put('integrations.3cx.link_template', $data['dial_template'] ?? '');
        return back()->with('status','Settings saved');
    }

    private function put($key,$value){ Setting::updateOrCreate(['key'=>$key],['value'=>$value]); }
    private function map(){
        return [
            'brand_name' => optional(Setting::where('key','brand.name')->first())->value,
            'dial_template' => optional(Setting::where('key','integrations.3cx.link_template')->first())->value,
        ];
    }
}
