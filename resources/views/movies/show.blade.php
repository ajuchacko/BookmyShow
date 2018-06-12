@extends('layouts.app')



@section('content')
<div class="container checkout col-xs-12 col-sm-12 col-xl-9">
  <div class="jumbotron py-3" id="checkout">
    <div class="text-center font-weight-bold mb-5 ">
    <h1 class="display-5">{{ $movie->name }}</h1>
  </div>
  <div class="row">
    <div class="col-md-4">
      <img src="{{asset('images/'. $movie->image )}}" alt="{{$movie->name}}">
    </div>
      <div class="col-md-8 mt-4">
        <span class="badge badge-pill badge-light px-2 py-2">{{ ucwords($movie->language) }}</span>
        <span class="badge badge-pill badge-primary mb-3 ml-4 px-2 py-2">{{ ucwords($movie->genre) }}</span>
        <p><b>{!! file_get_contents(asset('images/calendar.svg')) !!}Released: </b> {{ $movie->formatted_date }}</p>
        <p><b>{!! file_get_contents(asset('images/clock.svg')) !!}Duration: </b>{{ $movie->duration }}</p>
        {{-- <input type="text" id="price" v-model="price" v-text="{{$movie->ticket_price}}"> --}}
        <p><b>{!! file_get_contents(asset('images/dollar.svg')) !!}Price: {{$movie->ticket_price_in_dollars}}</b> @{{price}}</p>
        <p v-if="totalPrice != price"><b>{!! file_get_contents(asset('images/dollar.svg')) !!}Total price</b>: <span>@{{totalPrice}}</span></p>
      </div>
    </div>
    <div class="container">

    <div class="row">
    <p class="lead"></p>
    <hr class="my-3 ">

    <form action="{{route('checkout')}}" class="col-md-6" method="POST">
        @csrf
        <label for="ticketQuantity" class="form-lable text-center">Quanity</label>
        <input type="hidden" value="{{$movie->id}}" name="movie">
        <input type="number" min="1" v-model="quantity" name="ticketQuantity"
        class="mr-2 px-2 py-2 col-md-4 col-xs-3 mb-3">
        <button class="btn btn-primary btn-lg col-md-5" role="button">Buy Tickets</button>

    </form>


  </div>
  </div>

  </div>


<div class="jumbotron jumbotron-fluid py-3">
  <div class="container">
    <h3 class="display-6">Summary</h3>
    <hr class="my-2">
    <p class="lead">{{$movie->summary}}</p>

  </div>
  </div>

  <div class="container col-xs-12 col-sm-12 col-xl-12">

    <h3 class="display-6">Cast</h3>
    <hr class="my-2">

<div class="row">

  @foreach ($movie->casts->sortBy('name') as $cast)

    <div class="card mr-5 mt-3" style="width: 18rem;">
    <a href="{{route('casts.show', ['id' => $cast->id])}}"><img class="card-img-top"  src={{asset("casts/$cast->image")}} alt="{{$cast->name}}">
    <div class="card-body">
      <p class="card-text text-success">{{ucwords($cast->name)}}</p></a>
      <p class="card-text text-secondary">{{ $cast->male ? 'Actor' : 'Actress' }}</p>
    </div>
    </div>

  @endforeach

    {{-- <div class="card mr-5 mt-3" style="width: 18rem;">
    <img class="card-img-top"  src={{asset('images/tomHanks.jpg')}} alt="Card image cap">
    <div class="card-body">
      <p class="card-text text-success">Tom Hanks</p>
      <p class="card-text text-secondary">Actor</p>
    </div>
    </div>

    <div class="card mr-5 mt-3" style="width: 18rem;">
    <img class="card-img-top"  src={{asset('images/tomHanks.jpg')}} alt="Card image cap">
    <div class="card-body">
      <p class="card-text text-success">Tom Hanks</p>
      <p class="card-text text-secondary">Actor</p>
    </div>
    </div>

    <div class="card mr-5 mt-3" style="width: 18rem;">
    <img class="card-img-top"  src={{asset('images/tomHanks.jpg')}} alt="Card image cap">
    <div class="card-body">
      <p class="card-text text-success">Tom Hanks</p>
      <p class="card-text text-secondary">Actor</p>
    </div>
    </div> --}}




  </div>
    </div>

    <div class="container mt-5">
      <h3 class="display-6"><span>{!! file_get_contents(asset('images/video.svg')) !!}</span>Trailer</h3>
      <hr class="my-2 pb-2">
      <div class="embed-responsive embed-responsive-16by9"  class="w-25 p-3">
  <iframe class="embed-responsive-item" src="{{$movie->trailer}}"></iframe>
</div>
    </div>
</div>

  {{-- <p>{{ $movie->trailer }}</p> --}}
  {{-- <script>
  console.log(foo);
  var el = document.getElementById('price');
  el.innerHTML = 'foo';
  </script> --}}


@endsection
