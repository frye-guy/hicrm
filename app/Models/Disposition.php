<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model {
  protected $fillable = ['type','code','label','active'];
}
public function notes()
{
    return $this->hasMany(\App\Models\Note::class);
}