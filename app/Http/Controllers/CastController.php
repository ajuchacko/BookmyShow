<?php

namespace App\Http\Controllers;

use App\Cast;
use App\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CastController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth')->only('edit', 'update', 'store', 'create', 'destroy');
  }


  public function search(Request $request)
  {
if($request->input('q') == '') {
    $array = ['a', 'b', 'v', 'c', 'd'];
    $rand = array_rand($array,2);
     $movies = Movie::search($array[$rand[0]])->where('published_at', true)->get();
     $casts = Cast::search($array[$rand[0]])->paginate(9);

     return view('casts.search', compact('casts','movies'));
   }
     $movies = Movie::search(ucwords($request->input('q')))->where('published_at', true)->get();
     // dd($movies);
      $casts = Cast::search($request->input('q'))->paginate(9);
      // $casts = $movies->merge($cast);
      return view('casts.search', compact('movies', 'casts'));
   }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $casts = Cast::orderBy('name', 'asc')->paginate(6);
        return view('casts.index', compact('casts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('casts.create');
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
        'gender' => 'required',
      ]);

      $cast = new Cast;
      $cast->name = $request->name;
      $cast->birth = $request->birth;
      $cast->male = ($request->gender == 'male') ? 1 : 0;
      $cast->female = ($request->gender == 'female') ? 1 : 0;
      $cast->details = $request->details;
      $file = $request->file('image');
        if($file) {
          $cast->image = date('Y_m_d_H_i'). $file->getClientOriginalName();

          $destinationPath = 'casts/';
          $name = date('Y_m_d_H_i').$file->getClientOriginalName();
          $file->move($destinationPath, $name);
        }

      $cast->save() ?
                  $request->session()->flash('status', 'Cast was added successfully!')
                : $request->session()->flash('status', 'Adding Cast failed!');
      return redirect()->route('casts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cast  $cast
     * @return \Illuminate\Http\Response
     */
    public function show(Cast $cast)
    {
        return view('casts.show', compact('cast'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cast  $cast
     * @return \Illuminate\Http\Response
     */
    public function edit(Cast $cast)
    {
        return view('casts.edit', compact('cast'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cast  $cast
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cast $cast)
    {
      // dd($cast);

      $request->validate([
        'name' => 'required',
        'gender' => 'required',
      ]);
      $cast->name = $request->name;
      $cast->birth = $request->birth;
      $cast->male = ($request->gender == 'male') ? 1 : 0;
      $cast->female = ($request->gender == 'female') ? 1 : 0;
      $cast->details = $request->details;
      $file = $request->file('image');

      if($file) {
        if($cast->image) {
          $path = public_path().'/casts/'. $cast->image;
          unlink($path);
        }

        $cast->image = date('Y_m_d_H_i').$file->getClientOriginalName();

        $destinationPath = 'casts/';
        $name = date('Y_m_d_H_i').$file->getClientOriginalName();
        $file->move($destinationPath, $name);
      }

      $cast->save() ?
                  $request->session()->flash('status', 'Cast was updated successfully!')
                : $request->session()->flash('status', 'Updating Cast failed!');
        return redirect()->route('casts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cast  $cast
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cast $cast,Request $request)
    {
      if($cast->image && !strpos($cast->image, '.com')) {
      $path = public_path().'/casts/'. $cast->image;
      unlink($path);
      }
      $cast->delete() ?
                  $request->session()->flash('status', 'Cast was Removed successfully!')
                : $request->session()->flash('status', 'Removing Cast failed!');

      return redirect()->route('casts.index');
    }
}
