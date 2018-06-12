@extends('layouts.app')

@section('content')
<div class="container col-md-6" id="checkout">
  <div class="card text-center">
{{-- <div class="card-header">

</div> --}}
<div class="card-body">
  <h3 class="card-title">{{$movie->name}}</h3>
  <h4 class="card-text">Total Quantity: {{$quantity}}</h4>
</div>

</div>
</div>
<div class="container mt-4">
<div class="row">
<div class="col-md-12 text-center">
<form action="{{action('MovieOrdersController@store', ['movie' => $movie->id]) }}" method="POST">
  <input type="hidden" name="quantity" value="{{$quantity}}">
  <input type="hidden" name="id" value="{{$movie->id}}">
  @csrf
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_pIoK3RQnZeaNcLhll3zmu9CT"
    data-amount="3200"
    data-name="Movies"
    data-description="Grab Your Tickets"
    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
    data-locale="auto"
    data-zip-code="false"
    data-label="Confirm"
    data-allow-remember-me="false"
    data-image="~/Users/atom/Sites/movies/public/images/movie.jpg">
  </script>
</form>
</div>
</div>
</div>

<div class="container">
<table>
  <thead>
      <tr>
        <th>Number</th>
        <th>Brand</th>
      </tr>
  </thead>
  <tbody>
    <tr>
      <td>4242424242424242</td>
        	<td>Visa</td>
      </tr>
    <tr>
      <td>4000056655665556</td>
      <td>	Visa (debit)</td>
    </tr>
    <tr>
      <td>5555555555554444</td>
      <td>	Mastercard</td>
    </tr>
    <tr>
      <td>2223003122003222</td>
      <td>	Mastercard (2-series)</td>
    </tr>
    <tr>
      <td>5200828282828210</td>
      <td>	Mastercard (debit)</td>
    </tr>
    <tr>
      <td>5105105105105100</td>
      <td>	Mastercard (prepaid)</td>
    </tr>
    <tr>
      <td>378282246310005	</td>
      <td>American Express</td>
    </tr>
    <tr>
      <td>371449635398431	</td>
      <td>American Express</td>
    </tr>
    <tr>
      <td>6011111111111117</td>
      <td>	Discover</td>
    </tr>
    <tr>
      <td>6011000990139424</td>
      <td>	Discover</td>
    </tr>
    <tr>
      <td>30569309025904	D</td>
      <td>iners Club</td>
    </tr>
    <tr>
      <td>38520000023237	D</td>
      <td>iners Club</td>
    </tr>
    <tr>
      <td>3566002020360505</td>
      <td>	JCB</td>
    </tr>
    <tr>
      <td>6200000000000005</td>
      <td>	UnionPay</td>
    </tr>
  </tbody>
</table>

</div>
@endsection
