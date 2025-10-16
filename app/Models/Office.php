<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'address', 'lat', 'lng',
    ];

    // Example relationships you may want:
    public function users()
    {
        return $this->hasMany(User::class, 'primary_office_id');
    }
}
