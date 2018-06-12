<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Cast extends Model
{
  use Searchable;

    protected $fillable = ['name', 'male', 'female', 'birth', 'details', 'image'];

    public function movies()
    {
      return $this->belongsToMany('App\Movie')->withTimestamps();
    }
}
