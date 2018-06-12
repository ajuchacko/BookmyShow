<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotEnoughTicketsException;

class Movie extends Model
{
    protected $guarded = [];

    protected $dates = ['release_date'];

    public function casts()
    {
      return $this->belongsToMany('App\Cast')->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeUnpublished($query)
    {
        return $query->whereNull('published_at');
    }


    public function getDurationAttribute($valueInSeconds)
   {
     // $hours = number_format((float)($valueInSeconds / 3600), 4);
     // $hour_array = explode('.', $hours);
     // $minutes = number_format( ($hour_array[1] / 10000) * 60);
     // return $hour_array[0].'hrs ' . $minutes.'m';

     $hours = floor($valueInSeconds / 3600);
     $minutes = floor(($valueInSeconds / 60) % 60);
     $seconds = $valueInSeconds % 60;

       return $hours .'hrs ' . $minutes . 'm';

       //alternative
            // echo gmdate("H:i:s", 685); //works if seconds is below 86,400
            // OR

   }

   public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords($name);
    }

    public function setSummaryAttribute($summary)
    {
        $this->attributes['summary'] = ucwords($summary);
    }

    public function getFormattedDateAttribute()
    {
      return $this->release_date->format('j F, Y');
    }

    public function getTicketPriceInDollarsAttribute()
    {
      return number_format($this->ticket_price/100, 2);
    }

    public function orders()
    {
      // return $this->hasMany('App\Order');
      return $this->belongsToMany('App\Order', 'tickets');
    }

    public function tickets()
    {
      return $this->hasMany('App\Ticket');
    }

    // public function orderTickets($email, $ticketQuantity)
    // {
    //
    //   $tickets = $this->findTickets($ticketQuantity);
    //
    //   $order = $this->createOrder($email, $tickets);
    //   return $order;
    // }

    public function hasOrderFor($email)
    {
        return $this->orders()->where('email', $email)->count() > 0;
    }

    public function ordersFor($email)
    {
        return $this->orders()->where('email', $email)->get();
    }

    public function reserveTickets($quantity, $email)
    {
      $tickets =  $this->findTickets($quantity)->each(function ($ticket) {
        $ticket->reserve();
      });
      return new Reservation($tickets, $email);
    }


    function findTickets($quantity)
    {
      $tickets = $this->tickets()->available()->take($quantity)->get();

      if($tickets->count() < $quantity) {
        throw new NotEnoughTicketsException;
      }
      return $tickets;
    }


    // function createOrder($email, $tickets)
    // {
    //   return Order::forTickets($tickets, $email, $tickets->sum('price'));
    // }


    public function addTickets($quantity)
    {

      foreach (range(1, $quantity) as $key) {
        $this->tickets()->create([]);
      }
      return $this;
    }

    public function ticketsRemaining()
    {
      return $this->tickets()->available()->count();
    }

}
