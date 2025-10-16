<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'active',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
