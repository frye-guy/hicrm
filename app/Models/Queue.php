<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model {
  protected $fillable=['name','filters','created_by'];
  protected $casts=['filters'=>'array'];
}
