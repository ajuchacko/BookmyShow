@extends('layouts.app')



@section('content')
<div class="container checkout col-xs-12 col-sm-12 col-xl-9">
  <div class="jumbotron py-3" id="checkout">
    <div class="text-center font-weight-bold mb-5 ">
    <h1 class="display-5"><a href="#">{{ ucwords($cast->name) }}</a></h1>
  </div>
  <div class="row">
    <div class="col-md-4">
      <img src="{{asset('casts/'. $cast->image )}}" alt="{{$cast->name}}" class="img-thumbnail">
    </div>
      <div class="col-md-8 mt-4">
        <p>{{ ucwords($cast->birth) }}</p>
        <p>{{ ($cast->male) ? "Male" : '' }}</p>
        <p>{{ ($cast->female) ? "Female" : '' }}</p>
      </div>
    </div>
    <div class="container">

    <div class="row">
    <p class="lead"></p>
    <hr class="my-3 ">


  </div>
  </div>

  </div>


<div class="jumbotron jumbotron-fluid py-3">
  <div class="container">
    <h3 class="display-6">Details</h3>
    <hr class="my-2">
    <p class="lead">{{$cast->details}}</p>

  </div>
  </div>
<div class="row">

  @foreach ($cast->movies->sortBy('name') as $movie)

    <div class="card mr-5 mt-3" style="width: 18rem;">
    <a href="{{route('movies.show', ['id' => $movie->id])}}"><img class="card-img-top"  src={{asset("casts/$cast->image")}} alt="{{$movie->name}}">
    <div class="card-body">
      <p class="card-text text-success">{{ucwords($movie->name)}}</p></a>
    </div>
    </div>

  @endforeach
</div>

</div>

  {{-- <p>{{ $cast->trailer }}</p> --}}
  {{-- <script>
  console.log(foo);
  var el = document.getElementById('price');
  el.innerHTML = 'foo';
  </script> --}}


@endsection
