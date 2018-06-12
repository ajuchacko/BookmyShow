<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    protected $fillable = ['name', 'male', 'female', 'birth', 'details', 'image'];

    public function movies()
    {
      return $this->belongsToMany('App\Movie')->withTimestamps();
    }
}
