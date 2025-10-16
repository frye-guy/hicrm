<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id','sales_rep_id','subproduct_id',
        'scheduled_for','duration_min','appointment_disposition_id',
        'is_sale','finance_disposition_id','job_status_disposition_id',
        'set_by','meta','date_set','set_by','set_with','interested_in','result','notes'
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'is_sale' => 'boolean',
        'meta' => 'array',
    ];

    public function contact(){ return $this->belongsTo(Contact::class); }
    public function setter(){ return $this->belongsTo(User::class, 'set_by'); }
}
