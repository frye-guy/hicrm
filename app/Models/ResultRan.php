<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultRan extends Model
{
    protected $fillable = ['name','active'];
    protected $casts = ['active'=>'boolean'];
}
