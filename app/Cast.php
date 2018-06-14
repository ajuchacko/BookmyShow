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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'details' => $this->details
        ];
    }
}
