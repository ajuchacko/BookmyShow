@extends('layouts.app')

@section('content')
  <div class="container">

      <form class="col-md-10" action="{{route('movies.update', ['id' => $movie->id])}}" method="post" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
  <div class="form-group row">
    <label for="" class="col-sm-2 col-form-label">Movie Name</label>
    <div class="col-sm-5">
      <input type="text" name="name" class="form-control" id="" placeholder="Name" value="{{ $movie->name ? $movie->name : ''}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Movie Language</label>
    <div class="col-sm-5">
      <input type="text" name="language" class="form-control" id="inputPassword3" placeholder="language" value="{{ $movie->language ? $movie->language : ''}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Movie Genre</label>
    <div class="col-sm-5">
      <input type="text" name="genre" class="form-control" id="inputPassword3" placeholder="genre" value="{{ $movie->genre ? $movie->genre : ''}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Add trailer (embed)</label>
    <div class="col-sm-5">
      <input type="text" name="trailer" class="form-control" id="inputPassword3" placeholder="trailer" value="{{ $movie->trailer ? $movie->trailer : ''}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Ticket Price</label>
    <div class="col-sm-5">
      <input type="text" name="ticket_price" class="form-control" id="inputPassword3" placeholder="price" value="{{ $movie->ticket_price ? $movie->ticket_price : ''}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Release Date</label>
    <div class="col-sm-5">
      <input type="text" name="release_date" class="form-control" id="inputPassword3" placeholder="Release" value="{{ $movie->release_date ? $movie->release_date : ''}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Movie Duration</label>
    <div class="col-sm-5">
      <input type="text" name="duration" class="form-control" id="inputPassword3" placeholder="Duration" value="{{ $movie->duration ? $movie->getOriginal('duration') : ''}}">
    </div>
  </div>

  <div class="form-group row boxes">
    <label for="cast" class="col-sm-2 col-form-label">Casts</label>
    <div class="col-sm-5">
      @foreach ($casts as $id => $cast)
        <div class="form-check col-md-6">
          <label class="form-check-lable" for="{{$cast}}">
            <input type="checkbox" name="casts[]" value="{{$id}}"
            {{ $movie->casts()->allRelatedIds()->contains($id) ? 'checked' : '' }}>
            {{$cast}}
          </label>
        </div>
      @endforeach
    </div>
  </div>

  <div class="form-group">
   <label for="exampleFormControlTextarea1">Movie Summary</label>
   <textarea class="form-control col-md-10" name="summary" id="exampleFormControlTextarea1" rows="3">{{ $movie->summary ? $movie->summary : ''}}</textarea>
 </div>

 <div class="input-group mb-3 col-md-10">
 <div class="input-group-prepend">
   <span class="input-group-text">Add Poster</span>
 </div>
 <div class="custom-file">
   <input type="file" name="image" class="custom-file-input" id="inputGroupFile01">
   <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
 </div>
</div>

    <div class="form-group row">
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary btn-lg">Update</button>
        <button name="{{ $movie->published_at ? 'unpublish' : 'publish'}}" value="true" class="btn btn-grey btn-lg">{{$movie->published_at ? 'Unpublish' : 'Publish'}}</button>
      </div>
    </div>
  </form>

</div> <!-- container -->


<style media="screen">
.boxes {
  height: 150px;
  overflow: auto;
  /* width: auto; */
  width: 1000px;
}​`​
</style>


@endsection
