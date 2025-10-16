<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function edit()
    {
        $googleMapsKey = Setting::where('key','google_maps_key')->value('value');
        return view('settings.apis.edit', compact('googleMapsKey'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'google_maps_key' => ['nullable','string','max:255'],
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key'=>'google_maps_key'],
            ['group'=>'google','value'=>$data['google_maps_key'] ?? null]
        );

        return back()->with('status','API keys updated.');
    }
}
