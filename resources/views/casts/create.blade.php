@extends('layouts.app')

@section('content')
  <div class="container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="list-style-none">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

  <form class="col-md-10" action="{{route('casts.store')}}" method="POST" enctype="multipart/form-data">
      @csrf
  <div class="form-group row">
    <label for="" class="col-sm-2 col-form-label">Cast Name</label>
    <div class="col-sm-5">
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Cast Birth</label>
    <div class="col-sm-5">
      <input type="text" name="birth" class="form-control" value="{{ old('birth') }}" id="inputPassword3">
    </div>
  </div>

  <div class="form-group">
   <label for="exampleFormControlTextarea1">Cast Details</label>
   <textarea class="form-control col-md-10" name="details" id="exampleFormControlTextarea1" rows="3">{{ old('details') }}</textarea>
 </div>

  <span class="oi oi-calendar"></span>
  <fieldset class="form-group">
    <div class="row">
      <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
      <div class="col-sm-10">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="gender" value="male" id="gridRadios1">
          <label class="form-check-label" for="gridRadios1">
            Male
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="gender" value="female" id="gridRadios2">
          <label class="form-check-label" for="gridRadios2">
            Female
          </label>
        </div>
      </div>
    </div>
  </fieldset>

  <div class="input-group mb-3 col-md-10">
  <div class="input-group-prepend">
    <span class="input-group-text">Add Image</span>
  </div>
  <div class="custom-file">
    <input type="file" name="image" class="custom-file-input" id="inputGroupFile01">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>


  <div class="form-group row">
    <div class="col-sm-10">
      <button type="submit" class="btn btn-primary">Add</button>
    </div>
  </div>
</form>
  </div>



@endsection
