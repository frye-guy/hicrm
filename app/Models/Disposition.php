<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    use HasFactory;

    // use the columns you actually have; these match what we seeded earlier
    protected $fillable = [
        'name',
        'slug',
        'is_positive',
        'is_final',
        'row_color',
        // keep these only if you really have them:
        // 'type','code','label','active',
    ];

    public function notes()
    {
        return $this->hasMany(\App\Models\Note::class);
    }
}
