<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'phone',
        'dispo',
        'external_id',
        'needs_reset',

        'first_name',
        'spouse',
        'last_name',
        'email',

        'address',
        'city',
        'state',
        'zip',

        'mr_works',
        'mrs_works',
        'alt_phone',
        'alt_phone2',
        'alt_phone3',

        'search_tool',
        'age_of_home',
        'home_type',
        'color_of_home',
        'years_owned',

        'lat',
        'lng',
        'zone',

        'lead_source_id',
    ];

    protected $casts = [
        'needs_reset'   => 'boolean',
        'lat'           => 'decimal:7',
        'lng'           => 'decimal:7',
        'lead_source_id'=> 'integer',
    ];

public function notes()
{
    return $this->hasMany(\App\Models\Note::class)->latest();
}

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }
}
