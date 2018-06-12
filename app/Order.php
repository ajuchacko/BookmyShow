<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Facades\OrderConfirmationNumber;
use App\Facades\TicketCode;

class Order extends Model
{
    protected $guarded = [];

    public function movie()
    {
      return $this->belongsTo('App\Movie');
    }

    public function tickets()
    {
      return $this->hasMany('App\Ticket');
    }

    public static function forTickets($tickets, $email, $charge)
    {
      $order = self::create([
         'confirmation_number' => OrderConfirmationNumber::generate(),
         // 'confirmation_number' => app(OrderConfirmationNumberGenerator::class)->generate(),
         'email' => $email,
         'amount' => $charge->amount(),
         'card_last_four' => $charge->cardLastFour()
        ]);

      foreach ($tickets as $ticket) {
        $ticket->code = TicketCode::generate();
        $order->tickets()->save($ticket);
      }
      return $order;
    }

    public static function findByConfirmationNumber($confirmationNumber)
    {
      return self::where('confirmation_number', $confirmationNumber)->firstOrFail();
    }

    // public static function fromReservation($reservation)
    // {
    //   $order = self::create([
    //      'email' => $reservation->email(),
    //      'amount' => $reservation->totalCost(),
    //     ]);
    //
    //     $order->tickets()->saveMany($reservation->tickets());
    //
    //   return $order;
    // }

    // public function cancel()
    // {
    //   $tickets = $this->tickets()->get();
    //   // $tickets = $this->tickets; // Another way Ads
    //   foreach ($tickets as $ticket) {
    //     $ticket->release();
    //   }
    //   $this->delete();
    // }

    public function toArray()
    {
      return [
        'confirmation_number' => $this->confirmation_number,
        'email' => $this->email,
        'ticket_quantity' => $this->ticketQuantity(),
        'amount' => $this->amount,
      ];
    }

    public function ticketQuantity()
    {
      return $this->tickets()->count();
    }
}
