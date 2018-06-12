<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
      $movie = Movie::findOrFail($request->movie);
      return view('checkout', ['movie' => $movie])->withQuantity($request->ticketQuantity);
    }
    //
    // public function pay(Request $request)
    // {
    //   $email = $request->stripeEmail;
    //   $token = $request->stripeToken;
    //   $quantity = $request->quantity;
    //
    //   $json = json_encode(['email' => $email, 'payment_token' => $token, 'quantity' => $quantity]);
    //
    //   die();
    //
    // }
}
