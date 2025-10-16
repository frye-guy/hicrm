<?php

namespace Database\Seeders;

use App\Models\Disposition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DispositionSeeder extends Seeder
{
    public function run(): void
    {
        $sets = [
            'call' => ['NO_ANSWER','BUSY','CALLBACK','LEFT_VOICEMAIL','DO_NOT_CALL','CONNECTED','HUNG_UP'],
            'appointment' => ['BOOKED','CONFIRMED','RESCHEDULED','NO_SHOW','COMPLETED'],
            'sale' => ['SOLD','UNSOLD'],
            'finance' => ['APPROVED','PENDING','DECLINED'],
            'job' => ['SCHEDULED','IN_PROGRESS','INSTALLED','CANCELED'],
        ];

        foreach ($sets as $type => $codes) {
            foreach ($codes as $code) {
                Disposition::firstOrCreate(
                    ['type' => $type, 'code' => $code],
                    ['label' => Str::of($code)->lower()->replace('_',' ')->title()]
                );
            }
        }
    }
}
