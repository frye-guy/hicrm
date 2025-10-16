<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    protected $fillable = [
        'name',
        'short_code',
        'description',
        'type',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
