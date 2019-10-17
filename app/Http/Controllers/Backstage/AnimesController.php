<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Requests\AnimeRequest;
use App\Http\Type\EmptyType;
use App\Models\Anime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $animes = new Anime();
//        $animes->buildFilter();

        $animes = Anime::filter()->paginate(20);
        dd($animes);
        return view('backstage.anime.show',compact('animes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param EmptyType $anime
     * @return \Illuminate\Http\Response
     */
    public function create(EmptyType $anime)
    {
        return view('backstage.anime.create_edit',compact('anime'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AnimeRequest  $AnimeRequest
     * @return \Illuminate\Http\Response
     */
    public function store(AnimeRequest $request)
    {
        $request->validate([
            'cover' => 'required'
        ]);

        $rst = $request->file('cover')->store('', 'public');

        $anime = Anime::create(array_merge($request->all(), [
            'cover' => "/storage/$rst"
        ]));

        return redirect()->route('backstage.anime.edit', $anime->id)->with('message', 'Create successfully,Affected 1 line');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $anime = Anime::findOrFail($id);
        return view('backstage.anime.create_edit',compact('anime'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AnimeRequest  $AnimeRequest
     * @param  int  $id
     * @param Anime $anime
     * @return \Illuminate\Http\Response
     */
    public function update(AnimeRequest $request, $id)
    {
        $anime = new Anime();
    
        if($request->hasFile('cover')){
            $rst = $request->file('cover')->store('','public');
            $request->merge(['cover'=> "/storage/$rst"]);
        }
        $rest = Anime::where('id',$id)->update_filter($request->all());
        return back()->with('message',"Update successfully, Affected $rest line");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $rest = Anime::destroy($id);
        return back()->with('message',"Delete successfully, Affected $rest line");
    }
}
