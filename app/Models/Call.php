<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model {
  protected $fillable = ['contact_id','user_id','started_at','ended_at','duration_sec','disposition_id','notes'];
  protected $casts = ['started_at'=>'datetime','ended_at'=>'datetime'];
  public function contact(){ return $this->belongsTo(Contact::class); }
  public function user(){ return $this->belongsTo(User::class); }
  public function disposition(){ return $this->belongsTo(Disposition::class); }
}
