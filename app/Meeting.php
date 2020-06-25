<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
     protected $guarded = ['id'];


     public function users()
     {
          return $this->belongsToMany(User::class);
     }
}
