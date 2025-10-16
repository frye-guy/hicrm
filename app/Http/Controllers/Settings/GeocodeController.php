<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    public function geocode(Contact $contact, Request $request)
    {
        // if already has lat/lng, skip
        if ($contact->lat && $contact->lng) {
            return response()->json(['ok'=>true, 'skipped'=>true]);
        }

        $key = Setting::where('key','google_maps_key')->value('value');
        if (!$key) {
            return response()->json(['ok'=>false, 'error'=>'Missing Google Maps API key'], 422);
        }

        $address = trim(implode(' ', array_filter([
            $contact->address, $contact->city, $contact->state, $contact->zip
        ])));

        if (!$address) {
            return response()->json(['ok'=>false, 'error'=>'No address to geocode'], 422);
        }

        $res = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $key,
        ]);

        if (!$res->ok()) {
            return response()->json(['ok'=>false, 'error'=>'Geocode request failed'], 500);
        }

        $json = $res->json();
        if (($json['status'] ?? '') !== 'OK') {
            return response()->json(['ok'=>false, 'error'=>$json['status'] ?? 'Unknown'], 422);
        }

        $location = $json['results'][0]['geometry']['location'] ?? null;
        if (!$location) {
            return response()->json(['ok'=>false, 'error'=>'No location found'], 422);
        }

        $contact->lat = $location['lat'];
        $contact->lng = $location['lng'];
        $contact->saveQuietly();

        return response()->json(['ok'=>true, 'lat'=>$contact->lat, 'lng'=>$contact->lng]);
    }
}
