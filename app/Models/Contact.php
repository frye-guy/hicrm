<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contact extends Model
{
use HasFactory;


protected $fillable = [
        'first_name','last_name','phone','email',
        'address','city','state','zip',
        'lead_source_id','office_id','interests','tags',
        'consent_call','score','owner_id','import_batch_id',  'external_id','source','disposition','needs_reset',
  'spouse_name','phone2','alt_phone','alt_phone2','alt_phone3',
  'address','city','state','zip',
  'mr_works','mrs_works','age_of_home','type_of_home','color_of_home',
  'years_owned','search_tools','latitude','longitude','zone','lead_source_id',
];

protected $casts = [
'interests' => 'array',
'tags' => 'array',
'consent_call' => 'boolean',
];


public function leadSource()
{
    return $this->belongsTo(\App\Models\LeadSource::class);
}
public function source() { return $this->belongsTo(LeadSource::class, 'lead_source_id'); }
public function office() { return $this->belongsTo(Office::class); }
public function calls() { return $this->hasMany(Call::class); }
public function appointments() { return $this->hasMany(Appointment::class); }
}