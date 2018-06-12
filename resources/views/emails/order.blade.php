<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Successful Order</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center">

      <p>Dear Customer,<br>
      You have Successfully ordered tickets({{$order->email}}). You have paid {{'$'.number_format($order->amount/100,2) }} in total.
      You can get print of your tickets at this <a href="{{ url('orders/'.$order->confirmation_number) }}">link</a>.
      Your order confirmation number is <b>{{$order->confirmation_number}}</b>. You're advised to bring the tickets at the venue.
      Feel free to contact us if you face any kind of problems.<br><br><br>
      Bookmyshowteam,<br><br>
      Enjoy!</p>

      </div>
    </div>
  </body>
</html>
