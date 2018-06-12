<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Cast;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use Auth;

class MovieController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth')->only('edit', 'update', 'store', 'create', 'destroy');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // dd(Auth::user());
    if(Auth::user()) {
      if(request('published') ) {
        $movies = Movie::published()->orderBy('name', 'asc')->paginate(12);
        return view('movies.index', ['movies' => $movies]);
      } elseif(request('unpublished')) {
        $movies = Movie::unpublished()->orderBy('name', 'asc')->paginate(12);
        return view('movies.index', ['movies' => $movies]);
      }
    }
        $movies = Movie::published()->orderBy('name', 'asc')->paginate(12);
        return view('movies.index', ['movies' => $movies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $casts = Cast::get()->pluck('name', 'id')->sortBy('name');
      return view('movies.create', compact('casts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'name' => 'required',
        'language' => 'required',
        'genre' => 'required',
        'duration' => 'required',
        'release_date' => 'required',
        'summary' => 'required',
        'ticket_price' => 'required',
        'tickets' => 'required'
      ]);

      $movie = new Movie;
      $movie->name = $request->name;
      $movie->image = $request->image;
      $movie->trailer = $request->trailer ;
      $movie->language = $request->language;
      $movie->genre = $request->genre;
      $movie->ticket_price = $request->ticket_price;
      $movie->release_date = $request->release_date;
      $movie->duration = $request->duration;
      $movie->summary = $request->summary;

      $file = $request->file('image');
        if($file) {
          $movie->image = date('Y_m_d_H_i'). $file->getClientOriginalName();

          $destinationPath = 'images/';
          $name = date('Y_m_d_H_i').$file->getClientOriginalName();
          $file->move($destinationPath, $name);
        }

      if(request('publish')){
        $movie->published_at = Carbon::now();
      }
      $movie->save()?
                  $request->session()->flash('status', 'Movie was added successfully!')
                : $request->session()->flash('status', 'Adding Movie failed!');

      $movie->addTickets($request->tickets);
      $movie->casts()->sync($request->casts);

      return redirect()->route('movies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
      // $mov = Movie::published()->findOrFail($movie->id); // Extra

        if (!$movie->published_at) {
          throw new ModelNotFoundException('Movie not published');
        }

        return view('movies.show',['movie' => $movie]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
      $casts = Cast::get()->pluck('name', 'id')->sortBy('name');
        return view('movies.edit', compact('movie', 'casts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
      $movie->name = $request->name;
      $movie->trailer = $request->trailer ;
      $movie->language = $request->language;
      $movie->genre = $request->genre;
      $movie->ticket_price = $request->ticket_price;
      $movie->release_date = $request->release_date;
      $movie->duration = $request->duration;
      $movie->summary = $request->summary;

      $file = $request->file('image');

      // $file ? '' : $movie->image = $movie->image; ;


      if($file) {
        if($movie->image) {
          $path = public_path().'/images/'. $movie->image;
          unlink($path);
        }
        $movie->image = date('Y_m_d_H_i').$file->getClientOriginalName();

        $destinationPath = 'images/';
        $name = date('Y_m_d_H_i').$file->getClientOriginalName();
        $file->move($destinationPath, $name);
      }

      if(request('publish')){
        $movie->published_at = Carbon::now();
      } elseif(request('unpublish')) {
        $movie->published_at = null;
      }

      $movie->save() ?
                  $request->session()->flash('status', 'Movie was updated successfully!')
                : $request->session()->flash('status', 'Updating Movie failed!');
      $movie->casts()->sync($request->casts);
        return redirect()->route('movies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie,Request $request)
    {
      if($movie->image) {
      $path = public_path().'/images/'. $movie->image;
      unlink($path);
      }
      $movie->delete() ?
                  $request->session()->flash('status', 'Movie was Removed successfully!')
                : $request->session()->flash('status', 'Removing Movie failed!');

      return redirect()->route('movies.index');
    }
}
