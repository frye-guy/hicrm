<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'contact_id','user_id','disposition_id','body','follow_up_at',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
    ];

    public function contact()     { return $this->belongsTo(\App\Models\Contact::class); }
    public function user()        { return $this->belongsTo(\App\Models\User::class); }
    public function disposition() { return $this->belongsTo(\App\Models\Disposition::class); }
}
