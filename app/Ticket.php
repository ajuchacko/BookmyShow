<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model
{
  protected $guarded = [];

    public function scopeAvailable($query)
    {
      return $query->whereNull('order_id')->whereNull('reserved_at');
    }

    public function reserve()
    {
      return $this->update(['reserved_at' => Carbon::now()]);
    }

    public function release()
    {
      return $this->update(['reserved_at' => null]);
    }

    public function movie()
    {
      return $this->belongsTo(Movie::class);
    }

    public function getPriceAttribute()
    {
      return $this->movie->ticket_price;
    }
}
