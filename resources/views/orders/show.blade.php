@extends('layouts.app')

@section('content')



    {{-- {{$ticket->code}} --}}

    {{-- {{$ticket->movie->name}}

    {{$ticket->movie->image}}
    {{$ticket->movie->genre}}
    {{$ticket->movie->language}}
    {{$ticket->movie->duration}}
    {{$ticket->movie->ticket_price}} --}}

    <div class="container">
      <div class="row text-left">
        <div class="col-sm-12">
        <div class="">
          <h2><span>Order Summary</span></h2>
          <hr class="my-2 pb-2">
          <h5><b>Order Total {{'$'.number_format($order->amount/100,2)}}</b></h5>
          <h5 class="text-muted">Billed to Card #: {{'**** **** **** ' . $order->card_last_four}}</h5>
          <p  class="font-weight-normal">Order Confirmation Number: <a href="/orders/{{$order->confirmation_number}}">{{$order->confirmation_number}}</a></p>
          <p class="font-weight-light text-right">Email: {{$order->email}}</p>
          <hr class="my-4 pb-2 ">
          <h3>Your Tickets</h3>
          @foreach($order->tickets as $ticket)
            <div class="card my-3">
            <h4 class="card-header text-left">{{$loop->iteration .' '}}{{$ticket->movie->name}}</h4><div class="text-right text-muted py-1 px-1">
              <span>Admit one</span>
            </div>

            <div class="card-body">


              <div class="row">
                <div class="col-sm-6">
                  <div class="card">
                    <div class="card-body">
                      <p class="card-title font-weight-normal">Genre - {{$ticket->movie->genre}}</p>
                      <p class="card-text font-weight-normal">Language - {{$ticket->movie->language}}</p>
                      <hp class="card-title font-weight-light">Duration - {{$ticket->movie->duration}}</p>
                    </div>
                  </div>
                </div>

                {{-- <div class="col-sm-6">
                  <div class="card">
                    <div class="card-body">
                      <p class="card-text"></p>
                    </div>
                  </div>
                </div> --}}
              </div>


              <div class="card-footer">
               <h5 class="text-bold">{{$ticket->code}}</h5>
             </div>


            </div>
            </div>
          @endforeach
        </div>
          <div class="">
          </div>
      </div>
    </div>
  </div>
  <style>
    .card-footer {
      background-color:  white;
    }
    #app {
      background-color: white;
    }
  </style>
@endsection
