<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGateway;
use App\Billing\PaymentFailedException;
use App\Movie;
use App\Ticket;
use App\Order;
use App\Reservation;
use App\Exceptions\NotEnoughTicketsException;
use App\Mail\OrderSuccessful;
use Illuminate\Support\Facades\Mail;

class MovieOrdersController extends Controller
{
  private $paymentGateway;


  public function __construct(PaymentGateway $paymentGateway)
  {
    $this->paymentGateway = $paymentGateway;
  }


  public function store($movieId)
  {
    $movie = Movie::published()->findOrFail($movieId);
if(request()->has('stripeEmail')){
    $this->validate(request(), [
      'stripeEmail' => ['required', 'email'],
      'quantity' => ['required', 'integer', 'min:1'],
      'stripeToken' => ['required']
    ]);
  } else {
    //original
    $this->validate(request(), [
      'email' => ['required', 'email'],
      'ticket_quantity' => ['required', 'integer', 'min:1'],
      'payment_token' => ['required']
    ]);
  }

    try {
        if(request()->has('stripeEmail')){
          $reservation = $movie->reserveTickets(request('quantity'), request('stripeEmail'));
          $order = $reservation->complete($this->paymentGateway, request('stripeToken'));

        } else {
          //original
        $reservation = $movie->reserveTickets(request('ticket_quantity'), request('email'));


      // $this->paymentGateway->charge($reservation->totalCost(), request('payment_token'));

      // $order = $movie->createOrder(request('email'), $tickets);

      $order = $reservation->complete($this->paymentGateway, request('payment_token'));
        }
        if(request()->has('stripeEmail')){
      Mail::to($order->email)->send(new OrderSuccessful($order));

      return redirect()->action('OrdersController@show', ['id' => $order['confirmation_number']]);
}
      return response()->json($order, 201);
    } catch(PaymentFailedException $e) {
      $reservation->cancel();
      return response()->json(['Payment Failed'], 422);
    } catch(NotEnoughTicketsException $e) {
      return response()->json(['Not Enough Tickets available'], 422);
    }
  }



}
