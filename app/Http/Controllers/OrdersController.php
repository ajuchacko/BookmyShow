<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
// use Spatie\Browsershot\Browsershot;

class OrdersController extends Controller
{
    public function show($confirmationNumber)
    {
      $order = Order::findByConfirmationNumber($confirmationNumber);
      return view('orders.show',['order' => $order]);
    }
  //
  //   public function Download($confirmationNumber)
  //   {
  //
	// $html =  Browsershot::url("http://bookmyshow.dev/orders/{$confirmationNumber}")
  //       		->ignoreHttpsErrors()
  //       		->fullPage()
  //       		->bodyHtml();
  //
  // 	Browsershot::html($html)->save('/Users/atom/Sites/bookmyshow/public/casts/ticket.pdf');
  //
  //   }
}
