<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// use App\Http\Controllers\DataTables ;
use DataTables;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $data = Album::withCount('pictures')->get();
        if ($request->ajax()) {
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    // View Button
                    $viewButton = "<a class='btn btn-sm btn-info viewAlbum' href='/albums/".$row->id."' ><i class='fa-solid fa-eye'></i></a>";
   
                    // Update Button
                    $updateButton = "<button id = 'edit' class='btn btn-sm btn-info updateAlbum' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateModal' ><i class='fa-solid fa-pen-to-square'></i></button>";
   
                    // Delete Button
                    $deleteButton = "<button class='btn btn-sm btn-danger deleteAlbum' data-id='".$row->id."' data-count='".$row->pictures_count."' data-bs-toggle='modal' data-bs-target='#deleteAlbum'><i class='fa-solid fa-trash'></i></button>";
   
                    return $viewButton." ".$updateButton." ".$deleteButton;
   
               }) 
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Albums.albums',compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:albums,name',
        ]);
        if ($validator->fails()) {
            return back()->with('error',$validator->errors()->first());
        }
        $album = Album::create([
            'name'=>$request->name,
        ]);
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $id = $request->id;
        $album = Album::findorFail($id);
        $images = Picture::where('album_id',$id)->get();
        return view('Albums.show-album',compact('album','images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        //
    }

    
    public function getAlbum(Request $request){

        ## Read POST data 
        $id = $request->id;

        $albumdata = Album::findorFail($id);
        $response = array();
        if(!empty($albumdata)){
            $response['name'] = $albumdata->name;
            $response['success'] = 1;
        }else{
            $response['success'] = 0;
        }
        return response()->json($response);

    }
    public function updateAlbum(Request $request){

        ## Read POST data 
        $id = $request->id;
        $albumdata = Album::findorFail($id);
        $response = array();

        if(!empty($albumdata)){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:albums,name, '.$id,
            ]);
            if ($validator->fails()) {
                return back()->with('error',$validator->errors()->first());
            }
            $album = $albumdata->update([
                'name'=>$request->name,
            ]);
            if($album){
                $response['success'] = 1;
                $response['msg'] = 'Update successfully'; 
            }else{
                $response['success'] = 0;
                $response['msg'] = 'Record not updated';
            }

        }else{
            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }
        return response()->json($response);

    }
    public function DeleteAlbum(Request $request){
        $response = array();
        $album = Album::findorFail($request->id);
        if($album){
            $images = Picture::where('album_id',$request->id)->get();
            if($images){
                foreach($images as $image){
                    $image->delete();
                }
            }
            if($album->delete()){
                $response['success'] = 1;
                $response['msg'] = 'Album Deleted successfully'; 
            }else{
                $response['success'] = 0;
                $response['msg'] = 'Album not deleted'; 
            }

        }
        return response()->json($response);

    }
    public function MoveAlbum(Request $request){
        $response = array();
        $album = Album::findorFail($request->id);
        if($album){
            $images = Picture::where('album_id',$request->id)->update(
                [
                    'album_id'=>$request->move_to,
                ]
            );
            if($images){
                $album->delete();
                $response['success'] = 1;
                $response['msg'] = 'Album moved successfully'; 
            }else{
                $response['success'] = 0;
                $response['msg'] = 'Album not moved'; 
            }

        }
        return response()->json($response);

    }
    public function Chart(Request $request)
    {
        $lables = array();
        $count = array();
        $response = array();
        $data = Album::withCount('pictures')->get();
        
        foreach($data as $album){
            array_push($lables, $album->name);
            array_push($count, $album->pictures_count);
        }
        array_push($response, $lables,$count);
        return response()->json($response);
    }
    



}
